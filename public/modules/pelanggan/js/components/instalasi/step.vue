<template>
  <div style="font-size:14px">
    <PerubahanHarga
      @ajukanFalse="ajukanFalse"
      :statusStep="status_instalasi_props"
    />

    <CardDetailPelangganInstalasi
      v-bind:ajukanUlangFromStep="ajukanUlang"
      @changeStatusStep="changeStatusStep"
      :statusStep="status_instalasi_props"
    />
    <JadwalInstalasi v-if="pelanggan_id != null" :pelanggan_id="pelanggan_id" />

    <HasilInstalasiForISP v-if="user_roles.name == 'Admin ISP'" />
    <HasilInstalasiForOSP v-if="user_roles.name == 'Admin OSP'" />
    <!-- <HasilInstalasi @status_instalasi="status_instalasi" 
    :disableButton="disable_form_hasil_instalasi"/> -->
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
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";
import step_instalasi from "../../mixins/step_instalasi";
export default {
  mixins: [step_instalasi, PermissionsHelper],
  data() {
    return {};
  },
};
</script>

<style>
p {
  width: 23vmax;
}

tr {
  vertical-align: top;
}

.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}

.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}

.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>
