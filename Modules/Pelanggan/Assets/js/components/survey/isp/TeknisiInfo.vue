<template>
	<div>
       <!-- Teknisi Survey Info-->
       <div class="row">
         <div class="col-md-12">
           <Card class="card card-outline-info" 
           v-if="(surveyForm.teknisi.length != 0 && !surveyFinish ) || 
           (user_roles.name =='Admin OSP' && !surveyFinish )">
             <div class="card-header">
              <div class="card-actions">
                <a data-action="collapse"><i class="ti-minus text-white"></i></a>          
              </div>
              <h5 class="text-white">{{trans('surveys.form.teknisi')}}</h5>
            </div>
             <div class="card-body">
                 <table class="table" v-if="surveyForm.status == 'active'">
                     <tr class="py-1">
                         <th style="width: 250px; font-size: 14px;">{{trans('surveys.form.teknisi')}}</th>
                         <td style="width: 10px;">:</td>
                         <td>
                           <div v-for="(data, i) in noc" :key="i">
                             <span style="font-size: 14px;font-weight: 700;display: block;">
                                 {{data.nama_teknisi}}
                             </span>
                             </div>
                         </td>
                       </tr>
                 </table>
             </div>
           </Card>
         </div>
       </div>
       <!-- End Teknisi Survey Info-->
	</div>
</template>

<script>
    import step_survey from '../../../mixins/step_survey';

    export default {
      mixins: [step_survey],
      data() {
          return {}
      },
    }
</script>
<style type="text/css">
  .el-form-item__label{
    margin-bottom: 0px !important;
  }
  .el-date-editor.el-input, .el-date-editor.el-input__inner {
      width: 100%;
  }
</style>