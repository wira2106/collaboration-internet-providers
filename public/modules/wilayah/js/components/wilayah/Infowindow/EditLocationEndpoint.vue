<template>
  <div>
    <p style="margin-bottom:0px"><b>latitude</b> : {{ location.latitude }}</p>
    <p><b>longitude</b> : {{ location.longitude }}</p>
    <p style="margin-bottom:0px" class="d-flex justify-content-center">
      <el-button-group>
        <el-button
          type="primary"
          icon="fa fa-save"
          circle
          @click="updateLocation"
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
import _ from "lodash";
import TranslationHelper from "../../../../../../Core/Assets/js/mixins/TranslationHelper";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [TranslationHelper, Toast],
  props: {
    btnSaveLoading: false,
    location: {
      default: {
        latitude: 0,
        longitude: 0,
        address: "",
      },
    },
    endpoint_index: { default: null },
    handleClose: {
      default: () => {
        console.log("close function not passed : handleClose");
      },
      required: true,
    },
    handleClosePopover: {
      default: () => {
        console.log("close function not passed : handleClosePopover");
      },
      required: true,
    },
  },
  methods: {
    updateLocation() {
      this.$emit("handleSubmit", true);

      let properties = {
        latitude: this.location.latitude,
        longitude: this.location.longitude,
      };

      this.$emit("updated", properties);
    },
  },
};
</script>

<style></style>
