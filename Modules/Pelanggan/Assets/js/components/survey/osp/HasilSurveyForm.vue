<template>
  <div>
    <!-- Form Hasil Survey -->
    <el-form
      :label-position="'top'"
      ref="surveyForm"
      :model="surveyForm"
      :rules="rules"
      v-loading.body="dataSurveyLoading"
    >
      <!-- Hasil Survey Form-->
      <div class="row" v-if="surveyNow">
        <div class="col-md-12">
          <Card
            class="card card-outline-info"
            v-if="user_roles.name == 'Admin OSP'"
          >
            <div class="card-header">
              <div class="card-actions">
                <a data-action="collapse"
                  ><i class="ti-minus text-white"></i
                ></a>
              </div>
              <h5 class="text-white">{{ trans("surveys.title.hasil") }}</h5>
            </div>
            <div class="card-body collapse show">
              <!-- BUTTON EDIT -->
              <div class="container">
                <div
                  class="row mb-3"
                  v-if="
                    user_roles.name == 'Admin OSP' &&
                      surveyForm.status == 'finish'
                  "
                ></div>

                <!-- SIGNAL KABEL -->
                <div class="row">
                  <div class="col-md-6">
                    <label class="label-cusutom">{{
                      trans("surveys.form.signal")
                    }}</label>
                    <el-form-item>
                      <el-upload
                        :auto-upload="false"
                        action="#"
                        class="upload-demo"
                        :on-preview="handleSignalPreview"
                        :on-change="successSignal"
                        :on-remove="handleRemoveSignal"
                        :before-upload="beforeUploadSignal"
                        :file-list="surveyForm.foto_signal_kabel"
                        list-type="picture-card"
                      >
                        <el-tooltip
                          class="item"
                          effect="dark"
                          :content="trans('surveys.tooltip.upload signal')"
                          placement="top"
                        >
                          <i class="el-icon-plus"></i>
                        </el-tooltip>
                      </el-upload>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <label class="label-cusutom">{{
                      trans("surveys.form.kabel")
                    }}</label>
                    <el-form-item>
                      <el-upload
                        :auto-upload="false"
                        class="upload-demo"
                        action="#"
                        :on-preview="handleSignalPreview"
                        :on-change="successKabel"
                        :before-upload="beforeUploadKabel"
                        :on-remove="handleRemoveKabel"
                        :file-list="surveyForm.foto_jalur_kabel"
                        list-type="picture-card"
                      >
                        <el-tooltip
                          class="item"
                          effect="dark"
                          :content="trans('surveys.tooltip.upload jalur')"
                          placement="top"
                        >
                          <i class="el-icon-plus"></i>
                        </el-tooltip>
                      </el-upload>

                      <el-dialog :visible.sync="dialogVisible">
                        <img width="100%" :src="dialogImageUrl" alt="" />
                      </el-dialog>
                    </el-form-item>
                  </div>
                </div>

                <!-- PERANGKAT -->
                <div
                  class="mt-1"
                  v-if="
                    user_roles.name == 'Admin OSP' ||
                      (surveyFinish && surveyForm.perangkat_tambahan.length > 0)
                  "
                >
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="label-cusutom">{{
                        trans("surveys.form.perangkat")
                      }}</label>
                    </div>
                    <div class="col-md-6">
                      <el-tooltip
                        class="item"
                        effect="dark"
                        :content="
                          trans('surveys.tooltip.tambah input perangkat')
                        "
                        placement="top"
                      >
                        <el-button
                          class="pull-right"
                          size="mini"
                          type="primary"
                          icon="el-icon-plus"
                          @click="addInput('perangkat')"
                        >
                          {{ trans("surveys.button.tambah perangkat") }}
                        </el-button>
                      </el-tooltip>
                    </div>
                  </div>
                  <div
                    class="row"
                    v-for="(perangkat, index) in surveyForm.perangkat_tambahan"
                    :key="index"
                  >
                    <div class="col-md-4">
                      <el-form-item
                        :label="trans('surveys.form.namaPerangkat')"
                        :prop="'perangkat_tambahan.' + index + '.perangkat_id'"
                        :rules="rules.perangkat_id"
                      >
                        <SelectPerangkat
                          v-model="perangkat.perangkat_id"
                          :list_perangkat="fieldPerangkat"
                          :update_list="
                            (result) => {
                              this.fieldPerangkat = result;
                            }
                          "
                        >
                        </SelectPerangkat>
                      </el-form-item>
                    </div>
                    <div class="col-md-4">
                      <el-form-item
                        :label="trans('surveys.form.qty')"
                        :prop="'perangkat_tambahan.' + index + '.qty'"
                        :rules="rules.qty"
                      >
                        <el-input
                          type="number"
                          size="small"
                          v-model="perangkat.qty"
                        ></el-input>
                      </el-form-item>
                    </div>
                    <div class="col-md-3">
                      <el-form-item
                        :label="trans('surveys.form.jenis_perangkat')"
                        :prop="
                          'perangkat_tambahan.' + index + '.jenis_perangkat'
                        "
                        :rules="rules.jenis_perangkat"
                      >
                        <el-select
                          v-model="perangkat.jenis_perangkat"
                          size="small"
                        >
                          <el-option
                            v-for="item in listJenisPerangkat"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                          ></el-option>
                        </el-select>
                      </el-form-item>
                    </div>
                    <div class="col-md-1 text-right">
                      <el-form-item label=" ">
                        <el-tooltip
                          class="item"
                          effect="dark"
                          :content="
                            trans('surveys.tooltip.hapus input perangkat')
                          "
                          placement="top"
                        >
                          <el-button
                            size="mini"
                            type="danger"
                            icon="el-icon-minus"
                            @click="removeInput(perangkat, 'perangkat')"
                          ></el-button>
                        </el-tooltip>
                      </el-form-item>
                    </div>
                  </div>
                </div>

                <!-- KETERANGAN -->

                <div
                  class="row"
                  v-if="
                    user_roles.name == 'Admin OSP' ||
                      (surveyFinish && surveyForm.perangkat_tambahan.length > 0)
                  "
                >
                  <div class="col-md-12">
                    <label class="label-cusutom">{{
                      trans("surveys.form.keterangan")
                    }}</label>
                    <el-form-item prop="keterangan">
                      <el-input
                        type="textarea"
                        :placeholder="
                          trans('surveys.placeholder.enter keterangan')
                        "
                        v-model="surveyForm.keterangan"
                      >
                      </el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-tooltip
                      placement="top"
                      content="tandai jika proses survey sudah selesai"
                    >
                      <el-checkbox
                        v-model="status_checkbox"
                        v-if="surveyForm.status != 'finish'"
                        >{{
                          trans("surveys.messages.survey selesai")
                        }}</el-checkbox
                      >
                    </el-tooltip>
                  </div>
                </div>
              </div>
            </div>
          </Card>
        </div>
      </div>
      <div class="row" v-if="surveyNow && status_checkbox">
        <div class="col-md-12">
          <Card
            class="card card-outline-info"
            v-if="user_roles.name == 'Admin OSP'"
          >
            <div class="card-header">
              <div class="card-actions">
                <a data-action="collapse"
                  ><i class="ti-minus text-white"></i
                ></a>
              </div>
              <h5 class="text-white">
                {{ trans("installations.title.pengajuan jadwal") }}
              </h5>
            </div>
            <div class="card-body">
              <div class="container" v-if="user_roles.name == 'Admin OSP'">
                <div class="row">
                  <div class="col-md-12">
                    <!-- TEKNISI INSTALASI -->

                    <label class="label-cusutom">{{
                      trans("pengajuanjadwal.instalasi.teknisi")
                    }}</label>

                    <el-form-item
                      prop="teknisiInstalasi"
                      :rules="rules.required_field"
                    >
                      <el-select
                        v-model="surveyForm.teknisiInstalasi"
                        multiple
                        filterable
                        :placeholder="trans('surveys.placeholder.teknisi')"
                        size="small"
                        @change="changeTeknisi"
                        v-if="select_teknisi"
                      >
                        <el-option
                          v-for="(item, index) in list_teknisi"
                          :key="index"
                          :label="item.nama_teknisi"
                          :value="item.user_id"
                        >
                        </el-option>
                        <el-option
                          label="Tambah Teknisi"
                          value="new"
                        ></el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label class="label-cusutom">{{
                      trans("pengajuanjadwal.instalasi.title")
                    }}</label>
                  </div>
                  <div class="col-md-12">
                    <table class="table table-pengajuan-survey">
                      <tbody>
                        <tr
                          v-for="(val, key) in surveyForm.jadwalInstalasi"
                          :class="val.status == 'active' ? 'active' : ''"
                          :key="key"
                        >
                          <td style="width: 30px">
                            <el-form-item>
                              {{ key + 1 }}
                            </el-form-item>
                          </td>
                          <td>
                            <!-- select tanggal survey -->

                            <el-form-item
                              :prop="
                                'jadwalInstalasi.' + key + '.tgl_instalasi'
                              "
                              :rules="rules.required_field"
                            >
                              <el-date-picker
                                @change="validasiTime(key)"
                                style="width: 100%"
                                v-model="val.tgl_instalasi"
                                type="date"
                                value-format="yyyy-MM-dd"
                                placeholder="Pick a day"
                                :picker-options="pickerOptions"
                                size="small"
                              >
                              </el-date-picker>
                            </el-form-item>
                          </td>
                          <td>
                            <!-- select slot instalasi -->

                            <el-form-item
                              :prop="'jadwalInstalasi.' + key + '.slot_id'"
                              :rules="rules.required_field"
                            >
                              <el-select
                                v-model="val.slot_id"
                                @change="validasiTime(key)"
                                size="small"
                                :placeholder="
                                  trans(
                                    'surveys.placeholder.pilih slot instalasi'
                                  )
                                "
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
                          </td>
                          <td>
                            <el-form-item>
                              <a
                                href="#!"
                                @click="removeTanggal(key)"
                                v-if="key > 0"
                              >
                                <i class="fa fa-trash text-danger"></i>
                              </a>
                            </el-form-item>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-12 mt-3 text-center">
                    <el-button type="primary" @click="addJadwal()">
                      <i class="fa fa-plus"></i>
                      {{ trans("pengajuanjadwal.button.tambah jadwal") }}
                    </el-button>
                  </div>
                </div>
              </div>
            </div>
          </Card>
        </div>
      </div>
      <!-- End Hasil Survey Form-->
      <!-- Button Hasil Survey -->
      <div class="row" v-if="surveyNow">
        <div class="col-md-12">
          <div class="card" v-if="user_roles.name == 'Admin OSP'">
            <div class="card-footer text-center pb-4">
              <el-button
                v-if="display_button_batal"
                type="danger"
                @click="cancelEditHasilSurvey"
                >{{ trans("surveys.button.batal") }}</el-button
              >
              <el-button type="primary" @click="onSubmit()">
                {{ trans("surveys.button.save") }}
              </el-button>
            </div>
          </div>
        </div>
      </div>
      <!-- End Button Hasil Survey -->
    </el-form>
    <!-- Form Hasil Survey -->

    <!-- Modal comfirm -->
    <div class="modal fade modal-preview" id="myModal">
      <div class="modal-dialog modal-lg">
        <div
          class="modal-content"
          v-loading.body="dataSurveyLoading"
          v-if="user_roles.name == 'Admin OSP'"
        >
          <!-- Hasil Survey Info -->
          <HasilSurveyPreview
            v-on:changeSurveyFinish="changeSurveyFinish($event)"
            :surveyForm="surveyForm"
            :list_teknisi="list_teknisi"
            :noc="noc"
            :status_checkbox="status_checkbox"
            :slot_instalasi="slot_instalasi"
            :fieldPerangkat="fieldPerangkat"
            :foto_jalur_kabel_ready="surveyForm.foto_jalur_kabel"
            :foto_signal_kabel_ready="surveyForm.foto_signal_kabel"
            :tglSurvey="tglSurvey"
            :status_pelanggans="status_pelanggan"
          />
          <!-- End Hasil Survey Info -->

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">
              {{ trans("pengajuanjadwal.button.close") }}
            </button>
            <el-button type="primary" @click="send" icon="el-icon-upload">
              {{ trans("surveys.button.save") }}
            </el-button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Modal comfirm -->
  </div>
</template>

<script>
import step_survey from "../../../mixins/step_survey";
import HasilSurveyPreview from "./HasilSurveyPreview.vue";
// import TeknisiForm from './TeknisiForm.vue';

export default {
  mixins: [step_survey],
  props: ["tglSurvey", "display_button_batal"],
  components: {
    HasilSurveyPreview: HasilSurveyPreview,
  },
  data() {
    return {
      array_validasi_jadwal: [],
      status_checkbox: false,
      status_edit_jadwal: false,
    };
  },
  methods: {
    validasiTime(key) {
      var array = this.array_validasi_jadwal;
      var data_time = this.surveyForm.jadwalInstalasi;
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
            this.surveyForm.jadwalInstalasi[key].tgl_instalasi = null;
            this.surveyForm.jadwalInstalasi[key].slot_id = "";
            this.surveyForm.jadwalInstalasi[key].status = "not_active";
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
    cancelEditHasilSurvey() {
      this.dataSurveyLoading = true;
      this.$emit("changeSurveyFinish", false);
    },
    preview() {
      this.$emit("previewBeforeSubmit");
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
            this.saveFormToLocal(this.surveyForm);
            this.$router.push({
              name: "admin.user.staff.create",
            });
            this.select_teknisi = true;
            this.surveyForm.teknisiInstalasi.splice(
              this.surveyForm.teknisiInstalasi.length - 1,
              1
            );
          } else {
            this.select_teknisi = true;
            this.surveyForm.teknisiInstalasi.splice(
              this.surveyForm.teknisiInstalasi.length - 1,
              1
            );
          }
        });
      }
    },
  },
};
</script>
<style type="text/css">
.label-cusutom {
  color: black;
  /* font-weight: 500; */
}
.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}
.table-pengajuan-survey .el-form-item {
  margin-bottom: 0 !important;
}
</style>
