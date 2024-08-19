<template>
  <div>
    <perubahan-harga @ajukanFalse="ajukanFalse" :statusStep="form.status" />
    <CardDetailPelangganInstalasi
      @changeStatusStep="fetchDataAktivasi()"
      v-bind:ajukanUlangFromStep="ajukanUlang"
    />

    <Card
      class="card card-outline-info"
      v-if="form_edit && user_roles.name == 'Admin OSP'"
    >
      <div class="card-header" style="border-bottom: 0px">
        <div class="card-actions text-white">
          <a data-action="collapse"><i class="ti-minus"></i></a>
        </div>
        <h5 @click="fetchDataAktivasi" class="text-white">
          {{ trans("activations.title.form") }}
        </h5>
      </div>
      <div class="card-body collapse show">
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

            <div class="col-md-12" v-if="alertApprove">
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
                  @change="changeNOC"
                  filterable
                  multiple
                >
                  <el-option
                    :label="'+ ' + trans('installations.title.tambah noc')"
                    value="new"
                  >
                  </el-option>
                  <el-option
                    v-for="(item, index) in list_noc"
                    :key="index"
                    :label="item.full_name"
                    :value="item.user_id"
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
          </div>
        </el-form>
      </div>
      <div class="card-footer justify-content-center d-flex">
        <el-button
          icon="el-icon-edit"
          type="danger"
          v-if="user_roles.name == 'Admin OSP' && form.aktivasi_id"
          @click="editButton(false)"
        >
          {{ trans("activations.button.batal edit aktivasi") }}
        </el-button>
        <el-button
          type="primary"
          @click="onSubmit('form')"
          :loading="loading"
          icon="el-icon-upload"
        >
          {{ trans("activations.button.simpan aktivasi") }}
        </el-button>
      </div>
    </Card>

    <DetailAktivasi
      :form="form"
      v-if="!form_edit"
      @onEdit="editButton"
      @approveInstalasi="approveInstalasi"
      @tolakApproveInstalasi="tolakApproveInstalasi"
      :alertApprove="alertApprove"
    />
    <div class="card">
      <div class="card-body">
        <el-button type="danger" @click="$router.go(-1)">
          {{ trans("core.back") }}
        </el-button>
      </div>
    </div>

    <Cart
      :action="false"
      v-model="carts"
      @submit="bayar_biaya_pelanggan"
      id="cart-aktivasi"
      :loading="loadingBayarPelanggan"
    />
    <ModalRatingPelanggan
      @approve="onApprove"
      :status="this.form.status"
      :loading="loading"
    />
  </div>
</template>
<script>
import step_aktivasi from "../../mixins/step_aktivasi";
import CardDetailPelangganInstalasi from "../instalasi/components/CardDetailPelangganInstalasi.vue";
import PerubahanHarga from "../instalasi/components/PerubahanHarga.vue";
import DetailAktivasi from "./isp/DetailAktivasi.vue";
import ModalRatingPelanggan from "./ModalRatingPelanggan.vue";

export default {
  mixins: [step_aktivasi],
  components: {
    DetailAktivasi,
    CardDetailPelangganInstalasi,
    ModalRatingPelanggan,
    PerubahanHarga,
  },
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
