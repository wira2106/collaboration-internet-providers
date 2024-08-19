<template>
    <div>
      <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">  {{ trans('pengajuans.detail resource') }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/backend">{{ trans('core.breadcrumb.home') }}</a></li>
                <li class="breadcrumb-item"><router-link :to="{name:'admin.wilayah.pengajuan.index'}"> {{ trans('pengajuans.title.pengajuans') }} </router-link> </li>
                <li class="breadcrumb-item">
                  {{ trans('pengajuans.title.pengajuans detail') }}
                </li>
                </ol>
            </div>                
        </div>
        <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <el-steps :active="activeSteps" finish-status="success" align-center>
                    <el-step :title="trans('pengajuans.step.pengajuan')"></el-step>
                    <el-step :title="trans('pengajuans.step.konfirmasiOSP')"></el-step>
                    <el-step :title="trans('pengajuans.step.konfirmasiISP')"></el-step>
                  </el-steps>
                </div>
            </div>
          </div>
        </div>

        <!-- Data Step  -->
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline-info">
                        <div class="card-header">
                            <h5 class="card-title text-white">{{ header }}</h5>
                        </div>
                        <div class="card-body">
                          <div class="row">
                               <div class="col-md-12 mt-3 text-center">
                                <h3 style="display: inline-block;">
                                  {{trans('pengajuans.detail resource')}}
                                </h3>
                              </div>
                              <div class="col-md-4">
                                <table  style="font-size: 14px;" class="table">
                                  <tr class="text-center">
                                    <td colspan="3">
                                      <span><b>{{trans('pengajuans.modal_pengajuan.wilayah')}}</b></span>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td width="150">{{trans('pengajuans.modal_pengajuan.wilayah')}}</td>
                                    <td width="10">:</td>
                                    <td><b>{{this.formDetail.nama_wilayah}}</b></td>
                                  </tr>
                                  <tr>
                                    <td width="150">{{trans('pengajuans.modal_pengajuan.company')}}</td>
                                    <td width="10">:</td>
                                    <td>{{this.formDetail.nama_osp}}</td>
                                  </tr>
                                </table>
                              </div>

                              <div class="col-md-4">
                                <table  style="font-size: 14px;" class="table">
                                  <tr>
                                    <td colspan="3" class="text-center">
                                      <span><b>{{trans('pengajuans.modal_pengajuan.diajukan oleh')}}</b></span>
                                    </td>
                                  </tr>
                                  <tr class="pb-3">
                                    <td width="150">{{trans('pengajuans.modal_pengajuan.tanggal')}}</td>
                                    <td width="10">:</td>
                                    <td>{{this.formDetail.created_at}}</td>
                                  </tr>
                                  <tr>
                                    <td width="150">{{trans('pengajuans.modal_pengajuan.company')}}</td>
                                    <td width="10">:</td>
                                    <td>{{this.formDetail.nama_isp}}</td>
                                  </tr>
                                </table>
                              </div>
                              
                              <div class="col-md-4">
                                <table  style="font-size: 14px;" class="table">
                                    <tr>
                                      <td colspan="3" class="text-center">
                                        <span><b>{{trans('pengajuans.modal_pengajuan.alasan')}}</b></span>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3" class="text-center">{{this.formDetail.alasan}}</td>
                                    </tr>
                                </table>
                              </div>
                            </table>
                          </div>
                          <div class="row" v-if="user_role == 'Admin OSP'">
                            <div class="col-md-12 pt-3 text-center">
                              <PengajuanPaket v-if="formDetail.osp_id" :pengajuan="formDetail"></PengajuanPaket>
                            </div>
                          </div>
                          <div class="row" v-if="user_role == 'Admin OSP'">
                            <div class="col-md-12 pt-5 text-center">
                              <h3 style="display: inline-block;">
                                
                              </h3>
                            </div>
                          </div>
                          <el-form
                            ref="formDetail"
                            :model="formDetail"
                            :label-position="'top'">
                          <div class="row" v-if="user_role == 'Admin OSP'">
                            <div class="col-md-4">
                                <el-form-item :label="trans('pengajuans.form.toleransi')">
                                    <el-input v-model="formDetail.toleransi" size="small">
                                      <template slot="append">bulan</template>
                                    </el-input>
                                  </el-form-item>
                            </div>
                            <div class="col-md-3">
                                <el-form-item :label="trans('pengajuans.form.awal kontrak')">
                                    <el-date-picker
                                        type="date"
                                        v-model="formDetail.start_date" 
                                        size="small"
                                        ></el-date-picker>
                                  </el-form-item>
                            </div>
                            <div class="col-md-5">
                                  <el-form-item :label="trans('pengajuans.form.lama kontrak')">
                                    <el-input v-model="formDetail.lama_kontrak" size="small">
                                      <template slot="append">bulan</template>
                                    </el-input>
                                  </el-form-item>
                            </div>
                          </div>
                          <div class="row" v-if="user_role == 'Admin OSP'">
                              <div class="col-md-12">
                                <el-form-item
                                  :label="trans('pengajuans.form.file')">
                                  <el-upload
                                    class="upload-demo"
                                    drag
                                    action="getRoute"
                                     name="file[]"
                                    :on-success="handleSuccess"
                                    :on-preview="handlePreview"
                                    :on-remove="handleRemove"
                                    :on-exceed="handleExceed"
                                    :file-list="fileList"
                                    multiple>
                                    <i class="el-icon-upload"></i>
                                    <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                                    <div class="el-upload__tip" slot="tip">documents files with a size less than 500kb</div>
                                  </el-upload>
                                </el-form-item>
                              </div>
                              <div class="col-md-12">
                                <el-form-item>
                                      <el-button v-if="user_role != '' && user_role != 'Admin ISP'" type="primary" @click="onSubmit('formDetail')" :loading="loading">
                                          {{ trans('core.save') }}
                                      </el-button>
                                      <el-button @click="onCancel()" :loading="loading">{{ trans('core.back') }}
                                      </el-button>
                                </el-form-item>
                              </div>
                          </div>
                          </el-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Data Step-->
            


      </div>
    </div> 
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    import Form from 'form-backend-validation';
    import ShortcutHelper from '../../../../../Core/Assets/js/mixins/ShortcutHelper';
    import Toast from '../../../../../Core/Assets/js/mixins/Toast';
    import TranslationHelper from '../../../../../Core/Assets/js/mixins/TranslationHelper';
    import UserRolesPermission from '../../../../../Core/Assets/js/mixins/UserRolesPermission';
    import PengajuanPaket from './PengajuanPaket';

    export default {
        mixins: [ShortcutHelper,TranslationHelper,Toast,UserRolesPermission],
        components: {
            'PengajuanPaket' : PengajuanPaket,
        },
        data() {
            return {
                id:null,
                formDetail:{
                  status: 'request',
                  toleransi: '',
                  start_date: '',
                  lama_kontrak: '',
                  file_kontrak:[],
                  alasan: '',
                },
                fileList: [],
                user_role: '',
                header:this.trans('pengajuans.step.pengajuan'),
                loading:false,
                activeSteps:1,
                statusNow : 'request',
                form: new Form()
            };
        },
        methods: {
            fetchData(intoPaket=0){
              this.loading = true;
              this.required_pass = false;
              let routeUri = route('api.wilayah.pengajuan.detail', { id: this.id });
              axios.post(routeUri)
                  .then((response) => {
                      if(response.data != null){                              
                        let data = response.data.data;                              
                        this.formDetail = data;
                        this.statusNow  = data.status;
                      }
                    this.loading = false;
                    
                    // menuju ke tab paket
                    if (intoPaket == 1) {
                      Swal.fire(this.trans('pengajuans.messages.empty paket_isp'),"","warning");
                      this.activeTab = "paket_isp";
                    }
                  })
                  .catch(error => {
                    if (error.response.status === 403) {

                      this.$notify.error({
                          title: this.trans('core.unauthorized'),
                          message: this.trans('core.unauthorized-access'),
                      });
                      window.location = route('dashboard.index');
                  }
                      this.loading = false;
                  });
            },
            conditionSelectStatus(status){
              if(this.user_role == 'Super Admin'){
                return true;
              }else if (this.user_role == 'Admin OSP') {
                if (status != 'accepted') {
                  return true;
                }
              }
              return false;
            },
            conditionInfoStatus(status){
              if (this.user_role == 'Admin ISP') {
                return true;
              }else if(this.user_role == 'Admin OSP' && status == 'accepted'){
                return true;
              }
              return false;
            },
            showPickAccepted(status){
              if (status != 'accepted' && this.user_role == 'Admin OSP') {
                return false;
              }
              return true;
            },
            requiredAlasan(rule, value, callback){                
                if(this.formDetail.status == 'rejected' && this.formDetail.alasan == null){                  
                  return callback(new Error(this.trans('core.form.required')));
                } 
                callback()
            },
            onSubmit(formName){
              this.formDetail.file_kontrak = this.fileList;
              console.log(this.formDetail);
              this.$refs[formName].validate(valid => {
                if(valid) {                  
                   Swal.fire({
                    title: this.trans('core.messages.confirmation-form'),
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: this.trans('core.button.confirm'),
                    cancelButtonText: this.trans('core.button.cancel')
                  }).then((result) => {
                    if (result.value) {
                        this.loading = true;
                        this.form = new Form(this.formDetail);
                        this.form.post(route('api.wilayah.pengajuan.status'))
                        .then((response) => {
                          this.loading = false;
                          this.Toast({
                            icon: "success",
                            title: response.message
                          });

                          // jika jumlah paketnya ada 0, bawa dia untuk mengisi paket berlangganan
                          if (response.jumlah_paket == 0) {
                            this.fetchData(1);
                          }else{
                            this.fetchData();
                          }
                        }).catch((error) => {
                          this.loading = false;
                          this.Toast({
                            icon: "error",
                            title: "error"
                          });
                          this.fetchData();
                        });                        
                    }
                  });
                } else {
                    return false;
                }
              });
            },
             resetAlasan(){
              if (this.formDetail.status != 'rejected') {
                this.formDetail.alasan = null;
              }
            },
            onCancel(){
              this.$router.push({name: 'admin.wilayah.pengajuan.index'});
            },

            handleRemove(file, fileList) {
              console.log(file);
              return false;
                let vm = this
                axios.delete(route('api.wilayah.pengajuan.file.remove', { id: file.uid }))
                    .then(function () {
                        let index = _.findIndex(vm.fileList, ['uid', file.uid])
                        vm.$delete(vm.fileList, index)
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            handleSuccess(response, file, fileList) {
                
                var vm = this
                _.map(response, function (data) {
                    file['uid'] = data
                })
                vm.fileList = fileList;
                console.log(file.raw);
            },
            handleExceed(files, fileList) {
                this.$message.warning(`The limit is 3, you selected ${files.length} files this time, add up to ${files.length + fileList.length} totally`);
            },
            beforeRemove(file, fileList) {
              return this.$confirm(`do you really want to delete ${ file.name }？`);
            },
            handlePreview(files) {
                console.log(files)
            },
            getRoute(file){
              console.log(file);
              return route('api.wilayah.pengajuan.file.upload');
            //   if (file == '') {
            //   }else{
            //     return false;
            //     return route('api.wilayah.pengajuan.file.remove', { id: file.uid });
            //   }
            }

        },
        mounted() {
          this.getRolesPermission();
          this.id = this.$route.params.id;
          this.fetchData();          
        },
    };
</script>
