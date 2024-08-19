<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans(`barangs.${pageTitle}`) }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">{{
              trans("core.breadcrumb.home")
            }}</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'admin.peralatan.barang.index' }">
              {{ trans(`barangs.${pageTitle}`) }}
            </router-link>
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <el-form
            :model="barangForm"
            ref="barangForm"
            v-loading.body="loading"
            label-width="150px"
            label-position="left"
          >
            <div class="row">
              <div class="col-md-2"></div>
              <div class="col-md-8">
                <div
                  class="card card-outline-info"
                  v-for="(barang, index) in barangForm.barang"
                  :key="index"
                  style="margin-top: 15px;"
                >
                  <div class="card-header text-white">
                    <div class="pull-left">
                      {{ trans("barangs.title.barangs") }}
                    </div>
                    <el-button
                      v-if="index > 0 && !$route.params.alat"
                      style="float: right; padding: 3px 0; text-decoration:none;"
                      type="text"
                      @click="removeInput(barang)"
                    >
                      <i class="el-icon-close"></i>
                    </el-button>
                  </div>
                  <div class="card-body">
                    <el-form-item
                      :label="trans('barangs.form.name')"
                      :prop="'barang.' + index + '.nama_barang'"
                      :rules="{
                        required: true,
                        message: trans('core.validation.required', {
                          field: trans('barangs.form.name'),
                        }),
                        trigger: 'blur',
                      }"
                    >
                      <el-input
                        v-model="barang.nama_barang"
                        :placeholder="trans('barangs.placeholder.nama')"
                      ></el-input>
                    </el-form-item>
                    <el-form-item
                      :label="trans('barangs.form.type')"
                      :prop="'barang.' + index + '.tipe_qty'"
                      :rules="{
                        required: true,
                        message: trans('core.validation.required', {
                          field: trans('barangs.form.type'),
                        }),
                        trigger: 'blur',
                      }"
                    >
                      <el-select v-model="barang.tipe_qty">
                        <el-option
                          v-for="option in options"
                          :value="option.value"
                          :key="option.value"
                        >
                          {{ option.text }}
                        </el-option>
                      </el-select>
                    </el-form-item>
                    <el-form-item
                      :label="trans('barangs.form.price')"
                      :prop="'barang.' + index + '.harga_per_pcs'"
                      :rules="{
                        required: true,
                        message: trans('core.validation.required', {
                          field: trans('barangs.form.price'),
                        }),
                        trigger: 'blur',
                      }"
                    >
                      <InputCurrency
                        v-model="barang.harga_per_pcs"
                        :placeholder="trans('barangs.placeholder.harga')"
                      >
                      </InputCurrency>
                    </el-form-item>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <el-button
                          class="pull-left"
                          type="primary"
                          @click="onSubmit()"
                          >{{ trans("core.save") }}</el-button
                        >
                        <el-button
                          class="pull-left"
                          type="danger"
                          @click="$router.go(-1)"
                          >{{ trans("core.button.cancel") }}</el-button
                        >
                      </div>
                      <div class="col-md-6">
                        <el-button
                          v-if="!$route.params.barang"
                          type="primary"
                          class="pull-right"
                          @click="addInput"
                        >
                          <i class="fa fa-plus"></i>
                        </el-button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2"></div>
            </div>
            <!-- /.box -->
          </el-form>
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
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";

export default {
  mixins: [ShortcutHelper, Toast],
  components: {
    InputCurrency: InputCurrencyVue,
  },
  data() {
    return {
      barangForm: {
        barang: [
          {
            nama_barang: "",
            tipe_qty: "",
            harga_per_pcs: "",
          },
        ],
      },
      options: [
        { text: "Item", value: "Item" },
        { text: "Meter", value: "Meter" },
        { text: "Kg", value: "Kg" },
      ],
      loading: false,
      form: new Form(),
    };
  },
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  methods: {
    onSubmit(barangForm) {
      this.form = new Form(this.barangForm);

      this.$refs["barangForm"].validate((valid) => {
        console.log(valid);
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
                  this.$router.push({ name: "admin.peralatan.barang.index" });
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
      let params = this.$route.params.barang;
      this.loading = true;
      console.log(this.$refs["barangForm"]);
      if (params !== undefined) {
        let routeUrl = route("api.peralatan.barang.find", { barang: params });
        axios.get(routeUrl).then((response) => {
          if (response.data) {
            this.loading = false;
            this.barangForm.barang[0].nama_barang = response.data.nama_barang;
            this.barangForm.barang[0].tipe_qty = response.data.tipe_qty;
            this.barangForm.barang[0].harga_per_pcs =
              response.data.harga_per_pcs;
            console.log(response.data);
          }
        });
      } else {
        this.loading = false;
      }
    },
    getRoute() {
      let params = this.$route.params.barang;
      if (params !== undefined) {
        return route("api.peralatan.barang.update", { barang: params });
      }
      return route("api.peralatan.barang.store");
    },
    addInput() {
      console.log("testing");
      this.barangForm.barang.push({
        nama_barang: "",
        tipe_qty: "",
        harga_per_pcs: "",
      });
    },
    removeInput(item) {
      var index = this.barangForm.barang.indexOf(item);
      if (index !== -1) {
        this.barangForm.barang.splice(index, 1);
      }
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>
