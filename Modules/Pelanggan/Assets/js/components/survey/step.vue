<template>
  <div>
    <PerubahanHarga
      @ajukanFalse="ajukanFalse"
      :statusStep="surveyForm.status"
    />
    <CardDetailPelangganInstalasi
      v-bind:ajukanUlangFromStep="ajukanUlang"
      :change_status="change_status"
      :user_role="user_roles.name"
      :loadFetch="loadFetch"
      :statusStep="surveyForm.status"
    />

    <JadwalSurvey
      v-if="pelanggan_id != null"
      :status_pelanggan="status_pelanggan"
      :pelanggan_id="pelanggan_id"
      :survey_id="surveyForm.survey_id"
      :list_teknisi="list_teknisi"
      :statusSurvey="surveyForm.status"
      @rescheduleStatus="rescheduleStatus($event)"
      @getJadwalSurvey="getJadwalSurvey($event)"
    />

    <!-- Teknisi yang Bertugas -->
    <!-- <div
      v-if="
        surveyForm.status != 'cancel' &&
          surveyForm.status != 'finish' &&
          user_roles.name == 'Admin ISP'
      "
    >
      <TeknisiInfo />
    </div> -->
    <!-- End Teknisi yang Bertugas -->
    <!-- Hasil Survey -->
    <div v-if="surveyForm.status != 'cancel'">
      <div
        v-if="user_roles.name == 'Admin ISP' && surveyForm.status == 'finish'"
      >
        <!-- Hasil Survey Info -->
        <HasilSurveyInfo
          v-on:changeSurveyFinish="changeSurveyFinish($event)"
          :tglSurvey="tglSurvey"
          :jalur_kabel="surveyForm.foto_jalur_kabel"
          :signal_kabel="surveyForm.foto_signal_kabel"
        />
        <!-- End Hasil Survey Info -->
      </div>
      <!-- End Teknisi yang Bertugas -->
      <!-- Hasil Survey -->
      <div v-if="surveyForm.status != 'cancel'">
        <div
          v-if="user_roles.name == 'Admin OSP' && statusEditFormSurvey == false"
        >
          <!-- Hasil Survey Info -->
          <HasilSurveyInfo
            v-on:changeSurveyFinish="changeSurveyFinish($event)"
            :tglSurvey="tglSurvey"
            :jalur_kabel="surveyForm.foto_jalur_kabel"
            :signal_kabel="surveyForm.foto_signal_kabel"
            :status_pelanggans="status_pelanggan"
          />
          <!-- End Hasil Survey Info -->
        </div>
        <div v-else>
          <!-- Hasil Survey Form -->
          <div v-if="!reschedule">
            <HasilSurveyForm
              v-on:changeSurveyFinish="changeSurveyFinish($event)"
              v-on:previewBeforeSubmit="previewBeforeSubmit"
              :tglSurvey="tglSurvey"
              :jalur_kabel="surveyForm.foto_jalur_kabel"
              :signal_kabel="surveyForm.foto_signal_kabel"
              :display_button_batal="display_button_batal"
            />
          </div>
          <!-- End Hasil Survey Form -->
        </div>
      </div>
      <!-- End Hasil Survey -->
      <!-- Card Pengajuan Instalasi -->
      <div v-if="status_pelanggan == 'survey' && surveyForm.status == 'finish'">
        <JadwalInstalasi
          v-if="pelanggan_id != null"
          :pelanggan_id="pelanggan_id"
        />
      </div>
    </div>
    <!-- End Hasil Survey -->
    <!-- Card Pengajuan Instalasi -->
    <!-- End Card Pengajuan Instalasi -->
  </div>
</template>

<script>
import step_survey from "../../mixins/step_survey";
import HasilSurveyInfo from "./isp/HasilSurveyInfo.vue";
import HasilSurveyForm from "./osp/HasilSurveyForm.vue";
import TeknisiInfo from "./isp/TeknisiInfo.vue";

import JadwalSurvey from "../pengajuan/JadwalSurvey";

export default {
  mixins: [step_survey],
  components: {
    JadwalSurvey: JadwalSurvey,
    HasilSurveyInfo: HasilSurveyInfo,
    HasilSurveyForm: HasilSurveyForm,
    TeknisiInfo: TeknisiInfo,
  },
  data() {
    return {
      statusEditFormSurvey: false,
      tglSurvey: "",
      reschedule: false,
      display_button_batal: false,
    };
  },
  methods: {
    getJadwalSurvey(value) {
      this.tglSurvey = value;
    },
    changeSurveyFinish(status) {
      this.statusEditFormSurvey = status;
      this.display_button_batal = status;
    },
    previewBeforeSubmit() {
      $(".modal-preview").modal("show");
    },
    rescheduleStatus(value) {
      this.reschedule = value;
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
.span-teknisi {
  display: inline-block;
  font-size: 14px;
  border: 1px solid #b0b0ff;
  border-radius: 5px;
  padding: 0px 5px;
  margin-right: 2px;
}
.span_noc {
  display: inline-block;
  font-size: 14px;
  border: 1px solid #b0b0ff;
  border-radius: 5px;
  padding: 0px 5px;
  margin-right: 2px;
}
</style>
