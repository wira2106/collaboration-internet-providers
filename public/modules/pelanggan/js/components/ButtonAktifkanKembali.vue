<template>
  <div class="row">
    <div class="col-md-12">
      <el-button
        :disabled="disable == true"
        :hidden="disable == true"
        @click="aktifkan_step"
        type="primary"
        size="small"
        class="col-md-12 mt-4"
      >
        {{ trans("pelangganinstalasi.aktifkan") }}
      </el-button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import CurrencyHelper from "../../../../Core/Assets/js/mixins/CurrencyHelper";
import Toast from "../../../../../public/modules/core/js/mixins/Toast";
export default {
  mixins: [CurrencyHelper, Toast],
  props: ["buttonDisabled"],
  data() {
    return {
      disable: false,
      roles: "",
    };
  },
  methods: {
    checkSaldoSudahCukup() {
      return new Promise((resolve) => {
        this.loadingPengajuan = true;
        axios
          .get(
            route("api.pelanggan.saldo.check", {
              pelanggan_id: this.$route.query.pelanggan,
            })
          )
          .then((response) => {
            return resolve(response.data);
          });
      });
    },
    aktifkan() {
      var pelanggan = this.$route.query.pelanggan;
      axios
        .post(route("api.pelanggan.status.step.activate"), {
          pelanggan_id: pelanggan,
        })
        .then((res) => {
          this.$emit("changeStatusStep", "activate");
          var response = res.data;
          if (response.error == true) {
            var type = "error";
          } else {
            var type = "success";
          }
          Swal.fire({
            toast: true,
            icon: type,
            position: "top-end",
            timer: 3000,
            type: type,
            showConfirmButton: false,
            title: response.message,
          });
          this.$router.go();
        });
    },
    aktifkan_step() {
      Swal.fire({
        title: this.trans("core.messages.confirmation-form"),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then(async (result) => {
        if (result.value) {
          let check = await this.checkSaldoSudahCukup();
          if (check.cukup) {
            this.aktifkan();
          } else {
            this.bayarBiayaPelanggan("form", check.data);
          }
        }
      });
    },
    bayarBiayaPelanggan(form, data) {
      let total = parseInt(data.mrc) + parseInt(data.otc);
      let saldo = parseInt(data.saldo);
      let linkSaldo = window.origin + "/backend/saldo/topups";
      let sisa_saldo = saldo - total;
      let okButton = true;
      let cancelButton = true;
      let html = `
          <table style="width:100%">
            <tr><td>Saldo</td><td>:</td><td>${this.rupiah(data.saldo)}</td></tr>
            <tr><td colspan='3'><hr></td></tr>
            <tr><td>Biaya OTC</td><td>:</td><td>${this.rupiah(
              data.otc
            )}</td></tr>
            <tr><td>Biaya MRC</td><td>:</td><td>${this.rupiah(
              data.mrc
            )}</td></tr>`;

      if (data.terbayar != 0) {
        html += `<tr><th>Terbayar</th><td>:</td><td>- ${this.rupiah(
          data.terbayar
        )}</td></tr>`;
        total = total - data.terbayar;
        sisa_saldo = sisa_saldo + data.terbayar;
      }

      html += `<tr><th>Total Biaya</th><td>:</td><td>${this.rupiah(
        total
      )}</td></tr>
            <tr><td colspan='3'><hr></td></tr>
            <tr><th>Sisa Saldo</th><td>:</td><td>${this.rupiah(
              sisa_saldo
            )}</td></tr>
          </table>`;
      if (sisa_saldo < 0) {
        html += `
            <div class="py-2">
              <a href="${linkSaldo}">
                <button class="btn btn-md btn-primary ">Topup Sekarang</button>
              </a>
            </div>
            `;
        okButton = false;
        cancelButton = false;
      }
      Swal.fire({
        title: "",
        icon: "info",
        html: html,
        showCancelButton: cancelButton,
        showConfirmButton: okButton,
      }).then((result) => {
        if (result.value) {
          if (sisa_saldo < 0) {
            Swal.fire("Saldo tidak mencukupi", "", "error");
            this.loadingPengajuan = false;
          } else {
            axios
              .post(
                route("api.pelanggan.saldo.submit", {
                  pelanggan_id: this.$route.query.pelanggan,
                })
              )
              .then((response) => {
                if (response.data.errors) {
                  Swal.fire(response.data.message, "", "error");
                  this.loadingPengajuan = false;
                } else {
                  this.aktifkan();
                }
              })
              .catch((er) => {
                console.log(er);
                this.Toast({
                  icon: "error",
                  title: "error",
                });
                this.loadingPengajuan = false;
              });
          }
        } else {
          this.loadingPengajuan = false;
        }
      });
    },
  },
  watch: {
    buttonDisabled(value) {
      if (value == "1") {
        this.disable = false;
      } else {
        this.disable = true;
      }
      // this.disable = true;
    },
  },
};
</script>
