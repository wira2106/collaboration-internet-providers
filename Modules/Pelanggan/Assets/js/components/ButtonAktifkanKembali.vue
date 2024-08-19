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
    <Cart :action="false" v-model="carts" @submit="bayar_biaya_pelanggan" id="cart-button-aktifkan-kembali" :loading="loadingBayarPelanggan"/>
  </div>
</template>

<script>

import axios from "axios";
import CurrencyHelper from "../../../../Core/Assets/js/mixins/CurrencyHelper";
import Toast from "../../../../../public/modules/core/js/mixins/Toast";
import Cart from '../../../../Core/Assets/js/components/Cart.vue';
export default {
  mixins: [CurrencyHelper, Toast],
  props: ["buttonDisabled"],
  components: {
    Cart
  },
  computed: {
    carts: function() {
      return [
        {
          name: 'Biaya MRC',
          qty: 1,
          harga: this.biaya.mrc
        },
        {
          name: 'Biaya OTC',
          qty: 1,
          harga: this.biaya.otc
        },
      ]
    }
  },
  data() {
    return {
      disable: false,
      roles: "",
      biaya: {
        mrc: 0,
        otc:0
      },
      loadingBayarPelanggan: false,
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
          this.$Progress.finish();
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
          this.$Progress.start();
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
      this.biaya.mrc = data.mrc;
      this.biaya.otc = data.otc;
      $('#cart-button-aktifkan-kembali').modal();
    },
    bayar_biaya_pelanggan() {
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
          this.loadingBayarPelanggan = true;
            axios
              .post(
                route("api.pelanggan.saldo.submit", {
                  pelanggan_id: this.$route.query.pelanggan,
                })
              )
              .then((response) => {
                this.loadingBayarPelanggan = false;
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
                this.loadingBayarPelanggan = false
                this.loadingPengajuan = false;
              });
        } else {
          this.loadingPengajuan = false;
        }
      })      
    }
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
