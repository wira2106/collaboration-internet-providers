<template>
  <div>
    <div class="modal-header" style="margin-top: -65px">
      <div class="pull-left">
        <h3>
          <b>{{ trans("saldos.modal.tarik saldo") }}</b>
        </h3>
      </div>
    </div>
    <div class="modal-body" v-loading="modalLoading">
      <div class="row">
        <div class="col-md-12">
          <el-form :model="withdrawForm" :rules="rules" ref="withdrawForm">
            <el-form-item
              :label="trans('saldos.form.rek')"
              prop="bank_account_id"
              size="medium"
            >
              <el-select
                v-model="withdrawForm.bank_account_id"
                style="display: block"
              >
                <el-option
                  v-for="(rek, index) in selectRek"
                  :label="rek.namaBank + ' (' + rek.rekening + ')'"
                  :value="rek.bank_account_id"
                  :key="index"
                >
                </el-option>
                <el-option
                  :label="trans('saldos.form.tambah rekening')"
                  value="new"
                ></el-option>
              </el-select>
            </el-form-item>

            <el-form-item :label="trans('saldos.form.nominal')" prop="amount">
              <!-- <el-input type="number" v-model="withdrawForm.amount" size="small"></el-input> -->
              <InputCurrency v-model="withdrawForm.amount"></InputCurrency>
            </el-form-item>
            <el-form-item>
              <el-checkbox v-model="checked" @change="withdrawAll">{{
                trans("saldos.form.tarik")
              }}</el-checkbox>
            </el-form-item>
          </el-form>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <el-button
        size="small"
        :loading="modalLoading"
        type="primary"
        icon="el-icon-upload"
        @click="onSubmit()"
        >{{ trans("withdraws.button.withdraw") }}</el-button
      >
      <el-button
        size="small"
        type="danger"
        :loading="modalLoading"
        data-dismiss="modal"
        >{{ trans("saldos.button.cancel") }}</el-button
      >
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
  props: ["saldo", "company"],
  data() {
    return {
      withdrawForm: {
        bank_account_id: "",
        amount: "",
      },
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
      },
      selectRek: "",
      option: "",
      bank: "",
      checked: "",
      atas_nama: "",
      modalLoading: false,
      rekening: true,
      rekeningLainnya: false,
      dialogImageUrl: "",
      dialogVisible: false,
      disabled: false,
    };
  },
  watch: {
    "withdrawForm.bank_account_id": function (val) {
      if (val == "new") {
        $(".withdraw").modal("hide");
        let vm = this;
        setTimeout(function () {
          vm.$router.push({
            name: "admin.company.company.edit",
            params: { company: vm.company.company_id },
            query: { type: "rekening" },
          });
        }, 500);
      }
    },
    "withdrawForm.amount": function (val) {
      if (val != this.saldo) {
        this.checked = false;
      } else {
        this.checked = true;
      }
    },
  },
  methods: {
    withdrawAll() {
      console.log(this.saldo);
      if (this.checked) {
        this.withdrawForm.amount = this.saldo;
      } else {
        // this.withdrawForm.amount = 0;
      }
    },
    onSubmit() {
      this.form = new Form(this.withdrawForm);
      this.$refs["withdrawForm"].validate((valid) => {
        if (valid) {
          Swal.fire({
            title: this.trans("withdraws.messages.tarik saldo"),
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
                .post(route("api.saldo.withdraw.store"))
                .then((response) => {
                  if (response.errors) {
                    this.Toast({
                      icon: "error",
                      title: response.message,
                    });
                    reload();
                  }
                  $(".topup").modal();
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
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
        } else {
          return false;
        }
      });
    },
    getData() {
      axios.get(route("api.saldo.withdraw.rekening")).then((response) => {
        console.log(response.data);
        this.selectRek = response.data.rekeningCompany.data;
      });
    },
    rekeningPengirim(rek) {
      console.log(rek);
      this.modalLoading = true;
      if (rek == " ") {
        this.rekeningLainnya = true;
        this.rekening = false;
      } else {
        this.withdrawForm.atas_nama = rek.atas_nama;
      }
      this.modalLoading = false;
    },
    resetForm() {
      if (this.$refs["withdrawForm"]) {
        this.$refs["withdrawForm"].resetFields();
        this.checked = false;
      }
    },
    fetchData() {
      this.getData();
    },
    validAmount(rule, value, callback) {
      if (parseInt(value) > this.saldo) {
        return callback(
          new Error(this.trans("saldos.validation.withdraw saldo"))
        );
      } else if (parseInt(value) <= 0) {
        return callback(
          new Error(this.trans("configurations.validation.invalid number"))
        );
      }
      callback();
    },
    numberFormat() {
      var n = parseInt(this.withdrawForm.amount.replace(/\D/g, ""), 10);
      this.withdrawForm.amount = n.toLocaleString();
      if (
        isNaN(this.withdrawForm.amount) &&
        this.withdrawForm.amount.length == 3
      )
        this.withdrawForm.amount = 0;
      var test = this.withdrawForm.amount.replace(/\D/g, "");
      console.log(test);
    },
  },
  mounted() {
    $(".withdraw").on("hidden.bs.modal", this.resetForm);
    this.fetchData();
  },
};
</script>
