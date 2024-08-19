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
           <div class="card card-outline-info">
              <div class="card-header">
                  <h5 class="card-title text-white">{{ trans('pengajuans.detail resource') }}</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <el-tabs v-model="activeTab">
                      <el-tab-pane v-bind:label="trans('pengajuans.tab.status')" name="status">
                        <div class="row">
                          <div class="col-md-12">
                           <el-form ref="formDetail"
                                :model="formDetail"
                                label-width="120px"
                                label-position="top"
                                v-loading.body="loading">
                              <div class="col-md-12 mt-3">
                                <button type="button" class="btn btn-outline-info btn-sm">
                                  {{trans('pengajuans.modal_pengajuan.wilayah')}}</button>
                              </div>
                              <div class="col-md-12">
                                <table style="width: 100%">
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
                              <div class="col-md-12"><hr></div>
                              <div class="col-md-12">
                                <button type="button" class="btn btn-outline-success btn-sm">{{trans('pengajuans.modal_pengajuan.diajukan oleh')}}</button>
                              </div>
                              <div class="col-md-12">
                                <table style="width: 100%">
                                  <tr>
                                    <td width="150">{{trans('pengajuans.modal_pengajuan.company')}}</td>
                                    <td width="10">:</td>
                                    <td>{{this.formDetail.nama_isp}}</td>
                                  </tr>
                                  <tr>
                                    <td width="150">{{trans('pengajuans.modal_pengajuan.tanggal')}}</td>
                                    <td width="10">:</td>
                                    <td>{{this.formDetail.created_at}}</td>
                                  </tr>
                                </table>
                              </div>
                              <div class="col-md-12"><hr></div>
                              <div class="col-md-12">
                                <button type="button" class="btn btn-outline-danger btn-sm">{{trans('pengajuans.modal_pengajuan.status pengajuan')}}</button>
                              </div>
                              <div class="col-md-12">
                                <table style="width: 100%">
                                  <tr>
                                    <td width="150">{{trans('pengajuans.modal_pengajuan.status')}}</td>
                                    <td width="10">:</td>
                                    <td>
                                      <div v-show="conditionSelectStatus(formDetail.status)">
                                         <el-select v-model="formDetail.status" @change="resetAlasan" placeholder="Select" filterable size="small">
                                            <el-option label="Request" value="request" data-id="1"></el-option>
                                            <el-option label="Confirmed" value="confirmed" data-id="2"></el-option>
                                            <el-option v-if="showPickAccepted(formDetail.status)" label="Accepted" value="accepted" data-id="3"></el-option>
                                            <el-option label="Rejected" value="rejected" data-id="4"></el-option>
                                         </el-select>
                                       </div>
                                       <div v-show="conditionInfoStatus(formDetail.status)">                              
                                          <a href="#!" class="btn btn-sm btn-warning" 
                                            v-if="formDetail.status == 'request'">
                                            {{formDetail.status}}
                                          </a>
                                          <a href="#!" class="btn btn-sm btn-info" 
                                            v-if="formDetail.status == 'confirmed'">
                                            {{formDetail.status}}
                                          </a>
                                          <a href="#!" class="btn btn-sm btn-success" 
                                            v-if="formDetail.status == 'accepted'">
                                            {{formDetail.status}}
                                          </a>
                                          <a href="#!" class="btn btn-sm btn-danger" 
                                            v-if="formDetail.status == 'rejected'">
                                            {{formDetail.status}}
                                          </a>
                                        </div>
                                    </td>
                                  </tr>                      
                                </table>
                              </div>
                              <div class="col-md-12" v-if="formDetail.status == 'rejected'">
                                <table style="width: 100%">
                                  <tr>
                                    <td valign="top" width="150">{{trans('pengajuans.modal_pengajuan.alasan')}}</td>
                                    <td valign="top" width="10">:</td>
                                    <td valign="top">
                                       <el-form-item prop="alasan"
                                            :rules="[{required: true,validator: requiredAlasan, trigger:['blur','change']}]">
                                          <el-input                                
                                            placeholder="Please input"
                                            v-model="formDetail.alasan">
                                          </el-input>
                                      </el-form-item>
                                    </td>
                                  </tr>                      
                                </table>
                              </div>
                              <div class="col-md-12">
                                <hr>
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
                            </el-form>
                          </div>
                        </div>
                      </el-tab-pane>
                      <el-tab-pane v-bind:label="trans('pengajuans.tab.paket isp')" name="paket_isp" 
                                    v-if="['confirmed','accepted'].indexOf(statusNow) != -1">
                         <PengajuanPaket v-if="formDetail.osp_id" :pengajuan="formDetail"></PengajuanPaket>
                      </el-tab-pane>
                    </el-tabs>
                  </div>
               </div>
              </div>
            </div>
          </div>
        </div>             
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
                  alasan: null,
                },
                user_role: '',
                loading:false,
                activeTab:'status',
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
            }
        },
        mounted() {
          this.getRolesPermission();
          this.id = this.$route.params.id;
          this.fetchData();          
        },
    };
</script>
