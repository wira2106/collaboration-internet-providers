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
      <!-- Teknisi Survey Form-->
      <div
        v-if="surveyForm.status != 'cancel' && user_roles.name == 'Admin OSP'"
      >
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline-info">
              <div class="card-header">
                <div class="card-actions">
                  <a data-action="collapse"
                    ><i class="ti-minus text-white"></i
                  ></a>
                </div>
                <h5 class="text-white">{{ trans("surveys.form.teknisi") }}</h5>
              </div>
              <div class="card-body">
                <el-form-item prop="teknisi">
                  <el-select
                    v-model="surveyForm.teknisi"
                    multiple
                    filterable
                    :placeholder="trans('surveys.placeholder.teknisi')"
                    size="small"
                    v-if="select_teknisi"
                  >
                    <el-option
                      v-for="(item, index) in list_teknisi"
                      :key="index"
                      :label="item.nama_teknisi"
                      :value="item.nama_teknisi"
                    >
                    </el-option>
                    <el-option label="Tambah Teknisi" value="new"></el-option>
                  </el-select>
                </el-form-item>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Teknisi Survey Form-->

      <!-- Hasil Survey Form-->
      <div class="row" v-if="surveyNow">
        <div class="col-md-12">
          <div
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
              <div class="row mb-3">
                <div
                  class="col-md-12"
                  v-if="
                    user_roles.name == 'Admin OSP' &&
                      surveyForm.status == 'finish'
                  "
                >
                  <el-tooltip
                    class="item"
                    effect="dark"
                    :content="trans('surveys.tooltip.batal edit')"
                    placement="top"
                  >
                    <el-button
                      class="pull-right"
                      size="small"
                      type="primary"
                      icon="el-icon-back"
                      @click="cancelEditHasilSurvey"
                      >{{ trans("core.back") }}</el-button
                    >
                  </el-tooltip>
                </div>
              </div>

              <!-- SIGNAL KABEL -->
              <div class="row">
                <div class="col-md-6">
                  <el-form-item :label="trans('surveys.form.signal')">
                    <el-upload
                      action="#"
                      class="upload-demo"
                      :on-preview="handleSignalPreview"
                      :on-remove="handleRemoveSignal"
                      :before-upload="beforeUploadSignal"
                      :file-list="foto_signal_kabel_ready"
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
                  <el-form-item :label="trans('surveys.form.kabel')">
                    <el-upload
                      class="upload-demo"
                      action="#"
                      :on-preview="handleSignalPreview"
                      :before-upload="beforeUploadKabel"
                      :on-remove="handleRemoveKabel"
                      :file-list="foto_jalur_kabel_ready"
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
            </div>
          </div>

          <!-- PERANGKAT -->
          <div
            class="card mt-n5"
            v-if="
              user_roles.name == 'Admin OSP' ||
                (surveyFinish && surveyForm.perangkat_tambahan.length > 0)
            "
          >
            <div class="card-body">
              <div class="row p-0 mb-3">
                <div class="col-md-6">
                  <label>{{ trans("surveys.form.perangkat") }}</label>
                </div>
                <div class="col-md-6">
                  <el-tooltip
                    class="item"
                    effect="dark"
                    :content="trans('surveys.tooltip.tambah input perangkat')"
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
                    :prop="'perangkat_tambahan.' + index + '.jenis_perangkat'"
                    :rules="rules.jenis_perangkat"
                  >
                    <el-select v-model="perangkat.jenis_perangkat" size="small">
                      <el-option
                        v-for="item in listJenisPerangkat"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      ></el-option>
                    </el-select>
                  </el-form-item>
                </div>
                <div class="col-md-1 pt-4">
                  <el-tooltip
                    class="item"
                    effect="dark"
                    :content="trans('surveys.tooltip.hapus input perangkat')"
                    placement="top"
                  >
                    <el-button
                      size="mini"
                      type="danger"
                      icon="el-icon-minus"
                      @click="removeInput(perangkat, 'perangkat')"
                    ></el-button>
                  </el-tooltip>
                </div>
              </div>
            </div>
          </div>

          <!-- KETERANGAN -->
          <div
            class="card mt-n5"
            v-if="
              user_roles.name == 'Admin OSP' ||
                (surveyFinish && surveyForm.perangkat_tambahan.length > 0)
            "
          >
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <el-form-item
                    :label="trans('surveys.form.keterangan')"
                    prop="keterangan"
                  >
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
              </div>
            </div>
          </div>

          <!-- PEMBERIAN JADWAL INSTALASI -->
          <div
            class="card mt-n5"
            v-if="
              user_roles.name == 'Admin OSP' && surveyForm.status != 'finish'
            "
          >
            <div class="card-body" v-loading.body="hasilSurveyLoading">
              <div class="row">
                <div class="col-md-12">
                  <label>{{ trans("pengajuanjadwal.instalasi.title") }}</label>
                  <table class="table table-pengajuan-survey">
                    <tbody>
                      <tr
                        v-for="(val, key) in surveyForm.jadwalInstalasi"
                        :class="val.status == 'active' ? 'active' : ''"
                        :key="key"
                      >
                        <td style="width: 30px">{{ key + 1 }}</td>
                        <td>
                          <!-- select tanggal survey -->

                          <div>
                            <el-form-item
                              class="mb-0"
                              :prop="
                                'jadwalInstalasi.' + key + '.tgl_instalasi'
                              "
                              :rules="rules.required_field"
                            >
                              <el-date-picker
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
                          </div>
                        </td>
                        <td>
                          <!-- select slot instalasi -->
                          <div>
                            <el-form-item
                              class="mb-0"
                              :prop="'jadwalInstalasi.' + key + '.slot_id'"
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
                        <td>
                          <a
                            href="#!"
                            @click="removeTanggal(key)"
                            v-if="key > 0"
                          >
                            <i class="fa fa-trash text-danger"></i>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col-md-12 pt-3 d-flex justify-content-center">
                  <el-button type="primary" @click="addJadwal()">
                    <i class="fa fa-plus"></i>
                    {{ trans("pengajuanjadwal.button.tambah jadwal") }}
                  </el-button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Hasil Survey Form-->
      <!-- Button Hasil Survey -->
      <div class="row">
        <div class="col-md-12">
          <div v-if="user_roles.name == 'Admin OSP'">
            <div
              class="card"
              v-if="
                user_roles.name == 'Admin OSP' || surveyForm.status !== 'finish'
              "
            >
              <div class="card-footer">
                <el-tooltip
                  class="item"
                  effect="dark"
                  :content="trans('surveys.tooltip.hasil')"
                  placement="top"
                >
                  <el-button type="primary" @click="onSubmit">
                    {{ trans("surveys.button.save") }}
                  </el-button>
                </el-tooltip>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Button Hasil Survey -->
    </el-form>
    <!-- Form Hasil Survey -->
  </div>
</template>

<script>
import step_survey from "../../../mixins/step_survey";
// import TeknisiForm from './TeknisiForm.vue';

export default {
  mixins: [step_survey],
  data() {
    return {};
  },
  methods: {
    cancelEditHasilSurvey() {
      this.dataSurveyLoading = true;
      this.$emit("changeSurveyFinish", false);
    },
  },
};
</script>
<style type="text/css">
.el-form-item__label {
  margin-bottom: 0px !important;
}
.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}
</style>
