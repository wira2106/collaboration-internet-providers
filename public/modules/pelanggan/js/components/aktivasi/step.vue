<template>
  <div>
    <Card-detail @changeStatusStep="fetchDataAktivasi()" />
    <el-form
      ref="form"
      :model="form"
      label-width="120px"
      label-position="top"
      v-loading.body="loading"
      @keydown="form.errors.clear($event.target.name)"
      :rules="rules"
      :disabled="disable_form || this.form.status == 'selesai'"
    >
      <Card class="card">
        <div class="card-header" style="border-bottom: 0px">
          <div class="card-actions">
            <a data-action="collapse"><i class="ti-minus"></i></a>
          </div>
          <h5 @click="fetchDataAktivasi">
            {{ trans("activations.title.form") }}
          </h5>
        </div>
        <div class="card-body collapse show">
          <div class="row" v-if="user_roles.name == 'Admin OSP'">
            <div
              class="col-md-12"
              v-if="
                form.keterangan_unapprove != null &&
                  form.status == 'unapprove' &&
                  status_checkbox == false &&
                  status_aktivasi_now != null
              "
            >
              <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
              >
                <strong
                  >{{ trans("activations.title.aktivasi unapprove") }}
                </strong>
                <br />
                {{ trans("activations.title.alasan unapprove") }} :
                {{ form.keterangan_unapprove }}
                <button
                  type="button"
                  class="close"
                  data-dismiss="alert"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>

            <div
              class="col-md-12"
              v-if="status_checkbox == true && form.status_step != 'aktif' && status_aktivasi_now != null"
            >
              <div
                class="alert alert-warning alert-dismissible fade show"
                role="alert"
              >
                <strong
                  >{{ trans("activations.title.menunggu approve") }}
                </strong>
                <button
                  type="button"
                  class="close"
                  data-dismiss="alert"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>

            <div class="col-md-12">
              <el-form-item
                :label="trans('activations.form.tgl aktivasi')"
                prop="tgl_aktivasi"
                :rules="rules.required_field"
              >
                <el-date-picker
                  v-model="form.tgl_aktivasi"
                  type="datetime"
                  value-format="yyyy-MM-dd HH:mm:ss"
                  placeholder="Pick a day"
                  :picker-options="pickerOptions"
                  size="small"
                >
                </el-date-picker>
              </el-form-item>
            </div>
            <div class="col-md-12">
              <el-form-item
                :label="trans('activations.form.noc')"
                prop="noc"
                :rules="rules.nocRules"
              >
                <el-select
                  v-if="select_noc"
                  v-model="form.noc"
                  size="small"
                  @change="changeTeknisi"
                  filterable
                  multiple
                >
                  <el-option label="+ tambah NOC" value="new"></el-option>
                  <el-option
                    v-for="(item, index) in this.list_noc"
                    :key="index"
                    :label="item"
                    :value="item"
                  >
                  </el-option>
                </el-select>
                <input
                  v-else
                  type="text"
                  class="form-control form-control-sm"
                />
              </el-form-item>
            </div>
            <div class="col-md-12">
              <el-form-item
                :label="trans('activations.form.keterangan')"
                prop="keterangan"
              >
                <el-input
                  ref="keterangan"
                  style="width: 100%"
                  type="textarea"
                  :rows="4"
                  :placeholder="trans('activations.placeholder.keterangan')"
                  v-model="form.keterangan"
                >
                </el-input>
              </el-form-item>
            </div>
            <div class="col-md-12">
              <el-tooltip
                :content="trans('core.tooltip.tandai', { tandai: 'aktivasi' })"
                placement="top"
              >
                <el-checkbox size="mini" v-model="status_checkbox">{{
                  trans("activations.form.checkbox")
                }}</el-checkbox>
              </el-tooltip>
            </div>
            <div class="col-md-12">
              <hr />
            </div>
            <div class="col-md-12">
              <el-form-item>
                <el-button
                  type="primary"
                  @click="onSubmit('form')"
                  :loading="loading"
                >
                  {{ trans("core.save") }}
                </el-button>
              </el-form-item>
            </div>
          </div>

          <!-- layout ISP -->
          <div class="row" v-else>
            <table class="table" style="width: 100%">
              <tr class="py-1">
                <th style="width: 250px">
                  {{ trans("activations.form.noc") }}
                </th>
                <td style="width: 10px">:</td>
                <td v-if="form.noc.length > 0">
                  <span
                    class="span_noc"
                    v-for="(item, index) in form.noc"
                    :key="index"
                  >
                    {{ item }}
                  </span>
                </td>
                <td v-else>
                  {{ trans("activations.title.belum") }}
                </td>
              </tr>
              <tr class="py-5">
                <th style="width: 250px">
                  {{ trans("activations.form.tgl aktivasi") }}
                </th>
                <td style="width: 10px">:</td>
                <td>
                  {{
                    !(form.tgl_aktivasi == "" || form.tgl_aktivasi == null)
                      ? form.tgl_aktivasi
                      : trans("activations.title.belum")
                  }}
                </td>
              </tr>
              <tr class="py-5">
                <th style="width: 250px">
                  {{ trans("activations.form.status.title") }}
                </th>
                <td style="width: 10px">:</td>
                <td>
                  {{ form.status == null ? "Proses" : form.status }}
                  &nbsp;&nbsp;
                </td>
              </tr>
              <tr class="py-5">
                <th style="width: 250px">
                  {{ trans("activations.form.keterangan") }}
                </th>
                <td style="width: 10px">:</td>
                <td>
                  {{
                    !(form.keterangan == "" || form.keterangan == null)
                      ? form.keterangan
                      : "-"
                  }}
                </td>
              </tr>
              <tr
                class="py-5"
                v-if="
                  form.keterangan_unapprove != null &&
                    form.status == 'unapprove'
                "
              >
                <th style="width: 250px">
                  {{ trans("activations.title.alasan unapprove") }}
                </th>
                <td style="width: 10px">:</td>
                <td>
                  {{ form.keterangan_unapprove }}
                </td>
              </tr>
              <tr>
                <td colspan="3">
                  <button
                    type="button"
                    :hidden="
                      form.status_step == 'aktif' || form.status != 'selesai'
                    "
                    @click="approveInstalasi()"
                    class="btn btn-primary btn-sm"
                  >
                    {{ trans("activations.title.approve") }}
                  </button>

                  <button
                    type="button"
                    :hidden="
                      form.status_step == 'aktif' || form.status != 'selesai'
                    "
                    @click="tolakApproveInstalasi()"
                    class="btn btn-danger btn-sm"
                  >
                    {{ trans("activations.title.unapprove") }}
                  </button>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </Card>
    </el-form>

    <div class="card">
      <div class="card-body">
        <el-button type="danger" @click="$router.go(-1)">
          {{ trans("core.back") }}
        </el-button>
      </div>
    </div>
  </div>
</template>
<script>
import step_aktivasi from "../../mixins/step_aktivasi";
export default {
  mixins: [step_aktivasi],
};
</script>
<style type="text/css">
.el-form-item__label {
  margin-bottom: 0px !important;
}
.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}
.span_noc {
  border: 1px solid #b0b0ff;
  border-radius: 5px;
  padding: 0px 5px;
  margin-right: 2px;
}
</style>
