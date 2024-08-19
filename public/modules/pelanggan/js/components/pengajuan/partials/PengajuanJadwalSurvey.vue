<template>
  <div>
    <el-form
      ref="form"
      :model="form"
      label-width="120px"
      label-position="top"
      v-loading.body="loadingPengajuan"
      @keydown="form.errors.clear($event.target.name)"
      :rules="rules"
    >
      <!-- CARD PENGAJUAN JADWAL DAN PEMBERIAN JADWAL -->
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
          <h5 class="text-white">
            {{ trans("pengajuanjadwal.survey.title") }}
            <b class="text-warning" v-if="form.status == 'pending'"
              ><i>Pending</i></b
            >
            <b class="text-success" v-if="form.status == 'accept'"
              ><i>Accept</i></b
            >
            <b class="text-info" v-if="form.status == 'reschedule'"
              ><i>Rechedule</i></b
            >
          </h5>
        </div>
        <div class="card-body collapse show">
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-primary">
                <div class="row">
                  <div class="col-md-12">
                    <table>
                      <tr v-if="form.tgl_rekomendasi != null">
                        <td style="min-width: 150px">
                          {{trans('pengajuanjadwal.tgl rekomendasi')}}
                        </td>
                        <td style="width: 10px">:</td>
                        <th>
                          {{ form.tgl_rekomendasi_c }}
                          {{ form.jam_rekomendasi_c }}
                        </th>
                      </tr>
                      <tr>
                        <td style="min-width: 150px">{{trans('pengajuanjadwal.keterangan')}}</td>
                        <td style="width: 10px">:</td>
                        <td>{{ form.keterangan }}</td>
                      </tr>
                    </table>
                  </div>
                  <div
                    class="col-md-12 pt-3 d-flex justify-content-center"
                    v-if="user_company.type == 'osp' && form.list.length == 0"
                  >
                    <el-button
                      v-if="form.tgl_rekomendasi != null"
                      type="success"
                      @click="pilihTanggalRekomendasi(form.pengajuan_id)"
                    >
                      <i class="fa fa-check"></i> {{trans('pengajuanjadwal.button.pick rekomendasi')}}
                    </el-button>
                    <el-button type="primary" @click="addJadwal()">
                      <i class="fa fa-plus"></i> {{trans('pengajuanjadwal.button.tambah opsi jadwal')}}
                    </el-button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12" v-if="form.list.length == 0">
              <center>
                {{ trans("pengajuanjadwal.survey.jadwal belum") }}
              </center>
            </div>
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
                      <div
                        v-if="
                          user_company.type == 'osp' && form.status != 'accept'
                        "
                      >
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
                      <!-- informasi tanggal survey -->
                      <div v-else>
                        {{ val.tgl_survey }}
                      </div>
                    </td>
                    <td>
                      <!-- select jam survey -->
                      <div
                        v-if="
                          user_company.type == 'osp' && form.status != 'accept'
                        "
                      >
                        <el-form-item
                          class="mb-0"
                          :prop="'list.' + key + '.jam_survey'"
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
                      <!-- informasi jam survey -->
                      <div v-else>
                        {{ val.jam_survey }}
                      </div>
                    </td>
                    <td
                      v-if="
                        user_company.type == 'isp' && form.status == 'pending'
                      "
                      align="center"
                    >
                      <button
                        type="button"
                        class="btn btn-sm btn-info"
                        @click="pickTanggalSurvey(key)"
                      >
                        {{ trans("pengajuanjadwal.button.pilih") }}
                      </button>
                    </td>
                    <td
                      v-if="
                        user_company.type == 'osp' && form.status != 'accept'
                      "
                      align="center"
                    >
                      <a href="#!" @click="removeTanggal(key)">
                        <i class="fa fa-trash text-danger"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!-- tombol ajukan reschedule -->
              <div
                class="col-md-12 pt-2 tombol-ajukan-rechedule"
                v-if="user_company.type == 'isp' && form.list.length > 0"
              >
                <div class="row">
                  <div class="col-md-12">
                    <div class="text-center">
                      <button
                        type="button"
                        class="btn btn-primary"
                        @click="recheduleJadwal()"
                      >
                        {{trans('pengajuanjadwal.button.add reschedule')}}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="col-md-12 pt-3 d-flex justify-content-center"
                v-if="user_company.type == 'osp' && form.list.length > 0"
              >
                <el-button
                  type="success"
                  @click="onSubmit('form')"
                  :loading="loadingPengajuan"
                >
                  {{ trans("pengajuanjadwal.button.simpan jadwal") }}
                </el-button>
                <el-button type="primary" @click="addJadwal()">
                  <i class="fa fa-plus"></i> {{trans('pengajuanjadwal.button.tambah jadwal')}}
                </el-button>
              </div>
            </div>
          </div>
        </div>
        <!-- card footer and button save -->
      </div>
    </el-form>
  </div>
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
      tgl_survey: null,
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
      loadingModal: false,
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
      let data = this.response.data.data;
      let osp = this.response.data.osp;
      this.fetchDateRangeSlot(osp);
      this.history = data;
      if (data.length != 0) {
        this.form = data[data.length - 1];
      }
      this.loadingPengajuan = false;
    },
    addJadwal() {
      let push = {
        tgl_survey: "",
        jam_survey: "",
        status: "not_active",
      };
      this.form.list.push(push);
    },
    removeTanggal(key) {
      this.form.list.splice(key, 1);
    },
    pickTanggalSurvey(key) {
      Swal.fire({
        title: this.trans('pengajuanjadwal.survey.messages.confirm rekomendasi'),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then(async (result) => {
        this.resetPickTanggalSurvey();
        if (result.value) {
          this.form.list[key].status = "active";
          let check = await this.checkSaldoSudahCukup();
          if (check.cukup) {
            this.submitForm("form");
          } else {
            this.bayarBiayaPelanggan("form", check.data);
          }
        }
      });
    },
    resetPickTanggalSurvey() {
      this.form.list.forEach(function (val, index) {
        val.status = "not_active";
      });
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
    checkPilihTanggal() {
      if (
        this.user_company.type == "isp" &&
        this.form.list.length > 0 &&
        this.form.status == "pending"
      ) {
        let checkActive = true;
        this.form.list.forEach(function (val, index) {
          if (val.status == "active") {
            checkActive = false;
          }
        });
        return checkActive;
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

          if (this.checkPilihTanggal()) {
            Swal.fire(
              this.trans("pengajuanjadwal.belum memilih"),
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
              if (this.user_company.type == "isp") {
                let check = await this.checkSaldoSudahCukup();
                if (check.cukup) {
                  this.submitForm(form);
                } else {
                  this.bayarBiayaPelanggan(form, check.data);
                }
              } else {
                this.submitForm(form);
              }
            }
          });
        }
      });
    },
    bayarBiayaPelanggan(form, data) {
      let total = parseInt(data.mrc) + parseInt(data.otc);
      let saldo = parseInt(data.saldo);
      let linkSaldo = window.origin + "/backend/saldo/topups";
      let sisa_saldo = saldo - total;
      let okButton = true;
      let cancelButton = true;
      let html = `
          <table style="width:100%">
            <tr><td>Saldo</td><td>:</td><td>Rp. ${this.number_format(
              data.saldo
            )}</td></tr>
            <tr><td colspan='3'><hr></td></tr>
            <tr><td>Biaya OTC</td><td>:</td><td>Rp. ${this.number_format(
              data.otc
            )}</td></tr>
            <tr><td>Biaya MRC</td><td>:</td><td>Rp. ${this.number_format(
              data.mrc
            )}</td></tr>`;

      if (data.terbayar != 0) {
        html += `<tr><th>Terbayar</th><td>:</td><td>- Rp. ${this.number_format(
          data.terbayar
        )}</td></tr>`;
        total = total - data.terbayar;
        sisa_saldo = sisa_saldo + data.terbayar;
      }

      html += `<tr><th>Total Biaya</th><td>:</td><td>Rp. ${this.number_format(
        total
      )}</td></tr>
            <tr><td colspan='3'><hr></td></tr>
            <tr><th>Sisa Saldo</th><td>:</td><td>Rp. ${this.number_format(
              sisa_saldo
            )}</td></tr>
          </table>`;
      if (sisa_saldo < 0) {
        html += `
            <div class="py-2">
              <a href="${linkSaldo}">
                <button class="btn btn-md btn-primary ">Topup Sekarang</button>
              </a> 
            </div>
            `;
        okButton = false;
        cancelButton = false;
      }
      Swal.fire({
        title: "",
        icon: "info",
        html: html,
        showCancelButton: cancelButton,
        showConfirmButton: okButton,
      }).then((result) => {
        if (result.value) {
          if (sisa_saldo < 0) {
            Swal.fire("Saldo tidak mencukupi", "", "error");
            this.loadingPengajuan = false;
          } else {
            axios
              .post(
                route("api.pelanggan.saldo.submit", {
                  pelanggan_id: this.pelanggan_id,
                })
              )
              .then((response) => {
                if (response.data.errors) {
                  Swal.fire(response.data.message, "", "error");
                  this.loadingPengajuan = false;
                } else {
                  this.submitForm(form);
                }
              })
              .catch((er) => {
                console.log(er);
                this.Toast({
                  icon: "error",
                  title: "error",
                });
                this.loadingPengajuan = false;
              });
          }
        } else {
          this.loadingPengajuan = false;
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
    checkSaldoSudahCukup() {
      return new Promise((resolve) => {
        this.loadingPengajuan = true;
        axios
          .get(
            route("api.pelanggan.saldo.check", {
              pelanggan_id: this.pelanggan_id,
            })
          )
          .then((response) => {
            return resolve(response.data);
          });
      });
    },
    tambahSchedule() {
      this.form.list_reschedule = [];
    },
    batalSchedule() {
      this.form = this.history[this.history.length - 1];
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
    pilihTanggalRekomendasi(id) {
      Swal.fire({
        title: this.trans(
          "pengajuanjadwal.survey.messages.confirm rekomendasi"
        ),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then((result) => {
        if (result.value) {
          this.loadingPengajuan = true;
          let properties = {
            pelanggan_id: this.pelanggan_id,
            pengajuan_id: id,
          };
          axios
            .post(
              route(
                "api.pelanggan.form.jadwal.survey.pilih_rekomendasi",
                properties
              )
            )
            .then((response) => {
              this.loadingPengajuan = true;
              let data = response.data;
              this.Toast({
                icon: "success",
                title: data.message,
              });
              this.$router.go();
            })
            .catch((error) => {
              this.loadingPengajuan = false;
              this.Toast({
                icon: "error",
                title: "error",
              });
            });
        }
      });
    },
    number_format(number, decimals, decPoint, thousandsSep) {
      number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
      var n = !isFinite(+number) ? 0 : +number;
      var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
      var sep = typeof thousandsSep === "undefined" ? "." : thousandsSep;
      var dec = typeof decPoint === "undefined" ? "." : decPoint;
      var s = "";

      var toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return "" + (Math.round(n * k) / k).toFixed(prec);
      };

      // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
      }

      return s.join(dec);
    },
    recheduleJadwal() {
      this.$emit("rechedule");
    },
  },
  mounted() {
    this.fetchPengajuanSurvey();
  },
};
</script>