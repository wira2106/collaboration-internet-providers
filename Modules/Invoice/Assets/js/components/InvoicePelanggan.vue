<template>
  <div>
    <div class="row">
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-6">
            <el-select
              v-model="status"
              filterable
              size="small"
              @change="fetchData"
            >
              <el-option
                :label="trans('invoices.options.status.all')"
                :value="null"
              ></el-option>
              <el-option
                :label="trans('invoices.options.status.pending')"
                value="pending"
              ></el-option>
              <el-option
                :label="trans('invoices.options.status.settlement')"
                value="settlement"
              ></el-option>
            </el-select>
          </div>
        </div>
      </div>
      <div class="search col-md-4">
        <div class="col-md-12 pull-right">
          <el-input
            prefix-icon="el-icon-search"
            size="small"
            @keyup.enter.native="fetchData"
            v-model="searchQuery"
          >
            <!-- @keyup.native="performSearch" -->
            <template slot="append">
              <el-button size="small" @click="fetchData">
                <span class="fa fa-search"></span>
              </el-button>
            </template>
          </el-input>
        </div>
      </div>
      <div class="col-md-12">
        <el-table
          :data="data"
          stripe
          style="width: 100%"
          ref="pageTable"
          v-loading.body="tableIsLoading"
          @sort-change="handleSortChange"
        >
          <el-table-column
            :index="indexMethod"
            type="index"
            prop="nomor"
            label="#"
            sortable="custom"
          >
          </el-table-column>
          <el-table-column
            prop="nama_pelanggan"
            :label="trans('invoices.table.pelanggan')"
            sortable="custom"
          >
            <template slot-scope="scope">
              <a @click.prevent="gotoPelanggan(scope)" href="#">
                <text-highlight
                  :queries="highlight"
                  highlightClass="highlight-text"
                >
                  {{ scope.row.nama_pelanggan }}
                </text-highlight>
              </a>
            </template>
          </el-table-column>
          <el-table-column
            prop="paket"
            :label="trans('invoices.table.paket')"
            sortable="custom"
          >
            <template slot-scope="scope">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.paket }}
              </text-highlight>
            </template>
          </el-table-column>
          <el-table-column
            prop="amount"
            :label="trans('invoices.table.mrc')"
            sortable="custom"
          >
            <template slot-scope="scope">
              {{ scope.row.amount }}
            </template>
          </el-table-column>
          <el-table-column
            prop="status"
            :label="trans('invoices.table.status')"
            sortable="custom"
          >
            <template slot-scope="scope">
              <span
                :class="
                  'badge badge-' +
                    (scope.row.status == 'Pending' ? 'warning' : 'success')
                "
                >{{ scope.row.status }}</span
              >
            </template>
          </el-table-column>
        </el-table>
        <div
          class="pagination-wrap"
          style="text-align: center; padding-top: 20px"
        >
          <el-pagination
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
            :current-page.sync="meta.current_page"
            :page-sizes="[10, 20, 50, 100]"
            :page-size="parseInt(meta.per_page)"
            layout="total, sizes, prev, pager, next, jumper"
            :total="meta.total"
          >
          </el-pagination>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["invoice"],
  data() {
    return {
      data: [],
      request: null,
      tableIsLoading: false,
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      links: {},
      status: null,
      searchQuery: "",
      highlight: "",
    };
  },
  methods: {
    fetchData() {
      this.meta.current_page = 1;
      this.meta.per_page = 10;
      this.tableIsLoading = true;
      if (this.request) this.cancel();
      this.queryServer();
    },
    queryServer() {
      const cancelSource = axios.CancelToken.source();
      const properties = {
        params: {
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
          invoice_id: this.invoice.invoice_id,
          status: this.status,
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(route("api.invoice.pelanggan.list"), properties)
        .then((response) => {
          this.tableIsLoading = false;
          this.data = response.data.data;
          this.meta = response.data.meta;
          this.links = response.data.links;
          this.order_meta.order_by = properties.order_by;
          this.order_meta.order = properties.order;
          this.highlight = this.searchQuery;
        })
        .catch((error) => {
          this.tableIsLoading = false;
          this.Toast({
            icon: "error",
            title: this.trans("core.error 500 title"),
          });
        });
    },
    indexMethod(index) {
      return (this.meta.current_page - 1) * this.meta.per_page + index + 1;
    },
    handleSizeChange(event) {
      console.log(`per page :${event}`);
      this.tableIsLoading = true;
      this.queryServer({
        per_page: event,
      });
    },
    handleCurrentChange(event) {
      console.log(`current page :${event}`);
      this.tableIsLoading = true;
      this.queryServer({
        page: event,
      });
    },
    handleSortChange(event) {
      console.log("sorting", event);
      this.tableIsLoading = true;
      this.queryServer({
        order_by: event.prop,
        order: event.order,
      });
    },
    performSearch: _.debounce(function(query) {
      console.log(`searching:${query.target.value}`);
      this.tableIsLoading = true;
      this.queryServer({
        search: query.target.value,
      });
    }, 300),
    cancel() {
      this.request.cancel();
    },
    searchFunction() {
      var self = this;
      window.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
          self.fetchData();
        }
      });
    },
  },
  mounted() {
    this.fetchData();
    // this.searchFunction();
  },
};
</script>
<style>
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
