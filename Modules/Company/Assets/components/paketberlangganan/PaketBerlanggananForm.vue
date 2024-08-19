<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans(`paketberlangganans.${pageTitle}`) }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">{{
              trans("core.breadcrumb.home")
            }}</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link
              :to="{ name: 'admin.company.paketberlangganan.index' }"
            >
              {{ trans(`paketberlangganans.${pageTitle}`) }}
            </router-link>
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline-info" v-loading.body="loading">
            <div class="card-header text-white">
              <div class="pull-left">
                {{ trans(`paketberlangganans.${formTitle}`) }}
              </div>
            </div>
            <div class="card-body" style="margin-top:25px;">
              <div class="row">
                <div class="offset-md-2 col-md-8">
                  <el-form
                    :model="paketForm"
                    ref="paketForm"
                    label-width="150px"
                    label-position="left"
                  >
                    <el-form-item
                      :label="trans('paketberlangganans.form.osp')"
                      :prop="'company_id'"
                      :rules="{
                        required: true,
                        message: trans(
                          'paketberlangganans.validation.required',
                          { field: trans('paketberlangganans.form.osp') }
                        ),
                        trigger: 'blur',
                      }"
                      v-if="user_role == 'Super Admin' && showSelect"
                    >
                      <el-select v-model="paketForm.company_id" filterable>
                        <el-option
                          v-for="osp in optionOSP"
                          :label="osp.name"
                          :value="osp.company_id"
                          :key="osp.company_id"
                        >
                          {{ osp.name }}
                        </el-option>
                      </el-select>
                    </el-form-item>

                    <el-form-item
                      :label="trans('paketberlangganans.form.nama')"
                      :prop="'nama_paket'"
                      :rules="{
                        required: true,
                        message: this.trans(
                          'paketberlangganans.validation.required',
                          { field: trans('paketberlangganans.form.nama') }
                        ),
                        trigger: 'blur',
                      }"
                    >
                      <el-input
                        v-model="paketForm.nama_paket"
                        size="small"
                      ></el-input>
                    </el-form-item>

                    <el-form-item
                      :label="trans('paketberlangganans.form.biaya otc')"
                      :prop="'biaya_otc'"
                      :rules="[
                        {
                          required: true,
                          message: this.trans(
                            'paketberlangganans.validation.required',
                            {
                              field: trans('paketberlangganans.form.biaya otc'),
                            }
                          ),
                          trigger: 'blur',
                        },
                        { validator: validAmount },
                      ]"
                    >
                      <InputCurrency v-model="paketForm.biaya_otc">
                      </InputCurrency>
                      
                      <i style="line-height: 20px !important;
                          font-size: 12px;
                          display: block;">
                         {{trans('paketberlangganans.information otc')}}
                      </i>
                    </el-form-item>
                    <el-form-item
                      :label="trans('paketberlangganans.form.harga paket')"
                      :prop="'harga_paket'"
                      :rules="[
                        {
                          required: true,
                          message: this.trans(
                            'paketberlangganans.validation.required',
                            {
                              field: trans(
                                'paketberlangganans.form.harga paket'
                              ),
                            }
                          ),
                          trigger: 'blur',
                        },
                        { validator: validAmount },
                      ]"
                    >
                      <InputCurrency v-model="paketForm.harga_paket">
                      </InputCurrency>
                    </el-form-item>

                    <el-form-item
                      :label="trans('paketberlangganans.form.sla')"
                      :prop="'sla'"
                      :rules="[
                        {
                          required: true,
                          message: this.trans(
                            'paketberlangganans.validation.required',
                            { field: trans('paketberlangganans.form.sla') }
                          ),
                          trigger: 'blur',
                        },
                        { validator: validSLA },
                      ]"
                    >
                      <el-input
                        type="number"
                        min="0"
                        max="100"
                        v-model="paketForm.sla"
                        size="small"
                      >
                        <template slot="append"> % </template>
                      </el-input>
                    </el-form-item>
                  </el-form>
                </div>
              </div>
              <div class="col-12 d-flex justify-content-center">
                <el-button
                  class="pull-left"
                  type="danger"
                  @click="$router.go(-1)"
                  >{{ trans("core.button.cancel") }}</el-button
                >
                <el-button
                  type="primary"
                  @click="onSubmit()"
                  >{{ (this.$route.params.paketberlangganan == null?
                      trans("core.button.save"):
                      trans("core.button.update")
                    ) }}</el-button
                >

              </div>
            </div>
          </div>
          <!-- /.box -->
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
import UserRolesPermission from "../../../../Core/Assets/js/mixins/UserRolesPermission";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";

export default {
  mixins: [ShortcutHelper, UserRolesPermission, Toast],
  components: {
    InputCurrency: InputCurrencyVue,
  },
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
    formTitle: { default: null, String },
  },
  data() {
    return {
      paketForm: {
        company_id: "",
        nama_paket: "",
        biaya_otc: "",
        harga_paket: "",
        sla: "",
      },
      loading: false,
      form: new Form(),
      optionOSP: [],
      user_role: "",
      showSelect: "",
    };
  },
  methods: {
    onSubmit(paketForm) {
      this.form = new Form(this.paketForm);
      this.$refs["paketForm"].validate((valid) => {
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
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
                  this.$router.push({
                    name: "admin.company.paketberlangganan.index",
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
    fetchData() {
      let params = this.$route.params.paketberlangganan;
      this.loading = true;
      this.showSelect = false;
      if (params !== undefined) {
        let routeUrl = route("api.company.paketberlangganan.find", {
          paketberlangganan: params,
        });
        axios.get(routeUrl).then((response) => {
          if (response.data) {
            this.loading = false;
            this.paketForm.nama_paket = response.data.nama_paket;
            this.paketForm.biaya_otc = response.data.biaya_otc;
            this.paketForm.harga_paket = response.data.harga_paket;
            this.paketForm.sla = response.data.sla;
          }
        });
      } else {
        this.showSelect = true;
        axios
          .get(route("api.company.paketberlangganan.option"))
          .then((response) => {
            this.optionOSP = response.data.osp;
            this.loading = false;
          });
      }
    },
    getRoute() {
      let params = this.$route.params.paketberlangganan;
      if (params !== undefined) {
        return route("api.company.paketberlangganan.update", {
          paketberlangganan: params,
        });
      }
      return route("api.company.paketberlangganan.store");
    },
    validSLA(rule, value, callback) {
      if (value < 0) this.paketForm.sla = 0;
      if (value > 100) this.paketForm.sla = 100;
      callback();
    },
    validAmount(rule, value, callback) {
      if (value < 0)
        return callback(
          new Error(this.trans("configurations.validation.invalid number"))
        );
      callback();
    },
  },
  mounted() {
    this.getRolesPermission();
    this.fetchData();
  },
};
</script>
