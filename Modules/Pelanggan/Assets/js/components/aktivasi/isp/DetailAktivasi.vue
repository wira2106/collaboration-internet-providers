<template>
  <Card class="card card-outline-info">
    <div class="card-header" style="border-bottom: 0px">
      <div class="card-actions text-white">
        <a data-action="collapse" class="mx-2 text-white"
          ><i class="ti-minus"></i
        ></a>
      </div>
      <h5 class="text-white">
        {{ trans("activations.title.detail aktivasi") }}
      </h5>
    </div>
    <div class="card-body collapse show">
      <!-- layout ISP -->
      <div class="row">
        <div class="col-md-12" v-if="alertApprove">
          <div
            class="alert alert-warning alert-dismissible fade show"
            role="alert"
          >
            <strong>{{ trans("activations.title.menunggu approve") }} </strong>
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
          <table class="table" style="width: 100%">
            <tr class="py-1">
              <th style="width: 200px">
                {{ trans("activations.form.noc") }}
              </th>
              <td style="width: 10px">:</td>
              <td v-if="form.noc.length > 0">
                <span
                  class="span_noc"
                  v-for="(item, index) in form.noc_aktivasi"
                  :key="index"
                >
                  {{ item.name }}
                </span>
              </td>
              <td v-else>
                {{ trans("activations.title.belum") }}
              </td>
            </tr>
            <tr class="py-5">
              <th style="width: 200px">
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
              <th style="width: 200px">
                {{ trans("activations.form.status.title") }}
              </th>
              <td style="width: 10px">:</td>
              <td>
                {{ form.status == null ? "Proses" : form.status }}
                &nbsp;&nbsp;
              </td>
            </tr>
            <tr class="py-5">
              <th style="width: 200px">
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
                form.keterangan_unapprove != null && form.status == 'unapprove'
              "
            >
              <th style="width: 200px">
                {{ trans("activations.title.alasan unapprove") }}
              </th>
              <td style="width: 10px">:</td>
              <td>
                {{ form.keterangan_unapprove }}
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div
      class="card-footer justify-content-center d-flex"
      v-if="
        !(form.status_step == 'aktif' || form.status != 'selesai') ||
          (user_roles.name == 'Admin OSP' && form.aktivasi_id)
      "
    >
      <el-button
        type="primary"
        @click="goToEdit"
        icon="el-icon-edit"
        v-if="user_roles.name == 'Admin OSP' && form.aktivasi_id"
      >
        {{ trans("activations.button.ubah aktivasi") }}
      </el-button>
      <template v-if="user_roles.name == 'Admin ISP'">
        <el-button
          type="danger"
          v-if="!(form.status_step == 'aktif' || form.status != 'selesai')"
          @click="tolakApproveInstalasi()"
        >
          {{ trans("activations.title.unapprove") }}
        </el-button>

        <el-button
          type="primary"
          v-if="!(form.status_step == 'aktif' || form.status != 'selesai')"
          @click="approveInstalasi()"
        >
          {{ trans("activations.title.approve") }}
        </el-button>
      </template>
    </div>
  </Card>
</template>

<script>
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";

export default {
  mixins: [PermissionsHelper],
  props: ["form", "alertApprove"],
  methods: {
    goToEdit() {
      this.$emit("onEdit", true);
    },
    approveInstalasi() {
      this.$emit("approveInstalasi");
    },
    tolakApproveInstalasi() {
      this.$emit("tolakApproveInstalasi");
    },
  },
};
</script>

<style></style>
