<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">  {{ trans(`staff.`+title) }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/backend">{{ trans('core.breadcrumb.home') }}</a></li>
                <li class="breadcrumb-item"><router-link :to="{name:'admin.user.staff.index'}"> {{ trans('staff.title.staff') }} </router-link> </li>
                <li class="breadcrumb-item">
                  {{ trans(`staff.`+title) }}
                </li>
                </ol>
            </div>                
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                   <el-form ref="staffForm"
                      :model="staffForm"
                      label-width="120px"
                      label-position="top"
                      v-loading.body="loading">
                    <div class="card card-outline-info">
                        <div class="card-header">
                            <h5 class="card-title text-white">{{ trans(`staff.`+title) }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                              <div class="col-md-12">
                                  <el-form-item 
                                      :label="trans('staff.form.full-name')"
                                      :class="{'el-form-item is-error': form.errors.has('full_name') }"
                                      prop="full_name"
                                      :rules="[
                                            { required: true, 
                                              message: 'Fullname is required'
                                            },]">
                                      <el-input v-model="staffForm.full_name" size="small" ref="full_name"></el-input>
                                      <div 
                                          class="el-form-item__error" v-if="form.errors.has('full_name')"
                                          v-text="form.errors.first('full_name')">
                                      </div>
                                  </el-form-item>
                              </div>
                              <div class="col-md-12">
                                  <el-form-item 
                                      :label="trans('staff.form.phone')"
                                      :class="{'el-form-item is-error': form.errors.has('phone') }"
                                      prop="phone"
                                      :rules="[
                                            { required: true, 
                                              message: 'Phone is required'
                                            },]">
                                      <el-input v-model="staffForm.phone" size="small"></el-input>
                                      <div 
                                          class="el-form-item__error" v-if="form.errors.has('phone')"
                                          v-text="form.errors.first('phone')">
                                      </div>
                                  </el-form-item>
                              </div>
                              <div class="col-md-12">
                                  <el-form-item 
                                      :label="trans('staff.form.email')"
                                      :class="{'el-form-item is-error': form.errors.has('email') }"
                                      prop="email"
                                      :rules="[
                                            { required: true, 
                                              message: 'Email is required'
                                            },]">
                                      <el-input v-model="staffForm.email" size="small"></el-input>
                                      <div 
                                          class="el-form-item__error" v-if="form.errors.has('email')"
                                          v-text="form.errors.first('email')">
                                      </div>
                                  </el-form-item>
                              </div>
                              <div class="col-md-12">
                                <el-form-item :label="trans('staff.form.user_picture')"
                                              :class="{'el-form-item is-error': form.errors.has('user_picture') }">
                                  <upload-avatar
                                      :onSuccess="handleAvatarSuccess"
                                      :beforeUpload="beforeAvatarUpload"
                                      :image="staffForm.user_picture"
                                  >
                                  </upload-avatar>
                                  <div class="el-form-item__error" v-if="form.errors.has('user_picture')"
                                          v-text="form.errors.first('user_picture')"></div>
                                </el-form-item>
                              </div>
                              <div class="col-md-12">
                                  <el-form-item 
                                      :label="trans('staff.form.password')"
                                      :class="{'el-form-item is-error': form.errors.has('password') }"
                                      prop="password"
                                      :rules="[{ 
                                              required: required_pass, 
                                              validator: this.validatePass,
                                            }]">
                                      <el-input v-model="staffForm.password" size="small" type="password" autocomplete="off"></el-input>
                                      <div 
                                          class="el-form-item__error" v-if="form.errors.has('password')"
                                          v-text="form.errors.first('password')">
                                      </div>
                                  </el-form-item>
                              </div>
                              <div class="col-md-12">
                                  <el-form-item 
                                      :label="trans('staff.form.password-confirmation')"
                                      prop="check_pass"
                                      :rules="[{validator: validatePass2, required:true, trigger:['blur', 'change']}]">
                                      <el-input v-model="staffForm.check_pass" size="small" type="password" autocomplete="off"></el-input>
                                  </el-form-item>
                              </div>
                              <div class="col-md-12" v-if="user_role == 'Super Admin'">
                                <el-form-item
                                  :label="trans('staff.form.company')"
                                  prop="company_id"
                                  :rules="[
                                        {required:true, trigger:['blur', 'change']}]"
                                   >
                                  <el-select                                      
                                      v-model="staffForm.company_id"
                                      placeholder="Select"
                                      filterable
                                      size="small">
                                      <el-option
                                      v-for="item in companies"
                                      :key="item.company_id"
                                      :label="setLabel(item)"
                                      :value="item.company_id"
                                      :data-id="item.company_id"
                                      >
                                      </el-option>
                                  </el-select>
                                </el-form-item>
                              </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <el-form-item>
                                <el-button type="primary" @click="onSubmit('staffForm')" :loading="loading">
                                    {{ trans('core.save') }}
                                </el-button>
                                <el-button @click="onCancel('staffForm')">
                                  {{ trans('core.button.cancel')}}
                                </el-button>
                            </el-form-item>
                        </div>
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
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';
    import SingleFileSelector from '../../../../Media/Assets/js/mixins/SingleFileSelector';
    import TranslationHelper from '../../../../Core/Assets/js/mixins/TranslationHelper';
    import UserRolesPermission from '../../../../Core/Assets/js/mixins/UserRolesPermission';
    import PasswordValidation from '../../../../Core/Assets/js/mixins/PasswordValidation';
    import Toast from '../../../../Core/Assets/js/mixins/Toast';

    export default {
        mixins: [ShortcutHelper,SingleFileSelector,TranslationHelper,UserRolesPermission,Toast,PasswordValidation],
        props: {
          pageTitle: {default:null}
        },
        data() {
            return{
             staffForm: {
                user_id: null,
                full_name:'',
                email:'',
                password:'',
                check_pass: '',
                phone: '',
                company_id: null,
                user_picture:null,
                photo_profile:""
              },
             companies:[],
             loading: false,
             form: new Form(),
             autocomplete: null,
             required_pass: true,
             title: 'create staff',
             user_role: 'Super Admin',
            }
        },
        methods: {
          validatePass(rule, value, callback){
                let validate = this.validatePassword(value,'staffForm',callback,this.required_pass);
                return validate;
          },
          validatePass2(rule, value, callback){
              if(value !== this.staffForm.password) return callback(new Error(this.trans('companies.validation.not match', {field:'password'})))
              return callback()
          },
          handleAvatarSuccess(res, file) {
                this.staffForm.user_picture = URL.createObjectURL(file.raw);
            },
          beforeAvatarUpload(file) {
              const isJPG = file.type === 'image/jpeg' || file.type === "image/png";
              const isLt2M = file.size / 1024 / 1024 < 2;

              if (!isJPG) {
                this.$message.error('Avatar picture must be JPG or PNG format!');
              }
              if (!isLt2M) {
                this.$message.error('Avatar picture size can not exceed 2MB!');
              }
              this.staffForm.photo_profile = file
              return isJPG && isLt2M;
          },
          onSubmit(formName) {
            this.$refs[formName].validate((valid) => {
              if (valid) {
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
                    this.form = new Form(this.staffForm);
                    this.loading = true;
                    this.form.post(this.getRoute())
                        .then((response) => {
                          this.loading = false;                  
                          this.Toast({
                            icon: "success",
                            title: response.message
                          });
                          this.$router.push({ name: 'admin.user.staff.index' });
                        })
                        .catch((error) => {
                          this.loading = false;
                          this.Toast({
                            icon: "error",
                            title: "error"
                          });
                        });
                  }
                });
              }else{
                console.log('error submit!!');
                return false;              
              }
          });
        },
        getRoute(){          
          return route('api.user.staff.submit');
        },
        onCancel() {
          this.$router.push({ name: 'admin.user.staff.index' });
        },
        fetchStaff() {
                let routeUri = '';
                if (this.$route.params.staff !== undefined) {
                    this.loading = true;
                    this.required_pass = false;
                    routeUri = route('api.user.staff.find', { staff: this.$route.params.staff });
                    axios.post(routeUri)
                        .then((response) => {
                            if(response.data != null){                              
                              let data = response.data.data;                              
                              this.staffForm = data;                              
                            }
                          this.loading = false;                        
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
                }

         },
         generateTitle(){
            if (this.$route.params.staff !== undefined) {
              this.title = 'edit staff';
            }else{
              this.title = 'create staff';
            }
         },
         fetchCompany(){          
          let routeUri = route('api.company.company.list');
          axios.post(routeUri)
              .then((response) => {
                this.companies = response.data;
              }).catch(error => {
                if (error.response.status === 403) {
                  this.$notify.error({
                      title: this.trans('core.unauthorized'),
                      message: this.trans('core.unauthorized-access'),
                  });
                }
            });
         },
         setLabel(item){
            let val = "";
            val += item.name;
            if(item.type != null)
            {
              val += " ( "+item.type.toUpperCase()+" )";
            }
            return val;
         }


       },
       mounted() {
          this.generateTitle();
          this.fetchStaff();
          this.fetchCompany();
          this.getRolesPermission();
      },

    }
</script>