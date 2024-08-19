<template>
	<div>
       <!-- Teknisi Survey Info-->
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline-info" 
            v-if="(surveyForm.teknisi.length != 0 && !surveyFinish ) || 
            (user_role =='Admin OSP' && !surveyFinish )">
              <div class="card-header">
                <div class="card-actions">
                  <a data-action="collapse"><i class="ti-minus text-white"></i></a>          
                </div>
                <h5 class="text-white">{{trans('surveys.form.teknisi')}}</h5>
              </div>
              <div class="card-body">
                <el-form-item prop="teknisi">
                      <el-select
                        v-model="surveyForm.teknisi"
                        multiple
                        filterable
                        @change="changeTeknisi"
                        :placeholder="trans('surveys.placeholder.teknisi')"
                        size="small"
                        v-if="select_teknisi">
                        <el-option
                          v-for="item in list_noc"
                          :key="item.id"
                          :label="item.nama_teknisi"
                          :value="item.id">
                        </el-option>
                        <el-option :label="trans('surveys.title.add teknisi')" value="new"></el-option>
                      </el-select>
                </el-form-item>
              </div>
            </div>
            <h5 class="text-white">{{ trans("surveys.form.teknisi") }}</h5>
          </div>
          <div class="card-body">
            <el-form-item prop="teknisi">
              <el-select
                ref="teknisi"
                v-model="surveyForm.teknisi"
                multiple
                filterable
                @change="changeTeknisi"
                :placeholder="trans('surveys.placeholder.teknisi')"
                size="small"
                v-if="select_teknisi"
              >
                <el-option
                  v-for="item in list_noc"
                  :key="item.id"
                  :label="item.nama_teknisi"
                  :value="item.id"
                >
                </el-option>
                <el-option label="Tambah Teknisi" value="new"></el-option>
              </el-select>
            </el-form-item>
          </div>
        </div>
      </div>
    </div>
    <!-- End Teknisi Survey Info-->
  </div>
</template>

<script>
import step_survey from "../../../mixins/step_survey";

export default {
  mixins: [step_survey],
  data() {
    return {};
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
