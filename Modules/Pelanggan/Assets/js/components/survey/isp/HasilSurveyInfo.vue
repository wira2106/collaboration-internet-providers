<template>
  <div>
    <!-- Hasil Survey Info-->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline-info" v-loading.body="dataSurveyLoading">
          <div class="card-header">
            <div class="card-actions">
              <a data-action="collapse"><i class="ti-minus text-white"></i></a>
            </div>
            <h5 class="text-white">{{ trans("surveys.title.hasil") }}</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <table class="table" style="width: 100%; font-size:14;">
                  <tr class="py-1" style="font-size:14;">
                    <th style="width: 250px;">
                      {{ trans("surveys.form.teknisi") }}
                    </th>
                    <td style="width: 10px;">:</td>
                    <td>
                      <div
                        class="span-noc"
                        v-for="(list, index) in noc"
                        :key="index"
                      >
                        {{ list.nama_teknisi }}
                      </div>
                    </td>
                  </tr>
                  <tr
                    class="py-5"
                    style="font-size:14;"
                    v-if="surveyForm.status == 'finish'"
                  >
                    <th style="width: 250px;">
                      {{ trans("surveys.form.status") }}
                    </th>
                    <td style="width: 10px;">:</td>
                    <td>
                      {{ surveyForm.status }}
                    </td>
                  </tr>
                  <tr class="py-5" style="font-size:14;">
                    <th style="width: 250px;">
                      {{ trans("surveys.form.tgl survey") }}
                    </th>
                    <td style="width: 10px;">:</td>
                    <td>
                      {{ tglSurvey }}
                      <el-tooltip
                        class="item"
                        effect="dark"
                        :content="trans('surveys.tooltip.history')"
                        placement="top"
                      >
                        <el-button
                          size="mini"
                          type="primary"
                          @click="showModalHistory"
                          icon="el-icon-date"
                        >
                        </el-button>
                      </el-tooltip>
                    </td>
                  </tr>
                </table>

                <div class="col-md-12">
                  <el-tabs v-model="activeName" @tab-click="handleClick">
                    <el-tab-pane
                      :label="trans('surveys.form.signal')"
                      name="signal"
                    >
                      <PreviewSignal :signals="signal_kabel" />
                    </el-tab-pane>
                    <el-tab-pane label="Jalur Kabel" name="jalur_kabel">
                      <PreviewJalurKabel :jalurKabels="jalur_kabel" />
                    </el-tab-pane>
                    <el-tab-pane label="Perangkat" name="perangkat">
                      <PreviewPerangkat
                        :perangkats="surveyForm.perangkat_tambahan"
                        :status_pelanggans="status_pelanggans"
                      />
                    </el-tab-pane>
                  </el-tabs>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer" v-if="surveyForm.status != 'finish' && show">
            <div class="text-center" v-if="user_roles.name == 'Admin OSP'">
              <el-button
                type="primary"
                @click="editHasilSurvey"
                icon="fa fa-edit"
              >
                Edit hasil survey</el-button
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Hasil Survey Info-->
  </div>
</template>

<script>
import step_survey from "../../../mixins/step_survey";
import PreviewJalurKabel from "./../osp/PreviewJaluKabel.vue";
import PreviewSignal from "./../osp/PreviewSignal.vue";
import PreviewPerangkat from "./../../instalasi/components/PreviewPerangkat.vue";

export default {
  mixins: [step_survey],
  components: { PreviewPerangkat, PreviewSignal, PreviewJalurKabel },
  props: ["tglSurvey", "jalur_kabel", "signal_kabel", "status_pelanggans"],
  data() {
    return {
      activeName: "signal",
      show: false,
    };
  },
  methods: {
    handleClick(tab, event) {},
    showModalHistory() {
      $(".history-pengajuan-survey").modal("show");
    },
    editHasilSurvey() {
      this.$emit("changeSurveyFinish", true);
      // return this.surveyFinish = false;
    },
  },
  mounted() {
    var data = Object.assign(this.surveyForm);
    if (
      data.foto_jalur_kabel.length != 0 ||
      data.foto_signal_kabel.length != 0 ||
      data.perangkat_tambahan.length != 0 ||
      data.keterangan != null || data.keterangan != ""
    ) {
      this.show = true;
    }
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
  padding: 0px 10px;
  background: var(--primary);
  color: white;
  border-radius: 5px;
  margin-right: 2px;
}
.span-noc {
  display: inline-block;
  font-size: 14px;
  border: 1px solid #b0b0ff;
  border-radius: 5px;
  padding: 0px 5px;
  margin-right: 2px;
}
.span-status {
  display: inline-block;
  padding: 0px 10px;
  color: white;
  border-radius: 5px;
  margin-right: 2px;
}
.font-custom-info {
  font-weight: 500;
  color: #212529;
  padding-left: 27px;
}
</style>
