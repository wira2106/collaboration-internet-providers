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
            <router-link :to="{ name: 'dashboard.index' }">
              {{ trans("core.breadcrumb.home") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link
              :to="{ name: 'admin.company.paketberlangganans.index' }"
            >
              {{ trans(`paketberlangganans.${pageTitle}`) }}
            </router-link>
          </li>
        </ol>
      </div>
      <div class="">
        <el-tooltip
          :content="trans('core.tooltip.paket.kembali')"
          placement="top"
        >
          <button
            class="right-side-toggle waves-effect waves-light btn-inverse btn btn-circle btn-sm pull-right m-l-10"
          >
            <i class="ti-settings text-white"></i>
          </button>
        </el-tooltip>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
              <el-form
                :model="formIsp"
                ref="formIsp"
                v-loading.body="loading"
                label-width="150px"
                label-position="left"
              >
                <div class="card card-outline-info" style="margin-top: 15px;">
                  <div class="card-header text-white">
                    <div class="pull-left">
                      {{ trans("paketberlangganans.form.create isp form") }}
                    </div>
                  </div>
                  <div class="card-body">
                    <el-form-item
                      label="Daftar ISP"
                      :rules="{
                        required: true,
                        message: 'Data Tidak Boleh Kosong',
                        trigger: 'blur',
                      }"
                      prop="isp_id"
                    >
                      <el-select
                        v-model="formIsp.isp_id"
                        filterable
                        @change="show"
                      >
                        <el-option
                          v-for="listPaket in options2"
                          :value="listPaket.company_id"
                          :label="listPaket.name"
                          :key="listPaket.company_id"
                        >
                          {{ listPaket.name }}
                        </el-option>
                      </el-select>
                    </el-form-item>
                    <el-form-item
                      label="Daftar Paket"
                      :rules="{
                        required: true,
                        message: 'Data Tidak Boleh Kosong',
                        trigger: 'blur',
                      }"
                      prop="paket_id"
                      v-show="showSelect"
                    >
                      <el-select v-model="formIsp.paket_id" multiple filterable>
                        <el-option
                          v-for="isp in options1"
                          :label="isp.nama_paket"
                          :value="isp.paket_id"
                          :key="isp.paket_id"
                        >
                          {{ isp.nama_paket }}
                        </el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <el-tooltip
                          :content="trans('core.tooltip.paket.simpan')"
                          placement="top"
                        >
                          <el-button
                            class="pull-left"
                            type="primary"
                            @click="onSubmit()"
                            >{{ trans("core.button.save") }}</el-button
                          >
                        </el-tooltip>
                        <el-tooltip
                          :content="trans('core.tooltip.paket.kembali')"
                          placement="top"
                        >
                          <el-button
                            class="pull-left"
                            type="danger"
                            @click="$router.go(-1)"
                            >{{ trans("core.button.cancel") }}</el-button
                          >
                        </el-tooltip>
                      </div>
                    </div>
                  </div>
                </div>
              </el-form>
            </div>
            <div class="col-md-2"></div>
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

export default {
  data() {
    return {
      formIsp: {
        isp_id: "",
        paket_id: [],
      },
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      options1: [],
      options2: [],
      links: {},
      searchQuery: "",
      loading: false,
      showSelect: false,
    };
  },
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
  },
  methods: {
    show() {
      return (this.showSelect = true);
    },
    getPaket() {
      axios.get(route("api.company.paketforisp.option")).then((response) => {
        this.options1 = response.data.paket.data;
        this.options2 = response.data.isp.data;
      });
    },
    fetchData() {
      this.tableIsLoading = true;
      this.getPaket();
    },
    onSubmit(formIsp) {
      this.form = new Form(this.formIsp);
      const toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      });
      this.$refs["formIsp"].validate((valid) => {
        if (valid) {
          console.log(valid);
          Swal.fire({
            title: this.trans("core.modal.confirmasi-save"),
            showCancelButton: true,
            confirmButtonText: "Simpan",
            cancelButtonText: "Batal",
          }).then((result) => {
            console.log(result.value);
            this.loading = true;
            if (result.value) {
              this.form
                .post(route("api.company.paketforisp.store"))
                .then((response) => {
                  this.loading = false;
                  this.$router.push({
                    name: "admin.company.paketberlangganan.index",
                  });
                });
              toast.fire({
                icon: "success",
                title: this.trans("core.messages.data disimpan"),
              });
            } else {
              toast.fire({
                icon: "error",
                title: this.trans("core.messages.bata disimpan"),
              });
            }
          });
        } else {
          return false;
        }
      });
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>
