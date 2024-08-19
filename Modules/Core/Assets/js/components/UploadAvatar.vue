<template>
  <el-upload
    class="avatar-uploader"
    v-bind:action="action"
    :show-file-list="false"
    :on-success="onSuccess"
    :before-upload="beforeUpload"
  >
    <img
      v-if="image"
      :src="image"
      class="avatar"
      onerror="this.src = window.location.origin + '/uploadgambar/nologo.png'"
    />
    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
  </el-upload>
</template>

<style>
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
  height: auto !important;
  line-height: 178px;
  text-align: center;
}
.avatar {
  width: 100%;
  height: auto !important;
  display: block;
}
</style>

<script>
import axios from "axios";
export default {
  props: {
    image: { default: null },
    onSuccess: { default: null },
    beforeUpload: { default: null },
    onError: { default: null },
  },
  data() {
    return {
      action: window.location.origin + "/uploadgambar/nologo.png",
    };
  },
};
</script>
