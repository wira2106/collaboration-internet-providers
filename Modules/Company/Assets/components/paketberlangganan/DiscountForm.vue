<template>
  <div>
    <el-form
      :model="diskonForm"
      ref="diskonForm"
      v-loading="loading"
      label-width="150px"
      label-position="left"
    >
      <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
          <div class="row">
            <div class="col-md-12">
              <el-form-item
                :label="trans('paketberlangganans.form.range')"
                required
              >
                <el-col :span="11">
                  <el-form-item
                    prop="start_pelanggan"
                    :rules="rules.start_pelanggan"
                  >
                    <el-input
                      type="number"
                      size="small"
                      placeholder="Min Pelanggan"
                      v-model="diskonForm.start_pelanggan"
                      style="width: 100%;"
                    ></el-input>
                  </el-form-item>
                </el-col>
                <el-col class="line" :span="2"><center>-</center></el-col>
                <el-col :span="11">
                  <el-form-item
                    prop="end_pelanggan"
                    :rules="rules.end_pelanggan"
                  >
                    <el-input
                      type="number"
                      size="small"
                      placeholder="Max Pelanggan"
                      v-model="diskonForm.end_pelanggan"
                      style="width: 100%;"
                    ></el-input>
                  </el-form-item>
                </el-col>
              </el-form-item>
            </div>
            <div class="col-md-12">
              <el-form-item
                :label="trans('paketberlangganans.form.diskon')"
                prop="diskon"
                :rules="rules.diskon"
              >
                <el-input
                  type="number"
                  v-model="diskonForm.diskon"
                  size="small"
                  :label="trans('paketberlangganans.form.diskon')"
                  prop="diskon"
                >
                  <template slot="append">% </template>
                </el-input>
              </el-form-item>
            </div>
            <div class="col-md-12">
              <el-form-item
                :label="trans('paketberlangganans.form.harga paket')"
              >
                <input-currency
                  v-model="data_paket.harga_paket"
                  size="small"
                  readonly
                >
                </input-currency>
              </el-form-item>
            </div>
            <div class="col-md-12 setelah-diskon-form">
              <el-form-item
                :label="trans('paketberlangganans.form.harga setelah diskon')"
              >
                <input-currency
                  v-model="data_paket.harga_setelah_diskon"
                  size="small"
                  readonly
                >
                </input-currency>
              </el-form-item>
            </div>
            <div class="col-md-12">
              <br />
            </div>
            <div class="col-md-12">
              <el-button class="pull-left" type="primary" @click="onSubmit()">{{
                trans("core.button.save")
              }}</el-button>

              <el-button class="pull-left" type="danger" data-dismiss="modal">{{
                trans("core.button.cancel")
              }}</el-button>
            </div>
          </div>
        </div>
        <div class="col-md-1"></div>
      </div>
    </el-form>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import _ from "lodash";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";
export default {
  mixins: [Toast],
  props: ["form_diskon", "edit_data"],
  components: {
    "input-currency": InputCurrencyVue,
  },
  data() {
    return {
      diskonForm: {
        diskon: 0,
        start_pelanggan: "",
        end_pelanggan: "",
      },
      data_paket: {
        harga_paket: 0,
        harga_setelah_diskon: 0,
      },
      rules: {
        diskon: [
          {
            required: true,
            message: this.trans("paketberlangganans.validation.required", {
              field: this.trans("paketberlangganans.form.diskon"),
            }),
            trigger: "change",
          },
          { validator: this.validPercen, trigger: "change" },
        ],
        start_pelanggan: [
          {
            required: true,
            message: this.trans("paketberlangganans.validation.required", {
              field: this.trans("paketberlangganans.form.range"),
            }),
            trigger: "change",
          },
          { validator: this.validAmount, trigger: "change" },
        ],
        end_pelanggan: [
          {
            required: true,
            message: this.trans("paketberlangganans.validation.required", {
              field: this.trans("paketberlangganans.form.range"),
            }),
            trigger: "change",
          },
          { validator: this.validAmount, trigger: "change" },
        ],
      },
      loading: false,
    };
  },
  watch: {
    "diskonForm.diskon": {
      handler: function(after, before) {
        this.setSetelahDiskon();
      },
    },
  },
  methods: {
    getDiskon() {
      axios
        .get(
          route("api.company.diskonpaketberlangganan.find", {
            diskon: this.edit_data,
          })
        )
        .then((response) => {
          this.loading = false;
          this.diskonForm.diskon = response.data.diskon;
          this.diskonForm.start_pelanggan = response.data.start_pelanggan;
          this.diskonForm.end_pelanggan = response.data.end_pelanggan;
        });
    },
    getRoute() {
      if (this.edit_data) {
        // console.log(this.edit_data);
        return route("api.company.diskonpaketberlangganan.update", {
          diskon: this.edit_data,
        });
      }
      return route("api.company.diskonpaketberlangganan.store", {
        diskon: this.form_diskon.paket_id,
      });
    },
    fetchData() {
      this.loading = true;
      // console.log(this.edit_data);
      if (this.edit_data) {
        this.getDiskon();
      } else {
        this.loading = false;
      }
    },
    onSubmit(diskonForm) {
      this.form = new Form(this.diskonForm);
      this.$refs["diskonForm"].validate((valid) => {
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
              this.form
                .post(this.getRoute())
                .then((response) => {
                  this.loading = false;
                  this.$emit("handleSuccess", true);
                  this.Toast({
                    icon: "success",
                    title: this.trans(
                      "paketberlangganans.messages.diskon save"
                    ),
                  });
                })
                .catch((error) => {
                  this.loading = false;
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
    validPercen(rule, value, callback) {
      if (value < 0) this.diskonForm.diskon = 0;
      if (value > 100) this.diskonForm.diskon = 100;
      callback();
    },
    validAmount(rule, value, callback) {
      if (value < 0)
        return callback(
          new Error(this.trans("configurations.validation.invalid number"))
        );
      callback();
    },
    setSetelahDiskon() {
      let diskon = this.diskonForm.diskon;
      let hargaPaket = this.data_paket.harga_paket_default;
      let afterDiskon;
      if (diskon < 0) {
        afterDiskon = 0;
      } else {
        afterDiskon =
          hargaPaket - (parseInt(hargaPaket) * parseInt(diskon)) / 100;
      }
      this.data_paket.harga_setelah_diskon = afterDiskon;
    },
  },
  mounted() {
    this.fetchData();
    this.data_paket = this.form_diskon;
    this.setSetelahDiskon();
  },
};
</script>
<style type="text/css">
.setelah-diskon-form .el-form-item__label {
  line-height: 20px !important;
}
</style>
