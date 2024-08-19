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
            @click="seeHistoryPengajuanInstalasi"
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
              <div class="col-md-12" v-if="user_company.type == 'osp'">
                <el-form-item
                  prop="teknisi_instalasi"
                  :rules="rules.required_field"
                  label="Pilih teknisi yang bertugas"
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
                      v-for="(item, index) in form_option.DataTeknisi"
                      :label="item.nama_teknisi"
                      :value="item.user_id"
                      :key="index"
                    ></el-option>
                    <el-option
                      :label="
                        '+ ' + trans('installations.title.tambah teknisi')
                      "
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

          <!-- pilihan tanggal intalasi -->
          <div class="col-md-12" v-if="user_company.type == 'osp'">
            <div class="row">
              <div class="col-md-12">
                <center v-if="form.list.length == 0">
                  {{ trans("pengajuanjadwal.instalasi.jadwal belum") }}
                </center>
                <table
                  :hidden="form.list.length == 0"
                  class="table table-bordered table-pengajuan-instalasi"
                >
                  <thead>
                    <tr>
                      <td>
                        {{ trans("pengajuanjadwal.instalasi.table header.no") }}
                      </td>
                      <td>
                        {{
                          trans(
                            "pengajuanjadwal.instalasi.table header.tanggal"
                          )
                        }}
                      </td>
                      <td>
                        {{
                          trans("pengajuanjadwal.instalasi.table header.slot")
                        }}
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
                        <div>
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
                      </td>
                      <td>
                        <!-- select slot instalasi -->
                        <div>
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
      select_teknisi: true,
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
        DataTeknisi: [],
      },
      slot_instalasi: [],
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
    selectableRangePicker: function() {
      return [
        this.selectableRange[0].substring(0, 5),
        this.selectableRange[1].substring(0, 5),
      ];
      // return `${this.selectableRange[0]} - ${this.selectableRange[1]}`;
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
      this.loadingPengajuan = false;
      if (this.user_company.type == "osp") {
        this.addJadwal();
      }
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
    batalSchedule() {
      $(".tombol-ajukan-rechedule").show();
      this.$emit("unrechedule");
    },
    seeHistoryPengajuanInstalasi() {
      $(".history-pengajuan-instalasi").modal();
    },
    fetchDateRangeSlot(osp_id) {
      this.selectableRange = this.response.range_slot;
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
            DataTeknisi: res.data.data_teknisi,
          };
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
          var jumlah_teknisi_terpilih = res.length;
          var a = 0;
          for (a; a < jumlah_teknisi_terpilih; a++) {
            this.form.teknisi_instalasi.push(res[a].user_id);
          }
        });
    },
  },
  mounted() {
    this.loadDataTeknisiPelangganInstalasi();
    this.fetchPengajuanInstalasi();
    this.fecthDataTeknisi();
    $(".tombol-ajukan-rechedule").hide();
  },
};
</script>
