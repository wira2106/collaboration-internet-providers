<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("pengajuans.detail resource") }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">
              {{ trans("core.breadcrumb.home") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'admin.wilayah.pengajuan.index' }">
              {{ trans("pengajuans.title.pengajuans") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            {{ trans("pengajuans.title.pengajuans detail") }}
          </li>
        </ol>
      </div>
    </div>
    <div class="container">
      <div
        class="row"
        v-if="
          formDetail.status !== 'accepted' && formDetail.status !== 'rejected'
        "
      >
        <div class="col-md-12">
          <div class="card">
            <div class="card-body" v-loading.body="stepLoading">
              <el-steps
                :active="activeSteps"
                finish-status="success"
                align-center
              >
                <el-step :title="trans('pengajuans.step.pengajuan')"></el-step>
                <el-step
                  :title="trans('pengajuans.step.konfirmasiOSP')"
                ></el-step>
                <el-step
                  :title="trans('pengajuans.step.konfirmasiISP')"
                ></el-step>
              </el-steps>
            </div>
          </div>
        </div>
      </div>
      <div class="row pt-1">
        <div class="col-md-12">
          <!-- Card Detail -->
          <div class="card card-outline-info">
            <div class="card-header text-white">
              <h5 class="card-title text-white">
                {{ trans("pengajuans.detail resource") }}
              </h5>
            </div>
            <div class="card-body" v-loading.body="detailLoading">
              <div class="row">
                <div class="col-md-12 my-3 text-center">
                  <h3 style="display: inline-block">
                    {{ trans("pengajuans.detail resource") }}
                  </h3>
                </div>
                <div class="col-md-4">
                  <table style="font-size: 14px" class="table">
                    <tr class="text-center">
                      <td colspan="3">
                        <span
                          ><b>{{
                            trans("pengajuans.modal_pengajuan.wilayah")
                          }}</b></span
                        >
                      </td>
                    </tr>
                    <tr>
                      <td width="150">
                        {{ trans("pengajuans.modal_pengajuan.wilayah") }}
                      </td>
                      <td width="10">:</td>
                      <td>
                        <b>{{ this.formDetail.nama_wilayah }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td width="150">
                        {{ trans("pengajuans.modal_pengajuan.company") }}
                      </td>
                      <td width="10">:</td>
                      <td>{{ this.formDetail.nama_osp }}</td>
                    </tr>
                  </table>
                </div>

                <div class="col-md-4">
                  <table style="font-size: 14px" class="table">
                    <tr>
                      <td colspan="3" class="text-center">
                        <span
                          ><b>{{
                            trans("pengajuans.modal_pengajuan.diajukan oleh")
                          }}</b></span
                        >
                      </td>
                    </tr>
                    <tr class="pb-3">
                      <td width="150">
                        {{ trans("pengajuans.modal_pengajuan.tanggal") }}
                      </td>
                      <td width="10">:</td>
                      <td>{{ this.formDetail.created_at }}</td>
                    </tr>
                    <tr>
                      <td width="150">
                        {{ trans("pengajuans.modal_pengajuan.company") }}
                      </td>
                      <td width="10">:</td>
                      <td>{{ this.formDetail.nama_isp }}</td>
                    </tr>
                  </table>
                </div>

                <div class="col-md-4">
                  <table style="font-size: 14px" class="table">
                    <tr>
                      <td colspan="3" class="text-center">
                        <span
                          ><b>{{
                            trans("pengajuans.modal_pengajuan.status")
                          }}</b></span
                        >
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" class="text-center">
                        <a
                          href="#!"
                          class="btn btn-sm btn-warning"
                          v-if="this.formDetail.status == 'request'"
                        >
                          {{ this.formDetail.readable_status }}
                        </a>
                        <a
                          href="#!"
                          class="btn btn-sm btn-info"
                          v-if="this.formDetail.status == 'confirmed'"
                        >
                          {{ this.formDetail.readable_status }}
                        </a>
                        <a
                          href="#!"
                          class="btn btn-sm btn-danger"
                          v-if="this.formDetail.status == 'disagree'"
                        >
                          {{ this.formDetail.readable_status }}
                        </a>
                        <a
                          href="#!"
                          class="btn btn-sm btn-success"
                          v-if="this.formDetail.status == 'accepted'"
                        >
                          {{ this.formDetail.readable_status }}
                        </a>
                        <a
                          href="#!"
                          class="btn btn-sm btn-danger"
                          v-if="this.formDetail.status == 'rejected'"
                        >
                          {{ this.formDetail.readable_status }}
                        </a>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- End Card Detail -->

          <!-- Card alasan pengajuan -->
          <card-alasan-pengajuan
            :request_wilayah_id="formDetail.request_wilayah_id"
          >
          </card-alasan-pengajuan>

          <!-- End Card alasan pengajuan -->

          <!-- Card List Paket -->
          <div
            class="card"
            v-if="
              user_role == 'Admin OSP' ||
                !(
                  formDetail.status == 'request' ||
                  formDetail.status == 'rejected'
                )
            "
          >
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <PengajuanPaket
                    v-if="formDetail.osp_id"
                    :pengajuan="formDetail"
                    v-on:responsePaket="responsePaket($event)"
                  ></PengajuanPaket>
                </div>
              </div>
            </div>
          </div>
          <!-- End Card List Paket -->
          <!--Card Form Konfirmasi OSP-->
          <div
            class="card"
            v-if="
              user_role == 'Admin OSP' ||
                !(
                  formDetail.status == 'request' ||
                  formDetail.status == 'rejected'
                )
            "
          >
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="my-3 text-center">
                    <h3>{{ trans("pengajuans.form konfirmasi osp") }}</h3>
                    <div
                      class="pull-right"
                      v-if="formDetail.status == 'disagree'"
                    >
                      <el-tooltip v-if="user_role == 'Admin OSP'" content="hay">
                        <el-button
                          size="mini"
                          icon="el-icon-edit"
                          @click="editedAlasan()"
                        >
                        </el-button>
                      </el-tooltip>
                    </div>
                  </div>
                  <!-- Form Konfirmasi OSP -->
                  <el-form
                    ref="formDetail"
                    :model="formDetail"
                    :rules="rules"
                    v-loading.body="formKonfLoading"
                  >
                    <table class="table" v-if="!rejected">
                      <tr>
                        <td style="width: 25%">
                          {{ trans("pengajuans.form.toleransi") }}
                        </td>
                        <td width="10">:</td>
                        <td v-if="showPickAccepted(formDetail.status)">
                          {{ this.formDetail.toleransi }} Bulan
                        </td>
                        <td v-else>
                          <el-form-item prop="toleransi">
                            <el-input
                              type="number"
                              v-model="formDetail.toleransi"
                              size="small"
                            >
                              <template slot="append">bulan</template>
                            </el-input>
                          </el-form-item>
                        </td>
                      </tr>

                      <tr>
                        <td style="width: 25%">
                          {{ trans("pengajuans.form.awal kontrak") }}
                        </td>
                        <td width="10">:</td>
                        <td v-if="showPickAccepted(formDetail.status)">
                          {{ this.formDetail.start_date }}
                        </td>
                        <td v-else>
                          <el-form-item prop="start_date">
                            <el-date-picker
                              type="date"
                              v-model="formDetail.start_date"
                              size="small"
                            >
                            </el-date-picker>
                          </el-form-item>
                        </td>
                      </tr>

                      <tr v-if="showPickAccepted(formDetail.status)">
                        <td style="width: 25%">
                          {{ trans("pengajuans.form.akhir kontrak") }}
                        </td>
                        <td width="10">:</td>
                        <td>
                          {{ this.formDetail.end_date }}
                        </td>
                      </tr>
                      <tr v-else>
                        <td style="width: 25%">
                          {{ trans("pengajuans.form.lama kontrak") }}
                        </td>
                        <td width="10">:</td>
                        <td>
                          <el-form-item prop="lama_kontrak">
                            <el-input
                              type="number"
                              v-model="formDetail.lama_kontrak"
                              size="small"
                            >
                              <template slot="append">bulan</template>
                            </el-input>
                          </el-form-item>
                        </td>
                      </tr>

                      <tr>
                        <td style="width: 25%">
                          {{ trans("pengajuans.form.file") }}
                        </td>
                        <td
                          colspan="2"
                          v-if="showPickAccepted(formDetail.status)"
                        >
                          <el-upload
                            action="#"
                            :file-list="fileList"
                            :on-preview="handlePreview"
                          >
                          </el-upload>
                        </td>
                        <td colspan="2" class="text-center" v-else>
                          <el-form-item prop="file_kontrak">
                            <el-upload
                              class="upload-demo"
                              drag
                              action="#"
                              name="file[]"
                              :before-upload="beforeUpload"
                              :on-success="handleSuccess"
                              :on-preview="handlePreview"
                              :on-remove="handleRemove"
                              :on-exceed="handleExceed"
                              :file-list="formDetail.file_kontrak"
                              multiple
                            >
                              <i class="el-icon-upload"></i>
                              <div class="el-upload__text">
                                Drop file here or <em>click to upload</em>
                              </div>
                              <div class="el-upload__tip" slot="tip">
                                documents files with a size less than 500kb
                              </div>
                            </el-upload>
                          </el-form-item>
                        </td>
                      </tr>

                      <tr
                        class="text-center"
                        v-if="
                          conditionInfoStatus() &&
                            formDetail.status !== 'accepted' &&
                            formDetail.status !== 'disagree'
                        "
                      >
                        <td colspan="3">
                          <el-checkbox v-model="term">{{
                            trans("pengajuans.term")
                          }}</el-checkbox>
                        </td>
                      </tr>
                    </table>

                    <div v-else class="text-center">
                      <el-form-item
                        prop="alasan"
                        :label="trans('pengajuans.modal_pengajuan.alasan')"
                      >
                        <el-input
                          type="textarea"
                          :autosize="{ minRows: 8, maxRows: 8 }"
                          v-model="formDetail.alasan"
                        >
                        </el-input>
                      </el-form-item>
                    </div>
                  </el-form>
                  <!-- End Form Konfirmasi OSP -->
                </div>
              </div>
            </div>
            <div
              class="card-footer"
              v-if="
                !tolak &&
                  formDetail.status !== 'accepted' &&
                  user_role !== 'Admin OSP' &&
                  formDetail.status !== 'disagree'
              "
            >
              <div class="row text-center">
                <div class="col-md-12 pb-1">
                  <el-tooltip
                    :content="trans('core.tooltip.wilayah.terima')"
                    placement="top"
                  >
                    <el-button
                      :disabled="isDisabled"
                      type="primary"
                      style="width: 350px"
                      @click="onSubmit('formDetail')"
                      >{{ trans("saldos.button.terima") }}</el-button
                    >
                  </el-tooltip>
                </div>
                <div class="col-md-12">
                  <el-tooltip
                    :content="trans('core.tooltip.wilayah.tolak')"
                    placement="top"
                  >
                    <el-button
                      :disabled="isDisabled"
                      type="danger"
                      style="width: 350px"
                      @click="negosiasi()"
                      >{{ trans("saldos.button.tolak") }}</el-button
                    >
                  </el-tooltip>
                </div>
              </div>
            </div>
            <div class="card-footer" v-if="rejected">
              <div class="row text-center">
                <div class="col-md-12 pb-1">
                  <el-tooltip :content="trans('core.tooltip.save')">
                    <el-button
                      type="primary"
                      style="width: 350px"
                      @click="onSubmit('formDetail')"
                      >{{ trans("pengajuans.button.submit") }}</el-button
                    >
                  </el-tooltip>
                </div>
              </div>
            </div>
            <div
              class="card-footer"
              v-if="
                !rejected &&
                  conditionSelectStatus(formDetail.status) &&
                  formDetail.status === 'request'
              "
            >
              <div class="row text-center">
                <div class="col-md-12 pb-1">
                  <el-tooltip content="konfirmasi" placement="top">
                    <el-button
                      type="primary"
                      style="width: 350px"
                      @click="validatePaket()"
                      >{{ trans("pengajuans.button.konfirmasi") }}</el-button
                    >
                  </el-tooltip>
                </div>
                <div class="col-md-12">
                  <el-tooltip content="reject" placement="top">
                    <el-button
                      type="danger"
                      style="width: 350px"
                      @click="reject()"
                      >{{ trans("pengajuans.button.reject") }}</el-button
                    >
                  </el-tooltip>
                </div>
              </div>
            </div>
          </div>
          <!-- End Card Form Konfirmasi OSP -->
          <!--Card Button Save -->
          <div class="card">
            <div class="card-body">
              <div class="col-md-12">
                <!-- <el-button v-if="user_role != '' && user_role != 'Admin ISP'" type="primary" @click="onSubmit('formDetail')" :loading="loading">
                            {{ trans('core.save') }}
                        </el-button> -->
                <el-tooltip :content="trans('core.tooltip.kembali')">
                  <el-button @click="onCancel()" :loading="loading"
                    >{{ trans("core.back") }}
                  </el-button>
                </el-tooltip>
              </div>
            </div>
          </div>
          <!--End Card Button Save -->
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
import PengajuanPaket from "./PengajuanPaket";
import CardAlasanPengajuanVue from "../cards/CardAlasanPengajuan.vue";

export default {
  mixins: [ShortcutHelper, TranslationHelper, Toast, UserRolesPermission],
  components: {
    PengajuanPaket: PengajuanPaket,
    "card-alasan-pengajuan": CardAlasanPengajuanVue,
  },
  data() {
    return {
      id: null,
      formDetail: {
        status: "",
        lama_kontrak: "",
        alasan: null,
      },
      fileList: [],

      tolak: false,
      rejected: false,

      edited: false,
      term: false,
      jumlah_paket: -1,
      user_role: "",
      loading: false,
      stepLoading: false,
      detailLoading: false,
      formKonfLoading: false,
      activeSteps: 0,
      statusNow: "request",
      form: new Form(),
      rules: {
        alasan: [
          {
            required: true,
            message: this.trans("pengajuans.validation.required", {
              field: this.trans("pengajuans.modal_pengajuan.alasan"),
            }),
            trigger: "change",
          },
        ],
        toleransi: [
          {
            required: true,
            message: this.trans("pengajuans.validation.required", {
              field: this.trans("pengajuans.form.toleransi"),
            }),
            trigger: "change",
          },
          {
            validator: this.validInput,
          },
        ],
        start_date: [
          {
            required: true,
            message: this.trans("pengajuans.validation.required", {
              field: this.trans("pengajuans.form.awal kontrak"),
            }),
            trigger: "change",
          },
        ],
        lama_kontrak: [
          {
            required: true,
            message: this.trans("pengajuans.validation.required", {
              field: this.trans("pengajuans.form.lama kontrak"),
            }),
            trigger: "change",
          },
          {
            validator: this.validInput,
          },
        ],
        file_kontrak: [
          {
            required: true,
            message: this.trans("pengajuans.validation.required", {
              field: this.trans("pengajuans.form.file"),
            }),
            trigger: "change",
          },
        ],
      },
    };
  },
  computed: {
    isDisabled() {
      return !this.term;
    },
  },
  methods: {
    negosiasi() {
      this.formDetail.alasan = "";
      this.rejected = true;
      this.tolak = true;
    },
    reject() {
      this.formDetail.alasan = "";
      this.rejected = true;
    },
    fetchData(intoPaket = 0) {
      this.loading = true;
      this.formKonfLoading = true;
      this.stepLoading = true;
      this.detailLoading = true;
      this.required_pass = false;
      let routeUri = route("api.wilayah.pengajuan.detail", {
        id: this.id,
      });

      axios
        .post(routeUri)
        .then((response) => {
          if (response.data != null) {
            let data = response.data.data;
            this.formDetail = data;
            this.statusNow = data.status;
          }
          if (this.statusNow === "request") {
            this.formDetail.toleransi = "";
            this.formDetail.start_date = "";
            this.formDetail.end_date = "";
            this.formDetail.file_kontrak = [];
          }
          if (this.statusNow !== "request" && this.statusNow !== "rejected") {
            this.fileList = [];
            JSON.parse(this.formDetail.file_kontrak).forEach((index) => {
              this.fileList.push({
                name: index,
                url: window.location.origin + "/uploadfile/" + index,
              });
            });
          }
          this.step(this.user_role);
          this.loading = false;
          this.formKonfLoading = false;
          this.detailLoading = false;
          this.stepLoading = false;
        })
        .catch((error) => {
          this.loading = false;
          if (error.response.status === 403) {
            this.$notify.error({
              title: this.trans("core.unauthorized"),
              message: this.trans("core.unauthorized-access"),
            });
            window.location = route("dashboard.index");
          }
        });
    },
    conditionSelectStatus(status) {
      if (this.user_role == "Super Admin") {
        return true;
      } else if (this.user_role == "Admin OSP") {
        if (status != "accepted") {
          return true;
        }
      }
      return false;
    },
    conditionInfoStatus() {
      if (this.user_role == "Admin ISP") {
        return true;
      }
      return false;
    },
    showPickAccepted(status) {
      if (status == "request" && this.user_role == "Admin OSP") {
        return false;
      }

      return true;
    },
    requiredAlasan(rule, value, callback) {
      if (
        this.formDetail.status == "rejected" &&
        this.formDetail.alasan == null
      ) {
        return callback(new Error(this.trans("core.form.required")));
      }
      callback();
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
              this.loading = true;
              this.formKonfLoading = true;
              if (this.rejected && !this.tolak) {
                this.formDetail.status = "rejected";
                this.pendingReject = true;
              } else if (this.user_role == "Admin ISP" && !this.tolak) {
                this.formDetail.status = "accepted";
              } else if (this.user_role == "Admin ISP" && this.tolak) {
                this.formDetail.status = "disagree";
              } else {
                this.formDetail.status = "confirmed";
              }

              this.form = new Form(this.formDetail);

              this.form
                .post(route("api.wilayah.pengajuan.status"))
                .then((response) => {
                  // jika jumlah paketnya ada 0, bawa dia untuk mengisi paket berlangganan
                  // if (response.jumlah_paket == 0) {
                  //   this.fetchData(1);
                  // }else{
                  //   this.fetchData();
                  //   this.loading = false;
                  // }
                  this.rejected = false;
                  this.formKonfLoading = false;
                  this.fetchData();
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
                })
                .catch((error) => {
                  this.loading = false;
                  this.Toast({
                    icon: "error",
                    title: "error",
                  });
                  this.fetchData();
                });
            }
          });
        } else {
          return false;
        }
      });
    },
    resetAlasan() {
      if (this.formDetail.status != "rejected") {
        this.formDetail.alasan = null;
      }
    },
    validInput(rule, value, callback) {
      if (value < 0 || value > 100)
        return callback(
          new Error(this.trans("configurations.validation.invalid number"))
        );
      callback();
    },
    onCancel() {
      this.$router.push({
        name: "admin.wilayah.pengajuan.index",
      });
    },

    handleRemove(file, fileList) {
      var index = this.formDetail.file_kontrak.indexOf(file);
      if (index !== -1) {
        this.formDetail.file_kontrak.splice(index, 1);
      }
    },
    handleSuccess(response, file, fileList) {
      var vm = this;
      _.map(response, function(data) {
        file["uid"] = data;
        // vm.fileList.push(file);
      });
    },
    handleExceed(files, fileList) {
      this.$message.warning(
        `The limit is 3, you selected ${
          files.length
        } files this time, add up to ${files.length + fileList.length} totally`
      );
    },
    beforeUpload(file, fileList) {
      // console.log(String(file.type));
      const pdf = String(file.type) == "application/pdf";
      const isLt2M = file.size / 1024 / 1024 < 2;
      const name = file.name;

      if (!pdf) {
        Swal.fire(
          this.trans("pengajuans.messages.file kontrak"),
          "",
          "warning"
        );
        return false;
      }
      if (!isLt2M) {
        Swal.fire(this.trans("pengajuans.messages.file size"), "", "warning");
        return false;
      }
      if (pdf && isLt2M) {
        this.formDetail.file_kontrak.push(file);
      }
      // return pdf && isLt2M;
    },
    beforeRemove(file, fileList) {
      return this.$confirm(`do you really want to delete ${file.name}？`);
    },

    handlePreview(files) {
      window.open(files.url);
    },
    step(user) {
      if (user == "Admin ISP" || user == "Super Admin") {
        if (this.formDetail.status == "request") return (this.activeSteps = 0);
        if (this.formDetail.status == "confirmed")
          return (this.activeSteps = 1);
        if (this.formDetail.status == "disagree") return (this.activeSteps = 1);
        if (this.formDetail.status == "accepted") return (this.activeSteps = 3);
      } else {
        if (this.formDetail.status == "request") return (this.activeSteps = 1);
        if (this.formDetail.status == "disagree") return (this.activeSteps = 2);
        if (this.formDetail.status == "confirmed")
          return (this.activeSteps = 2);
        if (this.formDetail.status == "accepted") return (this.activeSteps = 3);
      }
    },
    editedAlasan() {
      this.formDetail.file_kontrak = [];
      this.formDetail.status = "request";
    },
    responsePaket(response) {
      this.jumlah_paket = response;
      return true;
    },
    validatePaket() {
      console.log(this.jumlah_paket);
      if (this.jumlah_paket > 0) {
        this.onSubmit("formDetail");
        return true;
      }
      Swal.fire(
        this.trans("pengajuans.messages.empty paket_isp"),
        "",
        "warning"
      );
      return false;
    },
  },
  mounted() {
    this.getRolesPermission();
    this.id = this.$route.params.id;
    this.fetchData();
  },
};
</script>
