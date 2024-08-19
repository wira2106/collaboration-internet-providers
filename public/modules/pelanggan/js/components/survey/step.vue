<template>
<<<<<<< HEAD
	<div>
    <JadwalSurvey v-if="pelanggan_id != null" :status_pelanggan="status_pelanggan" :pelanggan_id="pelanggan_id" v-on:tanggalSurvey="tanggalSurvey($event)"/>
    <PerubahanHarga @ajukanFalse="ajukanFalse" :statusStep="surveyForm.status"/>
    <CardDetailPelangganInstalasi v-bind:ajukanUlangFromStep="ajukanUlang" 
    :change_status="change_status" 
    :user_role="user_role" 
    :loadFetch="loadFetch"
    :statusStep="surveyForm.status"/>
    <el-form
        :label-position="'top'"
        ref="surveyForm"
        :model="surveyForm"
        :rules="rules">
      <!-- Teknisi yang Bertugas -->
      <div class="row" v-if="surveyForm.status != 'cancel'">
        <div class="col-md-12">
          <Card class="card card-outline-info" 
          v-if="(surveyForm.teknisi.length != 0 && !surveyFinish ) || 
          (user_role =='Admin OSP' && !surveyFinish )">
            <div class="card-header">
             <div class="card-actions">
               <a data-action="collapse"><i class="ti-minus text-white"></i></a>          
             </div>
             <h5 class="text-white">{{trans('surveys.form.teknisi')}}</h5>
           </div>
            <div class="card-body">
              <div v-if="user_role == 'Admin OSP'">
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
                         <el-option label="Tambah Teknisi" value="new"></el-option>
                       </el-select>
                 </el-form-item>
              </div>
              <div v-else>
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
   
               <!-- <el-form-item :label="trans('surveys.form.status')"  prop="status">
                     <el-select
                       v-model="surveyForm.status"
                       :placeholder="trans('surveys.placeholder.status')"
                       size="small">
                       <el-option value="active" label="active"></el-option>
                       <el-option value="finish" label="finish"></el-option>
                     </el-select>
               </el-form-item> -->
            </div>
          </Card>
        </div>
      </div>
       <!-- End Teknisi yang Bertugas -->
       <!-- Hasil Survey -->
       <div v-if="surveyNow">
         <div class="row"> 
           <div class="col-md-12">
             <Card class="card card-outline-info" v-if="surveyFinish">
               <div class="card-header">
                 <div class="card-actions">
                   <a data-action="collapse"><i class="ti-minus text-white"></i></a>          
                 </div>
                  <h5 class="text-white">{{trans('surveys.title.hasil')}}</h5>
               </div>
               <div class="card-body" v-loading.body="dataSurveyLoading">
                 
                    <div class="row">
                      <div class="col-md-12 pull-left" v-if="user_role == 'Admin OSP' && status_pelanggan == 'survey'">
                        <el-button class="pull-right" size="small" type="primary" @click="editHasilSurvey" icon="el-icon-edit"></el-button>
                      </div>
                     <div class="col-md-12">
                         <table class="table" style="width: 100%;">
                             <tr class="py-1">
                               <th style="width: 250px;">{{trans('surveys.form.teknisi')}}</th>
                               <td style="width: 10px;">:</td>
                               <td>
                                   <div v-for="(list, i) in noc" :key="i">
                                     {{list.nama_teknisi}}
                                   </div>
                               </td>
                             </tr>
                             <tr class="py-5">
                               <th style="width: 250px;">{{trans('surveys.form.status')}}</th>
                               <td style="width: 10px;">:</td>
                               <td>
                                   {{surveyForm.status}}
                               </td>
                             </tr>
                             <tr class="py-5">
                               <th style="width: 250px;">{{trans('surveys.form.tinggi')}}</th>
                               <td style="width: 10px;">:</td>
                               <td>
                                   {{surveyForm.tinggi_bangunan}} {{surveyForm.satuan_tinggi}}
                               </td>
                             </tr>
                             <tr class="py-1">
                               <th style="width: 250px;">{{trans('surveys.form.signal')}}</th>
                               <td style="width: 10px;">:</td>
                               <td>
                                     <el-upload
                                       action="#"
                                       class="upload-demo"
                                       :on-preview="handleSignalPreview"
                                       :on-remove="handleRemoveSignal"
                                       :before-remove="beforeRemove"
                                       :before-upload="beforeUploadSignal"
                                       disabled
                                       :file-list="foto_signal_kabel_ready"
                                       list-type="picture-card">
                                     </el-upload>
                               </td>
                             </tr>
                             <tr class="py-1">
                               <th style="width: 250px;">{{trans('surveys.form.kabel')}}</th>
                               <td style="width: 10px;">:</td>
                               <td>
                                   <el-upload
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
                                   </el-dialog>
                               </td>
                             </tr>
                         </table>
                     </div>
                   </div>
   
               </div>
             </Card>
           </div>
         </div>
         <div class="row">
           <div class="col-md-12">
             <Card class="card card-outline-info" v-if="user_role == 'Admin OSP' && !surveyFinish">
                  <div class="card-header">
                   <div class="card-actions">
                     <a data-action="collapse"><i class="ti-minus text-white"></i></a>          
                   </div>
                   <h5 class="text-white">{{trans('surveys.title.hasil')}}</h5>
                 </div>
                 <div class="card-body collapse show" v-loading.body="hasilSurveyLoading">
                     <div class="row mb-3">
                       <div class="col-md-12" v-if="user_role == 'Admin OSP' && status_pelanggan == 'survey' && surveyForm.status == 'finish'">
                         <el-button class="pull-right" size="small" type="primary" icon="el-icon-back" @click="cancelEditHasilSurvey">Back</el-button>
                       </div>
                     </div>
                     <div class="row">
                       <div class="col-md-12">
                         <el-form-item :label="trans('surveys.form.tinggi')" prop="tinggi_bangunan">
                           <el-input type="number" v-model="surveyForm.tinggi_bangunan" size="small">
                             <template slot="append">
                               <el-select v-model="surveyForm.satuan_tinggi" :placeholder="trans('surveys.placeholder.satuan')" style="width:105px;">
                                 <el-option value="meter" label="meter"></el-option>
                                 <el-option value="lantai" label="lantai"></el-option>
                               </el-select>
                             </template>
                           </el-input>
                         </el-form-item>
                       </div>
                     </div>
                     <div class="row">
                       <div class="col-md-6">
                           <el-form-item :label="trans('surveys.form.signal')" prop="foto_signal_kabel">
                               <el-upload
                                   
                                   action="#"
                                   class="upload-demo"
                                   :on-preview="handleSignalPreview"
                                   :on-remove="handleRemoveSignal"
                                   :before-upload="beforeUploadSignal"
                                   :file-list="foto_signal_kabel_ready"
                                   list-type="picture">
                                   <el-button size="small" type="primary">Click to upload</el-button>
                                   <div slot="tip" class="el-upload__tip">jpg/png files with a size less than 500kb</div>
                                 </el-upload>
                           </el-form-item>
                       </div>
                       <div class="col-md-6">
                           <el-form-item :label="trans('surveys.form.kabel')" prop="foto_jalur_kabel">
                               <el-upload
                                 class="upload-demo"
                                 action="#"
                                 :on-preview="handleSignalPreview"
                                 :before-upload="beforeUploadKabel"
                                 :on-remove="handleRemoveKabel"
                                 :file-list="foto_jalur_kabel_ready"
                                 list-type="picture">
                                 <el-button size="small" type="primary">Click to upload</el-button>
                                 <div slot="tip" class="el-upload__tip">jpg/png files with a size less than 1MB</div>
                               </el-upload>
   
                               <el-dialog :visible.sync="dialogVisible">
                                 <img width="100%" :src="dialogImageUrl" alt="">
                               </el-dialog>
                           </el-form-item>
                           
                         </div>
                     </div>
                 </div>
             </Card>
   
             <div class="card mt-n5" v-loading.body="hasilSurveyLoading" v-if="user_role!== 'Admin ISP' ||  surveyForm.barang_tambahan.length > 0" style="display:none;">
               <div class="card-body">
                 <div class="row p-0 mb-3">
                     <div class="col-6">
                       <label>{{trans('surveys.form.barang')}}</label>
                     </div>
                     <div class="col-md-6">
                        <el-button class="pull-right" size="mini" type="primary" icon="el-icon-plus" @click="addInput('barang')" v-if="!surveyFinish">
                          {{ trans("surveys.button.tambah barang") }}
                        </el-button>
                     </div>
                   </div>
                   <div v-if="user_role == 'Admin OSP' && !surveyFinish">
                     <div class="row"
                       v-for="(barang, index) in surveyForm.barang_tambahan"
                       :key="index">
                       <div class="col-md-3">
                         <el-form-item :label="trans('surveys.form.namaBarang')" 
                         :prop="'barang_tambahan.'+ index + '.barang_id'"
                         :rules="rules.barang_id">
                           <el-select
                             v-model="barang.barang_id"
                             size="small">
                             <el-option
                             v-for="item in fieldBarang"
                             :key="item.barang_id"
                             :label="item.nama_barang"
                             :value="item.barang_id"
                             ></el-option>
                           </el-select>
                         </el-form-item>
                       </div>
                       <div class="col-md-3">
                         <el-form-item :label="trans('surveys.form.harga per pcs')" 
                         :prop="'barang_tambahan.'+ index + '.harga_per_pcs'"
                         :rules="rules.harga_per_pcs"
                         >
                           <div class="p-1">
                             <InputCurrency  v-model="barang.harga_per_pcs" @input="handleChangeHargaBarang(index)"></InputCurrency>
                           </div>
                         </el-form-item>
                       </div>
                       <div class="col-md-3" prop="qty">
                         <el-form-item 
                         :label="trans('surveys.form.qty')"
                         :prop="'barang_tambahan.'+ index + '.qty'"
                         :rules="rules.qty"
                         >
                          <el-input type="number" size="small" v-model="barang.qty" @input="handleChangeHargaBarang(index)"></el-input>
                         </el-form-item>
                       </div>
                       <div class="col-md-2">
                         <el-form-item :label="trans('surveys.form.harga')" 
                           prop="harga">
                           <div class="p-1">
                             <InputCurrency  v-model="barang.harga" readonly></InputCurrency>
                           </div>
                         </el-form-item>
                       </div>
                       <div class="col-md-1 pt-4">
                         <el-button size="mini" type="danger" icon="el-icon-minus" @click="removeInput(barang,'barang')"></el-button>
                       </div>
                     </div>
                   </div>
                   <div v-else>
                     <div class="row">
                       <div class="col-md-12">
                         <table class="table">
                           <thead>
                                 <td>{{trans('surveys.form.namaBarang')}}</td>
                                 <td>{{trans('surveys.form.harga per pcs')}}</td>
                                 <td>{{trans('surveys.form.qty')}}</td>
                                 <td>{{trans('surveys.form.harga')}}</td>
                           </thead>
                           <tr v-for="(b1, index) in surveyForm.barang_tambahan" :key="index">
                               <td>{{b1.nama_barang}}</td>
                               <td>{{b1.harga_per_pcs}}</td>
                               <td>{{b1.qty}}</td>
                               <td>{{b1.harga}}</td>
                           </tr>
                         </table>
     
                       </div>
                     </div>
                   </div>
               </div>
             </div>
   
             <div class="card mt-n5" v-loading.body="hasilSurveyLoading" v-if="user_role!== 'Admin ISP' ||  surveyForm.perangkat_tambahan.length > 0">
               <div class="card-body">
                 <div class="row p-0 mb-3">
                   <div class="col-md-6">
                     <label>{{trans('surveys.form.perangkat')}}</label>
                   </div>
                   <div class="col-md-6">
                       <el-button class="pull-right" size="mini" type="primary" icon="el-icon-plus" @click="addInput('perangkat')" v-if="!surveyFinish">
                          {{ trans("surveys.button.tambah perangkat") }}
                       </el-button>
                   </div>
                 </div>
                 <div v-if="user_role == 'Admin OSP' && !surveyFinish">
                   <div class="row"
                     v-for="(perangkat, index) in surveyForm.perangkat_tambahan"
                     :key="index">
                     <div class="col-md-4">
                       <el-form-item 
                       :label="trans('surveys.form.namaPerangkat')" 
                       :prop="'perangkat_tambahan.' + index + '.perangkat_id'"
                       :rules="rules.perangkat_id">
                         <el-select
                           v-model="perangkat.perangkat_id"
                           size="small">
                           <el-option
                           v-for="item in fieldPerangkat"
                           :key="item.perangkat_id"
                           :label="item.nama_perangkat"
                           :value="item.perangkat_id"
                           ></el-option>
                         </el-select>
                       </el-form-item>
                     </div>
                     <div class="col-md-4" prop="qty">
                       <el-form-item 
                       :label="trans('surveys.form.qty')"
                       :prop="'perangkat_tambahan.' + index + '.qty'"
                       :rules="rules.qty">
                         <el-input type="number" size="small" v-model="perangkat.qty"></el-input>
                       </el-form-item>
                     </div>
                     <div class="col-md-3">
                       <el-form-item 
                       :label="trans('surveys.form.jenis_perangkat')" 
                       :prop="'perangkat_tambahan.' + index + '.jenis_perangkat'"
                       :rules="rules.jenis_perangkat">
                         <el-select
                           v-model="perangkat.jenis_perangkat"
                           size="small">
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
                       <el-button size="mini" type="danger" icon="el-icon-minus"  @click="removeInput(perangkat,'perangkat')"></el-button>
                     </div>
                   </div>
                 </div>
                 <div v-else>
                   <div class="row" >
                      <div class="col-md-12">
                        <table class="table">
                          <thead>
                            <td>{{trans('surveys.form.namaPerangkat')}}</td>
                            <td>{{trans('surveys.form.qty')}}</td>
                            <td>{{trans('surveys.form.jenis_perangkat')}}</td>
                           
                          </thead>
                          <tr v-for="(p1, index) in surveyForm.perangkat_tambahan" :key="index">
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
             
             <div v-if="user_role =='Admin OSP'">
               <div class="mt-n5"  v-loading.body="hasilSurveyLoading" v-if="surveyForm.status == 'active'">
                 <JadwalInstalasiInFormSurvey v-if="pelanggan_id != null" :pelanggan_id="pelanggan_id" ref="form"/>
               </div>  
             </div>
           </div>
         </div>
       </div>
       <!-- End Hasil Survey -->
       <!-- Button Hasil Survey -->
       <div class="row">
         <div class="col-md-12">
           <div v-if="user_role =='Admin OSP' && !surveyFinish">
             <div class="card" v-loading.body="hasilSurveyLoading" v-if="user_role =='Admin OSP'|| surveyForm.status !== 'finish'">
               <div class="card-footer">
                 <el-button type="primary" @click="onSubmit">
                   {{trans('surveys.button.save')}}
                  </el-button>
                </div>
              </div>
            </div>
          </div>
=======
  <div>
    <JadwalSurvey
      v-if="pelanggan_id != null"
      :status_pelanggan="status_pelanggan"
      :pelanggan_id="pelanggan_id"
    />
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

    <!-- Teknisi yang Bertugas -->
    <div
      v-if="
        surveyForm.status != 'cancel' &&
          surveyForm.status != 'finish' &&
          user_roles.name == 'Admin ISP'
      "
    >
      <TeknisiInfo />
    </div>
    <!-- End Teknisi yang Bertugas -->
    <!-- Hasil Survey -->
    <div v-if="surveyForm.status != 'cancel'">
      <div v-if="user_roles.name == 'Admin ISP'">
        <!-- Hasil Survey Info -->
        <HasilSurveyInfo v-on:changeSurveyFinish="changeSurveyFinish($event)" />
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
          />
          <!-- End Hasil Survey Info -->
>>>>>>> revisi-instalasi-adi-5Mei
        </div>
        <div v-else>
          <!-- Hasil Survey Form -->
          <HasilSurveyForm
            v-on:changeSurveyFinish="changeSurveyFinish($event)"
          />
          <!-- End Hasil Survey Form -->
        </div>
      </div>
      <!-- End Hasil Survey -->
      <!-- Card Pengajuan Instalasi -->
      <div
        v-if="
          status_pelanggan == 'survey' &&
            surveyForm.status !== 'cancel' &&
            surveyForm.status == 'finish'
        "
      >
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

export default {
  mixins: [step_survey],
  components: {
    HasilSurveyInfo: HasilSurveyInfo,
    HasilSurveyForm: HasilSurveyForm,
    TeknisiInfo: TeknisiInfo,
  },
  data() {
    return {
      statusEditFormSurvey: false,
    };
  },
  methods: {
    changeSurveyFinish(status) {
      this.statusEditFormSurvey = status;
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
