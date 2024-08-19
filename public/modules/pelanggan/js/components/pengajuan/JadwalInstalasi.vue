<template>
  <div>
    <InformasiJadwalInstalasi
      v-if="response != null && form.status == 'accept'"
      :response="response"
      :pelanggan_id="pelanggan_id_send"
      @rechedule="recheduleJadwal()"
      @refresh="refresh()"
    >
    </InformasiJadwalInstalasi>

    <PengajuanJadwalInstalasi
      v-if="responseLoaded && form.status == 'pending'"
      :response="response"
      :pelanggan_id="pelanggan_id_send"
      @rechedule="recheduleJadwal()"
    >
    </PengajuanJadwalInstalasi>

    <RecheduleJadwalInstalasi
      v-if="responseLoaded && isReschedule"
      :response="response"
      :pelanggan_id="pelanggan_id_send"
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
                <template v-if="key != history.length - 1">
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
                      <td>{{ val.keterangan }}</td>
                    </tr>
                  </table>

                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>{{ trans("pengajuanjadwal.table history.no") }}</th>
                        <th>
                          {{ trans("pengajuanjadwal.table history.tgl") }}
                        </th>
                        <th>
                          {{ trans("pengajuanjadwal.table history.jam") }}
                        </th>
                        <th>
                          {{ trans("pengajuanjadwal.table history.status") }}
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(v, k) in val.list" :key="k">
                        <td>{{ k + 1 }}</td>
                        <td>{{ v.tgl_instalasi }}</td>
                        <td>{{ v.full_name }}</td>
                        <td>
                          <b class="text-success" v-if="v.status == 'active'">{{
                            trans("pengajuanjadwal.dipilih")
                          }}</b>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <hr />
                </template>
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
      pelanggan_id_send: null,
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
            this.response.instalasi = response;
            this.response.instalasi.buttonRechedule = this.buttonRechedule;
            let data = response.data.data;
            let osp = response.data.osp;
            this.fetchDateRangeSlot(osp);
            this.history = data;
            if (data.length != 0) {
              this.form = data[data.length - 1];
            }
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
  },
  mounted() {
    this.pelanggan_id_send = this.pelanggan_id;
    this.fetchPengajuanInstalasi();
  },
};
</script>
