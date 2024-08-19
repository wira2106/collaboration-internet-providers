<template>
  <div>
    <div class="modal-header" style="margin-top: -65px">
      <div class="pull-left">
        <h3>
          <b>{{ trans("topups.title.top up saldo") }}</b>
        </h3>
      </div>
    </div>
    <div class="modal-body" v-loading="modalLoading">
      <div class="row">
        <div class="col-md-12">
          <el-tabs v-model="activeTab">
            <el-tab-pane label="Manual" name="manual">
              <div class="row">
                <div class="col-md-12">
                  <el-form
                    :model="topupForm"
                    :rules="rules"
                    ref="topupForm"
                    label-width="200px"
                    label-position="top"
                  >
                    <el-form-item
                      :label="trans('saldos.form.rek')"
                      prop="bank_account_id"
                    >
                      <el-select
                        v-model="topupForm.bank_account_id"
                        style="display: block"
                        size="small"
                      >
                        <el-option
                          v-for="rek in radio"
                          :label="rek.namaBank + ' (' + rek.rekening + ')'"
                          :value="rek.bank_account_id"
                          :key="rek.bank_id"
                        >
                        </el-option>
                      </el-select>
                    </el-form-item>

                    <el-form-item
                      :label="trans('saldos.form.tgl transfer')"
                      prop="tgl_transfer"
                    >
                      <el-date-picker
                        type="date"
                        :picker-options="pickerOptions"
                        v-model="topupForm.tgl_transfer"
                        size="small"
                        style="width: 100%"
                      ></el-date-picker>
                    </el-form-item>

                    <el-form-item
                      :label="trans('saldos.form.nominal')"
                      prop="amount"
                    >
                      <!-- <el-input type="number" v-model="topupForm.amount" size="small"></el-input> -->
                      <InputCurrency v-model="topupForm.amount">
                      </InputCurrency>
                    </el-form-item>

                    <el-form-item
                      v-if="rekening"
                      :label="trans('saldos.form.rek pengirim')"
                      prop="rekening_pengirim"
                    >
                      <el-select
                        size="small"
                        v-model="rekening_pengirim"
                        filterable
                        @change="rekeningPengirim()"
                      >
                        <el-option
                          v-for="(item, index) in option"
                          :key="index"
                          :label="item.namaBank + ' (' + item.rekening + ')'"
                          :value="item.bank_account_id"
                        >
                        </el-option>
                        <el-option value="lain">
                          {{
                            trans("topups.form.masukan rekening lain")
                          }}</el-option
                        >
                      </el-select>
                    </el-form-item>

                    <el-form-item
                      v-if="rekeningLainnya"
                      :label="trans('saldos.form.rek pengirim')"
                      prop="bank_id"
                    >
                      <el-input
                        type="number"
                        v-model="topupForm.rekening_pengirim"
                        class="input-with-select"
                      >
                        <el-select
                          v-model="topupForm.bank_id"
                          slot="prepend"
                          style="width: 105px"
                        >
                          <el-option
                            v-for="ops in bank"
                            :key="ops.bank_id"
                            :label="ops.namaBank"
                            :value="ops.bank_id"
                          >
                            {{ ops.namaBank }}
                          </el-option>
                        </el-select>
                      </el-input>
                    </el-form-item>

                    <el-form-item
                      :label="trans('saldos.form.nama')"
                      prop="atas_nama"
                    >
                      <el-input
                        v-model="topupForm.atas_nama"
                        size="small"
                      ></el-input>
                    </el-form-item>

                    <el-form-item
                      :label="trans('saldos.form.bukti')"
                      prop="bukti_transfer"
                    >
                      <upload-avatar
                        class="text-center"
                        :onSuccess="handleAvatarSuccess"
                        :beforeUpload="beforeAvatarUpload"
                        :image="topupForm.bukti_transfer"
                      >
                      </upload-avatar>
                    </el-form-item>
                  </el-form>
                </div>
              </div>
            </el-tab-pane>
            <el-tab-pane label="BRIVA" name="otomatis">
              <div class="row">
                <div class="col-md-12">
                  <el-form
                    :model="brivaForm"
                    :rules="rules"
                    ref="brivaForm"
                    label-width="200px"
                    label-position="top"
                  >
                    <el-form-item
                      :label="trans('saldos.form.nominal')"
                      prop="amount"
                    >
                      <!-- <el-input type="number" v-model="topupForm.amount" size="small"></el-input> -->
                      <InputCurrency v-model="brivaForm.amount">
                      </InputCurrency>
                    </el-form-item>
                  </el-form>
                </div>
              </div>
            </el-tab-pane>
          </el-tabs>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <el-button
        type="danger"
        size="mini"
        :loading="modalLoading"
        data-dismiss="modal"
      >
        {{ trans("saldos.button.cancel") }}
      </el-button>
      <el-button
        size="mini"
        :loading="modalLoading"
        type="primary"
        @click="onSubmit()"
      >
        {{ trans("salesorders.button.top up") }}
      </el-button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import _ from "lodash";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";
// import Vue from 'vue';
export default {
  mixins: [Toast],
  components: {
    InputCurrency: InputCurrencyVue,
  },
  data() {
    return {
      pickerOptions: {
        disabledDate(time) {
          return time.getTime() > Date.now();
        },
        shortcuts: [
          {
            text: "Today",
            onClick(picker) {
              picker.$emit("pick", new Date());
            },
          },
          {
            text: "Yesterday",
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24);
              picker.$emit("pick", date);
            },
          },
          {
            text: "A week ago",
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit("pick", date);
            },
          },
        ],
      },
      topupForm: {
        bank_id: "",
        bank_account_id: "",
        rekening_pengirim: [
          {
            rek: "",
            atas_nama: "",
            bank_id: "",
          },
        ],
        amount: "",
        atas_nama: "",
        bukti_transfer: "",
        file_bukti_transfer: "",
        tgl_transfer: "",
        status: "",
      },
      brivaForm: {
        amount: 0,
      },
      activeTab: "otomatis",
      rekening_pengirim: null,
      rules: {
        bank_account_id: [
          {
            required: true,
            message: this.trans("saldos.validation.required", {
              field: this.trans("saldos.form.rek"),
            }),
            trigger: "change",
          },
        ],
        rekening_pengirim: [
          {
            required: true,
            message: this.trans("saldos.validation.required", {
              field: this.trans("saldos.form.rek pengirim"),
            }),
            trigger: "change",
          },
        ],
        bank_id: [
          {
            required: true,
            message: this.trans("saldos.validation.required", {
              field: this.trans("saldos.form.rek"),
            }),
            trigger: "change",
          },
        ],
        amount: [
          {
            required: true,
            message: this.trans("saldos.validation.required", {
              field: this.trans("saldos.form.nominal"),
            }),
            trigger: "change",
          },
          { validator: this.validAmount, trigger: "change" },
        ],
        tgl_transfer: [
          {
            required: true,
            message: this.trans("saldos.validation.required", {
              field: this.trans("saldos.form.tgl transfer"),
            }),
            trigger: "change",
          },
        ],
        atas_nama: [
          {
            required: true,
            message: this.trans("saldos.validation.required", {
              field: this.trans("saldos.form.nama"),
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
      radio: "",
      option: "",
      bank: "",
      atas_nama: "",
      modalLoading: false,
      rekening: true,
      rekeningLainnya: false,
      dialogImageUrl: "",
      dialogVisible: false,
      disabled: false,
    };
  },
  methods: {
    setNull() {
      if (this.$refs["topupForm"]) {
        this.rekening = true;
        this.rekeningLainnya = false;
        this.$refs["topupForm"].resetFields();
        this.topupForm.bank_id = null;
        this.topupForm.bukti_transfer = "";
        this.rekening_pengirim = null;
      }
    },
    onSubmit() {
      if (this.activeTab == "otomatis") {
        this.form = new Form(this.brivaForm);
        var formName = "brivaForm";
        var routeName = "api.saldo.topup.briva.create";
      } else {
        this.form = new Form(this.topupForm);
        var formName = "topupForm";
        var routeName = "api.saldo.topup.store";
      }
      this.$refs[formName].validate((valid) => {
        if (valid) {
          Swal.fire({
            title: this.trans("saldos.messages.confirmation-form"),
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
                .post(route(routeName))
                .then((response) => {
                  $(".topup").modal();
                  this.modalLoading = false;
                  if (response.errors) {
                    this.Toast({
                      icon: "error",
                      title: response.message,
                    });
                  } else {
                    this.Toast({
                      icon: "success",
                      title: response.message,
                    });
                    this.$emit("modalResponse", "Response modal");

                    let kekurangan = false;
                    if (localStorage.getItem("kekurangan_saldo")) {
                      kekurangan = true;
                    }

                    localStorage.removeItem("kekurangan_saldo");

                    if (kekurangan) {
                      setTimeout(() => {
                        this.$router.back();
                      }, 300);
                    }
                  }
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
        } else {
          return false;
        }
      });
    },
    getData() {
      axios.get(route("api.saldo.topup.rekening")).then((response) => {
        this.radio = response.data.rekeningOpenaccess.data;
        this.option = response.data.rekeningPengirim.data;
        this.bank = response.data.bank.data;
      });
    },
    rekeningPengirim() {
      this.modalLoading = true;
      if (this.rekening_pengirim == "lain") {
        this.rekeningLainnya = true;
        this.rekening = false;
        this.topupForm.rekening_pengirim = "";
      } else if (this.rekeningPengirim != null) {
        let index = this.option.findIndex(
          (x) => x.bank_account_id == this.rekening_pengirim
        );
        this.topupForm.atas_nama = this.option[index].atas_nama;
        this.topupForm.rekening_pengirim = this.option[index];
      }
      this.modalLoading = false;
    },
    fetchData() {
      this.getData();
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    handleDownload(file) {
      console.log(file);
    },
    handleAvatarSuccess(res, file) {
      this.topupForm.bukti_transfer = URL.createObjectURL(file.raw);
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === "image/jpeg" || file.type === "image/png";
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error(this.trans("saldos.messages.file upload format"));
      }
      // if (!isLt2M) {
      //   this.$message.error(this.trans("saldos.messages.file upload size"));
      // }
      this.topupForm.file_bukti_transfer = file;
      return isJPG;
    },
    validAmount(rule, value, callback) {
      if (value <= 0)
        return callback(
          new Error(this.trans("configurations.validation.invalid number"))
        );
      callback();
    },
  },
  mounted() {
    $(".topup").on("show.bs.modal", () => {
      this.setNull;
      if (localStorage.getItem("kekurangan_saldo")) {
        this.topupForm.amount = localStorage.getItem("kekurangan_saldo");
        this.brivaForm.amount = localStorage.getItem("kekurangan_saldo");
      }
    });
    this.fetchData();
  },
};
</script>
