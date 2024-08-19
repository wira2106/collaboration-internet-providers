<template>
  <div>
      <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"> {{ trans('invoices.title.invoices') }}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/backend">{{ trans('core.breadcrumb.home') }}</a></li>
                <li class="breadcrumb-item">{{ trans('invoices.title.invoices') }} </li>
            </ol>
        </div>
    </div>

    <div class="container">
        <div class="card card-outline-info">
            <div class="card-header text-white">
                {{ trans('invoices.title.invoices') }}
            </div>
            <div class="card-body">
                <div class="sc-table">
                    <div class="row d-flex justify-content-between">
                        <div class="col-md-4">
                            <el-select v-if="companies.length > 0" v-model="company_id" placeholder="Select" filterable size="small" @change="fetchData">
                                <el-option v-for="item in companies" :key="item.company_id" :label="item.name" :value="item.company_id">
                                </el-option>
                            </el-select>
                        </div>
                        <div class="search col-md-4 col-12 pull-right">
                            <el-input prefix-icon="el-icon-search" size="small" @keyup.enter="fetchData" v-model="searchQuery">
                                <!-- @keyup.native="performSearch" -->
                                <template slot="append">
                                    <el-button size="small" @click="fetchData">
                                        <span class="fa fa-search"></span>
                                    </el-button>
                                </template>
                            </el-input>
                        </div>
                    </div>

                    <el-table :data="data" stripe style="width: 100%" ref="pageTable" v-loading.body="tableIsLoading" @sort-change="handleSortChange">
                        <el-table-column label="No" width="75">
                            <template slot-scope="scope">
                                {{(meta.per_page * (meta.current_page - 1)) + scope.$index+1}}
                            </template>
                        </el-table-column>
                        <el-table-column prop="invoice_no" :label="trans('invoices.table.invoice_no')" sortable="custom">
                            <template slot-scope="scope">
                                {{ scope.row.invoice_no }}
                            </template>
                        </el-table-column>
                        <el-table-column prop="periode" :label="trans('invoices.table.periode')" sortable="custom">
                            <template slot-scope="scope">
                                {{ scope.row.periode }}
                            </template>
                        </el-table-column>
                        <el-table-column prop="status" :label="trans('invoices.table.status')" sortable="custom">
                            <template slot-scope="scope">
                                <el-button
                                :type="getTypeStatus(scope.row.status)"
                                size="small"    
                                >
                                {{ getStatus(scope.row.status) }}
                                </el-button>
                            </template>
                        </el-table-column>
                        <el-table-column prop="created_at" :label="trans('invoices.table.created_at')" sortable="custom">
                            <template slot-scope="scope">
                                {{ scope.row.created_at }}
                            </template>
                        </el-table-column>
                        <el-table-column prop="actions" :label="trans('invoices.table.actions')" >
                            <template slot-scope="scope">
                                <el-button-group>
                                    <el-button
                                    size="small"
                                    type="secondary"
                                    @click="goToDetail(scope)"
                                    >
                                    {{ trans('invoices.button.detail') }}
                                    </el-button>
                                    <el-button
                                    size="small"
                                    type="primary"
                                    @click="goToBayar(scope)"
                                    v-if="scope.row.status === null"
                                    >
                                    {{ trans('invoices.button.bayar') }}
                                    </el-button>
                                    <el-button
                                    size="small"
                                    type="secondary"
                                    @click="goToPrint(scope.row.invoice_id)"
                                    icon="fa fa-print"
                                    >
                                    {{ trans('invoices.button.print') }}
                                    </el-button>
                                </el-button-group>
                            </template>
                        </el-table-column>
                        
                    </el-table>
                    <div class="pagination-wrap" style="text-align: center; padding-top: 20px;">
                        <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="meta.current_page" :page-sizes="[10, 20, 50, 100]" :page-size="parseInt(meta.per_page)" layout="total, sizes, prev, pager, next, jumper" :total="meta.total">
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <modal-bayar-invoice :invoice_id="invoice_id" @handleSuccess="handleSuccessBayar">

    </modal-bayar-invoice>
  </div>
</template>

<script>
import ModalBayarInvoiceVue from './ModalBayarInvoice.vue';

export default {
    components: {
        'modal-bayar-invoice': ModalBayarInvoiceVue
    },
  data() {
    return {
      data: [],
      company_id: null,
      companies: [],
      invoice_id: null,
      meta: {
          current_page: 1,
          per_page: 10,
      },
      order_meta: {
          order_by: '',
          order: '',
      },
      links: {},
      searchQuery: '',
      tableIsLoading: false,
      requests: [],
      request: null
    }
  },
  methods: {
      queryServer(customProperties) {
        const cancelSource = axios.CancelToken.source();
        const properties = {
            params: {
                page: this.meta.current_page,
                per_page: this.meta.per_page,
                order_by: this.order_meta.order_by,
                order: this.order_meta.order,
                search: this.searchQuery,
                company_id: this.company_id,
                ...customProperties
            },
            cancelToken: cancelSource.token
        };
        this.request = {
            cancel: cancelSource.cancel
        };
        axios.get(route('api.invoice.invoices.index'), properties)
            .then((response) => {
                if (typeof response !== 'undefined') {
                    this.tableIsLoading = false;
                    this.data = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;
                    this.order_meta.order_by = properties.order_by;
                    this.order_meta.order = properties.order;
                    this.companies = response.data.companies;
                }
            });
    },
    getTypeStatus(status) {
        if(status === 'settlement') return 'success';
        if(status === 'pending') return 'warning';
        return 'danger';
    },
    getStatus(status) {
        if(status) return status;
        return this.trans('invoices.belum bayar');
    },
    fetchData() {
        this.meta.current_page = 1;
        this.meta.per_page = 10;
        this.tableIsLoading = true;
        if (this.request) this.cancel();
        this.queryServer();
    },
    handleSizeChange(event) {
        console.log(`per page :${event}`);
        this.tableIsLoading = true;
        this.queryServer({
            per_page: event
        });
    },
    handleCurrentChange(event) {
        console.log(`current page :${event}`);
        this.tableIsLoading = true;
        this.queryServer({
            page: event
        });
    },
    handleSortChange(event) {
        console.log('sorting', event);
        this.tableIsLoading = true;
        this.queryServer({
            order_by: event.prop,
            order: event.order
        });
    },
    performSearch: _.debounce(function (query) {
        console.log(`searching:${query.target.value}`);
        this.tableIsLoading = true;
        this.queryServer({
            search: query.target.value
        });
    }, 300),
    cancel() {
        this.request.cancel();
    },
    searchFunction() {
        var self = this;
        window.addEventListener('keyup', function (event) {
            if (event.keyCode === 13) {
                self.fetchData();
            }
        });
    },
    goToDetail(scope) {
      this.$router.push({
          name: 'admin.invoice.invoices.detail',
          params: {
              invoice: scope.row.invoice_id
          }
      });
    },
    goToBayar(scope) {
        this.invoice_id = scope.row.invoice_id;
        $('#modal-bayar-invoice').modal('show');
    },
    goToPrint(invoice_id) {
        window.location.href = route('admin.invoice.invoices.print', {invoice:invoice_id})
    },
    handleSuccessBayar() {
        this.queryServer();
    }
  },
  mounted() {
    this.fetchData();
  }
}
</script>

<style>

</style>