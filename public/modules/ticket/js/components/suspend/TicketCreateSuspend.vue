<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("tickets.title.tickets") }}</h3>
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
                        @change="change_pelanggan($event)"
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
                  <div class="col-md-12">
                    <el-form-item
                      :label="trans('tickets.form.tgl suspend')"
                      class="el-form-item"
                      prop="tgl_suspend"
                      :rules="rules.required_field"
                    >
                      <el-date-picker
                        style="width: 100%"
                        size="small"
                        v-model="form.tgl_suspend"
                        :picker-options="pickerOptions"
                        type="datetime"
                        format="yyyy-MM-dd"
                        value-format="yyyy-MM-dd"
                        :placeholder="trans('tickets.form.select date time')"
                      >
                      </el-date-picker>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item
                      :label="trans('tickets.form.alasan')"
                      class="el-form-item"
                      prop="alasan"
                      :rules="rules.required_field"
                    >
                      <el-input
                        type="textarea"
                        :rows="3"
                        width="100%"
                        :placeholder="
                          trans('tickets.placeholder.alasan suspend')
                        "
                        v-model="form.alasan"
                      >
                      </el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-divider></el-divider>
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
        tgl_suspend: null,
        messages: null,
        alasan: null,
        attachments: [],
      },
      form_data: new Form(),
      loading: false,
      pelanggan_open_suspend: [],
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
    change_pelanggan(pelanggan_id) {
      var pelanggan = this.pelanggan_open_suspend;
      pelanggan.find((pelanggan) => {
        if (pelanggan_id == pelanggan.pelanggan_id) {
          Swal.fire({
            title: this.trans("ticketsSuspend.alert.pelanggan sudah terdaftar"),
            text: "",
            icon: "warning",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            confirmButtonText: this.trans("ticketsSuspend.button.lihat"),
          }).then((result) => {
            if (result.value == true) {
              this.$router.push({
                name: "admin.ticket.suspend.session",
                params: {
                  id: pelanggan.ticket_id,
                },
              });
            } else {
              this.form.pelanggan = null;
            }
          });
        }
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
                .post(route("admin.api.ticket.suspend.create"))
                .then((response) => {
                  // this.loading = false;
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
                  this.$router.push({ name: "admin.ticket.suspend.index" });
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
    fetchDataPelangganOpenSuspen() {
      //meload data pelanggan yang masih berstatus open suspend
      let company_id = this.user_company.company_id;
      const properties = {
        params: {
          company_id: company_id,
          type: "pelanggan aktif",
        },
      };
      axios
        .get(route("api.pelanggan.list.open.suspend"), _.merge(properties))
        .then((response) => {
          this.pelanggan_open_suspend = response.data;
        });
    },
    fetch() {
      //berjalan ketika pelanggan di pilih pada list pelanggan, klik suspend akan meload script ini agar data select
      //pelanggan langsung terpilih di select pelanggan
      var pelanggan = this.$route.params.pelanggan;
      if (typeof pelanggan != "undefined") {
        axios
          .get(
            route("admin.api.ticket.suspend.create.with.params", {
              id: pelanggan,
            })
          )
          .then((response) => {
            if (response.data == "") {
              this.$router.push({
                name: "admin.ticket.suspend.create",
              });
            }
            this.form.pelanggan = response.data.pelanggan_id;
          });
      }
    },
  },
  mounted() {
    this.fetch();
    this.fetchDataPelangganOpenSuspen();
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
      return {
        disabledDate(time) {
          var today = new Date();
          var tomorrow = new Date(today);
          var yesterday = new Date(tomorrow.setDate(tomorrow.getDate() - 1));
          return yesterday > time.getTime(today);
        },
      };
    },
  },
};
</script>
