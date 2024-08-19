<template>
  <div>
    <InformasiJadwalSurvey
      v-if="response != null && form.status == 'accept'"
      v-show="statusSurvey !== 'finish'"
      :response="response"
      :pelanggan_id="pelanggan_id_send"
      :list_teknisi="list_teknisi"
      :teknisi="teknisi"
      :loadTeknisi="loadTeknisi"
      :isReschedule="isReschedule"
      :changeEdit="changeEdit"
      @getTanggalSurvey="getTanggalSurvey($event)"
      @submitTeknisi="submitTeknisi($event)"
      @rechedule="recheduleJadwal()"
      @saveFormToLocal="saveFormToLocal($event)"
      @setShangeEdit="setShangeEdit($event)"
    >
    </InformasiJadwalSurvey>

    <RecheduleJadwalSurvey
      v-if="response != null && isReschedule"
      :response="response"
      :list_teknisi="list_teknisi"
      :loadTeknisi="loadTeknisi"
      :pelanggan_id="pelanggan_id_send"
      @send="send($event)"
      @unrechedule="recheduleJadwal(false)"
      @saveFormToLocal="saveFormToLocal($event)"
    >
    </RecheduleJadwalSurvey>

    <PengajuanJadwalSurvey
      v-if="response != null && form.status == 'pending' && !isReschedule"
      :response="response"
      :teknisi="teknisi"
      :loadTeknisi="loadTeknisi"
      :list_teknisi="list_teknisi"
      :pelanggan_id="pelanggan_id_send"
      @rechedule="recheduleJadwal()"
      @saveFormToLocal="saveFormToLocal($event)"
    >
    </PengajuanJadwalSurvey>

    <!-- MODAL HISTORY -->
    <div
      class="modal fade history-pengajuan-survey"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content" v-loading="loadingModal">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ trans("pengajuanjadwal.survey.history pengajuan") }}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" v-for="(val, key) in history" :key="key">
                <template>
                  <div class="card shadow">
                    <div class="card-body">
                      <table style="width: 100%">
                        <tr>
                          <td style="width: 200px">
                            {{ trans("pengajuanjadwal.tgl request") }}
                          </td>
                          <td style="width: 5px">:</td>
                          <td>{{ val.tgl_rekomendasi_c }}</td>
                        </tr>
                        <tr>
                          <td>{{ trans("pengajuanjadwal.keterangan") }}</td>
                          <td>:</td>
                          <td valign="top">
                            {{ val.keterangan !== null ? val.keterangan : "-" }}
                          </td>
                        </tr>
                        <tr>
                          <td>{{ trans("pengajuanjadwal.alasan") }}</td>
                          <td>:</td>
                          <td>
                            {{
                              val.current_alasan_reschedule !== null
                                ? val.current_alasan_reschedule
                                : "-"
                            }}
                          </td>
                        </tr>
                      </table>

                      <table class="table table-sm table-bordered mt-2">
                        <thead>
                          <tr width="10%">
                            <th>
                              {{ trans("pengajuanjadwal.table history.no") }}
                            </th>
                            <th width="40%">
                              {{ trans("pengajuanjadwal.table history.tgl") }}
                            </th>
                            <th width="40%">
                              {{ trans("pengajuanjadwal.table history.jam") }}
                            </th>
                            <th width="10%"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(v, k) in val.list" :key="k">
                            <td>{{ k + 1 }}</td>
                            <td>{{ v.tgl_survey }}</td>
                            <td>{{ v.jam_survey }}</td>
                            <td>
                              <b
                                class="text-success"
                                v-if="v.status == 'active'"
                                >{{ trans("pengajuanjadwal.dipilih") }}</b
                              >
                            </td>
                          </tr>
                          <tr v-if="val.list.length == 0">
                            <td colspan="4" class="text-center text-danger">
                              {{ trans("pengajuanjadwal.kosong") }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
import axios from "axios";
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";
import RecheduleJadwalVue from "./partials/RecheduleJadwalSurvey.vue";
import PengajuanJadwalVue from "./partials/PengajuanJadwalSurvey.vue";
import InformasiJadwalSurveyVue from "./partials/InformasiJadwalSurvey.vue";

export default {
  mixins: [Toast, PermissionsHelper],
  props: {
    statusSurvey: {
      // type: String,
      default: null,
    },
    pelanggan_id: {
      // type: String,
      default: null,
    },
    survey_id: {
      // type: String,
      default: null,
    },
    buttonRechedule: {
      type: Boolean,
      default: true,
    },
  },
  components: {
    RecheduleJadwalSurvey: RecheduleJadwalVue,
    PengajuanJadwalSurvey: PengajuanJadwalVue,
    InformasiJadwalSurvey: InformasiJadwalSurveyVue,
  },
  data() {
    return {
      pelanggan_id_send: null,
      list_teknisi: null,
      form: {
        rekomendasiExist: false,
        jam_rekomendasi: "08:00",
        tgl_rekomendasi: "",
        keterangan: "",
        status: "",
        list: [],
      },
      history: [],
      response: null,
      teknisi: null,
      loadTeknisi: [],
      dataTeknisi: {
        teknisi: null,
      },
      list_teknisi: null,
      loadingModal: false,
      isReschedule: false,
      changeEdit: false,
      tglSurvey: "",
    };
  },
  methods: {
    fetchPengajuanSurvey() {
      if (parseInt(this.pelanggan_id) != 0) {
        axios
          .get(
            route("api.pelanggan.form.jadwal.survey", {
              pelanggan_id: this.$route.query.pelanggan,
            })
          )
          .then((response) => {
            this.response = response;
            this.teknisi = response.data.teknisi;
            this.loadTeknisi = [];
            this.teknisi.forEach((element) => {
              this.loadTeknisi.push(element);
            });
            this.list_teknisi = response.data.list_teknisi;
            this.response.buttonRechedule = this.buttonRechedule;
            let data = response.data.data;
            this.history = data;
            if (data.length != 0) {
              this.form = data[data.length - 1];
            }

            //check local data
            let dataLocal = localStorage.getItem("data_fill_storage"),
              pengajuan = null;
            if (dataLocal !== null && dataLocal !== "" && dataLocal !== "") {
              pengajuan = JSON.parse(dataLocal);
              if (
                (pengajuan.mode == "reschedule_survey" ||
                  pengajuan.mode == "edit _teknisi") &&
                pengajuan.data.pelanggan_id == this.pelanggan_id
              ) {
                this.isReschedule = pengajuan.data.isReschedule;
                this.$emit("rescheduleStatus", pengajuan.data.isReschedule);
              }
            }
          });
      }
    },
    recheduleJadwal(reschedule = true) {
      this.isReschedule = reschedule;
      this.$emit("rescheduleStatus", reschedule);
    },
    submitTeknisi(data) {
      if (data.length == 0) {
        Swal.fire(this.trans("surveys.messages.teknisi kosong"), "", "warning");
        return false;
      }
      Swal.fire({
        title: this.trans("surveys.messages.update teknisi"),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then((result) => {
        if (result.value) {
          this.hasilSurveyLoading = true;
          this.dataSurveyLoading = true;
          this.send(data)
            .then((response) => {
              this.hasilSurveyLoading = false;
              this.dataSurveyLoading = false;
              this.removeFormDataLocal();
              this.Toast({
                icon: "success",
                title: response.data.message,
              });
              this.fetchPengajuanSurvey();
              this.changeEdit = true;
              // this.$router.go();
            })
            .catch((error) => {
              this.hasilSurveyLoading = false;
              this.dataSurveyLoading = false;
              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title"),
              });
            });
        }
      });
    },
    send(data) {
      this.dataTeknisi.teknisi = data;
      // let form = new FormData(this.dataTeknisi);
      return axios.post(
        route("api.pelanggan.teknisi.update", { survey_id: this.survey_id }),
        this.dataTeknisi
      );
    },
    getTanggalSurvey(value) {
      this.tglSurvey = value;
      this.$emit("getJadwalSurvey", this.tglSurvey);
    },
    saveFormToLocal(dataFill) {
      dataFill.data.isReschedule = this.isReschedule;
      dataFill.data.pelanggan_id = this.pelanggan_id;
      let data = JSON.stringify({
        mode: dataFill.mode,
        data: dataFill.data,
      });
      localStorage.setItem("data_fill_storage", data);
    },
    removeFormDataLocal() {
      localStorage.removeItem("data_fill_storage");
    },
    setShangeEdit(value) {
      return (this.changeEdit = value);
    },
  },
  mounted() {
    this.pelanggan_id_send = this.pelanggan_id;
    this.fetchPengajuanSurvey();
  },
};
</script>
<style type="text/css">
.span-noc {
  display: inline-block;
  font-size: 14px;
  border: 1px solid #b0b0ff;
  border-radius: 5px;
  padding: 0px 5px;
  margin-right: 2px;
}
</style>
