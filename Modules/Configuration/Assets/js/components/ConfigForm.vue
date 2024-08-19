<template>
  <div>
     
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("configurations.title.configurations") }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">{{
              trans("core.breadcrumb.home")
            }}</router-link>
          </li>
          <li class="breadcrumb-item">
            {{ trans("configurations.title.configurations") }}
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <el-form
            ref="configuration"
            :model="configuration"
            label-width="190px"
            label-position="right"
            v-loading.body="loading"
          >
            <div class="card card-outline-info">
              <div class="card-header text-white">
                <h3 class="text-white" @click="show()">
                  {{ trans("configurations.title.configurations") }}
                </h3>
              </div>
              <!-- /.box-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-1"></div>
                  <div class="col-md-10">
                    <el-form-item
                      :label="trans('configurations.label.admin_fee')"
                      prop="admin_fee"
                    >
                      <div class="row">
                        <div class="col-md-10">
                          <!--  <el-input type="number" v-model.number="configuration.admin_fee" :min="1" :max="100">
                                                    <template slot="append"> % </template>
                                                </el-input> -->
                          <InputCurrency
                            v-model="configuration.admin_fee"
                          ></InputCurrency>
                        </div>
                        <div class="col-md-2">
                          <el-button
                            size="medium"
                            @click="onSubmit('configuration', 0)"
                            type="primary"
                          >
                            {{ trans("core.button.save") }}
                          </el-button>
                        </div>
                      </div>
                    </el-form-item>
                    <el-form-item
                      :label="trans('configurations.label.percen instalasi')"
                      placeholder=""
                      prop="persentase_otc_mrc"
                      :rules="[
                        {
                          type: 'number',
                          message: trans(
                            'configurations.validation.not number'
                          ),
                        },
                        { validator: validInput },
                      ]"
                    >
                      <div class="row">
                        <div class="col-md-10">
                          <!-- <el-input type="number" v-model.number="configuration.persentase_otc_mrc" :min="1" :max="100">
                                                    <template slot="append"> % </template>
                                                </el-input> -->
                          <InputPercent
                            v-model="configuration.persentase_otc_mrc"
                          ></InputPercent>
                        </div>
                        <div class="col-md-2">
                          <el-button
                            size="medium"
                            @click="onSubmit('configuration', 1)"
                            type="primary"
                          >
                            {{ trans("core.button.save") }}
                          </el-button>
                        </div>
                      </div>
                    </el-form-item>
                    <el-form-item
                      :label="trans('configurations.label.percent_refund_osp')"
                      placeholder=""
                      prop="persentase_refund_osp"
                      :rules="[
                        {
                          type: 'number',
                          message: trans(
                            'configurations.validation.not number'
                          ),
                        },
                        { validator: validInput },
                      ]"
                    >
                      <div class="row">
                        <div class="col-md-10">
                          <!--  <el-input type="number" v-model.number="configuration.persentase_refund_osp" :min="1" :max="100">
                                                    <template slot="append"> % </template>
                                                </el-input> -->
                          <InputPercent
                            v-model="configuration.persentase_refund_osp"
                          ></InputPercent>
                        </div>
                        <div class="col-md-2">
                          <el-button
                            size="medium"
                            @click="onSubmit('configuration', 2)"
                            type="primary"
                          >
                            {{ trans("core.button.save") }}
                          </el-button>
                        </div>
                      </div>
                    </el-form-item>
                    <el-form-item
                      :label="trans('configurations.label.percent_refund_oa')"
                      placeholder=""
                      prop="persentase_refund_openaccess"
                      :rules="[
                        {
                          type: 'number',
                          message: trans(
                            'configurations.validation.not number'
                          ),
                        },
                        { validator: validInput },
                      ]"
                    >
                      <div class="row">
                        <div class="col-md-10">
                          <!-- <el-input type="number" v-model.number="configuration.persentase_refund_openaccess" :min="1" :max="100">
                                                    <template slot="append"> % </template>
                                                </el-input> -->
                          <InputPercent
                            v-model="configuration.persentase_refund_openaccess"
                          ></InputPercent>
                        </div>
                        <div class="col-md-2">
                          <el-button
                            size="medium"
                            @click="onSubmit('configuration', 3)"
                            type="primary"
                          >
                            {{ trans("core.button.save") }}
                          </el-button>
                        </div>
                      </div>
                    </el-form-item>
                    <el-form-item
                      :label="trans('configurations.label.sla_odp')"
                      prop="sla_odp"
                      :rules="[
                        {
                          type: 'number',
                          message: trans(
                            'configurations.validation.not number'
                          ),
                        },
                        { validator: validInputDay },
                      ]"
                    >
                      <div class="row">
                        <div class="col-md-10">
                          <el-input
                            type="number"
                            v-model.number="configuration.sla_odp"
                          >
                            <template slot="append">
                              {{ trans("configurations.title.day") }}
                            </template>
                          </el-input>
                        </div>
                        <div class="col-md-2">
                          <el-button
                            size="medium"
                            @click="onSubmit('configuration', 4)"
                            type="primary"
                          >
                            {{ trans("core.button.save") }}
                          </el-button>
                        </div>
                      </div>
                    </el-form-item>
                    <el-form-item
                      :label="trans('configurations.label.sla_jb')"
                      placeholder=""
                      prop="sla_join_box"
                      :rules="[
                        {
                          type: 'number',
                          message: trans(
                            'configurations.validation.not number'
                          ),
                        },
                        { validator: validInputDay },
                      ]"
                    >
                      <div class="row">
                        <div class="col-md-10">
                          <el-input
                            type="number"
                            v-model.number="configuration.sla_join_box"
                          >
                            <template slot="append">
                              {{ trans("configurations.title.day") }}
                            </template>
                          </el-input>
                        </div>
                        <div class="col-md-2">
                          <el-button
                            size="medium"
                            @click="onSubmit('configuration', 5)"
                            type="primary"
                          >
                            {{ trans("core.button.save") }}
                          </el-button>
                        </div>
                      </div>
                    </el-form-item>
                    <el-form-item
                      :label="trans('configurations.label.sla_fixing')"
                      placeholder=""
                      prop="sla_fixing_stack"
                      :rules="[
                        {
                          type: 'number',
                          message: trans(
                            'configurations.validation.not number'
                          ),
                        },
                        { validator: validInputDay },
                      ]"
                    >
                      <div class="row">
                        <div class="col-md-10">
                          <el-input
                            type="number"
                            v-model.number="configuration.sla_fixing_stack"
                          >
                            <template slot="append">
                              {{ trans("configurations.title.day") }}
                            </template>
                          </el-input>
                        </div>
                        <div class="col-md-2">
                          <el-button
                            size="medium"
                            @click="onSubmit('configuration', 6)"
                            type="primary"
                          >
                            {{ trans("core.button.save") }}
                          </el-button>
                        </div>
                      </div>
                    </el-form-item>
                    <el-form-item
                      :label="trans('configurations.label.biaya_ep')"
                      placeholder=""
                      prop="biaya_ep"
                    >
                      <div class="row">
                        <div class="col-md-10">
                          <InputCurrency
                            v-model="configuration.biaya_ep"
                          ></InputCurrency>
                        </div>
                        <div class="col-md-2">
                          <el-button
                            size="medium"
                            @click="onSubmit('configuration', 7)"
                            type="primary"
                          >
                            {{ trans("core.button.save") }}
                          </el-button>
                        </div>
                      </div>
                    </el-form-item>
                    <el-form-item
                      :label="trans('configurations.label.kabel_per_meter')"
                      placeholder=""
                      prop="kabel_per_meter"
                    >
                      <div class="row">
                        <div class="col-md-10">
                          <InputCurrency
                            v-model="configuration.kabel_per_meter"
                          ></InputCurrency>
                        </div>
                        <div class="col-md-2">
                          <el-button
                            size="medium"
                            @click="onSubmit('configuration', 8)"
                            type="primary"
                          >
                            {{ trans("core.button.save") }}
                          </el-button>
                        </div>
                      </div>
                    </el-form-item>
                    <el-form-item>
                      <!-- <el-button type="primary" @click="onSubmit('configuration')">{{trans('core.button.save')}}</el-button> -->
                      <el-button type="danger" @click="onCancel">{{
                        trans("core.back")
                      }}</el-button>
                    </el-form-item>
                  </div>
                  <div class="col-md-1"></div>
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
import axios from "axios";
import Form from "form-backend-validation";
import _ from "lodash";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import InputPercentVue from "../../../../Core/Assets/js/components/InputPercent.vue";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";

export default {
  mixins: [ShortcutHelper, Toast],
  components: {
    InputPercent: InputPercentVue,
    InputCurrency: InputCurrencyVue,
  },
  data() {
    return {
      configuration: {
        id: 1,
        admin_fee: 0,
        biaya_ep: 0,
        kabel_per_meter: 0,
        persentase_otc_mrc: "",
        persentase_refund_osp: "",
        persentase_refund_openaccess: "",
        sla_odp: "",
        sla_join_box: "",
        sla_fixing_stack: "",
        fieldEdit: "",
      },
      loading: false,
      form: new Form(),
      roles: {},
    };
  },
  methods: {
    validInput(rule, value, callback) {
      if (value < 0 || value > 999)
        return callback(
          new Error(this.trans("configurations.validation.invalid number"))
        );
      callback();
    },
    validInputDay(rule, value, callback) {
      if (value < 0)
        return callback(
          new Error(this.trans("configurations.validation.invalid number"))
        );
      callback();
    },
    fetchData() {
       
      let routeUri = route("admin.configuration.configuration.data");
      axios
        .get(routeUri)
        .then((response) => {
           
          if (response.data) {
            this.configuration = response.data;
            console.log(this.configuration);
          }
        })
        .catch((error) => {
           
        });
    },
    onSubmit(formName, fieldEdit) {
      this.configuration.fieldEdit = fieldEdit;
      let getRoute = route(
        "admin.configuration.configuration.update",
        this.configuration.id
      );
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
              this.form = new Form(this.configuration);
              this.loading = true;
              axios
                .post(getRoute, this.form)
                .then((response) => {
                  let data = response.data;
                  this.loading = false;
                  this.Toast({
                    title: data.message,
                  });
                  this.fetchData();
                })
                .catch((error) => {
                  this.loading = false;
                  this.Toast({
                    icon: "error",
                    title: this.trans("core.error 500 title"),
                  });
                });
            }
          });
        }
      });
    },
    onCancel() {
      // this.$router.push({
      //     name: 'admin.company.company.index'
      // });
      this.$router.go(-1);
    },
    show() {
      console.log(this.configuration);
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style>
.el-form-item__label {
  line-height: 20px !important;
}
</style>
