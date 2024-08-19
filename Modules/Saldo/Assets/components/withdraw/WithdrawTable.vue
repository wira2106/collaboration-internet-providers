<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("saldos.title.withdraw") }}</h3>
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
        <div
          :class="user_roles.name != 'Super Admin' ? 'col-md-12' : 'col-md-6'"
        >
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
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline-info">
            <div class="card-header text-white">
              {{ trans("saldos.title.withdraw") }}
              <el-button
                size="mini"
                class="el-button pull-right el-button--default el-button--small is-plain"
                @click="modalDetail(false, true, '')"
                v-if="user_roles.name !== 'Super Admin'"
              >
                <i class="fa fa-edit"></i>
                {{ trans("saldos.button.create withdraw") }}
              </el-button>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4 pb-3">
                  <el-select
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
                      :placeholder="trans('withdraws.placeholder.cari withdraw')"
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
                    :data="withdrawTable"
                    stripe
                    style="width: 100%"
                    v-loading.body="tableIsLoading"
                    @sort-change="handleSortChange"
                  >
                    <el-table-column label="No" width="75" sortable="custom">
                      <template slot-scope="scope">
                        {{
                          meta.per_page * (meta.current_page - 1) +
                            scope.$index +
                            1
                        }}
                      </template>
                    </el-table-column>
                    <el-table-column
                      prop="created_at"
                      :label="trans('saldos.table.tanggal')"
                      sortable="custom"
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
                            {{ scope.row.created_at }}
                          </span>
                        </div>
                        <div v-else>
                          <span>
                            {{ scope.row.created_at }}
                          </span>
                        </div>
                      </template>
                    </el-table-column>
                    <el-table-column
                      prop="amount"
                      :label="trans('saldos.table.jmlh withdraw')"
                      sortable="custom"
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
                          </el-button
                        >
                      </template>
                    </el-table-column>
                  </el-table>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
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
      class="modal fade withdraw"
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
          <ModalWithdraw
            v-if="create"
            :saldo="max_withdraw"
            :company="user_company"
            v-on:modalResponse="modalResponse($event)"
          ></ModalWithdraw>
          <ModalDetailWithdraw
            v-if="detail"
            v-on:modalResponse="modalResponse($event)"
            :tarik_saldo_id="tarik_saldo_id"
            :company="user_company"
          ></ModalDetailWithdraw>
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
import ModalWithdraw from "./ModalWithdraw";
import ModalDetailWithdraw from "./ModalDetailWithdraw";

export default {
  mixins: [UserRolesPermission],
  components: {
    ModalWithdraw: ModalWithdraw,
    ModalDetailWithdraw: ModalDetailWithdraw,
  },
  data() {
    return {
      highlight: "",
      withdrawTable: [],
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      links: {},
      status: "",
      tarik_saldo_id: "",
      saldo: "",
      max_withdraw: "",
      searchQuery: "",
      detail: false,
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
      user_company: {},
      request: null,
      requests: [],
    };
  },
  methods: {
    modalDetail(detail, create, id) {
      $(".withdraw").modal("show");
      this.tarik_saldo_id = "";
      this.detail = detail;
      this.create = create;
      if (detail) {
        this.tarik_saldo_id = [id, this.user_roles.name];
      }
    },
    modalResponse(response) {
      $(".withdraw").modal("hide");
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
          route("api.saldo.withdraw.index"),
          _.merge(properties, customProperties)
        )
        .then((response) => {
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.withdrawTable = response.data.data;
            this.saldo = response.data.saldo;
            this.max_withdraw = response.data.max_withdraw;
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
    goToEdit(scope) {
      this.$router.push({
        name: "admin.peralatan.alat.edit",
        params: {
          alat: scope.row.alat_id,
        },
      });
    },
  },
  mounted() {
    this.fetchData();
    this.getRolesPermission();
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
