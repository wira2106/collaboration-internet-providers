<template>
  <div>
    <div class="modal-header" style="margin-top:-65px;">
      <div class="pull-left">
        <h3>
          <b>{{ trans("saldos.modal.modal withdraw") }}</b>
        </h3>
      </div>
    </div>
    <div class="modal-body" v-loading="modalLoading">
      <div class="row" v-if="konfirm">
        <div class="col-md-12">
          <el-form
            :model="konfirmasi"
            :rules="rules"
            ref="formConfirm"
            label-width="200px"
            label-position="left"
          >
            <div class="row">
              <div class="col-md-12">
                <ul class="list-group">
                  <li class="list-group-item text-center" style="border: 0px;">
                    <IconStatus
                      :status="{
                        status: data.status,
                        color: color,
                        size: size,
                      }"
                    ></IconStatus>
                    <!-- <span>{{trans('saldos.table.status')}}</span> -->
                    <h4>{{ data.status }}</h4>
                    <span
                      v-if="data.keterangan != null && data.keterangan != ''"
                    >
                      {{ data.keterangan }}
                    </span>
                  </li>
                  <li class="list-group-item" style="border: 0px;">
                    <table class="table">
                      <tr>
                        <td style="font-size: 14px;">
                          {{ trans("saldos.modal.jmlh penarikan") }}
                        </td>
                        <td>
                          <div style="padding-top:0px;">
                            <span
                              style="font-size: 14px;font-weight: 700;display: block;"
                            >
                              {{ data.created_at }}
                            </span>
                            <span
                              class="search-regex"
                              style="font-size:12px;display:block;"
                            >
                              <b>Rp.{{ data.amount }}</b>
                            </span>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td style="font-size: 14px;">
                          {{ trans("saldos.modal.rek penerima") }}
                        </td>
                        <td>
                          <div style="padding-top:0px;">
                            <span
                              style="font-size: 14px;font-weight: 700;display: block;"
                            >
                              {{ data.atas_nama }}
                            </span>
                            <span
                              class="search-regex"
                              style="font-size:12px;display:block;"
                            >
                              <b
                                >{{ data.nama_bank_penerima }}
                                {{ data.rekening_penerima }}</b
                              >
                            </span>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </li>
                  <div class="col-md-12" v-show="data.status == 'success'">
                    <div class="text-center">
                      <li class="list-group-item" style="border: 0px;">
                        <h5>{{ trans("saldos.form.bukti") }}</h5>
                        <a
                          class="fancybox"
                          v-bind:href="data.bukti_transfer"
                          data-width="2048"
                          data-height="1365"
                          data-fancybox-type="image"
                          v-bind:id="'data.topup_id'"
                        >
                          <img
                            :src="data.bukti_transfer"
                            alt="bukti transfer"
                            style="height:150px; width:150px;"
                          />
                        </a>
                      </li>
                    </div>
                  </div>
                </ul>
              </div>

              <div
                class="col-md-12 text-center"
                v-if="
                  data.status == 'pending' && tarik_saldo_id[1] == 'Super Admin'
                "
              >
                <label>{{ trans("saldos.form.bukti") }}</label>
                <el-form-item
                  class="upload-image-withdraw"
                  prop="bukti_transfer"
                >
                  <upload-avatar
                    :onSuccess="handleAvatarSuccess"
                    :beforeUpload="beforeAvatarUpload"
                    :image="konfirmasi.bukti_transfer"
                  >
                  </upload-avatar>
                </el-form-item>
              </div>
            </div>
            <div
              class="row text-center"
              v-if="
                data.status !== 'success' &&
                  data.status !== 'cancel' &&
                  tarik_saldo_id[1] == 'Super Admin'
              "
            >
              <div class="col-md-12 pb-1">
                <el-button
                  type="primary"
                  style="width: 350px;"
                  @click="onSubmit('success')"
                  >{{ trans("saldos.button.konfirmasi") }}</el-button
                >
              </div>
              <div class="col-md-12">
                <el-button
                  type="danger"
                  style="width: 350px;"
                  @click="reject(true, false)"
                  >{{ trans("saldos.button.batal") }}</el-button
                >
              </div>
            </div>
          </el-form>
        </div>
      </div>

      <div class="row" v-if="batal">
        <div class="col-md-12">
          <label>{{ trans("saldos.form.alasan") }}</label>
          <el-form ref="alasan" :model="konfirmasi" :rules="rules">
            <el-form-item prop="keterangan">
              <el-input
                type="textarea"
                :rows="5"
                v-model="konfirmasi.keterangan"
              >
              </el-input>
            </el-form-item>
          </el-form>
        </div>
        <div class="col-md-12 text-center pt-2">
          <el-button
            type="primary"
            style="width: 350px;"
            @click="onSubmit('cancel')"
          >
            {{ trans("saldos.button.simpan") }}
          </el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import _ from "lodash";
import Toast from "../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [Toast],
  props: ["tarik_saldo_id"],
  data() {
    return {
      data: [],
      modalLoading: false,
      color: "",
      size: "95px",
      keterangan: "",
      konfirm: true,
      batal: false,
      konfirmasi: {
        tarik_saldo_id: "",
        company_id: "",
        status: "",
        saldo: "",
        pengirim: "",
        keterangan: "",
        bukti_transfer: null,
        file_bukti_transfer: null,
      },
      rules: {
        keterangan: [
          {
            required: true,
            message: this.trans("saldos.validation.required", {
              field: this.trans("saldos.form.alasan"),
            }),
            trigger: "change",
          },
        ],
        bukti_transfer: [
          {
            required: true,
            message: this.trans("saldos.validation.required", {
              field: this.trans("saldos.form.bukti"),
            }),
            trigger: "change",
          },
        ],
      },
    };
  },
  watch: {
    tarik_saldo_id: function() {
      this.fetchData();
    },
  },
  methods: {
    onSubmit(status) {
      (this.konfirmasi.tarik_saldo_id = this.data.tarik_saldo_id),
        (this.konfirmasi.company_id = this.data.company_id),
        (this.konfirmasi.status = status),
        (this.konfirmasi.saldo = this.data.saldo),
        (this.konfirmasi.pengirim = this.data.atas_nama);

      this.form = new Form(this.konfirmasi);
      if (status == "success") {
        this.$refs["formConfirm"].validate((valid) => {
          if (valid) {
            this.confirm();
          } else {
            return false;
          }
        });
      } else {
        this.$refs["alasan"].validate((valid) => {
          if (valid) {
            this.confirm(true);
          } else {
            return false;
          }
        });
      }
    },
    confirm(cancel=false) {
      Swal.fire({
        title: (cancel?this.trans("withdraws.messages.cancel withdraw"):this.trans("withdraws.messages.confirm withdraw")),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then((result) => {
        if (result.value) {
          this.modalLoading = true;
          this.form
            .post(route("api.saldo.withdraw.update"))
            .then((response) => {
              this.Toast({
                icon: "success",
                title: response.message,
              });
              this.reject(false, true);
              this.$emit("modalResponse", "Response modal");
              this.modalLoading = false;
            })
            .catch((error) => {
              this.modalLoading = false;
              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title"),
              });
            });
        }
      });
    },
    fetchData() {
      // if(this.topup_id.status == 'success') this.color = "#67C23A";
      // if(this.topup_id.status == 'pending') this.color = "#909399";
      // if(this.topup_id.status == 'cancel') this.color = "#e94e11";
      this.data = this.tarik_saldo_id[0];
      this.reject(false, true);
    },
    reject(batal, konfirm) {
      this.modalLoading = true;
      this.batal = batal;
      this.konfirm = konfirm;
      this.modalLoading = false;
      $(".fancybox").fancybox();
    },
    handleAvatarSuccess(res, file) {
      this.konfirmasi.bukti_transfer = URL.createObjectURL(file.raw);
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === "image/jpeg" || file.type === "image/png";
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error(this.trans("saldos.messages.file upload format"));
      }
      if (!isLt2M) {
        this.$message.error(this.trans("saldos.messages.file upload size"));
      }
      this.konfirmasi.file_bukti_transfer = file;
      return isJPG && isLt2M;
    },
  },
  mounted() {
    this.fetchData();
    $(".fancybox").fancybox();
  },
};
</script>
<style type="text/css">
.upload-image-withdraw .el-form-item__content {
  margin-left: 0px !important;
}
.upload-image-withdraw .el-form-item__error {
  width: 100% !important;
}
</style>
