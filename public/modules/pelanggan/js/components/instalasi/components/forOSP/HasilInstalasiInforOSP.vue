<template>
  <div v-loading="loading">
    <div
      v-if="
        form.teknisi_instalasi.length > 0 && form.jadwal_instalasi.selisih > 0
      "
    >
      <table class="table">
        <tr class="py-1">
          <th style="width: 250px; font-size: 14px;">
            <h4>{{ trans("pelangganinstalasi.form.teknisi") }}</h4>
          </th>
          <td style="width: 10px;">:</td>
          <td>
            <div v-for="(data, index) in form_show.DataTeknisi" :key="index">
              <span style="font-size: 14px;font-weight: 700;display: block;">
                {{ data.nama_teknisi }}
              </span>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div>
      <div v-if="form.jadwal_instalasi.selisih <= 0">
        <table class="table" style="width: 100%;">
          <tr class="py-5">
            <td
              style="width: 250px;font-weight: 500;"
              class="cellpadding-custom"
            >
              {{ trans("pelangganinstalasi.form.tgl instalasi") }}
            </td>
            <td style="width: 10px;" class="cellpadding-custom">:</td>
            <td colspan="2" class="cellpadding-custom">
              <span>
                <span>{{ form.jadwal_instalasi.tanggal }}</span>
                <el-tooltip
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
              style="width: 250px;font-weight: 500;"
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
              style="width: 250px;font-weight: 500;"
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
              style="width: 250px;font-weight: 500;"
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
              style="width: 250px;font-weight: 500;"
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
          <template v-if="form.dokumentasi_instalasi.length > 0">
            <tr>
              <td
                style="width: 250px;font-weight: 500;"
                class="cellpadding-custom"
              >
                {{ trans("pelangganinstalasi.form.dokumentasi") }}
              </td>

              <td class="cellpadding-custom">:</td>
              <td></td>
            </tr>
            <tr>
              <td colspan="3">
                <div
                  class="row"
                  v-for="(dokumentasi_show,
                  index) in form.dokumentasi_instalasi"
                  :key="index"
                >
                  <!-- start image pelanggan instalasi -->
                  <div class="col-md-4">
                    <a
                      :href="dokumentasi_show.foto_dokumentasi_url"
                      class="fancybox"
                    >
                      <div
                        class="image-preview attachments-image"
                        :style="{
                          'background-image':
                            'url(' +
                            dokumentasi_show.foto_dokumentasi_url +
                            ')',
                        }"
                      ></div>
                    </a>
                  </div>
                  <div class="col-md-5">
                    <p style="width: 250px;font-weight: 500;">
                      Keterangan :
                    </p>
                    {{ dokumentasi_show.keterangan }}
                    <br />
                    <br />
                  </div>
                  <div class="col-md-12">
                    <hr />
                  </div>
                  <!-- end image pelanggan instalasi -->
                </div>
              </td>
            </tr>
          </template>
        </table>

        <div v-if="form.perangkat_instalasi_digunakan.length > 0">
          <br />
          <p class="p-custom">
            {{ trans("pelangganinstalasi.form.perangkat") }}
          </p>
          <table class="table">
            <thead>
              <td>
                {{ trans("pelangganinstalasi.table perangkat.nama") }}
              </td>
              <td>
                {{ trans("pelangganinstalasi.table perangkat.jumlah") }}
              </td>
              <td>
                {{ trans("pelangganinstalasi.table perangkat.jenis") }}
              </td>
              <td>
                {{ trans("pelangganinstalasi.table perangkat.status") }}
              </td>
            </thead>
            <tr
              v-for="(p, index) in form.perangkat_instalasi_digunakan"
              :key="index"
            >
              <td>{{ p.nama_perangkat }}</td>
              <td>{{ p.qty }}</td>
              <td>{{ p.jenis_perangkat }}</td>
              <td>{{ p.status }}</td>
            </tr>
          </table>
        </div>
        <div v-if="form.alat_instalasi_digunakan.length > 0">
          <br />
          <p class="p-custom">
            {{ trans("pelangganinstalasi.form.alat") }}
          </p>
          <table class="table">
            <thead>
              <td>{{ trans("pelangganinstalasi.table alat.nama") }}</td>
              <td>{{ trans("pelangganinstalasi.table alat.jumlah") }}</td>
              <td>{{ trans("pelangganinstalasi.table alat.status") }}</td>
            </thead>
            <tr
              v-for="(a, index) in form.alat_instalasi_digunakan"
              :key="index"
            >
              <td>{{ a.nama_alat }}</td>
              <td>{{ a.qty }}</td>
              <td>{{ a.status }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["disableButton"],
  data() {
    return {
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
@import "../../../../../css/imagepreview.css";

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
