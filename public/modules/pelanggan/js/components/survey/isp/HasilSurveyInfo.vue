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
                <h5 class="text-white">{{trans('surveys.title.hasil')}}</h5>
              </div>
              <div class="card-body" >
                
                  <div class="row">
                    <div class="col-md-12 pull-left" v-if="user_roles.name == 'Admin OSP'">
                      <el-button class="pull-right" size="small" type="primary" @click="editHasilSurvey" icon="el-icon-edit"></el-button>
                    </div>
                    <div class="col-md-12">
                        <table class="table" style="width: 100%;">
                            <tr class="py-1">
                              <th style="width: 250px;">{{trans('surveys.form.teknisi')}}</th>
                              <td style="width: 10px;">:</td>
                              <td>
                                  <div class="span-teknisi" v-for="(list, index) in noc" :key="index">
                                    {{list.nama_teknisi}}
                                  </div>
                              </td>
                            </tr>
                            <tr class="py-5">
                              <th style="width: 250px;">{{trans('surveys.form.status')}}</th>
                              <td style="width: 10px;">:</td>
                              <td>
                                 <div v-if="surveyForm.status != null" :class="'span-status '+(surveyForm.status == 'finish'?'bg-success':'bg-info') ">
                                    {{surveyForm.status}}
                                </div>
                              </td>
                            </tr>
                            <!-- <tr class="py-5">
                              <th style="width: 250px;">{{trans('surveys.form.tinggi')}}</th>
                              <td style="width: 10px;">:</td>
                              <td>
                                  {{surveyForm.tinggi_bangunan}} {{surveyForm.satuan_tinggi}}
                              </td>
                            </tr> -->
                            <tr class="py-1">
                              <th style="width: 250px;">{{trans('surveys.form.signal')}}</th>
                              <td style="width: 10px;">:</td>
                              <td>
                                <div v-for="(signal,index) in foto_signal_kabel_ready" :key="index" class="d-inline p-2">
                                  <a class="fancybox" v-bind:href="signal.url"  data-width="2048" data-height="1365" data-fancybox-type="image" v-bind:id="'signal.name'">
                                      <img :src="signal.url" alt="" style="height:150px; width:150px;">
                                  </a>
                                </div>
                                    <!-- <el-upload
                                      action="#"
                                      class="upload-demo"
                                      :on-preview="handleSignalPreview"
                                      :on-remove="handleRemoveSignal"
                                      :before-remove="beforeRemove"
                                      :before-upload="beforeUploadSignal"
                                      disabled
                                      :file-list="foto_signal_kabel_ready"
                                      list-type="picture-card">
                                    </el-upload> -->
                              </td>
                            </tr>
                            <tr class="py-1">
                              <th style="width: 250px;">{{trans('surveys.form.kabel')}}</th>
                              <td style="width: 10px;">:</td>
                              <td>
                                <div v-for="(jalur,index) in foto_jalur_kabel_ready" :key="index" class="d-inline p-2">
                                  <a class="fancybox" v-bind:href="jalur.url"  data-width="2048" data-height="1365" data-fancybox-type="image" v-bind:id="'jalur.name'">
                                      <img :src="jalur.url" alt="bukti transfer" style="height:150px; width:150px;">
                                  </a>
                                </div>
                                  <!-- <el-upload
                                    class="upload-demo"
                                    action="#"
                                    :on-preview="handleSignalPreview"
                                    :before-upload="beforeUploadKabel"
                                    :on-remove="handleRemoveKabel"
                                    :before-remove="beforeRemove"
                                    disabled
                                    :file-list="foto_jalur_kabel_ready"
                                    list-type="picture-card">
                                  </el-upload>
  
                                  <el-dialog :visible.sync="dialogVisible">
                                    <img width="100%" :src="dialogImageUrl" alt="">
                                  </el-dialog> -->
                              </td>
                            </tr>
                        </table>
                        <br>
                        
                        <div class="row" v-if="surveyForm.perangkat_tambahan.length > 0">
                          <div class="col-md-6">
                            <label>{{trans('surveys.form.perangkat')}}</label>
                          </div>
                          <div class="col-md-12">
                            <table class="table">
                              <thead>
                                <td>{{trans('surveys.form.namaPerangkat')}}</td>
                                <td>{{trans('surveys.form.qty')}}</td>
                                <td>{{trans('surveys.form.jenis_perangkat')}}</td>
                              </thead>
                              <tr 
                                  v-for="(p1, index) in surveyForm.perangkat_tambahan" :key="index">
                                  <td>{{p1.nama_perangkat}}</td>
                                  <td>{{p1.qty}}</td>
                                  <td>{{p1.jenis_perangkat}}</td>
                              </tr>
                            </table>
                          </div>
                        </div>

                    </div>
                  </div>
  
              </div>
            </div>
          </div>
        </div>
       <!-- End Hasil Survey Info-->
	</div>
</template>

<script>
    import step_survey from '../../../mixins/step_survey';

    export default {
      mixins: [step_survey],
      data() {
          return {}
      },
      methods:{
          editHasilSurvey(){
            this.$emit('changeSurveyFinish',true);
            // return this.surveyFinish = false;
          },
      }
    }
</script>
<style type="text/css">
  .el-form-item__label{
    margin-bottom: 0px !important;
  }
  .el-date-editor.el-input, .el-date-editor.el-input__inner {
      width: 100%;
  }
  .span-teknisi{
    display: inline-block;
    padding: 0px 10px;
    background: var(--primary);
    color: white;
    border-radius: 5px;
    margin-right: 2px;
  }
  .span-status{
    display: inline-block;
    padding: 0px 10px;
    color: white;
    border-radius: 5px;
    margin-right: 2px;
  }
</style>