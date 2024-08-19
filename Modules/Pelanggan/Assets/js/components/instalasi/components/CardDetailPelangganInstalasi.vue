<template>
  <Card class="card card-outline-info" v-loading="loading">
    <div class="card-header" style="border-bottom: 0px;">
      <div class="card-actions">
        <a data-action="collapse">
          <i class="ti-minus text-white"></i>
        </a>
      </div>
      <h5 class="text-white">
        {{ trans("pelangganinstalasi.title.data pelanggan") }}
      </h5>
    </div>
    <div class="card-body collapse show">
      <div class="row mt-4">
        <div class="col-md-6">
          <table class="table">
            <tr>
              <td width="200px" style="font-width: 10px;font-weight:500">
                {{ trans("pelangganinstalasi.nama") }}
              </td>
              <td width="10px">:</td>
              <td>
                <span>{{ DataPelangganInstalasi.nama_pelanggan }}</span>
              </td>
            </tr>
            <tr>
              <td width="200px" style="font-width: 10px;font-weight:500">
                {{ trans("pelangganinstalasi.telepon") }}
              </td>
              <td width="10px">:</td>
              <td>
                <span>{{ DataPelangganInstalasi.telepon }}</span>
              </td>
            </tr>
            <tr>
              <td width="200px" style="font-width: 10px;font-weight:500">
                {{ trans("pelangganinstalasi.mail") }}
              </td>
              <td width="10px">:</td>
              <td>
                <span>{{ DataPelangganInstalasi.email }}</span>
              </td>
            </tr>
            <tr>
              <td width="200px" style="font-width: 10px;font-weight:500">
                {{ trans("pelangganinstalasi.status") }}
              </td>
              <td width="10px">:</td>
              <td>
                <span>{{ DataPelangganInstalasi.status_kepemilikan }}</span>
              </td>
            </tr>
            <tr>
              <td width="200px" style="font-width: 10px;font-weight:500">
                {{ trans("pelangganinstalasi.jenis identitas") }}
              </td>
              <td width="10px">:</td>
              <td>
                <span>
                  {{ DataPelangganInstalasi.jenis_identitas }} /
                  {{ DataPelangganInstalasi.nomor_identitas }}
                </span>
              </td>
            </tr>
            <tr>
              <td width="200px" style="font-width: 10px;font-weight:500">
                {{ trans("pelangganinstalasi.foto identitas") }}
              </td>
              <td width="10px">:</td>
              <td>
                <span>
                  <a :href="DataPelangganInstalasi.foto_url" class="fancybox">
                    <el-button
                      size="small"
                      class="el-icon-picture"
                      type="primary"
                    ></el-button
                  ></a>
                </span>
              </td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table">
            <tr>
              <td width="200px" style="font-width: 10px;font-weight:500">
                {{ trans("pelangganinstalasi.paket") }}
              </td>
              <td width="10px">:</td>
              <td>
                <span>{{ DataPelangganInstalasi.nama_paket }}</span>
              </td>
            </tr>
            <tr>
              <td width="200px" style="font-width: 10px;font-weight:500">
                {{ trans("pelangganinstalasi.harga mrc") }}
              </td>
              <td width="10px">:</td>
              <td>
                <span>{{ DataPelangganInstalasi.biaya_mrc }}</span>
              </td>
            </tr>
            <tr>
              <td width="200px" style="font-width: 10px;font-weight:500">
                {{ trans("pelangganinstalasi.harga otc") }}
              </td>
              <td width="10px">:</td>
              <td>
                <span> {{ DataPelangganInstalasi.biaya_otc }} </span>
              </td>
            </tr>
            <tr :hidden="DataPelangganInstalasi.cancel != '1'">
              <td width="200px" style="font-width: 10px">Status Instalasi</td>
              <td width="10px">:</td>
              <td>
                <span class="text-danger" style="font-weight: bold"
                  >Cancel</span
                >
              </td>
            </tr>
            <tr :hidden="DataPelangganInstalasi.cancel != '1'">
              <td width="200px" style="font-width: 10px">Alasan Cancel</td>
              <td width="10px">:</td>
              <td>
                <span>{{ DataPelangganInstalasi.alasan_cancel }}</span>
              </td>
            </tr>
          </table>
        </div>
      </div>

      <div>
        <el-button
          type="primary"
          v-show="ajukanUlang == true"
          plain
          size="small"
          class="col-md-12 mt-4"
          data-toggle="modal"
          data-target="#form_slott"
        >
          {{ trans("pelangganinstalasi.ajukan") }}
        </el-button>
      </div>

      <div v-show="show == true && user_roles.name == 'Admin ISP'">
        <hr />
        <Button-Cancel
          @changeStatusStep="changeStatusStep"
          :buttonDisabled="DataPelangganInstalasi.cancel"
          v-show="
            user_roles.name == 'Admin ISP' && DataPelangganInstalasi.cancel != 1
          "
          :hidden="hidden_button_aktifkan_kembali"
        />
        <Button-Aktifkan
          @changeStatusStep="changeStatusStep"
          :buttonDisabled="DataPelangganInstalasi.cancel"
          v-show="
            user_roles.name == 'Admin ISP' && DataPelangganInstalasi.cancel == 1
          "
          :hidden="hidden_button_aktifkan_kembali"
        />
      </div>
    </div>
  </Card>
</template>

<script>
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";
import ButtonAktifkanKembali from "../../ButtonAktifkanKembali";
import ButtonCancel from "../../ButtonCancel";

export default {
  props: {
    hidden_button_aktifkan_kembali: {
      default: false,
    },
    ajukanUlangFromStep: {
      default: null,
    },
    statusStep: {
      default: null,
    },
  },
  mixins: [PermissionsHelper],
  components: {
    "Button-Aktifkan": ButtonAktifkanKembali,
    "Button-Cancel": ButtonCancel,
  },

  data() {
    return {
      showFotoIdentitas: false,
      loading: true,
      DataPelangganInstalasi: {},
      ajukanUlang: true,
      status: "",
      show: false,
    };
  },
  methods: {
    loadDataPelangganInstalasi() {
      this.loading = true;
      var id_pelanggan = this.$route.query.pelanggan;
      axios
        .get(
          route("api.load.data.pelanggan.instalasi", {
            pelanggan: id_pelanggan,
          })
        )
        .then((res) => {
          this.DataPelangganInstalasi = res.data.data;
          $(".fancybox").fancybox();
          this.loading = false;
        });
    },
    loadDatabasePerubahanHarga() {
      const params = this.$route.query.pelanggan;
      axios
        .post(
          route("api.pelanggan.form.perubahan.harga", {
            pelanggan: params,
          })
        )
        .then((res) => {
          var jumlahHistori = res.data.data.length;
          if (jumlahHistori > 0) {
            this.ajukanUlang = false;
          } else {
            this.ajukanUlang = true;
          }
          this.loading = false;
        });
    },
    loadDataSurvey() {
      return this.$emit("loadFetch", true);
    },
    changeStatusStep(value) {
      this.loadDataPelangganInstalasi();
      this.$emit("changeStatusStep", value);
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
          this.status = res.data.status;
          if (typeof res.data.hide == "undefined") {
            this.show = true;
          } else {
            this.show = false;
          }
        });
    },
  },
  mounted() {
    this.loadStatusStep();
    this.loadDatabasePerubahanHarga();
    this.loadDataPelangganInstalasi();
  },
  watch: {
    ajukanUlangFromStep() {
      this.ajukanUlang = false;
    },
  },
};
</script>
