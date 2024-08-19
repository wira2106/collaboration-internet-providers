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
            <div class="col-md-6">
              <el-form-item
                :label="trans('tickets.form.start mati')"
                class="el-form-item mb-0"
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
                  :readonly="
                    form.status == 'closed' || edit_form == false ? true : false
                  "
                >
                </el-date-picker>
              </el-form-item>
            </div>
            <div class="col-md-6">
              <el-form-item
                :label="trans('tickets.form.end mati')"
                class="el-form-item mb-0"
                prop="end_gangguan"
                :rules="rules.required_field"
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
                  :readonly="
                    form.status == 'closed' || edit_form == false ? true : false
                  "
                >
                </el-date-picker>
              </el-form-item>
            </div>
            <div
              class="col-md-12"
              v-if="form.end_gangguan != null && form.start_gangguan != null"
            >
              <div class="row">
                <div class="col-md-12">
                  <hr />
                </div>
                <div class="col-md-6">
                  <table style="width:100%;font-size:11px;">
                    <tr>
                      <td style="width:100px;">
                        {{ trans("tickets.title.approve isp by") }}
                      </td>
                      <td style="width:5px;">:</td>
                      <td>{{ form.user_approve_isp }}</td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6">
                  <table style="width:100%;font-size:11px;">
                    <tr>
                      <td style="width:100px;">
                        {{ trans("tickets.title.approve osp by") }}
                      </td>
                      <td style="width:5px;">:</td>
                      <td>{{ form.user_approve_osp }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div
              class="col-md-12 mt-3"
              v-if="data.end_gangguan != null && form.status == 'open'"
            >
              <div
                class="alert alert-warning"
                v-if="form.accept_osp_by == null || form.accept_isp_by == null"
              >
                <div
                  v-if="
                    (user_company.type == 'osp' &&
                      form.accept_osp_by == null) ||
                      (user_company.type == 'isp' && form.accept_isp_by == null)
                  "
                >
                  {{ trans("ticketsSla.alert.approve") }}
                </div>
                <div v-else>
                  {{
                    trans("ticketsSla.alert.menunggu_approve", {
                      type: user_company.type == "osp" ? "ISP" : "OSP",
                    })
                  }}
                </div>
              </div>
            </div>
            <div class="col-md-12" v-if="form.status != 'closed'">
              <div class="row">
                <div class="col-md-12">
                  <hr />
                </div>
                <div class="col-md-12" v-if="user_company.type != null">
                  <div
                    class="pull-right"
                    v-if="
                      form.accept_isp_by == null || form.accept_osp_by == null
                    "
                  >
                    <div class="btn-group" v-if="!edit_form">
                      <button
                        v-if="
                          (user_company.type == 'isp' &&
                            form.accept_isp_by == null &&
                            data.end_gangguan != null) ||
                            (user_company.type == 'osp' &&
                              form.accept_osp_by == null &&
                              data.end_gangguan != null)
                        "
                        type="button"
                        class="btn btn-success btn-sm mr-1"
                        @click="approveTanggal()"
                      >
                        {{ trans("tickets.button.approve tanggal") }}
                      </button>
                      <button
                        type="button"
                        class="btn btn-warning btn-sm"
                        @click="ubahTanggal()"
                      >
                        Ubah tanggal
                      </button>
                    </div>
                    <div class="btn-group" v-else>
                      <button
                        type="button"
                        class="btn btn-primary btn-sm mr-1"
                        @click="submitForm('form')"
                      >
                        {{ trans("core.save") }}
                      </button>
                      <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="batalUbahTanggal()"
                      >
                        Batal
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
      edit_form: false,
      form: {
        ticket_id: null,
        start_gangguan: null,
        end_gangguan: null,
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
                .post(route("admin.api.ticket.session.time.update"))
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
                    title: this.trans("core.error 500 title"),
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
              route("admin.api.ticket.session.time.approve", {
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
              this.$emit("reloadApproveSLA");
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
    },
    ubahTanggal() {
      this.edit_form = true;
    },
    batalUbahTanggal() {
      var data = Object.assign({}, this.data);
      this.edit_form = false;
      this.form = data;
    },
  },
  watch: {
    data: {
      deep: true,
      handler() {
        this.form = this.data;
      },
    },
  },
  mounted() {
    var data = Object.assign({}, this.data);
    this.form = data;
  },
  computed: {
    pickerOptions: function() {
      let vm = this;
      return {
        disabledDate(time) {
          if (vm.form.end_gangguan != null) {
            return (
              time.getTime() > new Date(vm.form.created_at) ||
              time.getTime() > new Date(vm.form.end_gangguan)
            );
          } else {
            return time.getTime() > new Date(vm.form.created_at);
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
            if (get_minutes == 0 && get_getHours == 0) {
              d.setDate(d.getDate());
            } else {
              d.setDate(d.getDate() - 1);
            }
            return (
              // time.getTime() < new Date(vm.form.created_at) ||
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
<style>
.el-form-item__label {
  margin-bottom: -5px;
}
</style>
