<template>
  <div>
     
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("pelanggans.title.pelanggans") }}
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
            <router-link :to="{ name: 'admin.pelanggan.pelanggans.index' }">
              {{ trans("pelanggans.title.pelanggans") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            {{ trans(`pelanggans.title.pelanggans`) }}
          </li>
        </ol>
      </div>
    </div>
    <div class="container">
      <div class="card card-outline-info">
        <div class="card-header text-white">
          {{ trans("pelanggans.title.pelanggans") }}
          <el-button
            v-if="user_roles.name == 'Admin ISP'"
            plain
            size="small"
            icon="el-icon-plus"
            class="pull-right"
            @click.prevent="goToCreate()"
          >
            {{ trans("pelanggans.button.create order") }}
          </el-button>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <el-tabs v-model="activeTab">
                <el-tab-pane
                  :label="trans('pelanggans.tab.list pelanggan')"
                  name="list_pelanggan"
                >
                  <PelangganAktif
                    :company="companies"
                    :wilayah="wilayahs"
                    v-if="
                      load_companies == 1 &&
                        load_wilayah == 1 &&
                        activeTab == 'list_pelanggan'
                    "
                  />
                </el-tab-pane>
                <el-tab-pane
                  :label="trans('pelanggans.tab.sales order')"
                  name="so"
                >
                  <SalesOrder
                    :company="companies"
                    :wilayah="wilayahs"
                    v-if="
                      load_companies == 1 &&
                        load_wilayah == 1 &&
                        activeTab == 'so'
                    "
                  />
                </el-tab-pane>
                <el-tab-pane
                  :label="trans('pelanggans.tab.survey')"
                  name="survey"
                >
                  <Survey
                    :company="companies"
                    :wilayah="wilayahs"
                    v-if="
                      load_companies == 1 &&
                        load_wilayah == 1 &&
                        activeTab == 'survey'
                    "
                  />
                </el-tab-pane>
                <el-tab-pane
                  :label="trans('pelanggans.tab.instalasi')"
                  name="instalasi"
                >
                  <Instalasi
                    :company="companies"
                    :wilayah="wilayahs"
                    v-if="
                      load_companies == 1 &&
                        load_wilayah == 1 &&
                        activeTab == 'instalasi'
                    "
                  />
                </el-tab-pane>
                <el-tab-pane
                  :label="trans('pelanggans.tab.aktivasi')"
                  name="aktivasi"
                >
                  <Aktivasi
                    :company="companies"
                    :wilayah="wilayahs"
                    v-if="
                      load_companies == 1 &&
                        load_wilayah == 1 &&
                        activeTab == 'aktivasi'
                    "
                  />
                </el-tab-pane>
                <el-tab-pane
                  :label="trans('pelanggans.tab.cancel')"
                  name="cancel"
                >
                  <PelangganCancel
                    :company="companies"
                    :wilayah="wilayahs"
                    v-if="
                      load_companies == 1 &&
                        load_wilayah == 1 &&
                        activeTab == 'cancel'
                    "
                  />
                </el-tab-pane>
              </el-tabs>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import UserRolesPermission from "../../../../Core/Assets/js/mixins/UserRolesPermission";
import PelangganAktif from "./pelanggan/index";
import SalesOrder from "./so/index";
import Survey from "./survey/index";
import Instalasi from "./instalasi/index";
import Aktivasi from "./aktivasi/index";
import PelangganCancel from './cancel';
export default {
  mixins: [ShortcutHelper, TranslationHelper, UserRolesPermission],
  components: {
    PelangganAktif,
    SalesOrder,
    Survey,
    Instalasi,
    Aktivasi,
    PelangganCancel
  },
  data() {
    return {
      activeTab: "list_pelanggan",
      companies: [],
      load_companies: 0,
      wilayahs: [],
      load_wilayah: 0,
    };
  },
  watch: {
    activeTab: function (val) {
      this.getJumlahList();
    },
  },
  methods: {
    fetchCompany() {
       
      axios
        .post(route("api.company.company.list"))
        .then((response) => {
           
          this.companies = response.data;
          this.load_companies = 1;
        })
        .catch((error) => {
           
        });
    },
    fetchWilayah() {
      axios
        .get(
          route("api.wilayah.company.get", {
            company_id: this.user_company.company_id,
          })
        )
        .then((response) => {
           
          let osp = [];
          response.data.forEach(function(val, index) {
            if (osp.indexOf(val.nama_osp) == -1) {
              osp.push(val.nama_osp);
            }
          });
          let option = [];
          osp.forEach(function(val, index) {
            option.push({ label: val, options: [] });
          });
          response.data.forEach(function(val, index) {
            for (var i = 0; i < option.length; i++) {
              if (option[i].label == val.nama_osp) {
                option[i].options.push(val);
                break;
              }
            }
          });
          this.wilayahs = option;
          this.load_wilayah = 1;
        })
        .catch((error) => {
           
        });
    },
    goToCreate() {
      this.$router.push({
        name: "admin.presale.presale.index",
      });
    },
    getJumlahList(){
      axios.get(route('api.pelanggan.list.jumlah'))
      .then((response) => {
        let data = response.data;
        setTimeout(() => {
          if(typeof data.so !== 'undefined'){
            $("#tab-so").append(`<span class="badge badge-pill badge-danger" style="position:absolute;">${data.so}</span>`);
          }
          if(typeof data.survey !== 'undefined'){
            $("#tab-survey").append(`<span class="badge badge-pill badge-danger" style="position:absolute;">${data.survey}</span>`);
          }
          if(typeof data.instalasi !== 'undefined'){
            $("#tab-instalasi").append(`<span class="badge badge-pill badge-danger" style="position:absolute;">${data.instalasi}</span>`);
          }
          if(typeof data.aktivasi !== 'undefined'){
            $("#tab-aktivasi").append(`<span class="badge badge-pill badge-danger" style="position:absolute;">${data.aktivasi}</span>`);
          }
         },1000);
      });
    }
  },
  mounted() {
    this.fetchWilayah();
    this.fetchCompany();
    if(this.user_roles.name != 'Super Admin'){
      this.getJumlahList();
    }
  },
};
</script>
