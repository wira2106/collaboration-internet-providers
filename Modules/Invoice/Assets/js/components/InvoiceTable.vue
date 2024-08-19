<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("invoices.title.invoices") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">{{
              trans("core.breadcrumb.home")
            }}</router-link>
          </li>
          <li class="breadcrumb-item">
            {{ trans("invoices.title.invoices") }}
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="card card-outline-info">
        <div class="card-header text-white">
          {{ trans("invoices.title.invoices") }}
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-3" v-if="user_roles.name === 'Super Admin'">
                  <el-select
                    v-model="company_id"
                    :placeholder="trans('mutasi.placeholder.select')"
                    filterable
                    size="small"
                    @change="fetchData"
                  >
                    <el-option label="Seluruh Company" :value="null">
                    </el-option>
                    <el-option
                      v-for="item in companies_computed"
                      :key="item.company_id"
                      :label="setLabel(item)"
                      :value="item.company_id"
                      :data-id="item.company_id"
                    >
                    </el-option>
                  </el-select>
                </div>
                <div class="col-md-2">
                  <el-select
                    v-model="status"
                    filterable
                    size="small"
                    @change="fetchData"
                  >
                    <el-option
                      :label="trans('invoices.options.status.all')"
                      value="seluruh"
                    ></el-option>
                    <el-option
                      :label="trans('invoices.options.status.belum bayar')"
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
                <div class="col-md-2">
                  <el-select
                    v-model="tipe"
                    filterable
                    size="small"
                    @change="fetchData"
                  >
                    <el-option
                      :label="trans('invoices.options.type.all')"
                      :value="null"
                    ></el-option>
                    <el-option
                      :label="trans('invoices.options.type.mrc pelanggan')"
                      value="pelanggan"
                    ></el-option>
                    <el-option
                      :label="trans('invoices.options.type.presales')"
                      value="konfirmasi presale"
                    ></el-option>
                    <el-option
                      :label="trans('invoices.options.type.end point')"
                      value="biaya endpoint"
                    ></el-option>
                  </el-select>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="search pull-right">
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
              <div class="sc-table">
                <el-table
                  :data="data"
                  stripe
                  size="small"
                  style="width: 100%"
                  ref="pageTable"
                  v-loading.body="tableIsLoading"
                  @sort-change="handleSortChange"
                >
                  <el-table-column label="#" width="40">
                    <template slot-scope="scope">
                      {{
                        meta.per_page * (meta.current_page - 1) +
                          scope.$index +
                          1
                      }}
                    </template>
                  </el-table-column>
                  <el-table-column width="30">
                    <template slot-scope="scope">
                      <i
                        :class="[className(scope.row.status)]"
                        :style="
                          `${
                            scope.row.status === 'settlement'
                              ? 'color: #67c23a'
                              : ''
                          }`
                        "
                      ></i>
                    </template>
                  </el-table-column>
                  <el-table-column
                    prop="title"
                    :label="trans('invoices.table.invoice')"
                    sortable="custom"
                  >
                    <template slot-scope="scope">
                      <a href="#!" @click="goToDetail(scope)">
                        <b>
                          <text-highlight
                            :queries="highlight"
                            highlightClass="highlight-text"
                          >
                            {{ scope.row.invoice_no }}
                          </text-highlight></b
                        >

                        <el-tooltip
                          v-if="scope.row.jumlah_pending > 0"
                          :content="
                            trans('invoices.tooltip.pending invoice', {
                              jumlah: scope.row.jumlah_pending,
                            })
                          "
                          placement="top"
                        >
                          <span class="badge badge-danger span-pending">{{
                            scope.row.jumlah_pending
                          }}</span>
                        </el-tooltip>
                      </a>
                      <template v-if="company_id == null"
                        ><br /><i>{{ generateCompanyName(scope) }}</i></template
                      >
                      <br />

                      <text-highlight
                        :queries="highlight"
                        highlightClass="highlight-text"
                      >
                        {{ scope.row.title }}
                      </text-highlight>
                    </template>
                  </el-table-column>
                  <el-table-column
                    prop="amount"
                    :label="trans('invoices.table.amount')"
                    sortable="custom"
                  >
                    <template slot-scope="scope">
                      {{ scope.row.amount }}
                    </template>
                  </el-table-column>
                  <el-table-column
                    prop="created_at"
                    :label="trans('invoices.table.created_at')"
                    sortable="custom"
                    width="150"
                  >
                    <template slot-scope="scope">
                      {{ scope.row.created_at }}<br />
                      <i>Exp : {{ scope.row.due_date }}</i>
                    </template>
                  </el-table-column>
                  <el-table-column
                    prop="payment_at"
                    :label="trans('invoices.table.payment at')"
                    sortable="custom"
                    width="120"
                  >
                    <template slot-scope="scope">
                      {{ scope.row.payment_at_day }}<br />
                      <span v-if="scope.row.payment_at_day != null">{{
                        scope.row.payment_at_time
                      }}</span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    prop="actions"
                    :label="trans('invoices.table.actions')"
                    width="130"
                  >
                    <template slot-scope="scope">
                      <el-button-group>
                        <el-tooltip
                          :content="trans('invoices.tooltip.bayar invoice')"
                          placement="top"
                        >
                          <el-button
                            size="small"
                            type="primary"
                            @click="goToBayar(scope)"
                            v-if="scope.row.status === null"
                            icon="fa fa-credit-card"
                          >
                          </el-button>
                        </el-tooltip>
                        <el-tooltip
                          :content="trans('invoices.tooltip.cetak invoice')"
                          placement="top"
                        >
                          <el-button
                            size="small"
                            type="secondary"
                            @click="goToPrint(scope.row.invoice_id)"
                            icon="fa fa-print"
                          >
                          </el-button>
                        </el-tooltip>
                      </el-button-group>
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
                <div class="container mt-2 text-small">
                  <i class="fas fa-circle text-danger"></i> &nbsp;{{
                    trans("invoices.options.status.belum bayar")
                  }}
                  &nbsp;
                  <i class="fas fa-circle text-warning"></i> &nbsp;{{
                    trans("invoices.options.status.pending")
                  }}
                  &nbsp;
                  <i class="fas fa-circle" style="color: #67c23a"></i>
                  &nbsp;{{ trans("invoices.options.status.settlement") }} &nbsp;
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <modal-bayar-invoice
      :invoice_id="invoice_id"
      @handleSuccess="handleSuccessBayar"
    >
    </modal-bayar-invoice>
  </div>
</template>

<script>
import PermissionsHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";
import IconStatusInvoice from "./IconStatusInvoice.vue";
import ModalBayarInvoiceVue from "./ModalBayarInvoice.vue";

export default {
  mixins: [PermissionsHelper],
  components: {
    "modal-bayar-invoice": ModalBayarInvoiceVue,
    IconStatusInvoice,
  },
  data() {
    return {
      highlight: "",
      data: [],
      company_id: null,
      status: "seluruh",
      tipe: null,
      companies: [],
      invoice_id: null,
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      links: {},
      searchQuery: "",
      tableIsLoading: false,
      requests: [],
      request: null,
    };
  },
  computed: {
    companies_computed: function() {
      return this.companies.filter((item) => {
        if (item.type != null) {
          return item;
        }
      });
    },
  },
  methods: {
    className: function(status) {
      let className = "fas fa-circle text-danger";

      if (status === "pending") className = "fas fa-circle text-warning";

      if (status === "settlement") className = "fas fa-circle";

      return className;
    },
    fetchCompany() {
      axios.post(route("api.company.company.list")).then((response) => {
        this.companies = response.data;
      });
    },
    setLabel(item) {
      if (item.company_id == 1) {
        return item.name;
      } else {
        return item.name + " (" + item.type + ")";
      }
    },
    queryServer(customProperties) {
      this.data = [];
      const cancelSource = axios.CancelToken.source();
      const properties = {
        params: {
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
          company_id: this.company_id,
          status: this.status,
          tipe: this.tipe,
          ...customProperties,
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(route("api.invoice.invoices.index"), properties)
        .then((response) => {
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.data = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.highlight = this.searchQuery;

            this.fetchJumlahPelangganPending(this.data, cancelSource);
          }
        })
        .catch((error) => {});
    },
    getTypeStatus(status) {
      if (status === "settlement") return "success";
      if (status === "pending") return "warning";
      return "danger";
    },
    getStatus(status) {
      if (status) return status;
      return this.trans("invoices.belum bayar");
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
    goToDetail(scope) {
      this.$router.push({
        name: "admin.invoice.invoices.detail",
        params: {
          invoice: scope.row.invoice_id,
        },
      });
    },
    goToBayar(scope) {
      this.invoice_id = scope.row.invoice_id;
      $("#modal-bayar-invoice").modal("show");
    },
    goToPrint(invoice_id) {
      window.location.href = route("admin.invoice.invoices.print", {
        invoice: invoice_id,
      });
    },
    handleSuccessBayar() {
      this.queryServer();
    },
    generateCompanyName(scope) {
      if (this.company_id == scope.row.isp_id) {
        return scope.row.nama_isp;
      } else if (this.company_id == scope.row.osp_id) {
        return scope.row.nama_osp;
      } else {
        return scope.row.nama_isp != null
          ? scope.row.nama_isp
          : scope.row.nama_osp;
      }
    },
    fetchJumlahPelangganPending(data, cancelSource) {
      let vm = this;
      data.forEach((val, key) => {
        if (val.type == "pelanggan") {
          let properties = {
            cancelToken: cancelSource.token,
          };
          axios
            .get(
              route("api.invoice.pelanggan.jumlah.pending", {
                invoice: val.invoice_id,
              }),
              properties
            )
            .then((response) => {
              let jumlah = response.data;
              vm.data[key].jumlah_pending = jumlah;
            });
        }
      });
    },
  },
  mounted() {
    if (this.user_company.type != null) {
      this.company_id = this.user_company.company_id;
    }
    this.fetchCompany();
    this.fetchData();
  },
};
</script>

<style>
.span-pending {
  width: 15px;
}
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
