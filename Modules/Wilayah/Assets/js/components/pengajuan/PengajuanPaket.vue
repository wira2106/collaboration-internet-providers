<template>
  <div class="row">
    <div class="col-md-12 my-3 text-center">
      <h3 style="display: inline-block;" @click="fetchData()">
        {{ trans("paketberlangganans.title.paketberlangganans") }}
      </h3>
    </div>
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6">
          <div class="search">
            <el-input
              size="small"
              prefix-icon="el-icon-search"
              @keyup.native="performSearch"
              v-model="searchQuery"
            >
            </el-input>
          </div>
        </div>
        <div class="col-md-6" v-if="!hide_button_action">
          <div
            class="pull-right"
            v-if="user_role != '' && user_role != 'Admin ISP'"
          >
            <button
              type="button"
              class="btn btn-info btn-sm"
              @click="tambahPaketISP()"
            >
              <i class="fa fa-plus"></i>
              {{ trans("paketberlangganans.title.add paket") }}
            </button>
          </div>
        </div>
        <div class="col-md-12">
          <el-table
            :data="data_paket"
            stripe
            style="width: 100%"
            ref="pageTable"
            v-loading="tableIsLoading"
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
              prop="nama_paket"
              :label="trans('pengajuans.table.paket.nama paket')"
              sortable="custom"
            >
              <template slot-scope="scope">
                <a href="#!" @click="goToDetailPaket(scope)">
                  {{ scope.row.nama_paket }}
                </a>
              </template>
            </el-table-column>
            <el-table-column
              prop="biaya_otc"
              :label="trans('pengajuans.table.paket.biaya otc')"
              sortable="custom"
            >
              <template slot-scope="scope">
                {{ scope.row.biaya_otc_rp }}
              </template>
            </el-table-column>
            <el-table-column
              prop="biaya_mrc"
              :label="trans('pengajuans.table.paket.biaya mrc')"
              sortable="custom"
            >
              <template slot-scope="scope">
                {{ scope.row.harga_paket_rp }}
              </template>
            </el-table-column>
            <el-table-column
              prop="sla"
              :label="trans('pengajuans.table.paket.sla')"
              sortable="custom"
            >
              <template slot-scope="scope">
                {{ scope.row.sla_percent }}
              </template>
            </el-table-column>
            <el-table-column
              v-if="!hide_button_action"
              prop="actions"
              :label="trans('core.table.actions')"
            >
              <template slot-scope="scope">
                <el-button-group>
                  <el-tooltip
                    :content="trans('core.tooltip.paket.hapus')"
                    placement="top"
                  >
                    <delete-button
                      :scope="scope"
                      :rows="data_paket"
                      v-on:onDelete="tableIsLoading = $event"
                      v-on:onDeleteSuccess="fetchData"
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

    <div
      class="modal fade pilih-paket"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content" v-loading="loadingForm">
          <el-form
            ref="formPilihPaket"
            :model="formPilihPaket"
            label-width="120px"
            label-position="top"
          >
            <div class="modal-header">
              <h5 class="modal-title">
                {{ trans("pengajuans.form.choose paket") }}
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
                  <el-checkbox
                    v-model="checkAll"
                    @change="handleCheckAllChange"
                    >{{ trans("pengajuans.title.check all") }}</el-checkbox
                  >
                </div>
                <div class="col-md-12">
                  <el-checkbox-group
                    v-model="paket_select"
                    @change="handleCheckedChange"
                  >
                    <el-checkbox
                      v-for="val in listPaket"
                      :label="val.paket_id"
                      :key="val.paket_id"
                      style="width: 100%;"
                      >{{ val.nama_paket }}</el-checkbox
                    >
                  </el-checkbox-group>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="pull-right">
                <el-form-item>
                  <el-button
                    type="primary"
                    @click="onSubmit()"
                    :loading="loadingForm"
                  >
                    {{ trans("core.save") }}
                  </el-button>

                  <el-button @click="modalPaket(1)" :loading="loadingForm"
                    >{{ trans("core.button.cancel") }}
                  </el-button>
                </el-form-item>
              </div>
            </div>
          </el-form>
        </div>
      </div>
    </div>

    <div
      class="modal fade detail-paket"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ trans("pengajuans.title.detail paket") }}
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
                <table class="table table-sm" style="width: 100%;">
                  <tr>
                    <td>
                      <a href="#!">{{
                        trans("pengajuans.table.paket.nama paket")
                      }}</a>
                    </td>
                    <td>:</td>
                    <td>{{ detailPaket.nama_paket }}</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="#!">{{
                        trans("pengajuans.table.paket.biaya otc")
                      }}</a>
                    </td>
                    <td>:</td>
                    <td>{{ detailPaket.biaya_otc_rp }}</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="#!">{{
                        trans("pengajuans.table.paket.biaya mrc")
                      }}</a>
                    </td>
                    <td>:</td>
                    <td>{{ detailPaket.harga_paket_rp }}</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="#!">{{ trans("pengajuans.table.paket.sla") }}</a>
                    </td>
                    <td>:</td>
                    <td>{{ detailPaket.sla_percent }}</td>
                  </tr>
                </table>
              </div>
              <div class="col-md-12">
                <hr />
              </div>
              <div class="col-md-12">
                <p style="text-align: center;">
                  {{ trans("pengajuans.diskon.title") }}
                </p>
              </div>
              <div class="col-md-12" v-loading="loadingDiskon">
                <table
                  class="table table-sm table-striped"
                  style="width: 100%;"
                >
                  <thead>
                    <tr>
                      <th>{{ trans("pengajuans.table.no") }}</th>
                      <th>{{ trans("pengajuans.diskon.range") }}</th>
                      <th>{{ trans("pengajuans.diskon.diskon") }}</th>
                      <th>{{ trans("pengajuans.diskon.setelah diskon") }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(val, index) in diskons" :key="val.biaya_id">
                      <td>{{ indexDiskon(index) }}</td>
                      <td>{{ val.range }}</td>
                      <td>{{ val.percentage }}</td>
                      <td>{{ val.afterPercen }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="pull-right">
              <button
                class="btn btn-secondary"
                data-dismiss="modal"
                aria-label="Close"
              >
                {{ trans("core.button.close") }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import _ from "lodash";
import Form from "form-backend-validation";
import ShortcutHelper from "../../../../../Core/Assets/js/mixins/ShortcutHelper";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";
import TranslationHelper from "../../../../../Core/Assets/js/mixins/TranslationHelper";
import UserRolesPermission from "../../../../../Core/Assets/js/mixins/UserRolesPermission";

export default {
  mixins: [ShortcutHelper, TranslationHelper, Toast, UserRolesPermission],
  props: ["pengajuan"],
  data() {
    return {
      user_role: "",
      data: null,
      listPaket: [],
      paketOptions: [],
      paket_select: [],
      checkAll: false,
      loadingForm: false,
      formPilihPaket: {},
      form: new Form(),
      tableIsLoading: false,
      data_paket: [],
      jumlah_paket: -1,
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
      detailPaket: {},
      diskons: [],
      loadingDiskon: false,
    };
  },
  methods: {
    queryServer(customProperties) {
      const properties = {
        page: this.meta.current_page,
        per_page: this.meta.per_page,
        order_by: this.order_meta.order_by,
        order: this.order_meta.order,
        search: this.searchQuery,
        id_request: this.data.request_wilayah_id,
      };

      axios
        .post(
          route(
            "api.wilayah.pengajuan.paket.pagination",
            _.merge(properties, customProperties)
          )
        )
        .then((response) => {
          this.tableIsLoading = false;
          this.data_paket = response.data.data;
          this.meta = response.data.meta;
          this.links = response.data.links;
          this.order_meta.order_by = properties.order_by;
          this.order_meta.order = properties.order;
          this.$emit("responsePaket", this.data_paket.length);
        });
    },
    fetchData() {
      this.tableIsLoading = true;
      this.queryServer();
    },
    handleSizeChange(event) {
      console.log(`per page :${event}`);
      this.tableIsLoading = true;
      this.queryServer({ per_page: event });
    },
    handleCurrentChange(event) {
      console.log(`current page :${event}`);
      this.tableIsLoading = true;
      this.queryServer({ page: event });
    },
    handleSortChange(event) {
      console.log("sorting", event);
      this.tableIsLoading = true;
      this.queryServer({ order_by: event.prop, order: event.order });
    },
    modalPaket(hide = 0) {
      if (hide == 1) {
        $(".pilih-paket").modal("hide");
      } else {
        $(".pilih-paket").modal();
      }
    },
    tambahPaketISP() {
      let self = this;
      this.modalPaket();
      this.loadingForm = true;
      axios
        .post(
          route("api.wilayah.pengajuan.paket", {
            pengajuan_id: this.data.request_wilayah_id,
          })
        )
        .then((response) => {
          this.loadingForm = false;
          self.paket_select = [];
          response.data.forEach(function(value, index) {
            self.paket_select.push(value.paket_id);
            self.checkAll =
              self.paket_select.length === self.paketOptions.length;
          });
        });
    },
    handleCheckAllChange(val) {
      let idPaket = [];
      this.listPaket.forEach(function(val, index) {
        idPaket.push(val.paket_id);
      });
      this.paket_select = val ? idPaket : [];
    },
    handleCheckedChange(value) {
      let checkedCount = value.length;
      this.checkAll = checkedCount === this.paketOptions.length;
    },
    fetchPaketBerlangganan() {
      axios
        .post(
          route("api.company.paketberlangganan.list", {
            company: this.data.osp_id,
          })
        )
        .then((response) => {
          this.listPaket = this.paketOptions = response.data.data;
        })
        .catch((error) => {
          if (error.response.status === 403) {
            this.$notify.error({
              title: this.trans("core.unauthorized"),
              message: this.trans("core.unauthorized-access"),
            });
            window.location = route("dashboard.index");
          }
        });
    },
    goToDetailPaket(scope) {
      this.detailPaket = scope.row;
      $(".detail-paket").modal();
      this.loadingDiskon = true;
      axios
        .post(
          route("api.company.paketberlangganan.diskon", {
            paket_id: this.detailPaket.paket_id,
          })
        )
        .then((response) => {
          this.loadingDiskon = false;
          this.diskons = response.data.data;
        })
        .catch((error) => {
          this.loadingDiskon = false;
          this.Toast({
            icon: "error",
            title: this.trans("core.error 500 title"),
          });
        });
    },
    indexMethod(index) {
      return (this.meta.current_page - 1) * this.meta.per_page + index + 1;
    },
    indexDiskon(index) {
      return index + 1;
    },
    onSubmit() {
      if (this.paket_select.length == 0) {
        Swal.fire(
          this.trans("pengajuans.messages.empty select_paket"),
          "",
          "warning"
        );
        return false;
      }
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
          let data = {
            paket: this.paket_select,
            id: this.data.request_wilayah_id,
          };
          this.form = new Form(data);
          this.form
            .post(route("api.wilayah.pengajuan.paket.submit"))
            .then((response) => {
              this.loadingForm = false;
              this.Toast({
                icon: "success",
                title: response.message,
              });
              this.fetchData();
              this.modalPaket(1);
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
    },
    performSearch: _.debounce(function(query) {
      console.log(`searching:${query.target.value}`);
      this.tableIsLoading = true;
      this.queryServer({ search: query.target.value });
    }, 300),
  },
  mounted() {
    this.getRolesPermission();
    this.data = this.pengajuan;
    this.fetchPaketBerlangganan();
    this.fetchData();
  },
  computed: {
    hide_button_action: function() {
      if (this.pengajuan.status == "rejected") return true;
      if (this.user_role != "" && this.user_role != "Admin ISP") return false;
    },
  },
};
</script>
