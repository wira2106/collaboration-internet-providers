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
            <a href="#!" class="text-white" @click="seeHistoryPengajuanSurvey"
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
              <div
                v-if="
                  (form.tgl_rekomendasi != null ||
                    form.keterangan != null ||
                    teknisi != '') &&
                    (!inputJadwal || form.jam_rekomendasi_c !== null)
                "
              >
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
                      <tr
                        v-if="
                          form.keterangan != '' &&
                            form.keterangan != null &&
                            form.alasan_reschedule == null
                        "
                      >
                        <td style="min-width: 150px">
                          {{ trans("pengajuanjadwal.keterangan") }}
                        </td>
                        <td style="width: 10px">:</td>
                        <td>{{ form.keterangan }}</td>
                      </tr>
                      <tr
                        v-if="
                          form.alasan_reschedule != '' &&
                            form.alasan_reschedule != null &&
                            form.keterangan == null
                        "
                      >
                        <td style="min-width: 150px">
                          {{ trans("pengajuanjadwal.alasan") }}
                        </td>
                        <td style="width: 10px">:</td>
                        <td>{{ form.alasan_reschedule }}</td>
                      </tr>
                      <tr v-if="teknisi != ''">
                        <td style="min-width: 150px">
                          {{ trans("surveys.form.teknisi") }}
                        </td>
                        <td style="width: 10px">:</td>
                        <td>
                          <div class="row">
                            <div class="col-md-12">
                              <div
                                class="span-noc mt-1"
                                v-for="(list, index) in teknisi"
                                :key="index"
                              >
                                {{ list.nama_teknisi }}
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div
                    class="col-md-12 pt-3 d-flex justify-content-center"
                    v-if="user_company.type == 'osp' && form.list.length == 0"
                  ></div>
                </div>
              </div>
            </div>

            <!-- Ket Jadwal Survey Tidak Tersedia -->

            <div
              class="col-md-12"
              v-if="
                form.list.length == 0 &&
                  user_company.type == 'isp' &&
                  form.tgl_rekomendasi == null &&
                  form.keterangan == null
              "
            >
              <center>
                {{ trans("pengajuanjadwal.survey.jadwal belum") }}
              </center>
            </div>

            <!-- End Ket Jadwal Survey Tidak Tersedia -->

            <div class="col-md-12">
              <!-- Tambah Teknisi Survey -->

              <div
                class="pt-2"
                :hidden="form.list.length == 0"
                v-if="
                  inputJadwal &&
                    user_company.type == 'osp' &&
                    form.status != 'accept'
                "
              >
                <label> {{ trans("surveys.form.teknisi") }}</label>
                <el-form-item prop="loadTeknisi" :rules="rules.required_field">
                  <el-select
                    v-model="form.loadTeknisi"
                    multiple
                    filterable
                    @change="changeTeknisiSelect"
                    v-if="editTeknisi"
                    :placeholder="trans('surveys.placeholder.teknisi')"
                    size="small"
                  >
                  
                    <el-option
                      :label="trans('pengajuanjadwal.button.tambah teknisi')"
                      :value="'new'"
                    ></el-option>
                    <el-option
                      v-for="(item, index) in list_teknisi"
                      :key="index"
                      :label="item.nama_teknisi"
                      :value="item.user_id"
                    >
                    </el-option>
                  </el-select>
                </el-form-item>
              </div>

              <!-- End Tambah Teknisi Survey -->

              <!-- Table Jadwal -->

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
                    <td align="center" v-if="inputJadwal || user_company.type == 'isp'">
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
                          inputJadwal &&
                            user_company.type == 'osp' &&
                            form.status != 'accept'
                        "
                      >
                        <el-form-item
                          class="mb-0"
                          :prop="'list.' + key + '.tgl_survey'"
                          :rules="rules.required_field_jadwal"
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
                          inputJadwal &&
                            user_company.type == 'osp' &&
                            form.status != 'accept'
                        "
                      >
                        <el-form-item
                          class="mb-0"
                          :prop="'list.' + key + '.jam_survey'"
                          :rules="rules.required_field_jadwal"
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
                        inputJadwal &&
                          user_company.type == 'osp' &&
                          form.status != 'accept'
                      "
                      align="center"
                    >
                      <a href="#!" @click="removeTanggal(key)" v-if="key !== 0">
                        <i class="fa fa-trash text-danger"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- End Table Jadwal -->

              
              <!-- Button Tambah field jadwal -->
              <div class="col-md-12 pt-0 pb-2 d-flex justify-content-center"  v-if="user_company.type == 'osp' && lengthJadwal !== 0" >
                <el-button type="primary" @click="addJadwal()" :hidden="!inputJadwal">
                  <i class="fa fa-plus"></i>
                  {{ trans("pengajuanjadwal.button.tambah jadwal") }}
                </el-button>
              </div>
              <!-- End Button Tambah field jadwal -->
            </div>
          </div>
        </div>

        <!-- card footer and button save -->
        <div class="card-footer">
          <div class="row">
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
            <!-- end tombol ajukan reschedule -->

            <!-- button edit jadwal -->
            <div
              class="col-md-12 pt-0 pb-2 d-flex justify-content-center"
              v-if="user_company.type == 'osp' && lengthJadwal !== 0"
              :hidden="lengthJadwal == 0"
            >
              <el-tooltip
                class="item"
                effect="dark"
                :content="trans('surveys.tooltip.edit jadwal')"
                placement="top"
                v-if="!inputJadwal"
              >
                <el-button
                  type="primary"
                  class="pull-right"
                  icon="el-icon-edit-outline"
                  @click.prevent="editJadwal()"
                >
                  {{ trans("pengajuanjadwal.button.edit jadwal") }}
                </el-button>
              </el-tooltip>
            </div>
            <!-- End button edit jadwal -->
            <!-- Button Tambah Jadwal & Pilih Tgl Permintaan -->

            <div
              class="col-md-12"
              v-if="form.list.length == 0 && user_company.type == 'osp'"
            >
              <center>
                <el-button
                  v-if="form.tgl_rekomendasi != null"
                  type="success"
                  @click="modalAddTeknisi(form.pengajuan_id)"
                >
                  <i class="fa fa-check"></i>
                  {{ trans("pengajuanjadwal.button.pick rekomendasi") }}
                </el-button>
                <el-button type="primary" @click="addJadwal()">
                  <i class="fa fa-plus"></i>
                  {{ trans("pengajuanjadwal.button.tambah opsi jadwal") }}
                </el-button>
              </center>
            </div>

            <!-- Enc Button Tambah Jadwal & Pilih Tgl Permintaan -->
            <!-- Button Simpan Jadwal Baru & tambah tgl Permintaan -->

            <div
              class="col-md-12 d-flex justify-content-center"
              v-if="
                inputJadwal &&
                  user_company.type == 'osp' &&
                  form.list.length > 0
              "
            >
              <!-- Batal edi jadwal -->
              <div
                v-if="user_company.type == 'osp'"
                :hidden="lengthJadwal == 0"
              >
                <el-tooltip
                  class="item"
                  effect="dark"
                  :content="trans('surveys.tooltip.batal edit jadwal')"
                  placement="top"
                  v-if="inputJadwal"
                >
                  <el-button
                    type="danger"
                    class="mr-3"
                    icon="el-icon-close"
                    @click.prevent="batalEditJadwal()"
                  >
                    {{ trans("pengajuanjadwal.button.batal edit jadwal") }}
                  </el-button>
                </el-tooltip>
              </div>
              <!-- Batal edi jadwal -->
              <!-- Edit Batal edi jadwal -->
              <el-button
                type="success"
                @click="onSubmit('form')"
                :loading="loadingPengajuan"
                :disabled="disableSubmit"
              >
                {{ trans("pengajuanjadwal.button.simpan jadwal") }}
              </el-button>
              <el-button type="primary" @click="addJadwal()" v-if="user_company.type == 'osp'" :hidden="!(lengthJadwal == 0)">
                <i class="fa fa-plus"></i>
                {{ trans("pengajuanjadwal.button.tambah jadwal") }}
              </el-button>
            </div>

            <!-- End Button Simpan Jadwal Baru & tambah tgl Permintaan -->
          </div>
        </div>
      </Card>
    </el-form>

    <Cart
      :value="carts"
      :action="false"
      @submit="bayar_biaya_pelanggan"
      id="cart_pengajuan_jadwal"
      :loading="loadingBayarPelanggan"
    />
    <!-- Modal add teknisi -->

    <div class="modal fade modal-teknisi" id="myModal">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <modalTeknisiSurvey
            :data_teknisi="data_teknisi"
            :pelanggan_id="pelanggan_id"
            :pengajuan_id="form.pengajuan_id"
            @pilihTanggalRekomendasi="pilihTanggalRekomendasi($event)"
          ></modalTeknisiSurvey>
        </div>
      </div>
    </div>

    <!-- End Modal -->
  </div>
</template>

<script type="text/javascript">
import axios from "axios";
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";
import TeknisiForm from "../../survey/osp/TeknisiForm.vue";
import modalTeknisiSurvey from "../../modal/modalTeknisiSurvey.vue";
import Cart from "../../../../../../Core/Assets/js/components/Cart.vue";
export default {
  mixins: [Toast, PermissionsHelper],
  props: ["response", "pelanggan_id", "loadTeknisi", "teknisi", "list_teknisi"],
  components: {
    TeknisiForm: TeknisiForm,
    modalTeknisiSurvey: modalTeknisiSurvey,
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
      biaya: {
        mrc: 0,
        otc: 0,
      },
      tgl_survey: null,
      loadingPengajuan: false,
      disableSubmit: false,
      history: [],
      form: {
        rekomendasiExist: false,
        jam_rekomendasi: "08:00",
        tgl_rekomendasi: "",
        keterangan: "",
        alasan_reschedule: "",
        status: "",
        list: [],
        loadTeknisi: [],
      },
      rules: {
        required_field: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
        ],
        required_field_jadwal: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
          { validator: this.uniqueField, trigger: "change" },
        ],
      },
      data_teknisi: {
        list_teknisi: [],
        tgl_pengajuan: "",
        jam_pengajuan: "",
        keterangan: "",
      },
      loadingModal: false,
      selectableRange: ["00:00", "00:00"],
      loadingBayarPelanggan: false,
      inputJadwal: false,
      editTeknisi: true,
      lengthJadwal: 0,
      awalList: [],
    };
  },
  computed: {
    selectableRangePicker: function() {
      this.listDataTeknisi(this.list_teknisi);
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
  },
  methods: {
    changeTeknisiSelect(value) {
      this.form.inputJadwal = this.inputJadwal;
      if (value.includes("new")) {
        this.$emit("saveFormToLocal", {
          mode: "tambah_jadwal",
          data: this.form,
        });
        this.$router.push({
          name: "admin.user.staff.create",
        });
        // this.editTeknisi = false;
        // Swal.fire({
        //   icon: "warning",
        //   type: "warning",
        //   title: this.trans("core.messages.confirmation-form"),
        //   text: this.trans("pelangganinstalasi.alert.alert_pengalihan"),
        //   showCancelButton: true,
        //   confirmButtonColor: "#3085d6",
        //   cancelButtonColor: "#d33",
        //   confirmButtonText: this.trans("core.button.confirm"),
        //   cancelButtonText: this.trans("core.button.cancel"),
        // }).then((result) => {
        //   if (result.value) {
        //     this.editTeknisi = true;
        //     this.$router.push({
        //       name: "admin.user.staff.create",
        //     });
        //     this.form.loadTeknisi.splice(this.form.loadTeknisi.length - 1, 1);
        //   } else {
        //     this.editTeknisi = true;
        //     this.form.loadTeknisi.splice(this.form.loadTeknisi.length - 1, 1);
        //   }
        // });
      }
    },
    fetchPengajuanSurvey() {
      this.loadingPengajuan = true;
      let data = this.response.data.data;
      let osp = this.response.data.osp;
      this.fetchDateRangeSlot(osp);
      if (data.length != 0) {
        this.form = {
          ...data[data.length - 1],
          loadTeknisi: [],
        };

        this.form.list.forEach((value) => {
          this.awalList.push(value);
        });

        this.lengthJadwal = this.form.list.length;
      }
      this.loadTeknisi.forEach((element) => {
        this.form.loadTeknisi.push(element.user_id);
      });

      //check local data
      let dataLocal = localStorage.getItem("data_fill_storage"),
        pengajuan = null;
      if (dataLocal !== null && dataLocal !== "") {
        pengajuan = JSON.parse(dataLocal);
        if (
          pengajuan.mode == "tambah_jadwal" &&
          pengajuan.data.pelanggan_id == this.form.pelanggan_id
        ) {
          this.form.list = [];
          this.form.loadTeknisi = [];
          this.inputJadwal = pengajuan.data.inputJadwal;
          this.form.keterangan = pengajuan.data.keterangan;
          this.form.alasan_reschedule = pengajuan.data.alasan_reschedule;
          this.form.rekomendasiExist = pengajuan.data.rekomendasiExist;
          pengajuan.data.list.forEach((value) => {
            this.form.list.push(value);
          });
          pengajuan.data.loadTeknisi.forEach((teknisiIns) => {
            if (teknisiIns !== "new") {
              this.form.loadTeknisi.push(teknisiIns);
            }
          });
          console.log(pengajuan.user_baru);
          if (pengajuan.user_baru !== null && pengajuan.user_baru !== '' && pengajuan.user_baru !== undefined) {
            if(pengajuan.user_baru.role == 'teknisi'){
              this.form.loadTeknisi.push(pengajuan.user_baru.user_id);
            }
          }
        }
      }
      this.loadingPengajuan = false;
    },
    addJadwal() {
      // membuat  input jadwal menjadi true(menampilkan input jadwal)
      if (!this.inputJadwal) {
        this.inputJadwal = true;
      }
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
      this.form.list.forEach(function(val, index) {
        val.status = "not_active";
      });
    },
    checkTanggalIsEmpty() {
      // check apakah tanggalnya ada yang kosong atau tidak
      let emptyTanggal = false;
      this.form.list.forEach(function(val, index) {
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
        this.form.list.forEach(function(val, index) {
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
            $(fields[i].$el)
              .find("input")
              .focus();
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
            title: this.trans("pengajuanjadwal.simpan jadwal"),
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
      $("#cart_pengajuan_jadwal").modal();
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
              } else {
                this.submitForm("form");
              }

              this.loadingBayarPelanggan = false;
            })
            .catch((er) => {
              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title"),
              });
              this.loadingPengajuan = false;
              this.loadingBayarPelanggan = false;
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
          localStorage.removeItem("data_fill_storage");
          this.$Progress.finish();
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
    pilihTanggalRekomendasi(data) {
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
          $(".modal-teknisi").modal("hide");
          this.loadingPengajuan = true;
          let properties = {
            pelanggan_id: this.pelanggan_id,
            pengajuan_id: data.pengajuan_id,
          };
          let dataTeknisi = data.dataTeknisi;

          axios
            .post(
              route(
                "api.pelanggan.form.jadwal.survey.pilih_rekomendasi",
                properties
              ),
              dataTeknisi
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
                title: this.trans("core.error 500 title"),
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
    listDataTeknisi(data) {
      this.data_teknisi.list_teknisi = data;
      this.data_teknisi.keterangan = this.form.keterangan;
      this.data_teknisi.tgl_pengajuan = this.form.tgl_rekomendasi_c;
      this.data_teknisi.jam_pengajuan = this.form.jam_rekomendasi_c;
    },
    modalAddTeknisi(pelanggan_id) {
      this.teknisi.forEach((value) => {
        this.changeValueTeknisi(value.user_id, this.data_teknisi.list_teknisi);
      });
      $(".modal-teknisi").modal("show");
    },
    editJadwal() {
      return (this.inputJadwal = true);
    },
    batalEditJadwal() {
      this.form.list = [];
      this.awalList.forEach((value) => {
        this.form.list.push(value);
      });
      return (this.inputJadwal = false);
    },
    changeValueTeknisi(nameKey, myArray) {
      for (var i = 0; i < myArray.length; i++) {
        if (myArray[i].user_id === nameKey) {
          return (myArray[i].status = true);
        }
      }
    },
    checkUnique(field, list) {
      var jumlah = 0;
      if (field.jam_survey !== "" && field.tgl_survey !== "") {
        for (var i = 0; i < list.length; i++) {
          if (
            list[i].tgl_survey == field.tgl_survey &&
            list[i].jam_survey == field.jam_survey
          ) {
            jumlah++;
          }
        }
      }
      return jumlah;
    },
    uniqueField(rule, value, callback) {
      // get index field
      var field = rule.field,
        index = field.charAt(5);

      // end get index
      if (index == "e") {
        index = 0;
      }
      if (this.checkUnique(this.form.list[index], this.form.list) > 1) {
        Swal.fire(this.trans("pengajuanjadwal.unique"), "", "warning");
        // .then((result)=>{

        //   if (result.value || result.dismiss =="backdrop") {
        //     this.form.list[index].tgl_survey ='';
        //     this.form.list[index].jam_survey ='';
        //     // return callback(new Error(this.trans("pengajuanjadwal.unique")));
        //   }
        // });
        this.form.list[index].tgl_survey = "";
        this.form.list[index].jam_survey = "";
      } else {
        callback();
      }
    },
  },
  mounted() {
    this.fetchPengajuanSurvey();
    this.listDataTeknisi(this.list_teknisi);
  },
};
</script>
<style>
.modal-sm {
  max-width: 440px !important;
}
</style>
