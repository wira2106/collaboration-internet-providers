<template>
  <!-- Hasil Survey Info-->
  <div class="row" :wait="wait">
    <div class="col-md-12">
      <div class="card-header">
        <h3>{{ trans("surveys.title.hasil") }}</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12 mt-2 mb-2" v-if="status_checkbox">
            <el-alert
              :title="trans('surveys.messages.survey selesai')"
              type="success"
              effect="dark"
            >
            </el-alert>
          </div>
          <div class="col-md-12">
            <table style="width: 100%;">
              <tr class="py-1" valign="top">
                <th class="font-custom">
                  {{ trans("surveys.form.teknisi") }}
                </th>
                <td style="width: 10px;">:</td>
                <td>
                  <div
                    class="span-noc"
                    v-for="(list, index) in noc"
                    :key="index"
                  >
                    {{ list.nama_teknisi }}
                  </div>
                </td>
              </tr>
              <tr class="py-5" valign="top">
                <th class="font-custom">
                  {{ trans("surveys.form.tgl survey") }}
                </th>
                <td style="width: 10px;">:</td>
                <td>
                  {{ tglSurvey }}
                </td>
              </tr>
              <tr class="py-5" valign="top">
                <th class="font-custom">
                  {{ trans("surveys.form.keterangan") }}
                </th>
                <td style="width: 10px;">:</td>
                <td>
                  {{
                    surveyForm.keterangan == null ? "-" : surveyForm.keterangan
                  }}
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-12" v-if="status_checkbox">
            <table style="width: 100%;">
              <tr class="py-1" valign="top">
                <th class="font-custom">
                  Teknisi instalasi
                </th>
                <td style="width: 10px;">:</td>
                <td>
                  <div
                    class="span-noc"
                    v-for="(list, index) in surveyForm.teknisiInstalasi"
                    :key="index"
                  >
                    {{ getNameTeknisiInstalasi(list) }}
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-12" v-if="status_checkbox">
            <label style="font-weight:500;color:#212529"
              >Jadwal Instalasi</label
            >
            <table class="table" style="width: 100%;">
              <tr>
                <td>No</td>
                <td>Tanggal</td>
                <td>Slot</td>
              </tr>
              <tr
                v-for="(item, index) in surveyForm.jadwalInstalasi"
                :key="index"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ item.tgl_instalasi }}</td>
                <td>{{ slotinstalasi(item.slot_id) }}</td>
              </tr>
            </table>
          </div>

          <div class="col-md-12">
            <el-tabs v-model="activeName" @tab-click="handleClick">
              <el-tab-pane :label="trans('surveys.form.signal')" name="signal">
                <PreviewSignal :signals="foto_signal_kabel_ready" />
              </el-tab-pane>
              <el-tab-pane
                :label="trans('surveys.form.kabel')"
                name="jalur_kabel"
              >
                <PreviewJalurKabel :jalurKabels="foto_jalur_kabel_ready" />
              </el-tab-pane>
              <el-tab-pane label="Perangkat" name="perangkat">
                <PreviewPerangkat
                  :perangkats="namaPerangkat(surveyForm.perangkat_tambahan)"
                  :status_pelanggans="status_pelanggans"
                />
              </el-tab-pane>
            </el-tabs>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Hasil Survey Info-->
</template>

<script>
import PreviewJalurKabel from "./PreviewJaluKabel";
import PreviewSignal from "./PreviewSignal";
import PreviewPerangkat from "../../instalasi/components/PreviewPerangkat";
export default {
  components: { PreviewPerangkat, PreviewSignal, PreviewJalurKabel },
  props: [
    "slot_instalasi",
    "tglSurvey",
    "surveyForm",
    "noc",
    "foto_jalur_kabel_ready",
    "foto_signal_kabel_ready",
    "fieldPerangkat",
    "status_checkbox",
    "status_pelanggans",
    "list_teknisi",
  ],
  data() {
    return {
      activeName: "signal",
    };
  },
  computed: {
    wait: function() {
      if (
        this.foto_jalur_kabel_ready != "" ||
        this.foto_signal_kabel_ready != ""
      ) {
        setTimeout(() => {
          $(".fancybox").fancybox();
        }, 1000);
      }
    },
  },
  methods: {
    getNameTeknisiInstalasi(id) {
      var data = this.list_teknisi;
      var teknisi = data.find((teknisi) => teknisi.user_id == id);
      if (teknisi) {
        return teknisi.nama_teknisi;
      }
    },
    slotinstalasi(slot_id) {
      var slot = this.slot_instalasi.find((slot) => slot.slot_id == slot_id);
      if (slot) {
        return slot.full_name;
      } else {
        return "";
      }
    },
    handleClick(tab, event) {},
    editHasilSurvey() {
      this.$emit("changeSurveyFinish", true);
      // return this.surveyFinish = false;
    },
    namaPerangkat(value) {
      var data_perangkat = this.fieldPerangkat;
      var perangkat_dipilih = value;

      var jumlah_data_perangkat = data_perangkat.length;
      var jumlah_perangkat_dipilih = perangkat_dipilih.length;

      var array = [];
      for (var a = 0; a < jumlah_perangkat_dipilih; a++) {
        for (var b = 0; b < jumlah_data_perangkat; b++) {
          if (
            perangkat_dipilih[a].perangkat_id == data_perangkat[b].perangkat_id
          ) {
            array.push({
              nama_perangkat: data_perangkat[b].nama_perangkat,
              perangkat_id: perangkat_dipilih[a].perangkat_id,
              jenis_perangkat: perangkat_dipilih[a].jenis_perangkat,
              qty: perangkat_dipilih[a].qty,
            });
          }
        }
      }
      return array;
    },
  },
};
</script>
<style type="text/css">
.el-form-item__label {
  margin-bottom: 0px !important;
}
.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}
.span-status {
  display: inline-block;
  padding: 0px 10px;
  color: white;
  border-radius: 5px;
  margin-right: 2px;
}
.span-teknisi {
  display: inline-block;
  font-size: 14px;
  border: 1px solid #b0b0ff;
  border-radius: 5px;
  padding: 0px 5px;
  margin-right: 2px;
}
.font-custom {
  width: 250px;
  color: rgb(33, 37, 41);
  padding-bottom: 18px;
  font-weight: 500;
}
</style>
