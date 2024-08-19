<template>
  <div>
    <div class="container mt-3">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-3" v-if="user_roles.name != 'Admin ISP'">
              <el-select
                v-if="companies.length > 0"
                v-model="company_id"
                placeholder="Select"
                filterable
                size="small"
                @change="fetchData"
              >
                <el-option label="Seluruh ISP" value="" data-id=""> </el-option>
                <el-option
                  v-for="(item, index) in companies_computed"
                  :key="index"
                  :label="item.name"
                  :value="item.company_id"
                >
                </el-option>
              </el-select>
            </div>
            <div class="col-md-3">
              <el-select
                v-model="wilayah_id"
                placeholder="Select"
                filterable
                size="small"
                @change="fetchData"
              >
                <el-option label="Seluruh Wilayah" value="" data-id="">
                </el-option>
                <el-option-group
                  v-for="group in wilayahs"
                  :key="group.label"
                  :label="group.label"
                >
                  <el-option
                    v-for="(item, index) in group.options"
                    :key="index"
                    :label="item.name"
                    :value="item.wilayah_id"
                  >
                  </el-option>
                </el-option-group>
              </el-select>
            </div>
          </div>
        </div>
        <div class="col-md-12 mt-2">
          <el-input
            size="small"
            :placeholder="trans('pelanggans.placeholder.cari pelanggan')"
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
    <div class="mt-3">
      <hr />
      <el-table
        style="width: 100%"
        size="small"
        :data="data"
        :row-class-name="tableRowClassName"
        v-loading.body="tableIsLoading"
      >
        <el-table-column
          :width="50"
          type="index"
          prop="nomor"
          :index="nomor"
          :label="trans('pelanggans.table.no')"
          sortable="custom"
        ></el-table-column>
        <el-table-column
          min-width="120"
          :label="trans('pelanggans.table.pelanggan')"
          prop="pelanggan"
        >
          <template slot-scope="scope">
            <a href="#!" @click.prevent="goToEdit(scope)">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.nama_pelanggan }}
              </text-highlight>
            </a>
            <br />
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.isp_name }}
            </text-highlight>
            <br />
            <i style="font-size:11px;" v-show="checkIsShowing(scope)">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.address }}
              </text-highlight>
            </i>
          </template>
        </el-table-column>
        <el-table-column
          :width="110"
          :label="trans('pelanggans.table.site id')"
        >
          <template slot-scope="scope">
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.site_id }}
            </text-highlight>
            <br />
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.wilayah_name }}
            </text-highlight>
          </template>
        </el-table-column>
        <el-table-column :label="trans('pelanggans.table.tlp mail')">
          <template slot-scope="scope">
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.telepon }}
            </text-highlight>
            <br />
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.email }}
            </text-highlight>
          </template>
        </el-table-column>
        <el-table-column :label="trans('pelanggans.table.paket')">
          <template slot-scope="scope">
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.nama_paket }}<br />
            </text-highlight>
            <br /><i>{{ scope.row.harga_paket }}</i>
          </template>
        </el-table-column>
        <el-table-column
          :width="150"
          :label="trans('pelanggans.table.actions')"
        >
          <template slot-scope="scope">
            <el-button-group>
              <el-tooltip
                class="item"
                effect="dark"
                :content="trans('pelanggans.tooltip.alasan')"
                placement="top"
              >
                <el-button
                  size="mini"
                  @click.prevent="showAlasanCancel(scope.row.alasan_cancel)"
                  icon="fa fa-comment"
                >
                </el-button>
              </el-tooltip>
              <!-- <delete-button></delete-button> -->
            </el-button-group>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-wrap" style="text-align: center; padding-top: 20px;">
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

    <div
      class="modal fade modal-suspend"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <el-form
          ref="form"
          :model="form"
          label-width="120px"
          label-position="top"
          v-loading="loading_modal"
          @keydown="form.errors.clear($event.target.name)"
          :rules="rules"
        >
          <div class="modal-content">
            <div class="modal-body" style="padding: 30px;">
              <div class="row">
                <!-- informasi wilayah -->
                <div class="col-md-12">
                  <h5 class="modal-title" style="display: inline-block;">
                    {{ trans("pelanggans.title.suspend pelanggan") }}
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
                <div class="col-md-12">
                  <hr />
                </div>
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <el-form-item
                        :label="trans('pelanggans.form.tgl suspend')"
                        prop="tgl_suspend"
                        :rules="rules.required_field"
                      >
                        <el-date-picker
                          style="width: 100%;"
                          v-model="form.tgl_suspend"
                          type="date"
                          value-format="yyyy-MM-dd"
                          placeholder="Pick a day"
                          size="small"
                        >
                        </el-date-picker>
                      </el-form-item>
                    </div>
                    <div class="col-md-12">
                      <el-form-item
                        :label="trans('pelanggans.form.alasan suspend')"
                        prop="alasan"
                        :rules="rules.required_field"
                      >
                        <el-input
                          style="width: 100%;"
                          type="textarea"
                          :rows="2"
                          placeholder="Please input"
                          v-model="form.alasan"
                        >
                        </el-input>
                      </el-form-item>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <hr />
                </div>
                <div class="col-md-12">
                  <div class="float-right">
                    <el-form-item>
                      <el-button
                        type="primary"
                        @click="onSubmit('form')"
                        :loading="loading_modal"
                      >
                        {{ trans("core.save") }}
                      </el-button>
                      <el-button @click="closeModal()">
                        {{ trans("core.button.cancel") }}
                      </el-button>
                    </el-form-item>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </el-form>
      </div>
    </div>
    <div class="modal fade modal-history-sla" id="myModal">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <modalHistorySLA
            ref="dataSLA"
            v-if="modal_sla"
            :pelanggan_id="id_for_sla"
            :bulan="bulan"
          ></modalHistorySLA>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import ShortcutHelper from "../../../../../Core/Assets/js/mixins/ShortcutHelper";
import SingleFileSelector from "../../../../../Media/Assets/js/mixins/SingleFileSelector";
import TranslationHelper from "../../../../../Core/Assets/js/mixins/TranslationHelper";
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";
import modalHistorySLA from "../modal/modalHistorySLA.vue";

export default {
  mixins: [
    ShortcutHelper,
    SingleFileSelector,
    TranslationHelper,
    Toast,
    PermissionsHelper,
  ],
  components: {
    modalHistorySLA: modalHistorySLA,
  },
  props: ["company", "wilayah"],
  data() {
    return {
      highlight: "",
      data: [],
      getData: [],
      searchQuery: "",
      tableIsLoading: false,
      id_for_sla: "",
      modal_sla: false,
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "ascending",
      },
      form: {
        pelanggan_id: null,
        tgl_suspend: null,
        alasan: null,
        suspend: 1,
      },
      rules: {
        required_field: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
        ],
      },
      request: null,
      loading_modal: false,
      company_id: "",
      wilayah_id: "",
      companies: [],
      wilayahs: [],
      bulan: "",
    };
  },
  computed: {
    companies_computed: function() {
      return this.companies.filter((item) => {
        if (item.type == "isp") {
          return item;
        }
      });
    },
  },
  methods: {
    fetchData() {
      this.tableIsLoading = true;
      if (this.request) this.cancel();
      this.queryServer();
    },
    queryServer(customProperties) {
      this.data = [];
      const cancelSource = axios.CancelToken.source();
      const properties = {
        params: {
          current_page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
          company_id: this.company_id,
          wilayah_id: this.wilayah_id,
          type: "cancel",
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };

      if (this.searchQuery !== null) {
        this.data = [];
      }

      axios
        .get(route("api.pelanggan.list"), _.merge(properties, customProperties))
        .then((response) => {
          if (typeof response !== "undefined") {
            if (this.searchQuery == null) {
              this.highlight = "";
            } else {
              this.highlight = this.searchQuery;
            }
            this.tableIsLoading = false;

            this.data = response.data.data;

            this.meta = response.data.meta;
            this.links = response.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
          }
        });
    },
    performSearch: _.debounce(function(query) {
      this.meta.current_page = 1;
      this.tableIsLoading = true;
      this.queryServer({
        search: query.target.value,
      });
    }, 300),
    nomor(index) {
      var nomor = this.meta.current_page - 1;
      var nomorr = nomor * this.meta.per_page;
      var jumlah = index + 1 + nomorr;
      return jumlah;
    },
    tableRowClassName({ row, rowIndex }) {
      if (rowIndex % 2 === 1) {
        return "success-row";
      }
    },
    handleSizeChange(event) {
      this.tableIsLoading = true;
      this.queryServer({
        per_page: event,
      });
    },
    handleCurrentChange(event) {
      this.tableIsLoading = true;
      this.queryServer({
        page: event,
      });
    },
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
    checkIsShowing(scope) {
      let str = this.highlight;
      if (scope.row.address != null) {
        if (str != "" && scope.row.address.toLowerCase().indexOf(str) != -1) {
          return true;
        }
      }

      return false;
    },
    goToSuspend(pelanggan_id) {
      var pelanggan = this.pelanggan_open_suspend;
      var index = pelanggan.findIndex((s) => s.pelanggan_id == pelanggan_id);
      if (index >= 0) {
        pelanggan.findIndex((s) => {
          this.$router.push({
            name: "admin.ticket.suspend.session",
            params: {
              id: s.ticket_id,
            },
          });
        });
      } else {
        this.$router.push({
          name: "admin.ticket.suspend.create.with.params",
          params: {
            pelanggan: pelanggan_id,
          },
        });
      }

      // if (pelanggan_id != this.form.pelanggan_id) {
      //   this.form.tgl_suspend = null;
      //   this.form.alasan = null;
      // }
      // this.form.pelanggan_id = pelanggan_id;
      // $(".modal-suspend").modal();
    },
    activeSuspend(pelanggan_id) {
      Swal.fire({
        title: this.trans("pelanggans.messages.aktifkan pelanggan"),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            title: "Loading..",
            html: "",
            allowOutsideClick: false,
            onOpen: () => {
              swal.showLoading();
            },
          });
          let insert = {
            pelanggan_id: pelanggan_id,
            suspend: 0,
          };
          axios
            .post(route("api.pelanggan.suspend"), insert)
            .then((response) => {
              let data = response.data;
              Swal.close();
              this.Toast({
                icon: "success",
                title: data.message,
              });
              this.fetchData();
            })
            .catch((error) => {
              Swal.close();
              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title"),
              });
            });
        }
      });
    },
    closeModal() {
      $(".modal-suspend").modal("hide");
    },
    showAlasanCancel(alasan) {
      console.log(alasan);
      if (alasan == null) {
        alasan = "";
      }
      Swal.fire(this.trans("pelanggans.title.alasan cancel"), alasan, "info");
    },
    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          Swal.fire({
            title: this.trans("pelanggans.messages.suspend pelanggan"),
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: this.trans("core.button.confirm"),
            cancelButtonText: this.trans("core.button.cancel"),
          }).then((result) => {
            if (result.value) {
              this.loading_modal = true;
              axios
                .post(route("api.pelanggan.suspend"), this.form)
                .then((response) => {
                  this.loading_modal = false;
                  this.Toast({
                    icon: "success",
                    title: response.data.message,
                  });
                  this.closeModal();
                  this.fetchData();
                })
                .catch((error) => {
                  this.loading_modal = false;
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
    goToEdit(scope) {
      this.$router.push({
        name: "admin.pelanggan.form.detail",
        params: {
          pelanggan_id: scope.row.pelanggan_id,
        },
      });
    },
    modalUp(value, bulan) {
      this.bulan = bulan;
      this.id_for_sla = "";
      this.modal_sla = true;
      this.id_for_sla = value;
      $(".modal-history-sla").modal("show");
    },
  },
  mounted() {
    if (this.user_roles.name == "Admin ISP") {
      this.company_id = this.user_company.company_id;
    }
    this.fetchData();
    // this.searchFunction();
    this.companies = this.company;
    this.wilayahs = this.wilayah;
  },
};
</script>
<style>
.el-table .success-row {
  background: #f5faff;
}
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
