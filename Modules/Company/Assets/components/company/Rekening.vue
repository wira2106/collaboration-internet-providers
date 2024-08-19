<template>
  <div>
    <div class="search col-12 ">
      <div class="row d-flex justify-content-between">
        <div class="col-md-4">
          <el-input
            prefix-icon="el-icon-search"
            size="small"
            @keyup.enter.native="fetchData"
            v-model="searchQuery"
            :placeholder="trans('rekening.placeholder.cari rekening')"
          >
            <!-- @keyup.native="performSearch" -->
            <template slot="append">
              <el-button size="small" @click="fetchData">
                <span class="fa fa-search"></span>
              </el-button>
            </template>
          </el-input>
        </div>

        <el-button
          type="primary"
          icon="el-icon-plus"
          @click.prevent="addRekening()"
        >
          {{ trans("companies.button.create rekening") }}</el-button
        >
      </div>
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
          :label="trans('wilayahs.table.no')"
          sortable="custom"
        >
        </el-table-column>
        <el-table-column
          prop="bank"
          :label="trans('companies.table.rekening.bank')"
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
          prop="rekening"
          :label="trans('companies.table.rekening.rekening')"
          sortable="custom"
        >
          <template slot-scope="scope">
            <a @click.prevent="goToEdit(scope)" href="#">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.rekening }}
              </text-highlight>
            </a>
          </template>
        </el-table-column>
        <el-table-column
          prop="atas_nama"
          :label="trans('companies.table.rekening.atas nama')"
          sortable="custom"
        >
          <template slot-scope="scope">
            <a @click.prevent="goToEdit(scope)" href="#">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.atas_nama }}
              </text-highlight>
            </a>
          </template>
        </el-table-column>
        <el-table-column
          prop="created_at"
          :label="trans('companies.table.rekening.created_at')"
          sortable="custom"
        >
          <template slot-scope="scope">
            <a @click.prevent="goToEdit(scope)" href="#">
              {{ scope.row.created_at }}
            </a>
          </template>
        </el-table-column>
        <el-table-column prop="actions" :label="trans('core.table.actions')">
          <template slot-scope="scope">
            <el-button-group>
              <el-tooltip
                class="item"
                effect="dark"
                :content="trans('core.tooltip.rekening.edit')"
                placement="top"
              >
                <el-button
                  size="mini"
                  @click.prevent="goToEdit(scope)"
                  icon="el-icon-edit"
                >
                </el-button>
              </el-tooltip>
              <el-tooltip
                class="item"
                effect="dark"
                :content="trans('core.tooltip.rekening.hapus')"
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

    <div
      class="modal fade rekening-form"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <el-form
            ref="form_rekening"
            :model="form_rekening"
            label-width="120px"
            label-position="top"
            v-loading="loadingForm"
            @keydown="form.errors.clear($event.target.name)"
          >
            <div class="modal-header">
              <h5 class="modal-title">
                {{ trans("companies.form.title.rekening") }}
              </h5>
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
                  <el-form-item
                    :label="trans('companies.form.rekening.bank')"
                    :class="{
                      'el-form-item is-error': form.errors.has('bank_id'),
                    }"
                    prop="bank_id"
                    :rules="rules.required"
                  >
                    <el-select
                      v-model="form_rekening.bank_id"
                      placeholder="Select"
                      filterable
                      size="small"
                    >
                      <el-option
                        v-for="item in banks"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                        :data-id="item.value"
                      >
                      </el-option>
                    </el-select>
                  </el-form-item>
                </div>
                <div class="col-md-12">
                  <el-form-item
                    :label="trans('companies.form.rekening.rekening')"
                    :class="{
                      'el-form-item is-error': form.errors.has('rekening'),
                    }"
                    prop="rekening"
                    :rules="rules.required"
                  >
                    <el-input
                      type="number"
                      min="0"
                      v-model="form_rekening.rekening"
                      size="small"
                    ></el-input>
                  </el-form-item>
                </div>
                <div class="col-md-12">
                  <el-form-item
                    :label="trans('companies.form.rekening.atas nama')"
                    :class="{
                      'el-form-item is-error': form.errors.has('atas_nama'),
                    }"
                    prop="atas_nama"
                    :rules="rules.required"
                  >
                    <el-input
                      v-model="form_rekening.atas_nama"
                      size="small"
                    ></el-input>
                  </el-form-item>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <el-button
                type="danger"
                @click="onCancel()"
                :loading="loadingForm"
                >{{ trans("core.button.cancel") }}
              </el-button>
              <el-button
                type="primary"
                @click="onSubmit('form_rekening')"
                :loading="loadingForm"
                icon="el-icon-upload"
              >
                {{
                  form_rekening.bank_account_id == null
                    ? trans("rekening.button.save")
                    : trans("rekening.button.update")
                }}
              </el-button>
            </div>
          </el-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from "lodash";
import Form from "form-backend-validation";
import axios from "axios";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import Toast from "../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [TranslationHelper, Toast],
  props: ["company_id"],
  data() {
    return {
      data: [],
      highlight: "",
      form_rekening: {
        bank_account_id: null,
        company_id: "",
        bank_id: "",
        rekening: "",
        atas_nama: "",
      },
      form: new Form(),
      banks: [],
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
      loadingForm: false,
      rules: {
        required: [
          {
            required: true,
            trigger: "change",
            message: this.trans("core.form.required"),
          },
        ],
      },
    };
  },
  methods: {
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
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(
          route("api.company.rekening.pagination"),
          _.merge(properties, customProperties)
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
    fetchBank() {
      axios
        .post(route("api.bank.list"))
        .then((response) => {
          this.banks = response.data.data;
        })
        .catch((err) => {});
    },
    addRekening() {
      this.resetForm();
      $(".rekening-form").modal();
    },
    resetForm() {
      this.form_rekening.bank_account_id = null;
      this.form_rekening.bank_id = "";
      this.form_rekening.rekening = "";
      this.form_rekening.atas_nama = "";
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
              this.form = new Form(this.form_rekening);
              this.form
                .post(this.getRoute())
                .then((response) => {
                  this.loadingForm = false;
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
                  this.fetchData();
                  this.onCancel();
                  this.resetForm();
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
    getRoute() {
      if (this.form_rekening.bank_account_id) {
        return route("api.company.rekening.submit", {
          rekening: this.form_rekening.bank_account_id,
        });
      }

      return route("api.company.rekening.create");
    },
    onCancel() {
      this.resetForm();
      $(".rekening-form").modal("hide");
    },
    goToEdit(scope) {
      this.addRekening();
      let data = scope.row;
      this.form_rekening.bank_account_id = data.bank_account_id;
      this.form_rekening.bank_id = parseInt(data.bank_id);
      this.form_rekening.rekening = data.rekening;
      this.form_rekening.atas_nama = data.atas_nama;
    },
    searchFunction() {
      var self = this;
      window.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
          self.fetchData();
        }
      });
    },
    cancel() {
      this.request.cancel();
    },
  },
  mounted() {
    this.fetchBank();
    this.fetchData();
    // this.searchFunction();
    this.form_rekening.company_id = this.company_id;
  },
};
</script>
<style>
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
