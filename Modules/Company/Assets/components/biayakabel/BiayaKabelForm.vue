<template>
  <bs-modal size="medium" id="modal-biaya-kabel" @onClose="onCancel()">
    <div slot="header">
      {{ trans_header_modal }}
    </div>

    <div class="container">
      <el-form
        ref="biaya-kabel"
        :model="biayakabel"
        :rules="rules"
        v-loading="loading_get_data"
        label-position="top"
      >
        <div class="col-12">
          <el-form-item
            :label="trans('biaya_kabel.form.panjang kabel')"
            prop="panjang_kabel"
            :rules="rules.panjang_kabel"
          >
            <el-input
              v-model="biayakabel.panjang_kabel"
              type="number"
              size="small"
            >
              <template slot="append">.meter</template>
            </el-input>
          </el-form-item>
        </div>
        <div class="col-12">
          <el-form-item
            :label="trans('biaya_kabel.form.biaya')"
            prop="biaya"
            :rules="rules.biaya"
          >
            <input-currency v-model="biayakabel.biaya"> </input-currency>
          </el-form-item>
        </div>
      </el-form>
    </div>

    <div slot="footer">
      <el-button
        type="primary"
        icon="el-icon-upload"
        @click="onSubmit('biaya-kabel')"
        :loading="form_loading"
      >
        {{ trans_button_save }}
      </el-button>

      <el-button @click="onCancel()">
        {{ trans("core.button.cancel") }}
      </el-button>
    </div>
  </bs-modal>
</template>

<script>
import axios from "axios";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import BsModalVue from "../../../../Presale/Assets/components/modal/BsModal.vue";
import Form from "form-backend-validation";
import Toast from "../../../../Core/Assets/js/mixins/Toast";

export default {
  props: ["pageTitle", "range_id", "company_id", "biaya_kabel_id"],
  mixins: [TranslationHelper, Toast],
  components: {
    "input-currency": InputCurrencyVue,
    "bs-modal": BsModalVue,
  },
  data() {
    return {
      form: null,
      form_loading: false,
      loading_get_data: false,
      biayakabel: {
        panjang_kabel: "",
        biaya: 0,
      },
      rules: {
        panjang_kabel: [
          {
            required: true,
            message: this.trans("core.validation.required", {
              field: this.trans("biaya_kabel.form.panjang kabel"),
            }),
            trigger: ["blur", "change"],
          },
        ],
        biaya: [
          {
            required: true,
            message: this.trans("core.validation.required", {
              field: this.trans("biaya_kabel.form.biaya"),
            }),
            trigger: ["blur", "change"],
          },
        ],
      },
    };
  },
  methods: {
    onCancel() {
      $("#modal-biaya-kabel").modal("hide");
      this.$emit("onCancel", true);
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
              this.form_loading = true;
              this.form = new Form({
                ...this.biayakabel,
                biaya_kabel_id: this.biaya_kabel_id,
              });
              this.form
                .post(this.getRoute())
                .then((response) => {
                  this.form_loading = false;
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
                  this.$emit("onSuccess", true);
                  $("#modal-biaya-kabel").modal("hide");
                  this.resetForm(formName);
                })
                .catch((error) => {
                  this.form_loading = false;
                  console.log(error.response);
                  if (error.response.status === 409) {
                    this.$emit(
                      "onConflict",
                      JSON.parse(error.response.data.message)
                    );
                    return;
                  }
                  this.Toast({
                    icon: "error",
                    title: this.trans("companies.messages.error"),
                  });
                });
            }
          });
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    getRoute() {
      if (this.range_id) {
        return route("api.company.biayakabel.range.update", {
          range_biaya_kabel: this.range_id,
          biaya_kabel: this.biaya_kabel_id,
        });
      }

      return route("api.company.biayakabel.range.create");
    },
    fetchBiayaKabel() {
      this.loading_get_data = true;
      axios
        .get(this.getRouteBiayaKabel())
        .then((response) => {
          this.biayakabel = response.data.data;
          this.loading_get_data = false;
        })
        .catch((err) => {});
    },
    getRouteBiayaKabel() {
      if (this.range_id) {
        return route("api.company.biayakabel.range.find", {
          range_biaya_kabel: this.range_id,
        });
      }

      return route("api.company.biayakabel.range.find-new");
    },
  },
  mounted() {
    this.fetchBiayaKabel();
  },
  watch: {
    range_id: function () {
      this.fetchBiayaKabel();
    },
  },
  computed: {
    trans_button_save: function () {
      if (this.range_id) return this.trans("biaya_kabel.button.update");

      return this.trans("biaya_kabel.button.save");
    },
    trans_header_modal: function () {
      if (this.range_id) return this.trans("biaya_kabel.edit resource");

      return this.trans("biaya_kabel.create resource");
    },
  },
};
</script>

<style></style>
