<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor" @click="fetchInfoPelanggan">
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
      <template v-if="info.show">
        <div
          :class="'alert alert-' + info.type + ' alert-dismissible fade show'"
          role="alert"
        >
          {{ info.messages }}
          <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </template>
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12" v-if="timer_osp != null">
              <center>
                <h5>
                  Sisa waktu : <span>{{ timer_text_osp }}</span>
                </h5>
              </center>
              <hr />
            </div>
            
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
import SalesOrder from "./so/step";
import Survey from "./survey/step";
import Instalasi from "./instalasi/step";
import Aktivasi from "./aktivasi/step";
import SaldoPelanggan from "./SaldoPelanggan";
import PermissionsHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";

export default {
  mixins: [ShortcutHelper, TranslationHelper, PermissionsHelper],
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
      timer_osp: null,
      timer_text_osp: "",
      timer_pause: false,
      interval_timer: null,
      info: {
        show: false,
      },
    };
  },
  watch: {
    timer_osp: function(val) {
      this.timer_text_osp = this.convertTimer(val);
    },
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
    convertTimer(seconds) {
      var days = Math.floor(seconds / 24 / 60 / 60);
      var hoursLeft = Math.floor(seconds - days * 86400);
      var hours = Math.floor(hoursLeft / 3600);
      var minutesLeft = Math.floor(hoursLeft - hours * 3600);
      var minutes = Math.floor(minutesLeft / 60);
      var remainingSeconds = seconds % 60;
      function pad(n) {
        return n < 10 ? "0" + n : n;
      }

      let result = "";
      result += days > 0 ? pad(days) + " Hari " : "";
      result += hours > 0 ? pad(hours) + " Jam " : "";
      result += minutes > 0 ? pad(minutes) + " Menit " : "";
      result += remainingSeconds > 0 ? pad(remainingSeconds) + " Detik " : "";
      if (this.timer_pause) {
        result += " ( paused )";
      }
      return result;
    },
    fetchTimerOSP() {
      let pelanggan = this.$route.query.pelanggan;
      if (pelanggan !== undefined && pelanggan != "") {
        axios
          .get(route("api.pelanggan.timer.osp", { pelanggan: pelanggan }))
          .then((response) => {
            let data = response.data;
            if (data.status_pelanggan != "aktif") {
              this.timer_osp = data.sisa_waktu;
              this.timer_pause = data.pause;
              if (!data.pause) {
                let vm = this;
                this.interval_timer = setInterval(function() {
                  vm.timer_osp = vm.timer_osp - 1;
                  if (vm.timer_osp == 0) {
                    clearInterval(vm.interval_timer);
                  }
                }, 1000);
              }
            }
          });
      }
    },
    fetchInfoPelanggan() {
      let pelanggan = this.$route.query.pelanggan;
      if (pelanggan !== undefined && pelanggan != "") {
        axios
          .get(route("api.pelanggan.info.step", { pelanggan: pelanggan }))
          .then((response) => {
            let data = response.data;
            this.info = data;
          });
      }
    },
  },
  mounted() {
    this.setClickSteps();
    this.fetchData();
    this.fetchInfoPelanggan();
    if (this.user_roles.name == "Admin OSP") {
      this.fetchTimerOSP();
    }
  },
};
</script>
<style type="text/css">
.step_pelanggan {
  cursor: pointer;
}
</style>
