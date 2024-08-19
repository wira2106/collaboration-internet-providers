<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("saldos.title.topup") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">{{
              trans("core.breadcrumb.home")
            }}</router-link>
          </li>
          <li class="breadcrumb-item">{{ trans("saldos.title.saldo") }}</li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="row" v-if="user_roles.name !== 'Super Admin'">
        <div :class="user_roles.name == 'Admin OSP' ? 'col-md-12' : 'col-md-6'">
          <div class="card">
            <div class="card-body text-center" v-loading.body="tableIsLoading">
              <h2 class="m-b-0"><i class="mdi mdi-wallet text-purple"></i></h2>
              <h3 class="">Rp. {{ saldo }}</h3>
              <h6 class="card-subtitle">
                {{ trans("saldos.title.total saldo") }}
              </h6>
            </div>
          </div>
        </div>
        <div class="col-md-6" v-if="user_roles.name == 'Admin ISP'">
          <div class="card">
            <div class="card-body  text-center" v-loading.body="tableIsLoading">
              <h2 class="m-b-0">
                <i class="fa fa-dollar-sign text-green"></i>
              </h2>
              <h3 class="">{{ rupiah(total_tagihan) }}</h3>
              <h6 class="card-subtitle">
                {{ trans("saldos.title.total tagihan") }}
              </h6>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline-info">
            <div class="card-header text-white">
              {{ trans("saldos.title.topup") }}
              <el-button
                size="mini"
                class="el-button pull-right el-button--default el-button--small is-plain"
                @click="modalDetail(false, true, '')"
                v-if="user_roles.name !== 'Super Admin'"
              >
                <i class="fa fa-edit"></i>
                {{ trans("saldos.button.create topup") }}
              </el-button>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4 pb-3">
                  <el-select
                    class=""
                    size="mini"
                    v-model="status"
                    filterable
                    @change="fetchData"
                  >
                    <el-option
                      v-for="item in filter"
                      :value="item.value"
                      :label="item.label"
                      :key="item.value"
                    ></el-option>
                  </el-select>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4 pb-3 d-flex justify-content-end">
                  <div class="btn-group">
                    <el-input
                      prefix-icon="el-icon-search"
                      size="small"
                      @keyup.enter.native="fetchData"
                      v-model="searchQuery"
                      :placeholder="trans('topups.placeholder.cari topup')"
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
              <div class="row">
                <div class="col-md-12">
                  <el-table
                    :data="topupTable"
                    style="width: 100%"
                    v-loading.body="tableIsLoading"
                    @sort-change="handleSortChange"
                  >
                    <el-table-column
                      label="No"
                      width="65"
                      sortable="custom"
                      fixed
                    >
                      <template slot-scope="scope">
                        {{
                          meta.per_page * (meta.current_page - 1) +
                            scope.$index +
                            1
                        }}
                      </template>
                    </el-table-column>
                     <el-table-column
                      :label="trans('saldos.table.metode')"
                      sortable="custom"
                      fixed
                    >
                      <template slot-scope="scope">
                        {{scope.row.mode}}
                      </template>
                    </el-table-column>
                    <el-table-column
                      prop="created_at"
                      :label="trans('saldos.table.tanggal')"
                      width="210"
                      sortable="custom"
                      fixed
                    >
                      <template slot-scope="scope">
                        <div
                          style="padding-top:5px;"
                          v-if="user_roles.name == 'Super Admin'"
                        >
                          <span
                            style="font-size: 12px;font-weight: 700;display: block;"
                          >
                            {{ scope.row.nama_perusahaan }}
                          </span>
                          <span style="font-size:10px;">
                            {{ scope.row.created_at }}</span
                          >
                        </div>
                        <div v-else>
                          <span>
                            {{ scope.row.created_at }}
                          </span>
                        </div>
                      </template>
                    </el-table-column>
                    <div class="pr-4">
                      <el-table-column
                        prop="amount"
                        :label="trans('saldos.table.jmlh')"
                        sortable="custom"
                        class="text-center"
                      >
                        <template slot-scope="scope">
                          <div style="padding-bottom:17px; text-center">
                            <span
                              style="font-size: 12px;font-weight: 700;display: block;"
                            >
                              <text-highlight
                                :queries="highlight"
                                highlightClass="highlight-text"
                              >
                                Rp. {{ scope.row.amount }}
                              </text-highlight>
                            </span>
                          </div>
                        </template>
                      </el-table-column>
                    </div>
                    <el-table-column
                      prop="status"
                      :label="trans('saldos.table.status')"
                      sortable="custom"
                    >
                      <template slot-scope="scope">
                        <button
                          type="button"
                          class="btn btn-sm btn-warning"
                          v-if="scope.row.status == 'pending'"
                        >
                          <text-highlight
                            :queries="highlight"
                            highlightClass="highlight-text"
                          >
                            {{ scope.row.status }}
                          </text-highlight>
                        </button>
                        <button
                          type="button"
                          class="btn btn-sm btn-danger"
                          v-if="scope.row.status == 'cancel'"
                        >
                          <text-highlight
                            :queries="highlight"
                            highlightClass="highlight-text"
                          >
                            {{ scope.row.status }}
                          </text-highlight>
                        </button>
                        <button
                          type="button"
                          class="btn btn-sm btn-danger"
                          v-if="scope.row.status == 'expired'"
                        >
                          <text-highlight
                            :queries="highlight"
                            highlightClass="highlight-text"
                          >
                            {{ scope.row.status }}
                          </text-highlight>
                        </button>
                        <button
                          type="button"
                          class="btn btn-sm btn-success"
                          v-if="scope.row.status == 'success'"
                        >
                          <text-highlight
                            :queries="highlight"
                            highlightClass="highlight-text"
                          >
                            {{ scope.row.status }}
                          </text-highlight>
                        </button>
                      </template>
                    </el-table-column>
                    <el-table-column
                      prop="actions"
                      :label="trans('saldos.table.action')"
                    >
                      <template slot-scope="scope">
                        <el-button
                          v-if="scope.row.mode == 'Manual'"
                          size="mini"
                          :type="
                            user_roles.name == 'Super Admin' && scope.row.status == 'pending'
                              ? 'primary'
                              : 'secondary'
                          "
                          @click="modalDetail(true, false, scope.row)"
                        >
                          {{
                            user_roles.name == "Super Admin" && scope.row.status == 'pending'
                              ? trans("saldos.button.konfirmasi")
                              : trans("saldos.button.detail")
                          }}
                        </el-button>
                        <el-button v-else
                         size="mini"
                         type="secondary"
                         @click="modalDetailBriva(scope.row)">
                            {{trans("saldos.button.detail")}}
                        </el-button>
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
            <!-- /.box -->
          </div>
        </div>
      </div>
    </div>
    <div
      ref="topup"
      class="modal fade topup modal-topup-saldo"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="pull-right p-4">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <ModalTopup
            ref="topup"
            v-if="create"
            v-on:modalResponse="modalResponse($event)"
          ></ModalTopup>
          <ModalDetail
            ref="detail"
            v-if="detail"
            v-on:modalResponse="modalResponse($event)"
            :topup_id="topup_id"
          ></ModalDetail>
          <ModalBriva
            ref="topupbriva"
            v-if="detailbriva"
            v-on:modalResponse="modalResponse($event)"
            :topup_id="topup_id"
          ></ModalBriva>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import _ from "lodash";
import ModalTopup from "./ModalTopup";
import ModalDetail from "./ModalDetail";
import ModalBriva from "./ModalBriva";
import CurrencyHelper from "../../../../Core/Assets/js/mixins/CurrencyHelper";
import PermissionsHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";

export default {
  mixins: [CurrencyHelper, PermissionsHelper],
  components: {
    ModalTopup: ModalTopup,
    ModalDetail: ModalDetail,
    ModalBriva: ModalBriva,
  },
  data() {
    return {
      highlight: "",
      topupTable: [],
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      links: {},
      total_tagihan: 0,
      status: "",
      topup_id: "",
      saldo: "",
      searchQuery: "",
      detail: false,
      detailbriva: false,
      create: true,
      status: "all",
      filter: [
        {
          value: "all",
          label: "Seluruh",
        },
        {
          value: "success",
          label: "success",
        },
        {
          value: "pending",
          label: "pending",
        },
        {
          value: "cancel",
          label: "cancel",
        },
      ],
      tableIsLoading: false,
      colomn: "",
      request: null,
      requests: [],
    };
  },
  methods: {
    modalDetailBriva(data){
      $(".topup").modal("show");
      this.detailbriva = true;
      this.detail = false;
      this.create = false;
      this.topup_id = [data, this.user_roles.name];
      console.log(data);
      $(".fancybox").fancybox();
    },
    modalDetail(detail, create, data) {
      $(".topup").modal("show");
      this.topup_id = "";
      this.detail = detail;
      this.detailbriva = false;
      this.create = create;
      if (detail) {
        this.topup_id = [data, this.user_roles.name];
        console.log(data);
      }
      $(".fancybox").fancybox();
    },
    modalResponse(response) {
      $(".topup").modal("hide");
      this.fetchData();
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
          type: this.status,
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(
          route("api.saldo.topup.index"),
          _.merge(properties, customProperties)
        )
        .then((response) => {
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.topupTable = response.data.data;
            this.saldo = response.data.saldo;
            this.total_tagihan = response.data.total_tagihan;
            this.meta = response.data.meta;
            this.link = response.data.link;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.highlight = this.searchQuery;
          }
        })
        .catch((error) => {});
    },
    fetchData() {
      this.modalLoading = false;
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
    setDefault() {
      this.$refs.topup.setNull();
      this.$refs.detail.def();
    },
  },
  mounted() {
    $(".topup").on("hidden.bs.modal", this.def);
    this.fetchData();
    // this.searchFunction();

    if (localStorage.getItem("kekurangan_saldo")) {
      $(".topup").modal("show");

      this.create = true;
    }
  },
};
</script>
<style>
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
