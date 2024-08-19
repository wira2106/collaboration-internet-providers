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
      <Card class="card card-outline-info">
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
              <div class="row">
                <div class="col-md-12">
                  <table class="table">
                    <tr v-if="form.tgl_rekomendasi != null">
                      <td style="width:33.5%">
                        {{ trans("pengajuanjadwal.tgl rekomendasi") }}
                      </td>
                      <td style="width: 10px">:</td>
                      <th>
                        {{ form.tgl_rekomendasi_c }}
                        {{ form.jam_rekomendasi_c }}
                      </th>
                    </tr>
                    <tr>
                      <td style="width:33.5%">
                        {{
                          form.keterangan == null && getKeteranganJadwal != "-"
                            ? trans(
                                "pengajuanjadwal.instalasi.messages.alasan reschedule"
                              )
                            : trans("pengajuanjadwal.keterangan")
                        }}
                      </td>
                      <td style="width: 10px">:</td>
                      <td>
                        {{ getKeteranganJadwal }}
                      </td>
                    </tr>
                    <tr v-if="!edit_data_jadwal">
                      <td style="width:33.5%">
                        Teknisi yang bertugas
                      </td>
                      <td style="width: 10px">:</td>
                      <td>
                        <div v-if="form.teknisi_instalasi.length != 0">
                          <span
                            class="span_noc"
                            v-for="(item, index) in form.teknisi_instalasi"
                            :key="index"
                          >
                            {{ getNamaTeknisi(item) }}
                          </span>
                        </div>
                      </td>
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
                    <i class="fa fa-plus"></i>
                    {{ trans("pengajuanjadwal.button.tambah opsi jadwal") }}
                  </el-button>
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
            <div
              class="col-md-12"
              v-if="edit_data_jadwal && form.list.length != 0"
            >
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
                    :label="'+ ' + trans('installations.title.tambah teknisi')"
                    value="new"
                  ></el-option>
                  <el-option
                    v-for="(item, index) in form_option.teknisi_instalasi"
                    :label="item.nama_teknisi"
                    :value="item.user_id"
                    :key="index"
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
                    <td
                      align="center"
                      v-if="user_company.type == 'isp' || edit_data_jadwal"
                    >
                      {{
                        trans("pengajuanjadwal.instalasi.table header.action")
                      }}
                    </td>
                  </tr>
                </thead>

                <!-- form inputan for osp -->
                <tbody v-if="user_company.type == 'osp' && edit_data_jadwal">
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
                            @change="validasiTime(key)"
                          >
                          </el-date-picker>
                        </el-form-item>
                      </div>
                      <!-- informasi tanggal instalasi -->
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
                          <el-select
                            v-model="val.slot_id"
                            size="small"
                            @change="validasiTime(key)"
                          >
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
                    </td>
                    <td
                      v-if="
                        user_company.type == 'osp' && form.status != 'accept'
                      "
                      align="center"
                    >
                      <a href="#!" @click="removeTanggal(key)" v-if="key != 0">
                        <i class="fa fa-trash text-danger"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>

                <!-- detail informatif jadwal instalasi -->
                <tbody v-if="user_company.type == 'isp' || !edit_data_jadwal">
                  <tr v-for="(val, key) in form.list" :key="key">
                    <td style="width: 30px">{{ key + 1 }}</td>
                    <td>
                      {{ val.tgl_instalasi }}
                    </td>
                    <td>
                      {{ val.full_name }}
                    </td>
                    <td
                      align="center"
                      v-if="
                        user_company.type == 'isp' && form.status == 'pending'
                      "
                    >
                      <button
                        type="button"
                        class="btn btn-sm btn-info"
                        @click="pickTanggalInstalasi(key)"
                      >
                        {{ trans("pengajuanjadwal.button.pilih") }}
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div
                v-if="user_company.type == 'osp'"
                class="col-md-12 pt-3 d-flex justify-content-center"
              >
                <el-button
                  v-if="form.list.length > 0 && edit_data_jadwal"
                  type="primary"
                  @click="addJadwal()"
                  icon="fa fa-plus"
                >
                  {{ trans("pengajuanjadwal.button.tambah jadwal") }}
                </el-button>
              </div>
            </div>
          </div>
        </div>
        <div
          class="card-footer text-center tombol-ajukan-rechedule"
          v-if="form.list.length > 0"
        >
          <div
            class="button-group pb-4"
            v-if="user_company.type == 'osp' && edit_data_jadwal == true"
          >
            <el-button
              type="danger"
              @click="batalEditJadwal()"
              icon="fa fa-times"
            >
              {{ trans("pengajuanjadwal.button.batal edit jadwal") }}
            </el-button>
            <el-button
              type="success"
              @click="onSubmit('form')"
              :loading="loadingPengajuan"
              icon="fa fa-save"
            >
              {{ trans("pengajuanjadwal.button.simpan jadwal") }}
            </el-button>
          </div>
          <el-button
            @click="edit_data_jadwal = true"
            type="primary"
            icon="fa fa-edit"
            v-if="
              form.status != 'accept' &&
                !edit_data_jadwal &&
                user_company.type == 'osp'
            "
          >
            {{ trans("pengajuanjadwal.button.edit jadwal") }}
          </el-button>
          <button
            type="button"
            class="btn btn-primary"
            @click="recheduleJadwal()"
            v-if="user_company.type == 'isp'"
          >
            {{ trans("pengajuanjadwal.button.add reschedule") }}
          </button>
        </div>
        <!-- card footer and button save -->
      </Card>
    </el-form>
    <Cart
      :action="false"
      v-model="carts"
      @submit="bayar_biaya_pelanggan"
      id="cart-pengajuan-jadwal-instalasi"
      :loading="loadingBayarPelanggan"
    />

    <!-- modal untuk osp saat memilih jadwal rekomendasi dari isp -->
    <template>
      <el-dialog
        :title="trans('pengajuanjadwal.instalasi.title')"
        :visible.sync="dialogFormVisible"
        width="30rem"
        class="zindex-custom"
      >
        <el-form :model="form">
          <div class="row">
            <div class="col-md-12" style="font-weight:500">
              <h6>{{ trans("pengajuanjadwal.tgl rekomendasi") }} :</h6>
            </div>
            <div class="col-md-12">
              {{ form.tgl_rekomendasi_c }}
              {{ form.jam_rekomendasi_c }}
            </div>

            <div class="col-md-12" style="font-weight:500">
              <hr />
              <h6>
                {{
                  form.keterangan == null && getKeteranganJadwal != "-"
                    ? trans(
                        "pengajuanjadwal.instalasi.messages.alasan reschedule"
                      )
                    : trans("pengajuanjadwal.keterangan")
                }}
                :
              </h6>
            </div>
            <div class="col-md-12">
              {{ getKeteranganJadwal }}
            </div>

            <div class="col-md-12" style="font-weight:500">
              <hr />
              <h6>Pilih teknisi yang bertugas :</h6>
            </div>
            <div class="col-md-12">
              <el-input
                placeholder="cari teknisi"
                v-model="search_teknisi"
              ></el-input>
            </div>
            <div class="col-md-12 overflow-custom">
              <div class="pt-2">
                <span v-for="(item, i) in resultQuery" :key="i">
                  <el-checkbox
                    v-model="item.value"
                    :label="item.nama_teknisi"
                  ></el-checkbox>
                  <br />
                </span>
              </div>
            </div>
          </div>
        </el-form>

        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false" type="danger">{{
            trans("core.button.btn cancel")
          }}</el-button>
          <el-button
            type="primary"
            @click="pilihTanggalRekomendasi(form.pengajuan_id)"
            >{{ trans("core.button.save") }}
          </el-button>
        </span>
      </el-dialog>
    </template>
  </div>
</template>

<script type="text/javascript">
import axios from "axios";
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";
import Cart from "../../../../../../Core/Assets/js/components/Cart.vue";

export default {
  mixins: [Toast, PermissionsHelper],
  props: ["response", "pelanggan_id", "dataLocalStorage"],
  components: {
    Cart,
  },
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
      loadingBayarPelanggan: false,
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
      edit_data_jadwal: false,
      button_edit_jadwal: false,
      search_teknisi: null,
      checked2: "",
      selectableRange: ["00:00", "00:00"],
      biaya: {
        mrc: 0,
        otc: 0,
      },
      list_temp: [],
      array_validasi_jadwal: [],
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
    carts: function() {
      return [
        {
          name: "Biaya MRC",
          qty: 1,
          harga: this.biaya.mrc,
        },
        {
          name: "Biaya OTC",
          qty: 1,
          harga: this.biaya.otc,
        },
      ];
    },
    getKeteranganJadwal() {
      var keterangan_jadwal = this.form.keterangan;
      var history_jadwal = this.history;
      var jumlah_history_jadwal = history_jadwal.length;
      if (keterangan_jadwal == null) {
        if (jumlah_history_jadwal > 1) {
          keterangan_jadwal =
            history_jadwal[jumlah_history_jadwal - 2].alasan_reschedule;
          if (keterangan_jadwal == null) {
            return "-";
          }
        } else {
          return "-";
        }
      }
      return keterangan_jadwal;
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
    validasiTime(key) {
      var array = this.array_validasi_jadwal;
      var data_time = this.form.list;

      if (
        data_time[key].slot_id != null &&
        data_time[key].tgl_instalasi != null
      ) {
        array.find((data_find, index) => {
          if (
            data_time[key].slot_id == data_find.slot_id &&
            data_time[key].tgl_instalasi == data_find.tgl_instalasi &&
            key != index
          ) {
            this.form.list[key].tgl_instalasi = null;
            this.form.list[key].slot_id = "";
            this.form.list[key].status = "not_active";
            Swal.fire({
              title: this.trans(
                "pengajuanjadwal.instalasi.messages.jadwal sama"
              ),
              icon: "warning",
              showCancelButton: false,
              confirmButtonColor: "#3085d6",
              confirmButtonText: this.trans("pengajuanjadwal.button.ok"),
            }).then(() => {
              this.array_validasi_jadwal = [];
              this.array_validasi_jadwal.push(...data_time);
            });
          } else {
            this.array_validasi_jadwal = [];
            this.array_validasi_jadwal.push(...data_time);
          }
        });

        if (array.length == 0) {
          array.push(...data_time);
        }
      }
    },
    getNamaTeknisi(id) {
      this.form.teknisi_instalasi;
      var a = this.form_option.teknisi_instalasi.find((s) => {
        if (s.user_id == id) {
          return s.nama_teknisi;
        }
      });
      if (a) {
        return a.nama_teknisi;
      }
      return null;
    },
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

        this.array_validasi_jadwal.push(...data[data.length - 1].list);

        this.list_temp = [
          ...data[data.length - 1].list.map((item) => {
            return { ...item };
          }),
        ];
      }
      this.loadingPengajuan = false;
    },
    addJadwal() {
      let push = {
        tgl_instalasi: null,
        slot_id: null,
        status: "not_active",
      };
      this.form.list.push(push);
      this.edit_data_jadwal = true;
    },
    removeTanggal(key) {
      this.form.list.splice(key, 1);
      if (this.form.list.length == 0) {
        this.form.keterangan = null;
        this.form.teknisi_instalasi = [];
      }
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
        this.$Progress.start();
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

          //jika localstorage berisi data maka akan di load di kondisi if
          if (this.dataLocalStorage) {
            this.form = this.dataLocalStorage;
            this.edit_data_jadwal = true;
          } else {
            this.loadDataTeknisiPelangganInstalasi();
          }
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
          var res = response.data.data_teknisi;
          this.form_option.teknisi_instalasi = this.form_option.teknisi_instalasi.map(
            (teknisi) => {
              let check = res.find((item) => item.user_id == teknisi.user_id);

              if (check) {
                teknisi.value = true;
                this.form.teknisi_instalasi.push(check.user_id);
              }

              return teknisi;
            }
          );
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
              this.$Progress.start();
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
      this.biaya.mrc = data.mrc;
      this.biaya.otc = data.otc;
      $("#cart-pengajuan-jadwal-instalasi").modal();
    },
    batalEditJadwal() {
      this.form.list = [
        ...this.list_temp.map((item) => {
          return { ...item };
        }),
      ];
      this.edit_data_jadwal = false;
    },
    bayar_biaya_pelanggan() {
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
          this.loadingBayarPelanggan = true;
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
                this.loadingBayarPelanggan = false;
              } else {
                this.submitForm("form");
              }
            })
            .catch((er) => {
              console.log(er);
              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title"),
              });
              this.loadingPengajuan = false;
              this.loadingBayarPelanggan = false;
            });
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
          //hapus data local storage saat data di save
          localStorage.clear();
          this.dataLocalStorage = null;
          let data = response.data;
          this.Toast({
            icon: "success",
            title: data.message,
          });
          this.$Progress.finish();
          // this.fetchPengajuanInstalasi();
          // window.location = route('admin.pelanggan.form.step')+"?pelanggan="+this.pelanggan_id;
          this.$router.go();
        })
        .catch((error) => {
          this.$Progress.finish();
          this.loadingPengajuan = false;
          this.Toast({
            icon: "error",
            title: this.trans("core.error 500 title"),
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
      var data_teknisi_instalasi = this.form_option.teknisi_instalasi;
      var jumlah_data_teknisi_instalasi = data_teknisi_instalasi.length;
      for (var i = 0; i < jumlah_data_teknisi_instalasi; i++) {
        //mengecek apakah ada teknisi yang dipilih atau tidak
        if (data_teknisi_instalasi[i].value == true) {
          Swal.fire({
            title: this.trans(
              "pengajuanjadwal.instalasi.messages.simpan jadwal"
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
              this.form_option.teknisi_instalasi.forEach((s) => {
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
          return false;
        }

        // kondisi jika sampai data teknisi terakhir masih bernilai false
        if (i == jumlah_data_teknisi_instalasi - 1) {
          if (
            data_teknisi_instalasi[jumlah_data_teknisi_instalasi - 1].value ==
            false
          ) {
            Swal.fire({
              icon: "warning",
              type: "warning",
              title: this.trans(
                "pengajuanjadwal.instalasi.messages.teknisi required"
              ),
              showCancelButton: false,
              confirmButtonColor: "#3085d6",
              confirmButtonText: this.trans("pengajuanjadwal.button.ok"),
            }).then(() => {
              this.dialogFormVisible = true;
              return false;
            });
          }
        }
      }
    },
    changeTeknisi(value) {
      if (value.includes("new")) {
        this.form.teknisi_instalasi.splice(
          this.form.teknisi_instalasi.length - 1,
          1
        );

        // simpan data di local storage
        var data = {
          mode: "pengajuan_jadwal_instalasi",
          data: this.form,
        };
        var data_fill = JSON.stringify(data);
        localStorage.setItem("data_fill_storage", data_fill);

        this.$router.push({
          name: "admin.user.staff.create",
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

.overflow-custom {
  height: 10rem;
  overflow: auto;
}
</style>
