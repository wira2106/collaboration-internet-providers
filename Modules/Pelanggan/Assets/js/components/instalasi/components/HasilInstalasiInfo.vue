<template>
  <div v-loading="loading">
    <div>
      <div v-if="form.jadwal_instalasi.selisih <= 0">
        <table class="table" style="width: 100%;">
          <tr class="py-5">
            <td
              style="width: 200px;font-weight: 500;"
              class="cellpadding-custom"
            >
              {{ trans("pelangganinstalasi.form.tgl instalasi") }}
            </td>
            <td style="width: 10px;" class="cellpadding-custom">:</td>
            <td colspan="2" class="cellpadding-custom">
              <span>
                <span>{{ form.jadwal_instalasi.tanggal }}</span>
                <el-tooltip
                  v-if="form.status_instalasi == 'finish'"
                  placement="top"
                  :content="
                    trans('pelangganinstalasi.info.histori pengajuan instalasi')
                  "
                >
                  <el-button
                    type="primary"
                    size="small"
                    class="el-icon-date"
                    @click="seeHistoryPengajuanInstalasi"
                  ></el-button>
                </el-tooltip>
              </span>
            </td>
          </tr>
          <tr class="py-5">
            <td
              style="width: 200px;font-weight: 500;"
              class="cellpadding-custom"
            >
              {{ trans("pelangganinstalasi.form.slot") }}
            </td>
            <td style="width: 10px;" class="cellpadding-custom">:</td>
            <td colspan="2" class="cellpadding-custom">
              {{ form.jadwal_instalasi.slot }}
            </td>
          </tr>
          <tr class="py-1">
            <td
              style="width: 200px;font-weight: 500;"
              class="cellpadding-custom"
            >
              {{ trans("pelangganinstalasi.form.teknisi") }}
            </td>
            <td style="width: 10px;" class="cellpadding-custom">:</td>
            <td colspan="2" class="cellpadding-custom">
              <div v-if="form.teknisi_instalasi.length != 0">
                <span
                  class="span_noc"
                  v-for="(item, index) in form.teknisi_instalasi"
                  :key="index"
                >
                  {{ item }}
                </span>
              </div>
              <div v-else>
                {{ trans("pelangganinstalasi.info.belum_ditentukan") }}
              </div>
            </td>
          </tr>
          <tr class="py-5" v-if="form.status_instalasi == 'finish'">
            <td
              style="width: 200px;font-weight: 500;"
              class="cellpadding-custom"
            >
              {{ trans("pelangganinstalasi.form.status") }}
            </td>
            <td style="width: 10px;" class="cellpadding-custom">:</td>
            <td colspan="2" class="cellpadding-custom">
              {{ form.status_instalasi }}
            </td>
          </tr>
          <tr class="py-5">
            <td
              style="width: 200px;font-weight: 500;"
              class="cellpadding-custom"
            >
              {{ trans("pelangganinstalasi.form.keterangan") }}
            </td>
            <td style="width: 10px;" class="cellpadding-custom">:</td>
            <td colspan="2" class="cellpadding-custom">
              {{
                form.keterangan_instalasi != null
                  ? form.keterangan_instalasi
                  : trans("pelangganinstalasi.info.tidak_ada")
              }}
            </td>
          </tr>
        </table>

        <el-tabs v-model="tabActiveName">
          <el-tab-pane label="Perangkat" name="perangkat">
            <PreviewPerangkat
              :perangkats="form.perangkat_instalasi_digunakan"
            />
          </el-tab-pane>
          <el-tab-pane label="Alat" name="alat">
            <PreviewAlat :alats="form.alat_instalasi_digunakan" />
          </el-tab-pane>
          <el-tab-pane label="Dokumentasi" name="dokumentasi">
            <PreviewFotoDokumentasi :dokumentasi="form.dokumentasi_instalasi" />
          </el-tab-pane>
        </el-tabs>
      </div>
    </div>
  </div>
</template>

<script>
import PreviewPerangkat from "./PreviewPerangkat.vue";
import PreviewAlat from "./PreviewAlat.vue";
import PreviewFotoDokumentasi from "./PreviewFotoDokumentasi.vue";

export default {
  props: ["disableButton"],
  components: {
    PreviewPerangkat,
    PreviewAlat,
    PreviewFotoDokumentasi,
  },
  data() {
    return {
      tabActiveName: "perangkat",
      status_step: "",
      acceptedPerubahan: false,
      loadingPengajuan: true,
      loading: true,
      select_teknisi: true,
      teknisi: "",
      dokumentasi_key: 1,
      alat_key: 2,
      perangkat_key: 3,
      imageUrl: "",
      keterangan: "",
      disable: false,
      history: [],

      //data user yang terdata di database
      form: {
        instalasi_id: "",
        jadwal_instalasi: {},
        teknisi_instalasi: [],
        status_instalasi: "",
        keterangan_instalasi: "",
        perangkat_instalasi_digunakan: [],
        alat_instalasi_digunakan: [],
        dokumentasi_instalasi: [],
      },
    };
  },
  methods: {
    seeHistoryPengajuanInstalasi() {
      $(".history-pengajuan-instalasi").modal();
    },
    loadDataPelangganInstalasi() {
      const params = this.$route.query.pelanggan;
      axios
        .get(
          route("api.pelanggan.form.data.instalasi", {
            pelanggan: params,
          })
        )
        .then((response) => {
          this.status_step = response.data.status;
          var jumlah_data_teknisi = response.data.data_teknisi.length;
          var teknisi_push_to_array = [];
          for (var i = 0; i < jumlah_data_teknisi; i++) {
            var teknisi = response.data.data_teknisi[i].nama_teknisi;
            teknisi_push_to_array.push(teknisi);
          }
          if (response.data.status_instalasi) {
            this.$emit("status_instalasi", response.data.status_instalasi);
          }
          if (response.data.cancel == "1") {
            this.disable = true;
          }

          var res = response.data;
          this.form = {
            instalasi_id: res.instalasi_id,
            status_instalasi: res.status_instalasi,
            keterangan_instalasi: res.keterangan_instalasi,
            jadwal_instalasi:
              res.jadwal_instalasi[res.jadwal_instalasi.length - 1],
            perangkat_instalasi_digunakan: res.data_perangkat,
            alat_instalasi_digunakan: res.data_alat,
            dokumentasi_instalasi: res.dokumentasi,
            teknisi_instalasi: teknisi_push_to_array,
          };
          this.loading = false;
          setTimeout(() => {
            $(".fancybox").fancybox({
              beforeShow: function() {
                this.title = $(this.element).data("caption");
              },
            });
          }, 1000);
        });
    },
  },
  mounted() {
    this.loadDataPelangganInstalasi();
  },
};
</script>

<style>
@import "../../../../css/imagepreview.css";

.span_noc {
  border: 1px solid #b0b0ff;
  border-radius: 5px;
  padding: 0px 5px;
  margin-right: 2px;
}

.p-custom {
  font-weight: 500;
  color: black;
}
.table-custom {
  font-size: 14px;
  color: black;
  padding: 4px 0;
}

.cellpadding-custom {
  padding: 6px 0;
}
</style>
