<template>
  <div>
     
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("biaya_kabel.title.biaya-kabel") }}
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
            {{ trans("biaya_kabel.title.biaya-kabel") }}
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="card card-outline-info">
        <div class="card-header text-white">
          {{ trans("biaya_kabel.index resource") }}
        </div>
        <div class="card-body">
          <div class="tool-bar row" style="padding-bottom: 20px">
            <div class="col-12" v-if="companies.length > 0">
              <div class="row">
                <div class="col-4">
                  <el-select
                    v-model="company_id"
                    placeholder="Select"
                    filterable
                    size="small"
                    @change="queryServerBiayaKabel"
                  >
                    <el-option
                      v-for="item in companies"
                      :key="item.company_id"
                      :label="item.name"
                      :value="item.company_id"
                    >
                    </el-option>
                  </el-select>
                </div>
              </div>
            </div>
            <div class="col-12 mt-2" v-if="biaya_kabel.tipe">
              <table>
                <tr>
                  <td>
                    {{trans('biaya_kabel.label.tipe kabel')}}
                  </td>
                  <td width="20" align="center">:</td>
                  <td>
                     <el-radio-group
                      v-model="biaya_kabel.tipe"
                      @change="biayaKabelTipeChanged()"
                    >
                      <el-radio
                        v-for="(option, index) in list_tipe_biaya_kabel"
                        :key="index"
                        :label="option.value"
                      >
                        {{ option.label }}
                      </el-radio>
                    </el-radio-group>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        <div class="row" v-if="biaya_kabel.tipe === 'precone'">
          <div class="col-12">
            <el-form ref="biaya-kabel" :model="biaya_kabel">
              <el-form-item
                :label="trans('biaya_kabel.form.harga permeter')"
                prop="harga_per_meter"
                :rules="rules.harga_per_meter"
              >
                <input-currency v-model="biaya_kabel.harga_per_meter">
                </input-currency>
              </el-form-item>
            </el-form>

            <div class="row">
              <div class="col-12">
                <el-button
                  @click="onSubmit('biaya-kabel')"
                  icon="el-icon-upload"
                  type="primary"
                  :loading="buttonSaveIsLoading"
                >
                  {{ trans("biaya_kabel.button.save") }}
                </el-button>
              </div>
            </div>
          </div>
        </div>
        <div class="row" v-if="biaya_kabel.tipe === 'dropcore'">
            <div class="col-12">
              <div class="pull-right">
                <el-button
                    plain
                    size="small"
                    icon="el-icon-plus"
                    class="pull-right"
                    @click="goToCreate()"
                    >
                    {{ trans("biaya_kabel.button.create biaya kabel") }}
                  </el-button>
                </div>
            </div>
            <div class="col-12">
              <el-table
                :data="biayakabel"
                style="width: 100%"
                v-loading.body="tableIsLoading"
                @sort-change="handleSortChange">
                <el-table-column label="No" width="75">
                  <template slot-scope="scope">
                    {{ meta.per_page * (meta.current_page - 1) + scope.$index + 1 }}
                  </template>
                </el-table-column>
                <el-table-column
                  prop="panjang_kabel"
                  :label="trans('biaya_kabel.form.panjang kabel')"
                  sortable="custom"
                >
                  <template slot-scope="scope">
                    <a href="#" @click.prevent="gotoEdit(scope)">
                      {{ scope.row.panjang_kabel }} m
                    </a>
                  </template>
                </el-table-column>
                <el-table-column
                  prop="biaya"
                  :label="trans('biaya_kabel.form.biaya')"
                  sortable="custom"
                >
                  <template slot-scope="scope">
                    <a href="#" @click.prevent="gotoEdit(scope)">
                      {{ rupiah(scope.row.biaya) }}
                    </a>
                  </template>
                </el-table-column>
                <el-table-column prop="aksi" :label="trans('core.table.actions')">
                  <template slot-scope="scope">
                    <el-button-group>
                      <el-tooltip
                        :content="trans('core.tooltip.kabel.edit')"
                        placement="top"
                      >
                        <el-button
                          size="mini"
                          @click.prevent="gotoEdit(scope)"
                          icon="el-icon-edit"
                        >
                        </el-button>
                      </el-tooltip>
                      <el-tooltip
                        :content="trans('core.tooltip.kabel.hapus')"
                        placement="top"
                      >
                        <delete-button
                          :scope="scope"
                          :rows="biayakabel"
                          v-on:onDelete="tableIsLoading = $event"
                          v-on:onDeleteSuccess="tableIsLoading = !$event"
                        ></delete-button>
                      </el-tooltip>
                    </el-button-group>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <biaya-kabel-form
      :range_id="range_id"
      :biaya_kabel_id="biaya_kabel.biaya_kabel_id"
      @onSuccess="queryServer"
      @onConflict="handleBiayaKabelConflict"
    >
    </biaya-kabel-form>
  </div>
</template>

<script>
import BiayaKabelFormVue from "./BiayaKabelForm.vue";
import CurrencyHelper from "../../../../Core/Assets/js/mixins/CurrencyHelper.js";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";
import axios from "axios";

export default {
  mixins: [CurrencyHelper, Toast],
  components: {
    "biaya-kabel-form": BiayaKabelFormVue,
    "input-currency": InputCurrencyVue,
  },
  data() {
    return {
      tableIsLoading: false,
      buttonSaveIsLoading: false,
      range_id: null,
      biaya_kabel: {
        biaya_kabel_id: null,
        tipe: null,
        harga_per_meter: 0,
      },
      biayakabel: [],
      company_id: null,
      companies: [],
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      list_tipe_biaya_kabel: [
        {
          value: "precone",
          label: "Precone",
        },
        {
          value: "dropcore",
          label: "Dropcore",
        },
      ],
      rules: {
        harga_per_meter: [
          {
            required: true,
            message: this.trans("core.validation.required", {
              field: this.trans("biaya_kabel.form.harga kabel"),
            }),
            trigger: ["blur", "change"],
          },
        ],
      },
    };
  },
  methods: {
    goToCreate() {
      this.range_id = null;
      $("#modal-biaya-kabel").modal("show");
    },
    queryServerBiayaKabel() {
      const properties = {
        params: {
          company_id: this.company_id,
        },
      };

      axios
        .get(route("api.company.biayakabel.index"), properties)
        .then((response) => {
           
          this.biaya_kabel = response.data.data;
          this.company_id = response.data.company_id;
          this.companies = response.data.companies;

          if (this.biaya_kabel.tipe == "dropcore") {
            this.queryServer();
          }
        })
        .catch((error) => {
           
          this.Toast({
            icon: "error",
            title: this.trans("core.error 500 title"),
          });
        });
    },
    queryServer(customProperties) {
      this.range_id = null;
      this.tableIsLoading = true;
      const properties = {
        params: {
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          biaya_kabel_id: this.biaya_kabel.biaya_kabel_id,
          ...customProperties,
        },
      };
      axios
        .get(route("api.company.biayakabel.range.index"), properties)
        .then((response) => {
          this.tableIsLoading = false;
          this.biayakabel = response.data.data;
          this.meta = response.data.meta;
        });
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
              this.buttonSaveIsLoading = true;
              axios
                .post(
                  route("api.company.biayakabel.update", {
                    biaya_kabel: this.biaya_kabel.biaya_kabel_id,
                  }),
                  this.biaya_kabel
                )
                .then((response) => {
                  this.buttonSaveIsLoading = false;
                  this.Toast({
                    icon: "success",
                    title: response.data.message,
                  });
                })
                .catch((error) => {
                  this.buttonSaveIsLoading = false;
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
    handleSortChange(event) {
      this.queryServer({ order_by: event.prop, order: event.order });
    },
    gotoEdit(scope) {
      this.range_id = scope.row.range_id;
      $("#modal-biaya-kabel").modal("show");
    },
    handleBiayaKabelConflict(data) {
      Swal.fire({
        title: this.trans("core.messages.resource conflict"),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then((result) => {
        if (result) {
          this.range_id = data.range_id;
        }
      });
    },
    biayaKabelTipeChanged() {
      if (this.biaya_kabel.tipe == "dropcore") {
        this.queryServer();
      }

      const properties = {
        tipe: this.biaya_kabel.tipe,
        company_id: this.company_id,
      };
      axios
        .post(
          route("api.company.biayakabel.update", {
            biaya_kabel: this.biaya_kabel.biaya_kabel_id,
          }),
          properties
        )
        .catch((err) => {
          if (this.biaya_kabel === "precone") {
            this.biaya_kabel = "dropcore";
          } else {
            this.biaya_kabel = "precone";
          }
        });
    },
  },
  mounted() {
    this.queryServerBiayaKabel();
  },
};
</script>

<style>
  .el-radio{
    margin-bottom: 0px !important;
  }
</style>
