<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("companies.title.companies") }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/backend">{{ trans("core.breadcrumb.home") }}</a>
          </li>
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'admin.company.company.index' }">
              {{ trans("companies.title.companies") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">{{ trans(`companies.${pageTitle}`) }}</li>
        </ol>
      </div>
    </div>
    <div class="container">
      <el-form
        :ref="formName"
        :model="company"
        label-width="120px"
        label-position="top"
        v-loading.body="loading"
        @keydown="form.errors.clear($event.target.name)"
        :rules="rules"
      >
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline-info">
              <div class="card-header text-white">
                {{ trans("companies.form.title.company_profile") }}
              </div>
              <div class="card-body">
                <el-tabs v-model="activeTabProfile">
                  <el-tab-pane
                    v-bind:label="trans('companies.tab.title.company')"
                    :name="
                      convertTitleToName(trans('companies.tab.title.company'))
                    "
                  >
                    <div class="row pt-2">
                      <div class="col-12 d-flex justify-content-center">
                        <el-form-item
                          :label="trans('companies.form.company_picture')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'company_picture'
                            ),
                          }"
                        >
                          <upload-avatar
                            :onSuccess="handleAvatarSuccess"
                            :beforeUpload="beforeAvatarUpload"
                            :image="company.company_img"
                          >
                          </upload-avatar>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('company_picture')"
                            v-text="form.errors.first('company_picture')"
                          ></div>
                        </el-form-item>
                      </div>
                      <span slot="label" :class="{ error: form.errors.any() }">
                      </span>
                      <div class="col-6">
                        <el-form-item
                          :label="trans('companies.form.name')"
                          :class="{
                            'el-form-item is-error': form.errors.has('name'),
                          }"
                          prop="name"
                          :rules="rules.name"
                        >
                          <el-input
                            v-model="company.name"
                            size="small"
                            ref="name"
                          ></el-input>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('name')"
                            v-text="form.errors.first('name')"
                          ></div>
                        </el-form-item>
                      </div>

                      <div class="col-6">
                        <el-form-item
                          :label="trans('companies.form.display_name')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'display_name'
                            ),
                          }"
                          prop="display_name"
                          :rules="rules.display_name"
                        >
                          <el-input
                            v-model="company.display_name"
                            size="small"
                            ref="display_name"
                          ></el-input>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('display_name')"
                            v-text="form.errors.first('display_name')"
                          ></div>
                        </el-form-item>
                      </div>

                      <div class="col-6" v-if="pageType != 'profile'">
                        <el-form-item
                          :label="trans('companies.form.type')"
                          :class="{
                            'el-form-item is-error': form.errors.has('type'),
                          }"
                          prop="type"
                          :rules="rules.type"
                        >
                          <el-select v-model="company.type" size="small" ref="type">
                            <el-option
                              v-for="item in optionsType"
                              :key="item.value"
                              :label="item.label"
                              :value="item.value"
                            >
                            </el-option>
                          </el-select>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('type')"
                            v-text="form.errors.first('type')"
                          ></div>
                        </el-form-item>
                      </div>
                      <div class="col-6" v-if="company.type == 'osp'">
                        <el-form-item
                          :label="trans('companies.form.total_endpoint')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'total_endpoint'
                            ),
                          }"
                          prop="total_endpoint"
                          :rules="rules.total_endpoint"
                        >
                          <el-input
                            type="number"
                            v-model="company.total_endpoint"
                            size="small"
                            ref="total_endpoint"
                            min="0"
                          ></el-input>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('total_endpoint')"
                            v-text="form.errors.first('total_endpoint')"
                          ></div>
                        </el-form-item>
                      </div>
                      <div class="col-12" v-if="company.type == 'osp'">
                        <el-form-item>
                          <el-checkbox
                            v-model="company.ppn"
                            size="medium"
                            :label="trans('companies.form.ppn')"
                          ></el-checkbox>
                        </el-form-item>
                      </div>
                    </div>
                  </el-tab-pane>
                  <el-tab-pane
                    :label="trans('companies.tab.title.working-day')"
                    :name="
                      convertTitleToName(
                        trans('companies.tab.title.working-day')
                      )
                    "
                    v-if="company.company_id"
                  >
                    <working-day :company_id="company.company_id">
                    </working-day>
                  </el-tab-pane>
                  <el-tab-pane
                    :label="trans('companies.tab.title.day-off')"
                    :name="
                      convertTitleToName(trans('companies.tab.title.day-off'))
                    "
                    v-if="company.company_id"
                  >
                    <day-off :company_id="company.company_id"></day-off>
                  </el-tab-pane>
                  <el-tab-pane
                    :label="trans('companies.tab.title.rekening')"
                    :name="
                      convertTitleToName(trans('companies.tab.title.rekening'))
                    "
                    v-if="company.company_id"
                  >
                    <rekening :company_id="company.company_id"></rekening>
                  </el-tab-pane>
                  <el-tab-pane
                    :label="trans('companies.tab.title.admin-user')"
                    :name="
                      convertTitleToName(
                        trans('companies.tab.title.admin-user')
                      )
                    "
                    class="pt-2"
                    v-if="!company.company_id"
                  >
                    <div class="col-md-12">
                      <el-form-item
                        :label="trans('companies.form.admin.fullname')"
                        :class="{
                          'el-form-item is-error': form.errors.has('fullname'),
                        }"
                        prop="admin.fullname"
                        :rules="rules.fullname"
                      >
                        <el-input
                          v-model="company.admin.fullname"
                          size="small"
                          ref="fullname"
                        ></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('fullname')"
                          v-text="form.errors.first('fullname')"
                        ></div>
                      </el-form-item>
                    </div>
                    <!-- <div class="col-md-12">
                                            <el-form-item 
                                                :label="trans('companies.form.admin.phone')"
                                                :class="{'el-form-item is-error': form.errors.has('phone') }"
                                                prop="admin.phone"
                                                :rules="rules.phone">
                                                <el-input type="number" maxlength="15" v-model="company.admin.phone" size="small"></el-input>
                                                <div 
                                                    class="el-form-item__error" v-if="form.errors.has('phone')"
                                                    v-text="form.errors.first('phone')">
                                                </div>
                                            </el-form-item>
                                        </div> -->
                    <div class="col-md-12">
                      <el-form-item
                        :label="trans('companies.form.admin.email')"
                        :class="{
                          'el-form-item is-error': form.errors.has('email'),
                        }"
                        prop="admin.email"
                        :rules="rules.email"
                      >
                        <el-input
                          v-model="company.admin.email"
                          size="small"
                          ref="email"
                        ></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('email')"
                          v-text="form.errors.first('email')"
                        ></div>
                      </el-form-item>
                    </div>
                    <!-- <div class="col-md-12">
                                            <el-form-item 
                                                :label="trans('companies.form.admin.password')"
                                                :class="{'el-form-item is-error': form.errors.has('password') }"
                                                prop="admin.password"
                                                :rules="rules.password">
                                                <el-input v-model="company.admin.password" size="small" type="password" autocomplete="off"></el-input>
                                                <div 
                                                    class="el-form-item__error" v-if="form.errors.has('password')"
                                                    v-text="form.errors.first('password')">
                                                </div>
                                            </el-form-item>
                                        </div> -->
                    <!-- <div class="col-md-12">
                                            <el-form-item 
                                                :label="trans('companies.form.admin.check-pass')"
                                                prop="admin.check_pass"
                                                :rules="rules.check_pass">
                                                <el-input v-model="company.admin.check_pass" size="small" type="password" autocomplete="off"></el-input>
                                            </el-form-item>
                                        </div> -->
                  </el-tab-pane>
                  <el-tab-pane
                    :label="trans('companies.tab.title.rating')"
                    :name="
                      convertTitleToName(trans('companies.tab.title.rating'))
                    "
                    v-if="company.company_id"
                  >
                    <rating :company_id="company.company_id"></rating>
                  </el-tab-pane>
                </el-tabs>
              </div>
            </div>

            <div class="card card-outline-info">
              <div class="card-header text-white">
                {{ trans("companies.form.title.address") }}
              </div>
              <div class="card-body">
                <el-tabs v-model="activeTab">
                  <el-tab-pane label="Company" name="company">
                    <div class="row py-2">
                      <div class="col-6 col-md-3">
                        <el-form-item
                          :label="trans('companies.form.provinces_id')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'provinces_id'
                            ),
                          }"
                          prop="provinces_id"
                          :rules="rules.provinces_id"
                        >
                          <SelectProvinces
                            v-model="company.provinces_id"
                            ref="provinces_id"
                          ></SelectProvinces>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('provinces_id')"
                            v-text="form.errors.first('provinces_id')"
                          ></div>
                        </el-form-item>
                      </div>

                      <div class="col-6 col-md-3">
                        <el-form-item
                          :label="trans('companies.form.regencies_id')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'regencies_id'
                            ),
                          }"
                          prop="regencies_id"
                          :rules="rules.regencies_id"
                        >
                          <SelectRegencies
                            v-model="company.regencies_id"
                            :provinces_id="company.provinces_id"
                            ref="regencies_id"
                          ></SelectRegencies>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('regencies_id')"
                            v-text="form.errors.first('regencies_id')"
                          ></div>
                        </el-form-item>
                      </div>

                      <div class="col-6 col-md-3 controls">
                        <el-form-item
                          :label="trans('companies.form.districts_id')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'districts_id'
                            ),
                          }"
                          prop="districts_id"
                          :rules="rules.districts_id"
                        >
                          <SelectDistricts
                            v-model="company.districts_id"
                            :regencies_id="company.regencies_id"
                            ref="districts_id"
                          ></SelectDistricts>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('districts_id')"
                            v-text="form.errors.first('districts_id')"
                          ></div>
                        </el-form-item>
                      </div>

                      <div class="col-6 col-md-3 controls">
                        <el-form-item
                          :label="trans('companies.form.villages_id')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'villages_id'
                            ),
                          }"
                          prop="villages_id"
                          :rules="rules.villages_id"
                        >
                          <SelectVillages
                            v-model="company.villages_id"
                            :districts_id="company.districts_id"
                            ref="villages_id"
                          ></SelectVillages>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('villages_id')"
                            v-text="form.errors.first('villages_id')"
                          ></div>
                        </el-form-item>
                      </div>

                      <div class="col-12 col-md-9">
                        <el-form-item
                          :label="trans('companies.form.address')"
                          :class="{
                            'el-form-item is-error': form.errors.has('address'),
                          }"
                          prop="address"
                          :rules="rules.address"
                        >
                          <el-input
                            v-model="company.address"
                            size="small"
                            id="autoComplateCompanies"
                            ref="address"
                          ></el-input>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('address')"
                            v-text="form.errors.first('address')"
                          ></div>
                        </el-form-item>
                      </div>

                      <div class="col-12 col-md-3">
                        <el-form-item
                          :label="trans('companies.form.post_code')"
                          :class="{
                            'el-form-item is-error': form.errors.has(
                              'post_code'
                            ),
                          }"
                          prop="post_code"
                          :rules="rules.post_code"
                        >
                          <el-input
                            type="number"
                            min="0"
                            v-model="company.post_code"
                            ref="post_code"
                            size="small"
                          ></el-input>
                          <div
                            class="el-form-item__error"
                            v-if="form.errors.has('post_code')"
                            v-text="form.errors.first('post_code')"
                          ></div>
                        </el-form-item>
                      </div>

                      <div class="container">
                        <div style="height: 300px; width: 100%" class="my-3">
                          <Map
                            :id="'mapCompany'"
                            :latitude="company.company_lat"
                            :longitude="company.company_lon"
                            v-on:change="changePositionCompany"
                            :idAutoComplete="'autoComplateCompanies'"
                          >
                          </Map>
                        </div>
                      </div>
                    </div>
                  </el-tab-pane>
                  <el-tab-pane label="POP" name="pop">
                    <div class="col-12 py-2">
                      <el-form-item
                        :label="trans('companies.form.pop_address')"
                        :class="{
                          'el-form-item is-error': form.errors.has(
                            'pop_address'
                          ),
                        }"
                        prop="pop_address"
                        :rules="rules.pop_address"
                      >
                        <el-input
                          v-model="company.pop_address"
                          size="small"
                          ref="pop_address"
                          id="autoCompletePOP"
                        ></el-input>
                        <div
                          class="el-form-item__error"
                          v-if="form.errors.has('pop_address')"
                          v-text="form.errors.first('pop_address')"
                        ></div>
                      </el-form-item>
                      <el-checkbox
                        v-model="pop_address_equal_company"
                        @change="pop_address_equal_company_changed"
                        >{{
                          trans("companies.form.pop_address_equal_company")
                        }}</el-checkbox
                      >
                    </div>
                    <div class="container">
                      <div style="height: 300px; width: 100%" class="my-3">
                        <Map
                          :id="'mapPOP'"
                          :latitude="company.pop_lat"
                          :longitude="company.pop_lon"
                          v-on:change="changePosition"
                          :idAutoComplete="'autoCompletePOP'"
                        >
                        </Map>
                      </div>
                    </div>
                  </el-tab-pane>
                </el-tabs>
              </div>
              <div class="card-footer">
                <el-form-item>
                  <el-tooltip :content="trans('core.tooltip.save')" placement="top">
                    <el-button
                    type="primary"
                    @click="onSubmit('company')"
                    :loading="loading"
                    icon="el-icon-upload"
                  >
                    {{ trans("core.save") }}
                  </el-button>
                  </el-tooltip>
                  <el-tooltip :content="trans('core.tooltip.cancel')" placement="top">
                    <el-button @click="onCancel()" v-if="canIndexCompany"
                    >{{ trans("core.button.cancel") }}
                  </el-button>
                  </el-tooltip>
                </el-form-item>
              </div>
            </div>
          </div>
        </div>
      </el-form>
    </div>
    <button
      v-shortkey="['b']"
      @shortkey="pushRoute({ name: 'admin.company.company.index' })"
      v-show="false"
    ></button>
  </div>
</template>



<script>
import axios from "axios";
import Form from "form-backend-validation";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import PasswordValidation from "../../../../Core/Assets/js/mixins/PasswordValidation";
import SingleFileSelector from "../../../../Media/Assets/js/mixins/SingleFileSelector";
import ValidatorRequired from "../../../../Core/Assets/js/mixins/ValidatorRequired";
import MapGoogle from "./Map";
import DayOff from "./DayOff";
import WorkingDay from "./WorkingDay";
import Rating from "./Rating";
import Rekening from "./Rekening";
import _ from "lodash";
import PermissionsHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";

export default {
  mixins: [
    ShortcutHelper,
    SingleFileSelector,
    TranslationHelper,
    Toast,
    PasswordValidation,
    PermissionsHelper,
    ValidatorRequired,
  ],
  components: {
    Map: MapGoogle,
    DayOff: DayOff,
    WorkingDay: WorkingDay,
    rating: Rating,
    rekening: Rekening,
  },
  props: {
    locales: { default: null },
    pageTitle: { default: null, String },
    pageType: { default: null, String },
  },
  data() {
    let validationTabsCompanyProfile = (rule, value, callback) => {
      console.log();
        if (!value) {
          this.activeTabProfile = $(`[for='${rule.fullField}']`)
            .parents("[role='tabpanel']")
            .attr("id")
            .replace("pane-", "");
          return callback();
        }
      callback();
    };

    let validationTabsAddress = (rule, value, callback) => {
      console.log(rule);
      if (rule.required) {
        if (!value) {
          this.activeTab = $(`[for='${rule.fullField}']`)
            .parents("[role='tabpanel']")
            .attr("id")
            .replace("pane-", "");
          return callback(new Error(rule.message));
        }
      }

      callback();
    };

    let validatorEmail = _.debounce((rule, value, callback) => {
      let properties = {
        email: this.company.admin.email,
      };

      if (!this.company.admin.email)
        return (this.company.admin.is_valid_email = false);

      axios
        .post(route("api.user.user.find.email"), properties)
        .then((response) => {
          this.company.admin.is_valid_email = response.data.valid;

          if (!this.company.admin.is_valid_email)
            return callback(new Error(rule.message));
          callback();
        })
        .catch((err) => {
          return callback(new Error(this.trans("core.error 500 title")));
        });
    }, 500);

    return {
      formName: "company",
      activeTab: "company",
      activeTabProfile: "company",
      rules: {
        fullname: [
          {
            validator: validationTabsCompanyProfile,
            message: this.trans("companies.validation.required", {
              field: "fullname",
            }),
            trigger: "blur",
          },
          {
            validator: this.validatorRequired,
            message: this.trans("companies.validation.required", {
              field: "fullname",
            }),
            ref: "fullname",
          },
        ],
        email: [
          {
            validator: this.validatorRequired,
            message: this.trans("companies.validation.required", {
              field: "email",
            }),
            ref:'email'
          },
          {
            required: true,
            validator: validationTabsCompanyProfile,
            message: this.trans("companies.validation.required", {
              field: "email",
            }),
            trigger: ["blur", "change"],
          },
          {
            type: "email",
            message: this.trans("companies.validation.valid", {
              field: "email",
            }),
            trigger: ["blur", "change"],
          },
          {
            validator: validatorEmail,
            message: this.trans("companies.validation.used-email", {
              field: "email",
            }),
            trigger: ["blur", "change"],
          },
        ],
        password: [
          {
            required: true,
            validator: this.validatePass,
            trigger: ["blur", "change"],
          },
        ],
        check_pass: [
          {
            validator: this.validatePass2,
            required: true,
            trigger: ["blur", "change"],
          },
        ],
        phone: [
          {
            required: true,
            validator: validationTabsCompanyProfile,
            message: this.trans("companies.validation.required", {
              field: "phone",
            }),
            trigger: "blur",
          },
        ],
        address: [
          {
            required: true,
            validator: validationTabsAddress,
            message: this.trans("companies.validation.required", {
              field: "address",
            }),
            trigger: "blur",
          },
        ],
        display_name: [
          {
            validator: this.validatorRequired,
            message: this.trans("companies.validation.required", {
              field: "display name",
            }),
            ref:'display_name'
          },
          {
            required: true,
            validator: validationTabsCompanyProfile,
            message: this.trans("companies.validation.required", {
              field: "display name",
            }),
            trigger: "blur",
          },
        ],
        pop_address: [
          {
            validator: this.ValidatorRequired,
            message: this.trans("companies.validation.required", {
              field: "pop address",
            }),
            ref:'pop_address'
          },
          {
            required: true,
            validator: validationTabsAddress,
            message: this.trans("companies.validation.required", {
              field: "pop address",
            }),
            trigger: "blur",
          },
        ],
        name: [
          {
            validator: this.validatorRequired,
            message: this.trans("companies.validation.required", {
              field: "name",
            }),
            ref:'name'
          },
          {
            required: true,
            validator: validationTabsCompanyProfile,
            message: this.trans("companies.validation.required", {
              field: "name",
            }),
            trigger: "blur",
          },
        ],
        post_code: [
          {
            required: true,
            validator: validationTabsAddress,
            message: this.trans("companies.validation.required", {
              field: "post code",
            }),
            trigger: "blur",
          },
          { validator: this.validPostCode },
        ],
        total_endpoint: [
          {
            validator: this.validatorRequired,
            message: this.trans("companies.validation.required", {
              field: "total endpoint",
            }),
            ref:'total_endpoint'
          },
          {
            required: true,
            validator: validationTabsCompanyProfile,
            message: this.trans("companies.validation.required", {
              field: "total endpoint",
            }),
            trigger: "blur",
          },
          {
            validator: (rule, value, callback) => {
              if (value < 0) this.company.total_endpoint = 0;
              return callback();
            },
          },
        ],
        type: [
          {
            validator: this.validatorRequired,
            message: this.trans("companies.validation.required", {
              field: "type",
            }),
            ref:'type'
          },
          {
            required: true,
            validator: validationTabsCompanyProfile,
            message: this.trans("companies.validation.required", {
              field: "type",
            }),
            trigger: "blur",
          },
        ],
        villages_id: [
          {
            required: true,
            validator: validationTabsAddress,
            message: this.trans("companies.validation.required", {
              field: "village",
            }),
            trigger: "blur",
          },
        ],
        provinces_id: [
          {
            required: true,
            validator: validationTabsAddress,
            message: this.trans("companies.validation.required", {
              field: "provincy",
            }),
            trigger: "blur",
          },
        ],
        regencies_id: [
          {
            required: true,
            validator: validationTabsAddress,
            message: this.trans("companies.validation.required", {
              field: "regency",
            }),
            trigger: "blur",
          },
        ],
        districts_id: [
          {
            required: true,
            validator: validationTabsAddress,
            message: this.trans("companies.validation.required", {
              field: "district",
            }),
            trigger: "blur",
          },
        ],
      },
      company: {
        address: "",
        pop_address: "",
        company_id: null,
        created_at: "",
        display_name: "",
        logo_perusahaan: "",
        company_img: "",
        name: "",
        pop_lat: 0,
        pop_lon: 0,
        company_lat: 0,
        company_lon: 0,
        post_code: "",
        provinces_id: "",
        regencies_id: "",
        villages_id: "",
        rating: "",
        ppn: false,
        districts_id: "",
        total_endpoint: "",
        type: "",
        updated_at: "",
        admin: {
          fullname: "",
          email: "",
          password: "",
          check_pass: "",
          phone: "",
          is_valid_email: false,
        },
      },
      form: new Form(),
      loading: false,
      resetEmailIsLoading: false,
      optionsType: [
        {
          value: "isp",
          label: "ISP",
        },
        {
          value: "osp",
          label: "OSP",
        },
      ],
      pop_address_equal_company: false,
    };
  },
  methods: {
    validationEmail: _.debounce(function () {
      let properties = {
        email: this.company.admin.email,
      };

      if (!this.company.admin.email)
        return (this.company.admin.is_valid_email = false);

      axios
        .post(route("api.user.user.find.email"), properties)
        .then((response) => {
          this.company.admin.is_valid_email = response.data.valid;
          this.$refs["company"].validateField("email");
        });
    }, 500),
    validatePass(rule, value, callback) {
      let validate = this.validatePassword(value, this.formName, callback);
      return validate;
    },
    validatePass2(rule, value, callback) {
      if (value !== this.company.admin.password)
        return callback(
          new Error(
            this.trans("companies.validation.not match", { field: "password" })
          )
        );
      return callback();
    },
    validPostCode(rule, value, callback) {
      if (value < 0 || value > 99999) {
        this.company.post_code = 0;
        return callback(
          new Error(this.trans("configurations.validation.invalid number"))
        );
      }
      return callback();
    },

    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          Swal.fire({
            title: this.trans("core.messages.confirmation-form"),
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: this.trans("core.button.confirm"),
            cancelButtonText: this.trans("core.button.cancel"),
          }).then((result) => {
            if (result.value) {
              var ppn = this.company.ppn;
              if (ppn === true) {
                ppn = 1;
              } else {
                ppn = 0;
              }
              this.form = new Form(this.company);
              this.loading = true;
              if (!this.company.pop_address) this.activeTab = "pop";
              this.form
                .post(this.getRoute())
                .then((response) => {
                  this.loading = false;
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
                  if (this.pageType === "profile") {
                    this.fetchCompany();
                  } else {
                    this.$router.push({ name: "admin.company.company.index" });
                  }
                })
                .catch((error) => {
                  this.loading = false;
                  this.Toast({
                    icon: "error",
                    title: "There are some errors in the form.",
                  });
                });
            }
          });
        } else {
          return false;
        }
      });
    },
    onCancel() {
      this.$router.push({ name: "admin.company.company.index" });
    },
    fetchCompany() {
      this.loading = true;

      axios
        .post(this.getRouteFetchData())
        .then((response) => {
          this.loading = false;
          this.company = response.data.data;
          if (this.$route.query.type !== undefined) {
            this.activeTabProfile = this.$route.query.type;
          }
        })
        .catch((err) => {
          this.loading = false;
        });
    },
    getRoute() {
      if (this.$route.params.company !== undefined) {
        return route("api.company.company.update", {
          company: this.$route.params.company,
        });
      }
      if (this.pageType === "profile")
        return route("api.company.company.update", {
          company: this.company.company_id,
        });

      return route("api.company.company.store");
    },
    getRouteFetchData() {
      let routeUri = "";
      if (this.$route.params.company !== undefined) {
        routeUri = route("api.company.company.find", {
          company: this.$route.params.company,
        });
      } else if (this.pageType === "profile") {
        routeUri = route("api.company.company.profile");
      } else {
        routeUri = route("api.company.company.find-new");
      }

      return routeUri;
    },
    handleAvatarSuccess(res, file) {
      this.company.company_img = URL.createObjectURL(file.raw);
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === "image/jpeg" || file.type === "image/png";
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.Toast({
          icon: "error",
          title: "Avatar picture must be JPG or PNG format!",
        });
      }
      if (!isLt2M) {
        this.Toast({
          icon: "error",
          title: "Avatar picture size can not exceed 2MB!",
        });
      }
      this.company.logo_perusahaan = file;
      return isJPG && isLt2M;
    },
    changePosition(position) {
      this.company.pop_lat = position.lat();
      this.company.pop_lon = position.lng();
      if (position.address) {
        this.company.pop_address = position.address;
      }
    },
    changePositionCompany(position) {
      this.company.company_lat = position.lat();
      this.company.company_lon = position.lng();
      if (position.address) {
        this.company.address = position.address;
      }
      if (position.postal_code)
        this.company.post_code = position.postal_code.short_name;
    },
    pop_address_equal_company_changed() {
      if (this.pop_address_equal_company) {
        this.company.pop_lat = this.company.company_lat;
        this.company.pop_lon = this.company.company_lon;

        return (this.company.pop_address = this.company.address);
      }

      this.company.pop_address = "";
    },
  },
  mounted() {
    this.fetchCompany();
  },
  computed: {
    canIndexCompany: function () {
      return this.hasAccess("company.companies.index");
    },
  },
};
</script>
