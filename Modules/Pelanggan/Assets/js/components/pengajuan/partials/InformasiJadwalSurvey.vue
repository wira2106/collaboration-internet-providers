<template>
  <div>
    <!-- CARD INFORMASI JADWAL SURVEY YANG DIPILIH -->
    <Card
      class="card card-outline-info"
      v-if="form.status == 'accept'"
      v-loading.body="loadingPengajuan"
    >
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
          {{ trans("pengajuanjadwal.survey.tanggal dipilih") }}
        </h5>
      </div>
      <div class="card-body collapse show">
        <div class="row">
          <div class="col-md-12">
            <div class="d-flex justify-content-center">
              <table class="table" style="width: 100%;">
                <tr class="py-1">
                  <th style="width: 250px;">{{ trans("surveys.form.tgl") }}</th>
                  <td style="width: 10px;">:</td>
                  <td>
                    <span class="">{{ tanggal_survey }}</span>
                  </td>
                </tr>
                <tr class="py-1">
                  <th style="width: 250px;">
                    {{ trans("surveys.form.teknisi") }}
                  </th>
                  <td style="width: 10px;">:</td>
                  <td>
                    <div class="row" v-if="editTeknisi">
                      <div class="col-md-8">
                        <el-select
                          v-model="loadListTeknisi"
                          multiple
                          filterable
                          @change="changeTeknisiSelect"
                          :placeholder="trans('surveys.placeholder.teknisi')"
                          size="small"
                        >
                          <el-option
                            :label="
                              trans('pengajuanjadwal.button.tambah teknisi')
                            "
                            value="new"
                          ></el-option>
                          <el-option
                            v-for="(item, index) in list_teknisi"
                            :key="index"
                            :label="item.nama_teknisi"
                            :value="item.user_id"
                          >
                          </el-option>
                        </el-select>
                      </div>
                      <div class="col-md-4">
                        <div class="d-inline-block">
                          
                          <el-tooltip
                            class="item"
                            effect="dark"
                            :content="trans('surveys.tooltip.batal teknisi')"
                            placement="top"
                          >
                            <el-button
                              type="danger"
                              size="mini"
                              icon="el-icon-close"
                              style="margin-left:0;"
                              @click.prevent="changeTeknisi(false)"
                            >
                            </el-button>
                          </el-tooltip>
                          <el-tooltip
                            class="item"
                            effect="dark"
                            :content="trans('surveys.tooltip.simpan teknisi')"
                            placement="top"
                          >
                            <el-button
                              type="success"
                              size="mini"
                              icon="el-icon-check"
                              @click.prevent="submit()"
                            ></el-button>
                          </el-tooltip>
                        </div>
                      </div>
                    </div>
                    <div class="row" v-else>
                      <div class="col-md-12">
                        <div
                          class="span-noc mt-1"
                          v-for="(list, index) in teknisi"
                          :key="index"
                        >
                          {{ list.nama_teknisi }}
                        </div>
                        <a
                          href="#"
                          :hidden="isReschedule"
                          @click.prevent="changeTeknisi(true)"
                          v-if="user_company.type == 'osp'"
                        >
                          <i class="fa fa-edit"></i>
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr class="py-1">
                  <th style="width: 250px;">
                    {{ trans("surveys.form.keterangan") }}
                  </th>
                  <td style="width: 10px;">:</td>
                  <td>
                    <span>{{
                      keterangan !== "" && keterangan !== null
                        ? keterangan
                        : "-"
                    }}</span>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- card footer and button save -->
      <div
        class="card-footer tombol-ajukan-rechedule"
        v-if="user_company.type == 'osp' && buttonRechedule"
      >
        <!-- tombol ajukan reschedule -->
        <div
          class="col-md-12 pt-2 tombol-ajukan-rechedule"
          v-if="user_company.type == 'osp' && buttonRechedule"
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
      </div>
    </Card>
  </div>
</template>

<script type="text/javascript">
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [Toast, PermissionsHelper],
  props: [
    "response",
    "pelanggan_id",
    "teknisi",
    "list_teknisi",
    "loadTeknisi",
    "isReschedule",
    "changeEdit",
  ],
  data() {
    return {
      editTeknisi: false,
      loadingPengajuan: false,
      tanggal_survey: null,
      keterangan: null,
      form: {
        rekomendasiExist: false,
        jam_rekomendasi: "08:00",
        tgl_rekomendasi: "",
        keterangan: "",
        status: "",
        list: [],
      },
      history: [],
      teknisi_instalasi: Object.assign(this.loadTeknisi),
      loadListTeknisi: [],
      buttonRechedule: true,

      rules: {
        required_field: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
        ],
      },
    };
  },
  watch: {
    isReschedule: function(value) {
      if (value == true) {
        this.editTeknisi = false;
      }
    },
    changeEdit: function(value) {
      // balik ke info teknisi
      this.loadingPengajuan = true;
      if (value == true) {
        this.editTeknisi = false;

        this.$emit("setShangeEdit", false);
      }
      setTimeout(() => (this.loadingPengajuan = false), 3000);
    },
  },
  methods: {
    fetchPengajuanSurvey() {
      let data = this.response.data.data;
      this.buttonRechedule = this.response.buttonRechedule;
      this.history = data;

      if (data.length != 0) {
        this.form = data[data.length - 1];
        this.keterangan = this.form.keterangan;
        let vm = this;
        vm.loadTeknisi.forEach((element) => {
          vm.loadListTeknisi.push(element.user_id);
        });
        this.form.list.forEach((val) => {
          if (val.status == "active") {
            vm.tanggal_survey = val.full_name;
            vm.$emit("getTanggalSurvey", vm.tanggal_survey);
            return false;
          }
        });

        //check local data
        let dataLocal = localStorage.getItem("data_fill_storage"),
          pengajuan = null;
        if (dataLocal !== null && dataLocal !== "") {
          pengajuan = JSON.parse(dataLocal);
          if (
            pengajuan.mode == "edit_teknisi" &&
            pengajuan.data.pelanggan_id == this.pelanggan_id
          ) {
            this.form.loadListTeknisi = [];
            this.editTeknisi = pengajuan.data.editTeknisi;
            pengajuan.data.loadListTeknisi.forEach((teknisiIns) => {
              if (teknisiIns !== "new") {
                this.form.loadListTeknisi.push(teknisiIns);
              }
            });
            if (pengajuan.user_baru !== null && pengajuan.user_baru !== '' && pengajuan.user_baru !== undefined) {
              if(pengajuan.user_baru.role == 'teknisi'){
                console.log(this.form.loadListTeknisi,pengajuan.user_baru.user_id)
                  this.loadListTeknisi.push(pengajuan.user_baru.user_id);
              }
            }
          }
        }
      }
    },
    seeHistoryPengajuanSurvey() {
      $(".history-pengajuan-survey").modal();
    },
    recheduleJadwal() {
      this.$emit("rechedule");
    },
    changeTeknisi(value) {
      if (!this.isReschedule) {
        return (this.editTeknisi = value);
      }
    },
    changeTeknisiSelect(value) {
      if (value.includes("new")) {
        this.form.loadListTeknisi = this.loadListTeknisi;
        this.form.editTeknisi = this.editTeknisi;
        this.$emit("saveFormToLocal", {
          mode: "edit_teknisi",
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
        //     this.loadListTeknisi.splice(this.loadListTeknisi.length - 1, 1);
        //   } else {
        //     this.editTeknisi = true;
        //     this.loadListTeknisi.splice(this.loadListTeknisi.length - 1, 1);
        //   }
        // });
      }
    },
    submit() {
      this.loadingPengajuan = true;
      this.$emit("submitTeknisi", this.loadListTeknisi);
      this.loadingPengajuan = false;
    },
  },
  mounted() {
    this.fetchPengajuanSurvey();
  },
};
</script>
