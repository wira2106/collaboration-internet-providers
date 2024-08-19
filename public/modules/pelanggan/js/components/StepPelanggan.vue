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
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <el-steps
                :active="activeSteps"
                finish-status="success"
                align-center
              >
                <el-step title="Sales Order"></el-step>
                <el-step title="Survey"></el-step>
                <el-step title="Instalasi"></el-step>
                <el-step title="Aktivasi"></el-step>
              </el-steps>
            </div>
          </div>
        </div>
      </div>
      <SaldoPelanggan />
      <div v-if="0 <= stepSekarang && activeSteps == 0">
        <SalesOrder :stepSekarang="stepSekarang" />
      </div>
      <div v-if="1 <= stepSekarang && activeSteps == 1">
        <Survey :stepSekarang="stepSekarang" />
      </div>
      <div v-if="2 <= stepSekarang && activeSteps == 2">
        <Instalasi :stepSekarang="stepSekarang" />
      </div>
      <div v-if="3 <= stepSekarang && activeSteps == 3">
        <Aktivasi :stepSekarang="stepSekarang" />
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import UserRolesPermission from "../../../../Core/Assets/js/mixins/UserRolesPermission";
import SalesOrder from "./so/step";
import Survey from "./survey/step";
import Instalasi from "./instalasi/step";
import Aktivasi from "./aktivasi/step";
import SaldoPelanggan from "./SaldoPelanggan";

export default {
  mixins: [ShortcutHelper, TranslationHelper, UserRolesPermission],
  components: {
    SalesOrder: SalesOrder,
    Survey: Survey,
    Instalasi: Instalasi,
    Aktivasi: Aktivasi,
    SaldoPelanggan: SaldoPelanggan,
  },
  data() {
    return {
      activeSteps: -1,
      stepSekarang: -1,
      jumlah_pelanggan: 0,
      loadingForm: false,
    };
  },
  methods: {
    setClickSteps() {
      let vm = this;
      $(".is-horizontal").each(function(i, obj) {
        let index = i;
        $(this).addClass("step_pelanggan");
        $(this).addClass("step_pelanggan_" + index);
        $(".step_pelanggan_" + index).click(function() {
          if (index <= vm.stepSekarang) {
            vm.setActiveStep(index);
            setTimeout(function() {
              $(".step_pelanggan").each(function(x, obj) {
                if (x <= vm.stepSekarang && x != index) {
                  $(this)
                    .find(".el-step__head")
                    .addClass("is-success");
                  $(this)
                    .find(".el-step__title")
                    .addClass("is-success");
                }
              });
            }, 100);
          }
        });
      });
    },
    fetchData() {
      let presales = this.$route.query.presales;
      presales = presales !== undefined && presales != "" ? presales : 0;
      let pelanggan = this.$route.query.pelanggan;
      pelanggan = pelanggan !== undefined && pelanggan != "" ? pelanggan : 0;

      axios
        .post(route("api.pelanggan.form.status"), {
          presales: presales,
          pelanggan: pelanggan,
        })
        .then((response) => {
          let data = response.data;
          if (data.allow == 0) {
            Swal.fire({
              title: "",
              text: data.messages,
              icon: "info",
              confirmButtonText: `Ok`,
              allowOutsideClick: false,
            }).then((result) => {
              window.location = route("dashboard.index");
            });
          } else {
            this.setActiveStep(data.status);
            this.stepSekarang = data.status;
          }
        });
    },
    setActiveStep(index) {
      this.activeSteps = index;
    },
  },
  mounted() {
    this.setClickSteps();
    this.fetchData();
  },
};
</script>
<style type="text/css">
.step_pelanggan {
  cursor: pointer;
}
</style>
