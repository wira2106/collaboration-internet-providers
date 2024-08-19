<template>
  <div>
    <InformasiJadwalInstalasi
      v-if="
        response != null &&
          form.status == 'accept' &&
          status_instalasi == 'instalasi'
      "
      :response="response"
      :pelanggan_id="pelanggan_id_send"
      :dataLocalStorage="dataLocalStoragePengajuanTeknisi"
      :isReschedule="isReschedule"
      @rechedule="recheduleJadwal()"
      @refresh="refresh()"
    >
    </InformasiJadwalInstalasi>

    <PengajuanJadwalInstalasi
      v-if="responseLoaded && form.status == 'pending'"
      :response="response"
      :pelanggan_id="pelanggan_id_send"
      :dataLocalStorage="dataLocalStoragePengajuanJadwal"
      @rechedule="recheduleJadwal()"
    >
    </PengajuanJadwalInstalasi>

    <RecheduleJadwalInstalasi
      v-if="responseLoaded && isReschedule"
      :response="response"
      :pelanggan_id="pelanggan_id_send"
      :dataLocalStorage="dataLocalStorageReschedule"
      @unrechedule="recheduleJadwal(false)"
    >
    </RecheduleJadwalInstalasi>

    <!-- HISTORY PENGAJUAN INSTALASI -->
    <div
      class="modal fade history-pengajuan-instalasi"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content" v-loading="loadingModal">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ trans("pengajuanjadwal.instalasi.history pengajuan") }}
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
                <div class="card shadow">
                  <div class="card-body">
                    <template>
                      <table style="width: 100%">
                        <tr valign="top">
                          <td style="width: 200px">
                            {{ trans("pengajuanjadwal.tgl request") }}
                          </td>
                          <td style="width: 5px">:</td>
                          <td>{{ val.tgl_rekomendasi_c }}</td>
                        </tr>
                        <tr valign="top">
                          <td style="width: 200px">
                            {{ trans("pengajuanjadwal.keterangan") }}
                          </td>
                          <td style="width: 5px">:</td>
                          <td>
                            {{ val.keterangan == null ? "-" : val.keterangan }}
                          </td>
                        </tr>
                        <tr valign="top">
                          <td style="width: 200px">
                            {{
                              trans(
                                "pengajuanjadwal.instalasi.messages.alasan reschedule"
                              )
                            }}
                          </td>
                          <td style="width: 5px">:</td>
                          <td>
                            {{
                              val.alasan_reschedule == null
                                ? "-"
                                : val.alasan_reschedule
                            }}
                          </td>
                        </tr>
                      </table>

                      <table class="table table-sm table-bordered mt-2">
                        <thead>
                          <tr>
                            <th width="10%">
                              {{ trans("pengajuanjadwal.table history.no") }}
                            </th>
                            <th width="35%">
                              {{ trans("pengajuanjadwal.table history.tgl") }}
                            </th>
                            <th width="55%" colspan="2">
                              {{ trans("pengajuanjadwal.table history.jam") }}
                            </th>
                            <!-- <th>
                          {{ trans("pengajuanjadwal.table history.status") }}
                        </th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(v, k) in val.list" :key="k">
                            <td>{{ k + 1 }}</td>
                            <td>{{ v.tgl_instalasi }}</td>
                            <td :colspan="v.status == 'active' ? 1 : 2">
                              {{ v.full_name }}
                            </td>
                            <td v-if="v.status == 'active'" class="text-center">
                              <b class="text-success">{{
                                trans("pengajuanjadwal.dipilih")
                              }}</b>
                            </td>
                          </tr>
                          <tr v-if="val.list.length == 0">
                            <td colspan="4" class="text-danger text-center">
                              {{
                                trans(
                                  "pengajuanjadwal.instalasi.messages.jadwal kosong"
                                )
                              }}
                            </td>
                          </tr>
                          <!-- <tr v-if="val.length == 0">
                        <td colspan="4">

                        </td>
                      </tr> -->
                        </tbody>
                      </table>
                    </template>
                  </div>
                </div>
                <hr />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";
import PengajuanJadwalInstalasiVue from "./partials/PengajuanJadwalInstalasi.vue";
import InformasiJadwalInstalasiVue from "./partials/InformasiJadwalInstalasi.vue";
import RecheduleJadwalInstalasiVue from "./partials/RecheduleJadwalInstalasi.vue";

export default {
  mixins: [Toast, PermissionsHelper],
  components: {
    PengajuanJadwalInstalasi: PengajuanJadwalInstalasiVue,
    InformasiJadwalInstalasi: InformasiJadwalInstalasiVue,
    RecheduleJadwalInstalasi: RecheduleJadwalInstalasiVue,
  },
  // props: ["pelanggan_id"],
  props: {
    pelanggan_id: {
      type: [String, Number],
      default: null,
    },
    buttonRechedule: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      dataLocalStoragePengajuanTeknisi: null,
      dataLocalStorageReschedule: null,
      dataLocalStoragePengajuanJadwal: null,

      pelanggan_id_send: null,
      status_instalasi: null,
      pickerOptions: {
        disabledDate(time) {
          let date = new Date();
          date.setDate(date.getDate() - 1);
          return time.getTime() < date.getTime();
        },
      },
      form: {
        rekomendasiExist: false,
        jam_rekomendasi: "08:00",
        tgl_rekomendasi: "",
        keterangan: "",
        status: "",
        list: [],
      },
      history: [],
      rules: {
        required_field: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
        ],
      },
      loadingModal: false,
      isReschedule: false,
      rangeSlotInstalasi: null,
      response: {
        instalasi: null,
        range_slot: null,
      },
      responseLoaded: false,
    };
  },
  methods: {
    fetchPengajuanInstalasi() {
      this.loadingPengajuan = true;
      this.btnbatalSchedule = false;
      if (this.pelanggan_id != 0) {
        axios
          .get(
            route("api.pelanggan.form.jadwal.instalasi", {
              pelanggan_id: this.pelanggan_id,
            })
          )
          .then((response) => {
            this.status_instalasi = response.data.status;
            this.response.instalasi = response;
            this.response.instalasi.buttonRechedule = this.buttonRechedule;
            let data = response.data.data;
            let osp = response.data.osp;
            this.fetchDateRangeSlot(osp);
            this.history = data;
            if (data.length != 0) {
              this.form = data[data.length - 1];
            }
            this.dataFillStorage();
          });
      }
    },
    fetchDateRangeSlot(osp_id) {
      axios
        .get(route("api.company.slotinstalasi.get-range-time"), {
          company_id: osp_id,
        })
        .then((response) => {
          this.response.range_slot = response.data.data;
          this.responseLoaded = true;
        })
        .catch((err) => {
          setTimeout(() => {
            this.fetchDateRangeSlot(osp_id);
          }, 2000);
        });
    },
    recheduleJadwal(reschedule = true) {
      this.isReschedule = reschedule;
    },
    dataFillStorage() {
      var LocalStorageGet = JSON.parse(
        localStorage.getItem("data_fill_storage")
      );
      if (LocalStorageGet) {
        switch (LocalStorageGet.mode) {
          case "reschedule_jadwal_instalasi":
            this.dataLocalStorageReschedule = LocalStorageGet.data;
            if (LocalStorageGet.user_baru.role == "teknisi") {
              this.dataLocalStorageReschedule.teknisi_instalasi.push(
                LocalStorageGet.user_baru.user_id
              );
            }
            this.isReschedule = true;
            break;
          case "pengajuan_jadwal_instalasi":
            this.dataLocalStoragePengajuanJadwal = LocalStorageGet.data;
            if (LocalStorageGet.user_baru.role == "teknisi") {
              this.dataLocalStoragePengajuanJadwal.teknisi_instalasi.push(
                LocalStorageGet.user_baru.user_id
              );
            }
            break;

          case "pengajuan_ulang_teknisi_instalasi":
            this.dataLocalStoragePengajuanTeknisi = {
              ...LocalStorageGet.data,
              mode: "pengajuan_ulang_teknisi_instalasi",
            };
            if (LocalStorageGet.user_baru.role == "teknisi") {
              this.dataLocalStoragePengajuanTeknisi.teknisi_instalasi.push(
                LocalStorageGet.user_baru.user_id
              );
            }
            break;

          default:
            console.log("-");
        }
      }
    },
  },
  mounted() {
    this.pelanggan_id_send = this.pelanggan_id;
    this.fetchPengajuanInstalasi();
  },
};
</script>
