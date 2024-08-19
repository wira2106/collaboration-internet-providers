<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">  {{ trans(`perangkats.${pageTitle}`) }} </h3> 
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/backend"> 
                            {{ trans('core.breadcrumb.home') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link :to="{name :'admin.peralatan.perangkat.index'}">
                            {{ trans(`perangkats.${pageTitle}`) }} 
                        </router-link> 
                    </li>
                </ol>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                  <el-form
                      :model="perangkatForm"
                      ref ="perangkatForm"
                      v-loading.body="loading">
                    <div class="card card-outline-info">
                        <div class="card-header text-white">
                            {{trans('perangkats.title.perangkats')}}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                        <el-form-item
                                            v-for="(nama_perangkat, index) in perangkatForm.nama_perangkat"
                                            :key="nama_perangkat.key"
                                            :prop="'nama_perangkat.'+index+'.value'"
                                            :rules="{required: true, 
                                                    message:trans('core.validation.required',{field:trans('perangkats.form.name')}),
                                                    trigger:'blur'}">
                                            <el-input :placeholder="trans('perangkats.placeholder.perangkats')" v-model="nama_perangkat.value">
                                                <el-button v-if="index == 0 && !$route.params.perangkat" type="primary" slot="append" @click="addInput">
                                                    <i class="fa fa-plus"></i>
                                                </el-button>
                                                <el-button v-if="index > 0 && !$route.params.perangkat" type="danger" slot="append" @click="removeInput(nama_perangkat)">
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
                                    <el-button type="primary" @click="onSubmit">
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
    import Toast from '../../../../Core/Assets/js/mixins/Toast';

    export default{
        
        mixins: [ShortcutHelper,Toast],
        data(){
           return {
               perangkatForm :{
                   nama_perangkat:[{
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
                this.form = new Form(this.perangkatForm);
                this.$refs['perangkatForm'].validate((valid)=>{
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
                            this.$router.push({name:'admin.peralatan.perangkat.index'});
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
                let params = this.$route.params.perangkat;
                this.loading = true;
                console.log(this.$route.path);
                if (params !== undefined) {
                    let routeUrl = route('api.peralatan.perangkat.find',{perangkat: params});
                    axios.get(routeUrl).then((response)=>{
                        this.perangkatForm.nama_perangkat[0].value = response.data.nama_perangkat;
                        this.loading = false;
                    });

                }else{
                    this.loading = false;
                }
            },
            getRoute(){
                let params = this.$route.params.perangkat;
                if (params !== undefined) {
                    return route('api.peralatan.perangkat.update',{perangkat: params});
                }
                return route('api.peralatan.perangkat.store');
            },
            addInput(){
                this.perangkatForm.nama_perangkat.push({
                    key: Date.now(),
                    value:''
                });
            },
            removeInput(item){
                var index = this.perangkatForm.nama_perangkat.indexOf(item);
                if(index !== -1){
                    this.perangkatForm.nama_perangkat.splice(index,1)
                }
            }
        },
        mounted(){
            this.fetchData();
        }
    }
</script>