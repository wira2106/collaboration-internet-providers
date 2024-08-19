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
              @click="seeHistoryPengajuanInstalasi"
              ><u>{{ trans("pengajuanjadwal.see history") }}</u></a
            >
            <a data-action="collapse"><i class="ti-minus text-white"></i></a>
          </div>
          <h5 class="text-white">
            {{ trans("pengajuanjadwal.instalasi.title") }}
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
                          {{ trans("pengajuanjadwal.tgl rekomendasi") }}
                        </td>
                        <td style="width: 10px">:</td>
                        <th>
                          {{ form.tgl_rekomendasi_c }}
                          {{ form.jam_rekomendasi_c }}
                        </th>
                      </tr>
                      <tr>
                        <td style="min-width: 150px">
                          {{ trans("pengajuanjadwal.keterangan") }}
                        </td>
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
                      @click="dialogFormVisible = true"
                    >
                      <i class="fa fa-check"></i>
                      {{ trans("pengajuanjadwal.button.pick rekomendasi") }}
                    </el-button>
                    <el-button type="primary" @click="addJadwal()">
                      <i class="fa fa-check"></i>
                      {{ trans("pengajuanjadwal.button.tambah opsi jadwal") }}
                    </el-button>
                  </div>
                </div>
              </div>
            </div>
            <div
              class="col-md-12"
              v-if="form.list.length == 0 && user_company.type == 'isp'"
            >
              <center>
                {{ trans("pengajuanjadwal.instalasi.jadwal belum") }}
              </center>
            </div>
            <div class="col-md-12" :hidden="form.list.length == 0">
              <el-form-item
                prop="teknisi_instalasi"
                :rules="rules.required_field"
                label="Pilih teknisi yang bertugas"
                v-if="user_company.type == 'osp'"
              >
                <el-select
                  v-if="select_teknisi"
                  v-model="form.teknisi_instalasi"
                  size="small"
                  ref="teknisi"
                  @change="changeTeknisi"
                  filterable
                  multiple
                >
                  <el-option
                    v-for="(item, index) in form_option.teknisi_instalasi"
                    :label="item.nama_teknisi"
                    :value="item.user_id"
                    :key="index"
                  ></el-option>
                  <el-option
                    :label="'+ ' + trans('installations.title.tambah teknisi')"
                    value="new"
                  ></el-option>
                </el-select>
                <input
                  v-else
                  type="text"
                  class="form-control form-control-sm"
                />
              </el-form-item>
            </div>
            <div class="col-md-12">
              <table
                :hidden="form.list.length == 0"
                class="table table-bordered table-pengajuan-survey"
              >
                <thead>
                  <tr>
                    <td>
                      {{ trans("pengajuanjadwal.instalasi.table header.no") }}
                    </td>
                    <td>
                      {{
                        trans("pengajuanjadwal.instalasi.table header.tanggal")
                      }}
                    </td>
                    <td>
                      {{ trans("pengajuanjadwal.instalasi.table header.slot") }}
                    </td>
                    <td align="center">
                      {{
                        trans("pengajuanjadwal.instalasi.table header.action")
                      }}
                    </td>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(val, key) in form.list" :key="key">
                    <td style="width: 30px">{{ key + 1 }}</td>
                    <td>
                      <!-- select tanggal instalasi -->
                      <div
                        v-if="
                          user_company.type == 'osp' && form.status != 'accept'
                        "
                      >
                        <el-form-item
                          class="mb-0"
                          :prop="'list.' + key + '.tgl_instalasi'"
                          :rules="rules.required_field"
                        >
                          <el-date-picker
                            style="width: 100%; margin-bottom: 0px"
                            v-model="val.tgl_instalasi"
                            type="date"
                            value-format="yyyy-MM-dd"
                            placeholder="Pick a day"
                            :picker-options="pickerOptions"
                            size="small"
                          >
                          </el-date-picker>
                        </el-form-item>
                      </div>
                      <!-- informasi tanggal instalasi -->
                      <div v-else>
                        {{ val.tgl_instalasi }}
                      </div>
                    </td>
                    <td>
                      <!-- select slot instalasi -->
                      <div
                        v-if="
                          user_company.type == 'osp' && form.status != 'accept'
                        "
                      >
                        <el-form-item
                          class="mb-0"
                          :prop="'list.' + key + '.slot_id'"
                          :rules="rules.required_field"
                        >
                          <el-select v-model="val.slot_id" size="small">
                            <el-option
                              v-for="item in slot_instalasi"
                              :key="item.slot_id"
                              :label="item.full_name"
                              :value="item.slot_id"
                            >
                            </el-option>
                          </el-select>
                        </el-form-item>
                      </div>
                      <!-- informasi slot instalasi -->
                      <div v-else>
                        {{ val.full_name }}
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
                        @click="pickTanggalInstalasi(key)"
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
                        {{ trans("pengajuanjadwal.button.add reschedule") }}
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
                  <i class="fa fa-plus"></i>
                  {{ trans("pengajuanjadwal.button.tambah jadwal") }}
                </el-button>
              </div>
            </div>
          </div>
        </div>
        <!-- card footer and button save -->
      </div>
    </el-form>

    <!-- modal untuk osp saat memilih jadwal rekomendasi dari isp -->
    <template>
      <el-dialog
        title="Tanggal request pelanggan"
        :visible.sync="dialogFormVisible"
        width="30rem"
        class="zindex-custom"
      >
        <el-form :model="form">
          <div class="card-body collapse show">
            <div class="row">
              <div class="">
                <table cellpadding="6">
                  <tr v-if="form.tgl_rekomendasi != null">
                    <td style="min-width: 150px">
                      {{ trans("pengajuanjadwal.tgl rekomendasi") }}
                    </td>
                    <td style="width: 10px">:</td>
                    <th>
                      {{ form.tgl_rekomendasi_c }}
                      {{ form.jam_rekomendasi_c }}
                    </th>
                  </tr>
                  <tr>
                    <td style="min-width: 150px">
                      {{ trans("pengajuanjadwal.keterangan") }}
                    </td>
                    <td style="width: 10px">:</td>
                    <td>{{ form.keterangan }}</td>
                  </tr>
                  <tr>
                    <td>
                      Pilih teknisi yang bertugas
                    </td>
                    <td style="width: 10px" colspan="2">:</td>
                  </tr>
                  <tr>
                    <td colspan="3">
                      <el-input
                        placeholder="cari teknisi"
                        v-model="search_teknisi"
                      ></el-input>
                    </td>
                  </tr>
                  <div class="scroll-custom">
                    <tr v-for="(item, i) in resultQuery" :key="i">
                      <td colspan="3">
                        <el-checkbox
                          v-model="item.value"
                          :label="item.nama_teknisi"
                        ></el-checkbox>
                      </td>
                    </tr>
                  </div>

                  <tr>
                    <td v-if="show_error" colspan="3" class="text-danger">
                      data teknisi tidak boleh kosong
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </el-form>

        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false" type="danger"
            >Cancel</el-button
          >
          <el-button
            type="primary"
            @click="pilihTanggalRekomendasi(form.pengajuan_id)"
            >Confirm</el-button
          >
        </span>
      </el-dialog>
    </template>
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
        teknisi_instalasi: [],
      },
      form_option: {
        teknisi_instalasi: [],
      },
      history: [],
      slot_instalasi: [],
      rules: {
        required_field: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
        ],
      },
      select_teknisi: true,
      loadingModal: false,
      dialogFormVisible: false,
      show_error: false,
      search_teknisi: null,
      checked2: "",
      selectableRange: ["00:00", "00:00"],
    };
  },
  computed: {
    selectableRangePicker: function() {
      return [
        this.selectableRange[0].substring(0, 5),
        this.selectableRange[1].substring(0, 5),
      ];
      // return `${this.selectableRange[0]} - ${this.selectableRange[1]}`;
    },
    resultQuery() {
      if (this.search_teknisi) {
        return this.form_option.teknisi_instalasi.filter((item) => {
          return this.search_teknisi
            .toLowerCase()
            .split(" ")
            .every((v) => item.nama_teknisi.toLowerCase().includes(v));
        });
      } else {
        return this.form_option.teknisi_instalasi;
      }
    },
  },
  methods: {
    fetchPengajuanInstalasi() {
      this.loadingPengajuan = true;
      let data = this.response.instalasi.data.data;
      let osp = this.response.instalasi.data.osp;
      this.fetchDateRangeSlot(osp);
      this.history = data;
      this.slot_instalasi = this.response.instalasi.data.slot_instalasi;
      if (data.length != 0) {
        this.form = {
          ...data[data.length - 1],
          teknisi_instalasi: [],
        };
      }
      this.loadingPengajuan = false;
    },
    addJadwal() {
      let push = {
        tgl_instalasi: "",
        slot_id: "",
        status: "not_active",
      };
      this.form.list.push(push);
    },
    removeTanggal(key) {
      this.form.list.splice(key, 1);
    },
    pickTanggalInstalasi(key) {
      Swal.fire({
        title: this.trans(
          "pengajuanjadwal.instalasi.messages.confirm rekomendasi"
        ),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then(async (result) => {
        this.resetPickTanggalInstalasi();
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
    resetPickTanggalInstalasi() {
      this.form.list.forEach(function(val, index) {
        val.status = "not_active";
      });
    },
    checkTanggalIsEmpty() {
      // check apakah tanggalnya ada yang kosong atau tidak
      let emptyTanggal = false;
      this.form.list.forEach(function(val, index) {
        if (
          val.slot_id == "" ||
          val.tgl_instalasi == "" ||
          val.tgl_instalasi == null
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
        this.form.list.forEach(function(val, index) {
          if (val.status == "active") {
            checkActive = false;
          }
        });
        return checkActive;
      }
      return false;
    },
    fecthDataTeknisi() {
      var pelanggan_id = this.$route.query.pelanggan;
      axios
        .get(
          route("api.pelanggan.form.data.instalasi.data.instalasi", {
            pelanggan: pelanggan_id,
          })
        )
        .then((res) => {
          this.form_option = {
            teknisi_instalasi: res.data.data_teknisi.map((teknisi) => {
              return {
                nama_teknisi: teknisi.nama_teknisi,
                user_id: teknisi.user_id,
                value: false,
              };
            }),
          };
          this.loadDataTeknisiPelangganInstalasi();
        });
    },
    loadDataTeknisiPelangganInstalasi() {
      const params = this.$route.query.pelanggan;
      axios
        .get(
          route("api.pelanggan.form.data.instalasi", {
            pelanggan: params,
          })
        )
        .then((response) => {
          var i = 0;
          var res = response.data.data_teknisi;
          this.form_option.teknisi_instalasi.find((s) => {
            if (typeof res[i] != "undefined") {
              if (s.user_id == res[i].user_id) {
                s.value = true;
              }
            }
            i++;
          });

          var res = response.data.data_teknisi;
          var jumlah_teknisi_terpilih = res.length;
          var a = 0;
          for (a; a < jumlah_teknisi_terpilih; a++) {
            this.form.teknisi_instalasi.push(res[a].user_id);
          }
        });
    },
    async onSubmit(form) {
      this.$refs[form].validate((valid) => {
        let fields = this.$refs[form].fields;
        for (let i = 0; i < fields.length; i++) {
          if (fields[i].validateState == "error") {
            $(fields[i].$el)
              .find("input")
              .focus();
            return false;
          }
        }
        if (valid) {
          if (this.checkTanggalIsEmpty()) {
            Swal.fire(
              this.trans("pengajuanjadwal.instalasi.messages.required jadwal"),
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
    sendForm() {
      return axios.post(
        route("api.pelanggan.form.jadwal.instalasi.submit", {
          pelanggan_id: this.pelanggan_id,
        }),
        this.form
      );
    },
    submitForm(form) {
      this.sendForm()
        .then((response) => {
          let data = response.data;
          this.Toast({
            icon: "success",
            title: data.message,
          });
          // this.fetchPengajuanInstalasi();
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
    seeHistoryPengajuanInstalasi() {
      $(".history-pengajuan-instalasi").modal();
    },
    fetchDateRangeSlot(osp_id) {
      this.selectableRange = this.response.range_slot;
    },
    pilihTanggalRekomendasi(id) {
      this.form_option.teknisi_instalasi.find((s) => {
        if (s.value == true) {
          Swal.fire({
            title: this.trans(
              "pengajuanjadwal.instalasi.messages.confirm rekomendasi"
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
              this.dialogFormVisible = false;
              this.loadingPengajuan = true;
              this.form.teknisi_instalasi = [];
              this.form_option.teknisi_instalasi.find((s) => {
                if (s.value == true) {
                  this.form.teknisi_instalasi.push(s.user_id);
                }
              });
              let properties = {
                pelanggan_id: this.pelanggan_id,
                pengajuan_id: id,
              };
              axios
                .post(
                  route(
                    "api.pelanggan.form.jadwal.instalasi.pilih_rekomendasi",
                    properties
                  ),
                  this.form.teknisi_instalasi
                )
                .then((response) => {
                  let data = response.data;
                  if (data.errors) {
                    this.Toast({
                      icon: "error",
                      title: data.message,
                    });
                    this.loadingPengajuan = false;
                  } else {
                    this.Toast({
                      icon: "success",
                      title: data.message,
                    });
                    this.loadingPengajuan = true;
                    this.$router.go();
                  }
                })
                .catch((error) => {
                  this.loadingPengajuan = false;
                  this.Toast({
                    icon: "error",
                    title: "error",
                  });
                });
            } else {
              return false;
            }
          });
        } else {
          this.show_error = true;
          setTimeout(() => {
            this.show_error = false;
          }, 1000);
          this.dialogFormVisible = true;
          return false;
        }
      });
    },
    changeTeknisi(value) {
      if (value.includes("new")) {
        this.select_teknisi = false;
        Swal.fire({
          icon: "warning",
          type: "warning",
          title: this.trans("core.messages.confirmation-form"),
          text: this.trans("pelangganinstalasi.alert.alert_pengalihan"),
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: this.trans("core.button.confirm"),
          cancelButtonText: this.trans("core.button.cancel"),
        }).then((result) => {
          if (result.value) {
            this.$router.push({
              name: "admin.user.staff.create",
            });
            this.select_teknisi = true;
            this.form.teknisi_instalasi.splice(
              this.form.teknisi_instalasi.length - 1,
              1
            );
          } else {
            this.select_teknisi = true;
            this.form.teknisi_instalasi.splice(
              this.form.teknisi_instalasi.length - 1,
              1
            );
          }
        });
      }
    },
    number_format(number, decimals, decPoint, thousandsSep) {
      number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
      var n = !isFinite(+number) ? 0 : +number;
      var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
      var sep = typeof thousandsSep === "undefined" ? "." : thousandsSep;
      var dec = typeof decPoint === "undefined" ? "." : decPoint;
      var s = "";

      var toFixedFix = function(n, prec) {
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
    this.fecthDataTeknisi();
    this.fetchPengajuanInstalasi();
  },
};
</script>
<style>
.zindex-custom {
  z-index: 1001 !important;
}
.v-modal {
  z-index: 1000 !important;
}
.validatorRequired {
  border: solid 1px red;
  box-shadow: 1px 1px 2px red;
}
.scroll-custom {
  display: block;
  max-height: 179px;
  width: 156%;
  overflow-y: auto;
}
</style>
