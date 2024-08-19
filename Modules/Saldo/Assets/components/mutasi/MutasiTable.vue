<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor" @click="fetchData">
          {{ trans("mutasi.list resource") }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/backend/saldo/mutasi">{{
              trans("mutasi.list resource")
            }}</a>
          </li>
        </ol>
      </div>
    </div>
    <div class="container">
      <div class="card">
        <div class="card-body">
          <div class="sc-table">
            <div class="tool-bar row" style="padding-bottom: 20px;">
              <div class="col-12">
                <div class="row">
                  <div class="col-md-9">
                    <div class="row">
                      <div
                        class="col-md-6 pb-3"
                        v-if="user_role != '' && user_role == 'Super Admin'"
                      >
                        <el-select
                          v-if="companies.length > 0"
                          v-model="company_id"
                          :placeholder="trans('mutasi.placeholder.select')"
                          filterable
                          size="small"
                          @change="fetchData"
                        >
                          <el-option
                            v-for="item in companies"
                            :key="item.company_id"
                            :label="setLabel(item)"
                            :value="item.company_id"
                            :data-id="item.company_id"
                          >
                          </el-option>
                        </el-select>
                      </div>
                      <div class="col-md-4 text-center pb-3">
                        <el-date-picker
                          size="small"
                          v-model="periode"
                          type="month"
                          format="MMM yyyy"
                          value-format="yyyy-MM"
                          @change="fetchData"
                          :placeholder="trans('mutasi.placeholder.periode')"
                        >
                        </el-date-picker>
                      </div>
                    </div>
                  </div>
                  <div class="search col-md-3 text-center pb-3">
                    <div>
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
                </div>
              </div>
              <div class="col-12">
                <hr />
              </div>
              <div class="col-12" style="margin-bottom: 10px;">
                <b>{{ trans("mutasi.title.saldo awal") }}</b> :
                <span class="bg-white text-info p-1 border border-info rounded">
                  {{ saldo.saldo_awal_rp }} </span
                >&nbsp;&nbsp; <b>{{ trans("mutasi.title.saldo akhir") }}</b> :
                <span class="bg-white text-info p-1 border border-info rounded">
                  {{ saldo.saldo_akhir_rp }}
                </span>
              </div>
              <div class="col-12">
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
                    :label="trans('pengajuans.table.no')"
                    sortable="custom"
                  >
                  </el-table-column>
                  <el-table-column
                    width="150"
                    prop="tanggal"
                    :label="trans('mutasi.table.tanggal')"
                  >
                    <template slot-scope="scope">
                      {{ scope.row.tanggal }}<br />{{ scope.row.jam }}
                    </template>
                  </el-table-column>
                  <el-table-column
                    prop="deskripsi"
                    :label="trans('mutasi.table.deskripsi')"
                  >
                    <template slot-scope="scope">
                      <text-highlight
                        :queries="highlight"
                        highlightClass="highlight-text"
                      >
                        {{ scope.row.deskripsi }}
                      </text-highlight>
                    </template>
                  </el-table-column>
                  <el-table-column
                    width="120"
                    prop="debit"
                    :label="trans('mutasi.table.debit')"
                  >
                    <template slot-scope="scope">
                      <span v-if="scope.row.tipe == 'debit'">
                        <text-highlight
                          :queries="highlight"
                          highlightClass="highlight-text"
                        >
                          {{ scope.row.nominal_rp }}
                        </text-highlight>
                      </span>
                      <span v-else> </span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    width="120"
                    prop="kredit"
                    :label="trans('mutasi.table.kredit')"
                  >
                    <template slot-scope="scope">
                      <span v-if="scope.row.tipe == 'credit'">
                        <text-highlight
                          :queries="highlight"
                          highlightClass="highlight-text"
                        >
                          {{ scope.row.nominal_rp }}
                        </text-highlight>
                      </span>
                      <span v-else> </span>
                    </template>
                  </el-table-column>
                  <el-table-column
                    width="130"
                    prop="saldo_akhir"
                    :label="trans('mutasi.table.saldo akhir')"
                  >
                    <template slot-scope="scope">
                      <text-highlight
                        :queries="highlight"
                        highlightClass="highlight-text"
                      >
                        {{ scope.row.saldo_akhir }}
                      </text-highlight>
                    </template>
                  </el-table-column>
                </el-table>
                <div
                  class="pagination-wrap"
                  style="text-align: center; padding-top: 20px;"
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
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import _ from "lodash";
import UserRolesPermission from "../../../../Core/Assets/js/mixins/UserRolesPermission";

export default {
  mixins: [UserRolesPermission],
  data() {
    return {
      highlight: "",
      companies: [],
      company_id: "",
      periode: "",
      data: [],
      saldoAkhirList: 0,
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "ascending",
      },
      links: {},
      searchQuery: "",
      tableIsLoading: false,
      user_role: "",
      saldo: {
        saldo_awal_rp: 0,
        saldo_akhir_rp: 0,
      },
      request: null,
      requests: [],
    };
  },
  methods: {
    fetchCompany() {
      axios.post(route("api.company.company.list")).then((response) => {
        this.companies = response.data;
      });
    },
    setLabel(item) {
      if (item.company_id == 1) {
        return item.display_name;
      } else {
        return item.display_name + " (" + item.type + ")";
      }
    },
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
          periode: this.periode,
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(
          route("api.saldo.mutasi.pagination"),
          _.merge(properties, customProperties)
        )
        .then((response) => {
          if (typeof response !== "undefined") {
            this.saldoAkhirList = response.data.saldoAkhirList;
            this.tableIsLoading = false;
            this.setSaldoAkhirEachData(response.data.data);
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.highlight = this.searchQuery;
            if (response.data.company_select != null) {
              this.company_id = parseInt(response.data.company_select);
            } else {
              this.company_id = "";
            }
            this.periode = response.data.periode_select;
          }
        })
        .catch((error) => {});
    },
    fetchData() {
      this.tableIsLoading = true;
      if (this.request) this.cancel();
      this.queryServer();
      this.fetchSaldoMutasi();
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
    fetchSaldoMutasi() {
      let data = {
        periode: this.periode,
        company_id: this.company_id,
      };
      axios.post(route("api.saldo.mutasi.getsaldo"), data).then((response) => {
        this.saldo = response.data.data;
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
    setSaldoAkhirEachData(data) {
      let vm = this;
      let saldo_akhir = parseInt(this.saldoAkhirList);
      data.forEach(function(val, index) {
        val.saldo_akhir = "Rp. " + vm.number_format(saldo_akhir);
        if (val.tipe == "debit") {
          saldo_akhir -= parseInt(val.nominal);
        } else {
          saldo_akhir += parseInt(val.nominal);
        }
      });
      this.data = data;
    },
    number_format(number, decimals, decPoint, thousandsSep) {
      number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
      var n = !isFinite(+number) ? 0 : +number;
      var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
      var sep = typeof thousandsSep === "undefined" ? "." : thousandsSep;
      var dec = typeof decPoint === "undefined" ? "." : decPoint;
      var s = "";

      var toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return "" + (Math.round(n * k) / k).toFixed(prec);
      };

      // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
      }

      return s.join(dec);
    },
  },
  mounted() {
    this.fetchCompany();
    this.getRolesPermission();
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
