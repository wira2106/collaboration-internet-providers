<template>
  <div class="col-md-12">
    <el-form
      :model="form"
      :rules="rules"
      ref="form"
      :disabled="user_company.type == null ? true : false"
    >
      <div class="card" style="margin-bottom: 10px" v-loading="loading">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <el-form-item
                label="tanggal suspend"
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
                  :readonly="user_company.type == 'osp'"
                >
                </el-date-picker>
              </el-form-item>
            </div>
            <div class="col-md-12" v-if="form.status == 'open'">
              <div
                class="alert alert-warning"
                v-if="form.accept_osp_by == null"
              >
                <div v-if="user_company.type == 'osp'">
                  {{ trans("ticketsSuspend.alert.approve") }}
                </div>
                <div v-else>
                  {{ trans("ticketsSuspend.alert.menunggu_approve") }}
                </div>
              </div>
            </div>
            <div class="col-md-12" v-if="form.status != 'closed'">
              <div class="row">
                <div class="col-md-12">
                  <hr />
                </div>
                <div class="col-md-12" v-if="user_company.type != null">
                  <div class="pull-right">
                    <div class="btn-group">
                      <button
                        v-if="
                          user_company.type == 'osp' &&
                            form.accept_osp_by == null
                        "
                        type="button"
                        class="btn btn-success btn-sm"
                        @click="approveTanggal()"
                      >
                        {{ trans("tickets.button.approve tanggal") }}
                      </button>
                    </div>
                    <div class="btn-group">
                      <button
                        v-if="
                          user_company.type == 'isp' &&
                            form.accept_osp_by == null
                        "
                        type="button"
                        class="btn btn-primary btn-sm"
                        @click="submitForm('form')"
                      >
                        {{ trans("core.save") }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </el-form>
  </div>
</template>
<script>
import axios from "axios";
import Form from "form-backend-validation";
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [Toast, PermissionsHelper],
  props: ["data"],
  data() {
    return {
      form: {
        ticket_id: null,
        tgl_suspend: null,
      },
      rules: {
        required_field: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
        ],
      },
      loading: false,
    };
  },
  methods: {
    submitForm(formName) {
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
                .post(route("admin.api.ticket.suspend.session.time.update"))
                .then((response) => {
                  this.loading = false;
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
                  this.$emit("saveAcceptSLA");
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
    approveTanggal() {
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
          axios
            .post(
              route("admin.api.ticket.suspend.session.time.approve", {
                ticket_id: this.form.ticket_id,
              })
            )
            .then((response) => {
              this.loading = false;
              this.Toast({
                icon: "success",
                title: response.data.message,
              });
              this.form = response.data.data;
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
    },
  },
  mounted() {
    this.form = this.data;
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
<style>
.el-form-item__label {
  margin-bottom: -5px;
}
</style>
