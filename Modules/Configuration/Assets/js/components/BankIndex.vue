<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("bank.title.bank") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">{{
              trans("core.breadcrumb.home")
            }}</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'admin.configuration.bank.index' }">
              {{ trans("bank.title.bank") }}
            </router-link>
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="card">
        <div class="card-body">
          <div class="sc-table">
            <div class="tool-bar row" style="padding-bottom: 20px;">
              <div class="actions col-12 pb-2">
                <el-button
                  type="primary"
                  size="small"
                  @click.prevent="goToCreate()"
                  ><i class="el-icon-edit"></i>
                  {{ trans("bank.button.create bank") }}
                </el-button>
              </div>
              <div class="col-12">
                <div class="row">
                  <div class="search col-12 ">
                    <div class="col-md-6 pull-right">
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
            </div>

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
                :label="trans('staff.table.no')"
                sortable="custom"
              >
              </el-table-column>
              <el-table-column
                prop="logo"
                :label="trans('bank.table.logo')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    <SmallImage :url="scope.row.logo_url"> </SmallImage>
                  </a>
                </template>
              </el-table-column>
              <el-table-column
                prop="name"
                :label="trans('bank.table.name')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    <text-highlight
                      :queries="highlight"
                      highlightClass="highlight-text"
                    >
                      {{ scope.row.namaBank }}
                    </text-highlight>
                  </a>
                </template>
              </el-table-column>
              <el-table-column
                prop="created_at"
                :label="trans('core.table.created at')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  {{ scope.row.created_at }}
                </template>
              </el-table-column>
              <el-table-column
                prop="actions"
                :label="trans('core.table.actions')"
              >
                <template slot-scope="scope">
                  <el-button-group>
                    <el-tooltip
                      class="item"
                      effect="dark"
                      :content="trans('bank.tooltip.button.edit')"
                      placement="top"
                    >
                      <el-button
                        size="mini"
                        @click.prevent="goToEdit(scope)"
                        v-if="scope.row.urls.edit_url"
                      >
                        <i class="fa fa-edit"></i>
                      </el-button>
                    </el-tooltip>
                    <el-tooltip
                      class="item"
                      effect="dark"
                      :content="trans('bank.tooltip.button.hapus')"
                      placement="top"
                    >
                      <delete-button
                        :scope="scope"
                        :rows="data"
                        v-if="scope.row.urls.delete_url != null"
                        v-on:onDelete="tableIsLoading = $event"
                        v-on:onDeleteSuccess="tableIsLoading = !$event"
                      ></delete-button>
                    </el-tooltip>
                  </el-button-group>
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

    <div
      class="modal fade bank-form"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <el-form
            ref="form_bank"
            :model="form_bank"
            label-width="120px"
            label-position="top"
            v-loading="loadingForm"
            @keydown="form.errors.clear($event.target.name)"
          >
            <div class="modal-header">
              <h5 class="modal-title">{{ trans("bank.form.title") }}</h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <center>
                    <el-form-item
                      :class="{
                        'el-form-item is-error': form.errors.has('logo'),
                      }"
                    >
                      <upload-avatar
                        :onSuccess="handleAvatarSuccess"
                        :beforeUpload="beforeAvatarUpload"
                        :image="form_bank.logo_preview"
                      >
                      </upload-avatar>
                      <div
                        class="el-form-item__error"
                        v-if="form.errors.has('logo_preview')"
                        v-text="form.errors.first('logo_preview')"
                      ></div>
                    </el-form-item>
                  </center>
                </div>
                <div class="col-md-12">
                  <el-form-item
                    :label="trans('bank.form.name')"
                    :class="{
                      'el-form-item is-error': form.errors.has('name'),
                    }"
                    prop="name"
                    :rules="rules.required"
                  >
                    <el-input v-model="form_bank.name" size="small"></el-input>
                    <div
                      class="el-form-item__error"
                      v-if="form.errors.has('name')"
                      v-text="form.errors.first('name')"
                    ></div>
                  </el-form-item>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="pull-right">
                <el-form-item>
                  <el-button
                    type="primary"
                    @click="onSubmit('form_bank')"
                    :loading="loadingForm"
                  >
                    <template v-if="form_bank.bank_id == null">{{
                      trans("bank.button.simpan")
                    }}</template>
                    <template v-else>{{
                      trans("bank.button.update")
                    }}</template>
                  </el-button>
                  <el-button
                    type="danger"
                    @click="onCancel()"
                    :loading="loadingForm"
                    >{{ trans("core.button.cancel") }}
                  </el-button>
                </el-form-item>
              </div>
            </div>
          </el-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import _ from "lodash";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import Toast from "../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [ShortcutHelper, Toast],
  data() {
    return {
      highlight: "",
      data: [],
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
      loadingForm: false,
      form: new Form(),
      form_bank: {
        bank_id: null,
        name: "",
        logo_preview: "",
      },
      rules: {
        required: [
          {
            required: true,
            trigger: "change",
            message: this.trans("core.form.required"),
          },
        ],
      },
      request: null,
      requests: [],
    };
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
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(
          route(
            "admin.configuration.bank.list",
            _.merge(properties, customProperties)
          ),
          properties
        )
        .then((response) => {
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.data = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.highlight = this.searchQuery;
          }
        });
    },
    fetchData() {
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
    indexMethod(index) {
      return (this.meta.current_page - 1) * this.meta.per_page + index + 1;
    },
    goToCreate() {
      $(".bank-form").modal();
    },
    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          Swal.fire({
            title: this.trans("core.messages.confirmation-form"),
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: this.trans("core.button.confirm"),
            cancelButtonText: this.trans("core.button.cancel"),
          }).then((result) => {
            if (result.value) {
              this.loadingForm = true;
              this.form = new Form(this.form_bank);
              this.form
                .post(route("api.configuration.bank.submit"))
                .then((response) => {
                  this.loadingForm = false;
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
                  this.fetchData();
                  this.onCancel();
                })
                .catch((error) => {
                  this.loadingForm = false;
                  this.Toast({
                    icon: "error",
                    title: this.trans("core.error 500 title"),
                  });
                });
            }
          });
        }
      });
    },
    onCancel() {
      this.resetForm();
      $(".bank-form").modal("hide");
    },
    resetForm() {
      this.form_bank.bank_id = null;
      this.form_bank.name = "";
      this.form_bank.logo_preview = "";
    },
    handleAvatarSuccess(res, file) {
      this.form_bank.logo_preview = URL.createObjectURL(file.raw);
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === "image/jpeg" || file.type === "image/png";
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error("Avatar picture must be JPG or PNG format!");
      }
      if (!isLt2M) {
        this.$message.error("Avatar picture size can not exceed 2MB!");
      }
      this.form_bank.logo = file;
      return isJPG && isLt2M;
    },
    goToEdit(scope) {
      this.loadingForm = true;
      this.goToCreate();
      axios
        .post(
          route("api.configuration.bank.find", {
            id: scope.row.bank_id,
          })
        )
        .then((response) => {
          let data = response.data.data;
          this.form_bank.bank_id = data.bank_id;
          this.form_bank.name = data.namaBank;
          this.form_bank.logo_preview = data.logo_url;
          this.loadingForm = false;
        })
        .catch((err) => {});
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
