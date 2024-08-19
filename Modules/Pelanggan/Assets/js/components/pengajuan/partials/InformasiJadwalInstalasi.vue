<template>
  <div>
    <!-- CARD INFORMASI JADWAL INSTALASI YANG DIPILIH -->
    <Card class="card card-outline-info" v-if="form.status == 'accept'">
      <div class="card-header" style="border-bottom: 0px">
        <div class="card-actions">
          <a href="#!" class="text-white" @click="seeHistoryPengajuanInstalasi"
            ><u>{{ trans("pengajuanjadwal.see history") }}</u></a
          >
          <a data-action="collapse"><i class="ti-minus text-white"></i></a>
        </div>
        <h5 class="text-white">
          {{ trans("pengajuanjadwal.instalasi.tanggal dipilih") }}
        </h5>
      </div>
      <div class="card-body collapse show">
        <div class="row">
          <div class="col-md-12">
            <table class="table" style="width: 100%;">
              <tr class="py-5">
                <th style="width: 250px;">
                  {{ trans("pelangganinstalasi.form.tgl instalasi") }}
                </th>
                <td style="width: 10px;">:</td>
                <td colspan="2">
                  <span>{{ tanggal_instalasi }}</span>
                </td>
              </tr>
              <tr class="py-5">
                <th style="width: 250px;">
                  {{ trans("pelangganinstalasi.form.slot") }}
                </th>
                <td style="width: 10px;">:</td>
                <td colspan="2">
                  <span>{{ slot_instalasi }}</span>
                </td>
              </tr>
              <tr class="py-5">
                <th style="width: 250px;">
                  {{ trans("pelangganinstalasi.form.teknisi") }}
                </th>
                <td style="width: 10px;">:</td>
                <td colspan="2">
                  <!-- teknisi -->
                  <template>
                    <div v-if="!edit_teknisi">
                      <div class="row">
                        <div class="col-md-12">
                          <div v-if="teknisi_afterEdit.length != 0">
                            <span
                              class="span_noc"
                              v-for="(item, index) in teknisi_afterEdit"
                              :key="index"
                            >
                              {{ item.nama_teknisi }}
                            </span>
                            <a
                              class="icon-edit"
                              v-if="user_company.type == 'osp'"
                              :hidden="isReschedule"
                              @click="edit_teknisi = true"
                              ><i class="fa fa-edit text-primary"></i
                            ></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </template>

                  <template>
                    <div v-if="edit_teknisi">
                      <div class="row">
                        <div class="col-md-9">
                          <el-form
                            ref="form"
                            :model="form"
                            label-width="120px"
                            label-position="top"
                            :rules="rules"
                            v-loading="loading_teknisi"
                          >
                            <el-form-item
                              prop="teknisi_instalasi"
                              :rules="rules.required_field"
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
                                  :label="
                                    '+ ' +
                                      trans(
                                        'installations.title.tambah teknisi'
                                      )
                                  "
                                  value="new"
                                ></el-option>
                                <el-option
                                  v-for="(item,
                                  index) in form_option.teknisi_instalasi"
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
                          </el-form>
                        </div>
                        <div class="col-md-3">
                          <div v-if="edit_teknisi">
                            <el-form>
                              <el-form-item>
                                <el-button
                                  type="danger"
                                  size="mini"
                                  icon="el-icon-close"
                                  @click="
                                    (edit_teknisi = false),
                                      loadDataTeknisiPelangganInstalasi()
                                  "
                                >
                                </el-button>
                                <el-button
                                  type="success"
                                  size="mini"
                                  icon="el-icon-check"
                                  @click="submitTeknisi()"
                                >
                                </el-button>
                              </el-form-item>
                            </el-form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </template>
                  <!-- end teknisi -->
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div
        class="card-footer tombol-ajukan-rechedule"
        v-if="user_company.type == 'osp' && buttonRechedule"
      >
        <!-- tombol ajukan reschedule -->

        <div class="text-center">
          <button
            id="click"
            type="button"
            class="btn btn-primary "
            @click="recheduleJadwal(), (edit_teknisi = false)"
          >
            {{ trans("pengajuanjadwal.button.add reschedule") }}
          </button>
        </div>
      </div>
    </Card>
  </div>
</template>

<script type="text/javascript">
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [Toast, PermissionsHelper],
  props: ["response", "pelanggan_id", "dataLocalStorage","isReschedule"],
  data() {
    return {
      loadingPengajuan: false,
      tanggal_instalasi: null,
      slot_instalasi: null,
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
      rules: {
        required_field: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
        ],
      },
      teknisi_afterEdit: [],
      teknisi_load: [],
      history: [],
      select_teknisi: true,
      edit_teknisi: false,
      buttonRechedule: true,
      loading_teknisi: false,
    };
  },
  methods: {
    fetchPengajuanInstalasi() {
      let data = this.response.instalasi.data.data;
      this.buttonRechedule = this.response.instalasi.buttonRechedule;
      this.history = data;
      if (data.length != 0) {
        this.form = {
          ...data[data.length - 1],
          teknisi_instalasi:[]
          };
        let vm = this;
        this.form.list.forEach((val) => {
          if (val.status == "active") {
            vm.tanggal_instalasi = val.tanggal;
            vm.slot_instalasi = val.full_name;
            return false;
          }
        });
      }
    },
    seeHistoryPengajuanInstalasi() {
      $(".history-pengajuan-instalasi").modal();
    },
    recheduleJadwal() {
      $(".hasil-instalasi").hide();
      $(".icon-edit").hide();
      this.$emit("rechedule");
    },
    changeTeknisi(value) {
      if (value.includes("new")) {
        this.form.teknisi_instalasi.splice(
          this.form.teknisi_instalasi.length - 1,
          1
        );

        // simpan data di local storage
        var data = {
          mode: "pengajuan_ulang_teknisi_instalasi",
          data: this.form,
        };
        var data_fill = JSON.stringify(data);
        localStorage.setItem("data_fill_storage", data_fill);

        this.$router.push({
          name: "admin.user.staff.create",
        });
      }
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

          if (this.dataLocalStorage) {
            this.form = this.dataLocalStorage;
            this.form.status = "accept";
            if (
              this.dataLocalStorage.mode == "pengajuan_ulang_teknisi_instalasi"
            ) {
              this.edit_teknisi = true;
            }
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
          var i = 0;
          var res = response.data.data_teknisi;
          this.teknisi_afterEdit = res;
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
          
            // console.log( this.form.teknisi_instalasi,this.teknisi_afterEdit);
          if (this.user_company.type == "osp") {
            this.form.teknisi_instalasi = [];
            // for (a; a < jumlah_teknisi_terpilih; a++) {
            //   this.form.teknisi_instalasi.push(res[a].user_id);
            // }
            this.teknisi_afterEdit.forEach((value)=>{
                this.form.teknisi_instalasi.push(value.user_id);
            });
            
          }
        });
    },
    submitTeknisi() {
      this.$refs["form"].validate((valid) => {
        if (valid) {
          Swal.fire({
            title: this.trans("core.messages.confirmation-form"),
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: this.trans("core.button.confirm"),
            cancelButtonText: this.trans("core.button.cancel"),
          }).then((result) => {
            if (result.value) {
              // this.form.teknisi_instalasi =  this.teknisi_load;
              this.loading_teknisi = true;
              var data = {
                teknisi: this.form.teknisi_instalasi,
                pelanggan_id: this.$route.query.pelanggan,
              };
              axios
                .post(route("api.pelanggan.form.teknisi.instalasi.save"), data)
                .then((res) => {
                  localStorage.clear();
                  this.dataLocalStorage = null;
                  this.Toast({
                    icon: "success",
                    title: this.trans("core.messages.data disimpan"),
                  });
                  this.loading_teknisi = false;
                  this.edit_teknisi = false;
                  this.fecthDataTeknisi();
                });
            }
          });
        }
      });
    },
  },
  watch: {
    teknisi_afterEdit: function() {
      let data = {
        slot_instalasi: this.slot_instalasi,
        tanggal_instalasi: this.tanggal_instalasi,
        teknisi: this.teknisi_afterEdit,
      };
      this.$store.commit("setJadwalInstalasi", data);
    },
  },
  mounted() {
    this.fecthDataTeknisi();
    this.fetchPengajuanInstalasi();
  },
};
</script>
