<template>
  <div>
      <p style="margin-bottom:0px"><b>latitude</b> : {{location.latitude}}</p>
      <p><b>longitude</b> : {{location.longitude}}</p>
      <p style="margin-bottom:0px" class="d-flex justify-content-center">
        <el-button-group>
          <el-button
          type="primary"
          icon="fa fa-check"
          circle
          @click="handlePositionFixed"
          >
          </el-button>
          <el-button
          type="danger"
          icon="el-icon-close"
          circle
          @click="handleClose"
          >
          </el-button>
        </el-button-group>
      </p>
  </div>
</template>

<script>
export default {
    props: {
        btnSaveLoading:false,
        location: {
            default: {
                latitude: 0,
                longitude: 0,
                address:''
            }
        },
    },
    methods: {
        handleClose(params) {
            this.$emit('handleClose', params);
        },
        handlePositionFixed(params) {
            this.$emit('handlePositionFixed', params)
        }
    }
}
</script>

<style>

</style>