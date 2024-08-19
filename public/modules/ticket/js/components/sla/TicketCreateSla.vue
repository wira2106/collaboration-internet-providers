<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("ticketsSla.title.ticket support") }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/backend">
              {{ trans("core.breadcrumb.home") }}
            </a>
          </li>
          <li class="breadcrumb-item">{{ trans("tickets.title.tickets") }}</li>
        </ol>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <el-form
            ref="form"
            :model="form"
            label-width="120px"
            label-position="top"
            v-loading.body="loading"
          >
            <Card class="card">
              <div class="card-header" style="border-bottom: 0px">
                <div class="card-actions">
                  <a data-action="collapse"><i class="ti-minus"></i></a>
                </div>
                <h5 @click="fetchDataPelanggan">
                  {{ trans("tickets.title.create ticket") }}
                </h5>
              </div>
              <div class="card-body collapse show">
                <div class="row">
                  <div class="col-md-12">
                    <el-form-item
                      :label="trans('tickets.form.pelanggan')"
                      class="el-form-item"
                      prop="pelanggan"
                      :rules="rules.required_field"
                    >
                      <el-select
                        v-model="form.pelanggan"
                        placeholder="Select"
                        filterable
                        size="small"
                      >
                        <el-option
                          v-for="(item, key) in pelanggans"
                          :key="key"
                          :label="item.nama_pelanggan2"
                          :value="item.pelanggan_id"
                        >
                        </el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('tickets.form.start mati')"
                      class="el-form-item"
                      prop="start_gangguan"
                      :rules="rules.required_field"
                    >
                      <el-date-picker
                        style="width: 100%"
                        size="small"
                        v-model="form.start_gangguan"
                        :picker-options="pickerOptions"
                        type="datetime"
                        format="yyyy-MM-dd HH:mm"
                        value-format="yyyy-MM-dd HH:mm:ss"
                        :placeholder="trans('tickets.form.select date time')"
                      >
                      </el-date-picker>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('tickets.form.end mati')"
                      class="el-form-item"
                      prop="end_gangguan"
                    >
                      <el-date-picker
                        style="width: 100%"
                        size="small"
                        v-model="form.end_gangguan"
                        :picker-options="pickerOptions2"
                        type="datetime"
                        format="yyyy-MM-dd HH:mm"
                        value-format="yyyy-MM-dd HH:mm:ss"
                        :placeholder="trans('tickets.form.select date time')"
                      >
                      </el-date-picker>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item
                      :label="trans('tickets.form.messages')"
                      class="el-form-item"
                      prop="messages"
                    >
                      <el-input
                        type="textarea"
                        :rows="3"
                        style="width: 100%"
                        :placeholder="trans('tickets.form.please input')"
                        v-model="form.messages"
                      >
                      </el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <div class="input-field">
                      <label class="active">{{
                        trans("tickets.form.upload image")
                      }}</label>
                      <div
                        class="drag-drop-image"
                        style="padding-top: 0.5rem"
                      ></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <el-form-item>
                  <el-button
                    type="primary"
                    @click="onSubmit('form')"
                    :loading="loading"
                  >
                    {{ trans("core.save") }}
                  </el-button>
                  <el-button @click="$router.go(-1)">
                    {{ trans("core.button.cancel") }}
                  </el-button>
                </el-form-item>
              </div>
            </Card>
          </el-form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import Form from "form-backend-validation";
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [Toast, PermissionsHelper],
  data() {
    return {
      data: null,
      form: {
        pelanggan: null,
        start_gangguan: null,
        end_gangguan: null,
        messages: null,
        attachments: [],
      },
      form_data: new Form(),
      loading: false,
      pelanggans: [],
      rules: {
        required_field: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
        ],
      },
    };
  },
  methods: {
    initDragAndDropImage() {
      $(".drag-drop-image").imageUploader({
        imagesInputName: "attachments",
        vmodel: this.form.attachments,
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
              this.loading = true;
              this.form_data = new Form(this.form);
              this.form_data
                .post(route("admin.api.ticket.create"))
                .then((response) => {
                  // this.loading = false;
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
                  this.$router.push({ name: "admin.ticket.sla.index" });
                })
                .catch((error) => {
                  this.loading = false;
                  this.Toast({
                    icon: "error",
                    title: "error",
                  });
                });
            }
          });
        }
      });
    },
    fetchDataPelanggan() {
      let company_id = this.user_company.company_id;
      const properties = {
        params: {
          company_id: company_id,
          type: "pelanggan aktif",
        },
      };
      axios
        .get(route("api.pelanggan.list.all"), _.merge(properties))
        .then((response) => {
          let data = response.data;
          this.pelanggans = data;
        });
    },
  },
  mounted() {
    this.initDragAndDropImage();
    this.fetchDataPelanggan();
  },
  created() {
    window.showGambarFancyBox = (gambar) => {
      $.fancybox([gambar], {
        type: "image",
      });
    };
  },
  computed: {
    pickerOptions: function() {
      let vm = this;
      return {
        disabledDate(time) {
          if (vm.form.end_gangguan != null) {
            return (
              time.getTime() > Date.now() ||
              time.getTime() > new Date(vm.form.end_gangguan)
            );
          } else {
            return time.getTime() > Date.now();
          }
        },
      };
    },
    pickerOptions2: function() {
      let vm = this;
      return {
        disabledDate(time) {
          let get_minutes = new Date(vm.form.start_gangguan).getMinutes();
          let get_getHours = new Date(vm.form.start_gangguan).getHours();
          let d = new Date(vm.form.start_gangguan);

          if (vm.form.start_gangguan != null) {
            if (get_minutes === 0 && get_getHours === 0) {
              d.setDate(d.getDate());
            } else {
              d.setDate(d.getDate() - 1);
            }
            return (
              time.getTime() > new Date(vm.form.created_at) ||
              time.getTime() < d
            );
          } else {
            return time.getTime() > new Date(vm.form.created_at);
          }
        },
      };
    },
  },
};
</script>
