<template>
  <el-form
    ref="form"
    :model="form"
    label-width="120px"
    label-position="top"
    v-loading.body="loadingPengajuan"
    @keydown="form.errors.clear($event.target.name)"
    :rules="rules"
  >
    <!-- CARD RECHEDULE -->
    <div class="card card-outline-info">
      <div class="card-header" style="border-bottom: 0px">
        <div class="card-actions">
          <a
            v-show="history.length > 1"
            href="#!"
            class="text-white"
            @click="seeHistoryPengajuanSurvey"
            ><u>{{ trans("pengajuanjadwal.see history") }}</u></a
          >
          <a data-action="collapse"><i class="ti-minus text-white"></i></a>
        </div>
        <h5 class="text-white">{{ trans("pengajuanjadwal.reschedule") }}</h5>
      </div>
      <div class="card-body collapse show">
        <div class="row">
          <!-- input keterangan dan rekomendasi -->
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <el-form-item
                  :label="trans('pengajuanjadwal.keterangan')"
                  prop="keterangan"
                  :rules="rules.required_field"
                >
                  <el-input
                    type="textarea"
                    placeholder="Keterangan.."
                    v-model="form.keterangan"
                    size="small"
                  >
                  </el-input>
                </el-form-item>
              </div>
            </div>
          </div>

          <div class="col-md-12" v-if="user_company.type == 'isp'">
            <el-checkbox v-model="form.rekomendasiExist">{{
              trans("pengajuanjadwal.is rekomendasi")
            }}</el-checkbox>
          </div>
          <div class="col-md-12" v-show="form.rekomendasiExist">
            <div class="row">
              <div class="col-md-6">
                <el-form-item
                  :label="trans('pengajuanjadwal.tgl rekomendasi')"
                  prop="tgl_rekomendasi"
                >
                  <el-date-picker
                    style="width: 100%"
                    v-model="form.tgl_rekomendasi"
                    type="date"
                    value-format="yyyy-MM-dd"
                    placeholder="Pick a day"
                    :picker-options="pickerOptions"
                    size="small"
                  >
                  </el-date-picker>
                </el-form-item>
              </div>
              <div class="col-md-6">
                <el-form-item
                  :label="trans('pengajuanjadwal.jam rekomendasi')"
                  prop="jam_rekomendasi"
                >
                  <el-time-select
                    style="width: 100%"
                    v-model="form.jam_rekomendasi"
                    placeholder="Select time"
                    :picker-options="{
                      start: selectableRangePicker[0],
                      step: '00:30',
                      end: selectableRangePicker[1],
                    }"
                    size="small"
                  >
                  </el-time-select>
                </el-form-item>
              </div>
            </div>
          </div>

          <!-- pilihan tanggal survey -->
          <div class="col-md-12" v-if="user_company.type == 'osp'">
            <div class="row">
              <div class="col-md-12">
                <table
                  :hidden="form.list.length == 0"
                  class="table table-bordered table-pengajuan-survey"
                >
                  <thead>
                    <tr>
                      <td>
                        {{ trans("pengajuanjadwal.table survey.no") }}
                      </td>
                      <td>
                        {{ trans("pengajuanjadwal.table survey.tgl") }}
                      </td>
                      <td>
                        {{ trans("pengajuanjadwal.table survey.waktu") }}
                      </td>
                      <td align="center">
                        {{ trans("pengajuanjadwal.table survey.action") }}
                      </td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(val, key) in form.list" :key="key">
                      <td style="width: 30px">{{ key + 1 }}</td>
                      <td>
                        <!-- select tanggal survey -->
                        <div>
                          <el-form-item
                            class="mb-0"
                            :prop="'list.' + key + '.tgl_survey'"
                            :rules="rules.required_field"
                          >
                            <el-date-picker
                              v-model="val.tgl_survey"
                              type="date"
                              value-format="yyyy-MM-dd"
                              placeholder="Pick a day"
                              :picker-options="pickerOptions"
                              size="small"
                            >
                            </el-date-picker>
                          </el-form-item>
                        </div>
                      </td>
                      <td>
                        <!-- select jam survey -->
                        <div>
                          <el-form-item
                            class="mb-0"
                            :prop="'list.' + key + '.tgl_survey'"
                            :rules="rules.required_field"
                          >
                            <el-time-select
                              v-model="val.jam_survey"
                              placeholder="Select time"
                              :picker-options="{
                                start: selectableRangePicker[0],
                                step: '00:30',
                                end: selectableRangePicker[1],
                              }"
                              size="small"
                            >
                            </el-time-select>
                          </el-form-item>
                        </div>
                      </td>
                      <td align="center">
                        <a href="#!" @click="removeTanggal(key)" v-if="key > 0">
                          <i class="fa fa-trash text-danger"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div
                  class="col-md-12 pt-3 d-flex justify-content-center"
                  v-if="user_company.type == 'osp'"
                >
                  <el-button type="primary" @click="addJadwal()">
                    <i class="fa fa-plus"></i>
                    {{ trans("pengajuanjadwal.button.tambah jadwal") }}
                  </el-button>
                </div>
              </div>
            </div>
          </div>
          <!-- ----- -->
        </div>
      </div>

      <!-- card footer and button save -->
      <div class="card-footer">
        <div class="d-flex justify-content-center">
          <el-form-item>
            <el-button
              type="success"
              @click="onSubmit('form')"
              :loading="loadingPengajuan"
            >
              {{ trans("pengajuanjadwal.button.add reschedule") }}
            </el-button>
            <el-button type="danger" @click="batalSchedule()">
              {{ trans("pengajuanjadwal.button.cancel reschedule") }}
            </el-button>
          </el-form-item>
        </div>
      </div>
    </div>
  </el-form>
</template>

<script type="text/javascript">
import axios from "axios";
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [Toast, PermissionsHelper],
  props: ["response", "pelanggan_id"],
  data() {
    return {
      pickerOptions: {
        disabledDate(time) {
          let date = new Date();
          date.setDate(date.getDate() - 1);
          return time.getTime() < date.getTime();
        },
      },
      loadingPengajuan: false,
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
      selectableRange: ["00:00", "00:00"],
    };
  },
  computed: {
    selectableRangePicker: function () {
      return [
        this.selectableRange[0].substring(0, 5),
        this.selectableRange[1].substring(0, 5),
      ];
      // return `${this.selectableRange[0]} - ${this.selectableRange[1]}`;
    },
  },
  methods: {
    fetchPengajuanSurvey() {
      this.loadingPengajuan = true;
      this.btnbatalSchedule = false;
      let data = this.response.data.data;
      let osp = this.response.data.osp;
      this.fetchDateRangeSlot(osp);
      this.history = data;
      this.loadingPengajuan = false;
      if (this.user_company.type == "osp") {
        this.addJadwal();
      }
    },
    addJadwal() {
      let push = {
        tgl_survey: "",
        jam_survey: "00:00",
        status: "not_active",
      };
      this.form.list.push(push);
    },
    removeTanggal(key) {
      this.form.list.splice(key, 1);
    },
    checkTanggalIsEmpty() {
      // check apakah tanggalnya ada yang kosong atau tidak
      let emptyTanggal = false;
      this.form.list.forEach(function (val, index) {
        if (
          val.jam_survey == "00:00" ||
          val.tgl_survey == "" ||
          val.tgl_survey == null
        ) {
          emptyTanggal = true;
        }
      });

      if (emptyTanggal) {
        return true;
      }
      return false;
    },
    async onSubmit(form) {
      this.$refs[form].validate((valid) => {
        let fields = this.$refs[form].fields;
        for (let i = 0; i < fields.length; i++) {
          if (fields[i].validateState == "error") {
            $(fields[i].$el).find("input").focus();
            return false;
          }
        }
        if (valid) {
          if (this.checkTanggalIsEmpty()) {
            Swal.fire(
              this.trans("pengajuanjadwal.survey.messages.required jadwal"),
              "",
              "warning"
            );
            return false;
          }
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
              this.loadingPengajuan = true;
              this.submitForm(form);
            }
          });
        }
      });
    },
    submitForm(form) {
      axios
        .post(
          route("api.pelanggan.form.jadwal.survey.submit", {
            pelanggan_id: this.pelanggan_id,
          }),
          this.form
        )
        .then((response) => {
          let data = response.data;
          this.Toast({
            icon: "success",
            title: data.message,
          });
          // this.fetchPengajuanSurvey();
          // window.location = route('admin.pelanggan.form.step')+"?pelanggan="+this.pelanggan_id;
          this.$router.go();
        })
        .catch((error) => {
          this.loadingPengajuan = false;
          this.Toast({
            icon: "error",
            title: "error",
          });
        });
    },
    batalSchedule() {
      $(".tombol-ajukan-rechedule").show();
      this.$emit("unrechedule");
    },
    seeHistoryPengajuanSurvey() {
      $(".history-pengajuan-survey").modal();
    },
    fetchDateRangeSlot(osp_id) {
      axios
        .get(route("api.company.slotinstalasi.get-range-time"), {
          company_id: osp_id,
        })
        .then((response) => {
          this.selectableRange = response.data.data;
        })
        .catch((err) => {
          setTimeout(() => {
            this.fetchDateRangeSlot(osp_id);
          }, 2000);
        });
    },
  },
  mounted() {
    this.fetchPengajuanSurvey();
    $(".tombol-ajukan-rechedule").hide();
  },
};
</script>
<style type="text/css">
.table-pengajuan-survey .active {
  background: #49a4e7 !important;
  color: white;
}
</style>