<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">  {{ trans(`alats.${pageTitle}`) }} </h3> 
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/backend"> 
                          {{ trans('core.breadcrumb.home') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link :to="{name :'admin.peralatan.alat.index'}">
                            {{ trans(`alats.${pageTitle}`) }} 
                        </router-link> 
                    </li>
                </ol>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                  <el-form
                      :model="alatForm"
                      ref ="alatForm"
                      v-loading.body="loading">
                    <div class="card card-outline-info">
                        <div class="card-header text-white">
                            {{trans('alats.title.alats')}}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                  <el-form-item
                                      v-for="(nama_alat, index) in alatForm.nama_alat"
                                      :key="nama_alat.key"
                                      :prop="'nama_alat.'+index+'.value'"
                                      :rules="{required: true, 
                                               message:trans('core.validation.required',{field:trans('alats.form.name')}),
                                               trigger:'blur'}">
                                      <el-input :placeholder="trans('alats.placeholder.alats')" v-model="nama_alat.value">
                                          <el-button v-if="index == 0 && !$route.params.alat" type="primary" slot="append" @click="addInput">
                                              <i class="fa fa-plus"></i>
                                          </el-button>
                                          <el-button v-if="index > 0 && !$route.params.alat" type="danger" slot="append" @click="removeInput(nama_alat)">
                                              <i class="fa fa-minus"></i>
                                          </el-button>
                                      </el-input>
                                  </el-form-item>                                        
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <el-button type="primary" @click="onSubmit()">
                                      {{ trans('core.save') }}
                                    </el-button>
                                    <el-button type="danger" @click="$router.go(-1)">
                                      {{ trans('core.button.cancel')}}
                                    </el-button>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>
                        <!-- /.box -->
                    </div>                    
                  </el-form>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import _ from 'lodash';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';
    import TranslationHelper from '../../../../Core/Assets/js/mixins/TranslationHelper';
    import Toast from '../../../../Core/Assets/js/mixins/Toast';

    export default{
        
        mixins: [ShortcutHelper,TranslationHelper,Toast],
        data(){
           return {
               alatForm :{
                   nama_alat:[{
                       key:1,
                       value:''
                   }]
               },
               loading:false,
               form: new Form()
           }
        },
         props: {
            locales: { default: null },
            pageTitle: { default: null, String },
        },
        methods:{
            onSubmit(){
                this.form = new Form(this.alatForm);
                this.$refs['alatForm'].validate((valid)=>{                    
                    if(valid){
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
                            this.form.post(this.getRoute()).then((response)=>{
                                this.loading = false;
                                this.Toast({
                                  icon: "success",
                                  title: response.message
                                });                               
                                this.$router.push({name:'admin.peralatan.alat.index'});
                            }).catch((error) => {
                              this.loading = false;
                              this.Toast({
                                icon: "error",
                                title: "error"
                              });
                            });
                          }
                      });                        
                    }else{
                        return false;
                    }
                });
                
            },
            fetchData(){
                let params = this.$route.params.alat;
                this.loading = true;
                if (params !== undefined) {
                    let routeUrl = route('api.peralatan.alat.find',{alat: params});
                    axios.get(routeUrl).then((response)=>{
                        this.loading = false;
                        this.alatForm.nama_alat[0].value = response.data.nama_alat;
                    });

                }else{
                    this.loading = false;
                }
            },
            getRoute(){
                let params = this.$route.params.alat;
                if (params !== undefined) {
                    return route('api.peralatan.alat.update',{alat: params});
                }
                return route('api.peralatan.alat.store');
            },
            addInput(){
                this.alatForm.nama_alat.push({
                    key: Date.now(),
                    value:''
                });
            },
            removeInput(item){
                var index = this.alatForm.nama_alat.indexOf(item);
                if(index !== -1){
                    this.alatForm.nama_alat.splice(index,1)
                }
            }
        },
        mounted(){
            this.fetchData();
        }
    }
</script>