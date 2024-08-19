<template>
  <div
    class="card mt-4 card-outline-info"
    v-loading="loading"
    v-if="
      form.teknisi_instalasi.length > 0 &&
        form.jadwal_instalasi.selisih <= 0 &&
        !info
    "
  >
    <div class="card-header ">
      <div class="card-actions">
        <a data-action="collapse">
          <i class="ti-minus text-white"></i>
        </a>
      </div>
      <h5 class="text-white">
        {{
          form.teknisi_instalasi.length > 0 &&
          form.jadwal_instalasi.selisih >= 0
            ? trans("pelangganinstalasi.title.hasil")
            : trans("pelangganinstalasi.title.hasil")
        }}
      </h5>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="table" style="width: 100%;">
            <tr class="py-5">
              <th style="width: 250px;">
                {{ trans("pelangganinstalasi.form.tgl instalasi") }}
              </th>
              <td style="width: 10px;">:</td>
              <td colspan="2">
                <span>
                  <span>{{ form.jadwal_instalasi.tanggal }}</span>
                  <el-tooltip
                    v-if="form.status_instalasi == 'finish'"
                    placement="top"
                    :content="
                      trans(
                        'pelangganinstalasi.info.histori pengajuan instalasi'
                      )
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
              <th style="width: 250px;">
                {{ trans("pelangganinstalasi.form.slot") }}
              </th>
              <td style="width: 10px;">:</td>
              <td colspan="2">
                {{ form.jadwal_instalasi.slot }}
              </td>
            </tr>
            <tr class="py-1">
              <th style="width: 250px;">
                {{ trans("pelangganinstalasi.form.teknisi") }}
              </th>
              <td style="width: 10px;">:</td>
              <td colspan="2">
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
                  {{ trans("pelangganinstalasi.info.tidak_ada") }}
                </div>
              </td>
            </tr>
            <tr class="py-5" v-if="form.status_instalasi == 'finish'">
              <th style="width: 250px;">
                {{ trans("pelangganinstalasi.form.status") }}
              </th>
              <td style="width: 10px;">:</td>
              <td colspan="2">
                {{ form.status_instalasi }}
              </td>
            </tr>
            <tr class="py-5">
              <th style="width: 250px;">
                {{ trans("pelangganinstalasi.form.keterangan") }}
              </th>
              <td style="width: 10px;">:</td>
              <td colspan="2">
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
                    <div class="col-md-5 mb-3">
                      <p style="width: 250px;font-weight: 500;">
                        Keterangan :
                      </p>
                      {{ dokumentasi_show.keterangan }}
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
              <tr>
                <td width="25%">
                  {{ trans("pelangganinstalasi.table perangkat.nama") }}
                </td>
                <td width="25%">
                  {{ trans("pelangganinstalasi.table perangkat.jumlah") }}
                </td>
                <td width="25%">
                  {{ trans("pelangganinstalasi.table perangkat.status") }}
                </td>
              </tr>

              <tr
                v-for="(p, index) in form.perangkat_instalasi_digunakan"
                :key="index"
              >
                <td>{{ p.nama_perangkat }} ({{ p.jenis_perangkat }})</td>
                <td>{{ p.qty }}</td>
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
              <tr>
                <td width="25%">
                  {{ trans("pelangganinstalasi.table alat.nama") }}
                </td>
                <td width="25%">
                  {{ trans("pelangganinstalasi.table alat.jumlah") }}
                </td>
                <td width="25%">
                  {{ trans("pelangganinstalasi.table alat.status") }}
                </td>
              </tr>

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
  </div>
</template>

<script>
export default {
  props: ["disableButton", "info"],
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
      showFotoIdentitas: false,
      url_foto: null,

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
  line-height: 25px;
}

.p-custom {
  font-weight: 500;
  color: black;
}
</style>
