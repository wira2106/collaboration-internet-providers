<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("wilayahs.title.form") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">
              {{ trans("core.breadcrumb.home") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'admin.wilayah.wilayah.index' }">
              {{ trans("wilayahs.title.wilayahs") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            {{ trans(`wilayahs.${pageTitle}`) }}
          </li>
        </ol>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <el-form
            ref="wilayahForm"
            :model="wilayahForm"
            label-width="120px"
            label-position="top"
            v-loading.body="loading"
          >
            <div class="card card-outline-info">
              <div class="card-header">
                <h5 class="card-title text-white">
                  {{ trans(`wilayahs.${pageTitle}`) }}
                </h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 py-2">
                    <el-form-item
                      style="margin:0; padding:0"
                      :label="trans('wilayahs.form.name')"
                      :class="{
                        'el-form-item is-error': form.errors.has('name'),
                      }"
                      prop="name"
                      :rules="[
                        {
                          validator: validatorRequired,
                          message: 'Wilayah name is required',
                          ref: 'name',
                          required: true,
                        },
                      ]"
                    >
                      <el-input
                        type="text"
                        :placeholder="trans('wilayahs.placeholder.name')"
                        v-model="wilayahForm.name"
                        size="small"
                        ref="name"
                      ></el-input>
                      <div
                        class="el-form-item__error"
                        v-if="form.errors.has('name')"
                        v-text="form.errors.first('name')"
                      ></div>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-3 py-2">
                        <el-form-item
                          style="margin-bottom:0; padding-bottom:0"
                          :label="trans('companies.form.provinces_id')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'provinces_id'
                            ),
                          }"
                          prop="provinces_id"
                          :rules="[
                            {
                              validator: validatorRequired,
                              message: 'Provinces is required',
                              ref: 'provinces_id',
                              required: true,
                            },
                          ]"
                        >
                          <SelectProvinces
                            v-model="wilayahForm.provinces_id"
                          ></SelectProvinces>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('provinces_id')"
                            v-text="form.errors.first('provinces_id')"
                          ></div>
                        </el-form-item>
                      </div>
                      <div class="col-md-3 py-2">
                        <el-form-item
                          style="margin-bottom: 2px;"
                          :label="trans('companies.form.regencies_id')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'regencies_id'
                            ),
                          }"
                          prop="regencies_id"
                          :rules="[
                            {
                              validator: validatorRequired,
                              required: true,
                              message: 'Regencies is required',
                              ref: 'regencies_id',
                            },
                          ]"
                        >
                          <SelectRegencies
                            v-model="wilayahForm.regencies_id"
                            :provinces_id="wilayahForm.provinces_id"
                          ></SelectRegencies>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('regencies_id')"
                            v-text="form.errors.first('regencies_id')"
                          ></div>
                        </el-form-item>
                      </div>
                      <div class="col-md-3 py-2">
                        <el-form-item
                          style="margin-bottom: 2px;"
                          :label="trans('companies.form.districts_id')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'districts_id'
                            ),
                          }"
                          prop="districts_id"
                          :rules="[
                            {
                              validator: validatorRequired,
                              required: true,
                              message: 'District is required',
                              ref: 'districts_id',
                            },
                          ]"
                        >
                          <SelectDistricts
                            v-model="wilayahForm.districts_id"
                            :regencies_id="wilayahForm.regencies_id"
                          ></SelectDistricts>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('districts_id')"
                            v-text="form.errors.first('districts_id')"
                          ></div>
                        </el-form-item>
                      </div>
                      <div class="col-md-3 py-2">
                        <el-form-item
                          style="margin-bottom: 2px;"
                          :label="trans('companies.form.villages_id')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'villages_id'
                            ),
                          }"
                          prop="villages_id"
                          :rules="[
                            {
                              validator: validatorRequired,
                              required: true,
                              message: 'Villages is required',
                              ref: 'villages_id',
                            },
                          ]"
                        >
                          <SelectVillages
                            v-model="wilayahForm.villages_id"
                            :districts_id="wilayahForm.districts_id"
                          ></SelectVillages>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('villages_id')"
                            v-text="form.errors.first('villages_id')"
                          ></div>
                        </el-form-item>
                      </div>
                      <div class="col-md-6 py-2">
                        <el-form-item
                          style="margin-bottom: 2px;"
                          :label="trans('wilayahs.form.postal_code')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'post_code'
                            ),
                          }"
                          prop="post_code"
                          :rules="[
                            {
                              validator: validatorRequired,
                              message: 'Postal Code is required',
                              ref: 'post_code',
                            },
                          ]"
                        >
                          <el-input
                            v-model="wilayahForm.post_code"
                            ref="post_code"
                            size="small"
                            type="number"
                          ></el-input>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('post_code')"
                            v-text="form.errors.first('post_code')"
                          ></div>
                        </el-form-item>
                      </div>
                      <div
                        class="col-md-6 py-2"
                        v-if="user_role == 'Super Admin'"
                      >
                        <el-form-item
                          style="margin-bottom: 2px;"
                          :label="trans('companies.title.companies')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'companies'
                            ),
                          }"
                          prop="company_id"
                          :rules="[
                            {
                              validator: validatorRequired,
                              message: 'Companies is required',
                              ref: 'company_id',
                            },
                          ]"
                        >
                          <el-select
                            v-model="wilayahForm.company_id"
                            ref="company_id"
                            placeholder="Select"
                            filterable
                            size="small"
                          >
                            <el-option
                              v-for="item in listCompany"
                              :key="item.value"
                              :label="item.label"
                              :value="item.value"
                              :data-id="item.value"
                            >
                            </el-option>
                          </el-select>
                        </el-form-item>
                      </div>
                      <div class="col-md-6 py-2">
                        <el-form-item
                          :label="trans('pengajuans.form.status.open')"
                        >
                          <el-switch v-model="status_open"></el-switch>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                  <div class="col-12" v-if="wilayahForm.wilayah_id === null">
                    <el-form-item
                      style="margin-bottom: 2px;"
                      :label="trans('wilayahs.form.endpoint')"
                      prop="endpoint"
                      :rules="[
                        {
                          required: true,
                          message: 'Endpoint is required',
                          ref: 'endpoint',
                        },
                      ]"
                    >
                      <el-input
                        v-model="address"
                        size="small"
                        id="auto_complete_endpoint"
                      >
                      </el-input>
                      <presale
                        style="height:400px"
                        v-model="wilayahForm.endpoint"
                        :idAutoComplete="'auto_complete_endpoint'"
                      />
                    </el-form-item>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <el-form-item>
                  <el-tooltip
                    :content="trans('core.tooltip.save')"
                    placement="top"
                  >
                    <el-button
                      type="primary"
                      @click="onSubmit('wilayahForm', false)"
                      :loading="loading"
                    >
                      {{ trans("core.button.save") }}
                    </el-button>
                  </el-tooltip>
                  <el-tooltip placement="top" content="simpan dan bayar">
                    <el-button
                      type="success"
                      @click="saveAndPay"
                      :loading="loading"
                      v-if="wilayahForm.wilayah_id === null"
                    >
                      {{ trans("core.button.save & pay") }}
                    </el-button>
                  </el-tooltip>
                  <el-tooltip
                    :content="trans('core.tooltip.cancel')"
                    placement="top"
                  >
                    <el-button type="danger" @click="onCancel('wilayahForm')">
                      {{ trans("core.button.cancel") }}
                    </el-button>
                  </el-tooltip>
                </el-form-item>
              </div>
            </div>
          </el-form>
        </div>
      </div>
    </div>

    <cart
      v-model="carts"
      @submit="onSubmit('wilayahForm', true)"
      :action="false"
    />
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import ShortcutHelper from "../../../../../Core/Assets/js/mixins/ShortcutHelper";
import SingleFileSelector from "../../../../../Media/Assets/js/mixins/SingleFileSelector";
import TranslationHelper from "../../../../../Core/Assets/js/mixins/TranslationHelper";
import ValidatorRequired from "../../../../../Core/Assets/js/mixins/ValidatorRequired";
import UserRolesPermission from "../../../../../Core/Assets/js/mixins/UserRolesPermission";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";
import PresaleVue from "./Presale.vue";
import SaldoVue from "../../../../../Saldo/Assets/components/Saldo/Saldo.vue";
import CartVue from "../../../../../Core/Assets/js/components/Cart.vue";

export default {
  components: {
    presale: PresaleVue,
    saldo: SaldoVue,
    cart: CartVue,
  },
  mixins: [
    ShortcutHelper,
    SingleFileSelector,
    TranslationHelper,
    UserRolesPermission,
    Toast,
    ValidatorRequired,
  ],
  props: {
    pageTitle: {
      default: null,
    },
  },
  data() {
    return {
      wilayahForm: {
        wilayah_id: null,
        name: "",
        provinces_id: "",
        regencies_id: "",
        villages_id: "",
        districts_id: "",
        post_code: "",
        company_id: "",
        open: 1,
        endpoint: [],
      },
      address: "",
      status_open: true,
      loading: false,
      form: new Form(),
      autocomplete: null,
      location: undefined,
      listCompany: [],
      user_role: "",
      listStatusPresale: [
        {
          label: "Request Presale",
          value: "request",
        },
        {
          label: "Review",
          value: "review",
        },
        {
          label: "Finish",
          value: "finish",
        },
      ],
    };
  },
  methods: {
    saveAndPay() {
      this.$refs["wilayahForm"].validate((valid) => {
        if (valid) {
          $("#modal-cart").modal("show");
        }
      });
    },
    onSubmit(formName, bayar) {
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
              let properties = {
                ...this.wilayahForm,
                bayar: bayar,
              };

              axios
                .post(this.getRoute(), properties)
                .then((response) => {
                  console.log(response);
                  this.loading = false;
                  this.Toast({
                    icon: "success",
                    title: response.data.message,
                  });
                  this.$router.push({
                    name: "admin.wilayah.wilayah.index",
                  });
                })
                .catch((err) => {
                  this.loading = false;
                  if (err.response.status === 417) {
                    return this.Toast({
                      icon: "info",
                      title: err.response.data.message,
                    });
                  }
                  this.Toast({
                    icon: "error",
                    title: "something error",
                  });
                });
            }
          });
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    },
    getRoute() {
      if (this.$route.params.wilayah !== undefined) {
        return route("api.wilayah.wilayah.update", {
          wilayah: this.$route.params.wilayah,
        });
      }
      return route("api.wilayah.wilayah.store");
    },
    fetchWilayah() {
      let routeUri = "";
      if (this.$route.params.wilayah !== undefined) {
        this.loading = true;
        routeUri = route("api.wilayah.wilayah.find", {
          wilayah: this.$route.params.wilayah,
        });
        axios
          .post(routeUri)
          .then((response) => {
            if (response.data.data.open === 1) {
              this.status_open = true;
            } else {
              this.status_open = false;
            }
            this.wilayahForm = response.data.data;
            this.loading = false;
          })
          .catch((error) => {
            if (error.response.status === 403) {
              this.$notify.error({
                title: this.trans("core.unauthorized"),
                message: this.trans("core.unauthorized-access"),
              });
              window.location = route("dashboard.index");
            }
            this.loading = false;
          });
      }
    },

    fetchCompany() {
      let routeUri = "";
      routeUri = route("api.company.company.list", {
        type: "osp",
      });
      axios.post(routeUri).then((response) => {
        response.data.forEach((item, index) => {
          this.listCompany.push({
            value: item.company_id,
            label: item.name,
          });
        });
      });
    },

    onCancel() {
      this.$router.push({
        name: "admin.wilayah.wilayah.index",
      });
    },
  },
  mounted() {
    this.$store.dispatch("config/getconfiguration");
    this.getRolesPermission();
    this.fetchWilayah();
    this.fetchCompany();
  },
  watch: {
    status_open() {
      if (this.status_open === true) {
        this.wilayahForm.open = 1;
      } else {
        this.wilayahForm.open = 0;
      }
      console.log(this.wilayahForm.open);
    },
  },
  computed: {
    saldo() {
      return this.$store.state.saldo.saldo;
    },
    carts() {
      if (!this.wilayahForm.endpoint) return [];
      return this.wilayahForm.endpoint.map((item) => {
        return {
          name: item.nama_end_point,
          qty: 1,
          harga: this.$store.state.config.config.biaya_ep,
        };
      });
    },
  },
};
</script>
<style>
.el-form--label-top .el-form-item__label {
  padding: 0;
  margin: 0;
}
</style>
