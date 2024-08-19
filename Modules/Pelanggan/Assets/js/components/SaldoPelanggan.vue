<template>
  <div class="card" v-show="show == true && total_biaya_non_RP != 0">
    <div class="card-body">
      <label style="margin: 0px"
        >{{ trans("pelanggans.saldo mengendap.total biaya") }} :
        {{ total_biaya }}</label
      >
      <hr style="margin: 10px 0px" />
      <label style="margin: 0px"
        >{{ trans("pelanggans.saldo mengendap.saldo mengendap") }} :
        {{ saldo_mengendap }}
        <b
          :class="status == 'kurang' ? 'text-danger' : 'text-success'"
          v-if="status != 'pas'"
        >
          (
          <span v-if="status == 'kurang'">{{
            trans("pelanggans.saldo mengendap.kekurangan biaya")
          }}</span>
          <span v-else>{{
            trans("pelanggans.saldo mengendap.kelebihan biaya")
          }}</span>
          : {{ selisih }} )
        </b>
      </label>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";

export default {
  mixins: [ShortcutHelper, TranslationHelper],
  data() {
    return {
      show: false,
      pelanggan_id: null,
      total_biaya: "",
      saldo_mengendap: "",
      selisih: "",
      status: "pas",
      total_biaya_non_RP: "",
    };
  },
  methods: {
    fetchSaldoPelanggan() {
      if (this.pelanggan_id != null) {
        this.show = true;
        axios
          .get(
            route("api.pelanggan.saldo.detail", {
              pelanggan_id: this.pelanggan_id,
            })
          )
          .then((response) => {
            if (response.data.show == 1) {
              let data = response.data.data;
              this.status = data.status;
              this.selisih = data.selisih;
              this.saldo_mengendap = data.saldo_mengendap_rp;
              this.total_biaya = data.total_biaya_rp;
              this.total_biaya_non_RP = data.total_biaya;
            } else {
              this.show = false;
            }
          });
      }
    },
    loadStatusStep() {
      let presales = this.$route.query.presales;
      presales = presales !== undefined && presales != "" ? presales : 0;
      let pelanggan = this.$route.query.pelanggan;
      pelanggan = pelanggan !== undefined && pelanggan != "" ? pelanggan : 0;

      axios
        .post(route("api.pelanggan.form.status"), {
          presales: presales,
          pelanggan: pelanggan,
        })
        .then((res) => {
          if (typeof res.data.hide == "undefined") {
            this.show = true;
          } else {
            this.show = false;
          }
        });
    },
  },
  mounted() {
    let pelanggan = this.$route.query.pelanggan;
    this.pelanggan_id =
      pelanggan !== undefined && pelanggan != "" ? pelanggan : null;
    this.fetchSaldoPelanggan();
    this.loadStatusStep();
  },
};
</script>
