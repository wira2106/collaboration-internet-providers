<template>
  <div>
     
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("users.title.edit-profile") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">
              {{ trans("core.breadcrumb.home") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            {{ trans("users.title.edit-profile") }}
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <el-form
            ref="form"
            :model="user"
            label-width="120px"
            label-position="top"
            v-loading.body="loading"
            @keydown="form.errors.clear($event.target.name)"
          >
            <div class="card card-outline-info">
              <div class="card-header">
                <h5 class="card-title text-white">
                  {{ trans("users.title.edit-profile") }}
                </h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <div class="box-body">
                        <el-tabs v-model="activeTab">
                          <el-tab-pane
                            style="padding-top: 10px;"
                            name="profile"
                            :label="trans('users.tabs.data')"
                          >
                            <span
                              slot="label"
                              :class="{ error: form.errors.any() }"
                            >
                              {{ trans("users.tabs.data") }}
                            </span>
                            <el-form-item
                              :label="trans('users.form.full-name')"
                              :class="{
                                'el-form-item is-error': form.errors.has(
                                  'full-name'
                                ),
                              }"
                            >
                              <el-input v-model="user.full_name"></el-input>
                              <div
                                class="el-form-item__error"
                                v-if="form.errors.has('full_name')"
                                v-text="form.errors.first('full_name')"
                              ></div>
                            </el-form-item>
                            <el-form-item
                              :label="trans('users.form.email')"
                              :class="{
                                'el-form-item is-error': form.errors.has(
                                  'email'
                                ),
                              }"
                            >
                              <el-input v-model="user.email"></el-input>
                              <div
                                class="el-form-item__error"
                                v-if="form.errors.has('email')"
                                v-text="form.errors.first('email')"
                              ></div>
                            </el-form-item>
                            <el-form-item
                              :label="trans('users.form.phone')"
                              :class="{
                                'el-form-item is-error': form.errors.has(
                                  'phone'
                                ),
                              }"
                            >
                              <el-input v-model="user.phone"></el-input>
                              <div
                                class="el-form-item__error"
                                v-if="form.errors.has('phone')"
                                v-text="form.errors.first('phone')"
                              ></div>
                            </el-form-item>
                            <el-form-item
                              :label="trans('users.form.user_picture')"
                              :class="{
                                'el-form-item is-error': form.errors.has(
                                  'user_picture'
                                ),
                              }"
                            >
                              <upload-avatar
                                :onSuccess="handleAvatarSuccess"
                                :beforeUpload="beforeAvatarUpload"
                                :image="user.user_picture"
                              >
                              </upload-avatar>
                              <div
                                class="el-form-item__error"
                                v-if="form.errors.has('user_picture')"
                                v-text="form.errors.first('user_picture')"
                              ></div>
                            </el-form-item>
                          </el-tab-pane>
                          <el-tab-pane
                            style="padding-top: 10px;"
                            name="password"
                            :label="trans('users.tabs.new password')"
                            v-if="!user.is_new"
                          >
                            <h4>{{ trans("users.new password setup") }}</h4>
                            <el-form-item
                              :label="trans('users.form.password')"
                              :class="{
                                'el-form-item is-error': form.errors.has(
                                  'password'
                                ),
                              }"
                              prop="password"
                              :rules="[
                                {
                                  required: true,
                                  validator: this.validatePass,
                                },
                              ]"
                            >
                              <el-input
                                v-model="user.password"
                                type="password"
                              ></el-input>
                              <div
                                class="el-form-item__error"
                                v-if="form.errors.has('password')"
                                v-text="form.errors.first('password')"
                              ></div>
                            </el-form-item>
                            <el-form-item
                              :label="trans('users.form.password-confirmation')"
                              :class="{
                                'el-form-item is-error': form.errors.has(
                                  'password_confirmation'
                                ),
                              }"
                              prop="check_pass"
                              :rules="[
                                {
                                  validator: validatePass2,
                                  required: true,
                                  trigger: ['blur', 'change'],
                                },
                              ]"
                            >
                              <el-input
                                v-model="user.password_confirmation"
                                type="password"
                              ></el-input>
                              <div
                                class="el-form-item__error"
                                v-if="form.errors.has('password_confirmation')"
                                v-text="
                                  form.errors.first('password_confirmation')
                                "
                              ></div>
                            </el-form-item>
                          </el-tab-pane>
                        </el-tabs>
                      </div>
                      <div class="box-footer">
                        <el-form-item>
                          <el-button
                            type="primary"
                            @click="onSubmit()"
                            :loading="loading"
                          >
                            <template v-if="activeTab == 'profile'">{{ trans("users.button.update profile") }}</template>
                            <template v-else>{{ trans("users.button.update password") }}</template>
                          </el-button>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </el-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import PasswordValidation from "../../../../Core/Assets/js/mixins/PasswordValidation";

export default {
  mixins: [PasswordValidation],
  props: {
    locales: { default: null },
  },
  data() {
    return {
      activeTab: 'profile',
      user: {
        full_name: "",
        // last_name: '',
        email: "",
        phone: "",
        user_picture: "",
        photo_profile: null,
        permissions: {},
        roles: {},
        is_new: false,
        password: "",
        password_confirmation: "",
      },
      roles: {},
      form: new Form(),
      loading: false,
    };
  },
  methods: {
    validatePass(rule, value, callback) {
      let validate = this.validatePassword(value, "form", callback, false);
      return validate;
    },
    validatePass2(rule, value, callback) {
      if (this.user.password_confirmation !== this.user.password)
        return callback(
          new Error(
            this.trans("companies.validation.not match", { field: "password" })
          )
        );
      return callback();
    },
    handleAvatarSuccess(res, file) {
      this.user.user_picture = URL.createObjectURL(file.raw);
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === "image/jpeg" || file.type === "image/png";
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error("Avatar picture must be JPG or PNG format!");
      }
      if (!isLt2M) {
        this.$message.error("Avatar picture size can not exceed 2MB!");
      }
      this.user.photo_profile = file;
      return isJPG && isLt2M;
    },
    onSubmit() {
      this.$refs["form"].validate((valid) => {
        if (valid) {
          this.form = new Form(this.user);
          this.loading = true;

          this.form
            .post(route("api.account.profile.update"))
            .then((response) => {
              // this.loading = false;
              this.$message({
                type: "success",
                message: response.message,
              });
              this.fetchUser();
            })
            .catch((error) => {
              console.log(error);
              this.loading = false;
              this.$notify.error({
                title: "Error",
                message: "There are some errors in the form.",
              });
            });
        }
      });
    },
    fetchUser() {
       
      this.loading = true;
      axios
        .get(route("api.account.profile.find-current-user"))
        .then((response) => {
           
          this.loading = false;
          this.user = response.data.data;
          if (
            this.user.photo_profile != null &&
            this.user.photo_profile != ""
          ) {
            this.setPhotoProfile(response.data.data.user_picture);
          }
        })
        .catch((error) => {
           
        });
    },
    setPhotoProfile(photo) {
      var bg = document.querySelectorAll(".photo-profile-background");
      bg.forEach(function(el, index) {
        el.style.backgroundImage = "url('" + photo + "')";
      });
    },
  },
  mounted() {
    this.fetchUser();
  },
};
</script>
