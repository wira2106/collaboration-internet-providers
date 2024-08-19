<template>
  <div>
    <div style="margin-bottom: 10px;width: 162px;text-align: center;">
      <p>
        Tentukan posisi presale yang akan ditambah. Jika sudah, klik tombol
        tambah(+). Ingin batal klik tombol (x)
      </p>
    </div>
    <p style="margin-bottom:0px" class="d-flex justify-content-center">
      <el-button-group>
        <el-button
          type="primary"
          icon="el-icon-plus"
          circle
          @click="addButtonClick"
        >
        </el-button>
        <el-button
          type="danger"
          icon="el-icon-close"
          circle
          @click="handleClose(true)"
        >
        </el-button>
      </el-button-group>
    </p>
  </div>
</template>

<script>
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import _ from "lodash";
import axios from "axios";

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
    wilayah_id: {
      default: null,
      required: true,
    },
    company_id: {
      default: null,
      required: true,
    },
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
    map: {
      default: null,
    },
    endpoint: {
      default: null,
    },
  },
  data() {
    return {
      isValidName: false,
      region: {
        nama_end_point: "",
        tipe: "",
        address: this.location.address,
      },
    };
  },
  methods: {
    addButtonClick() {
      this.$emit("addButtonClicked", true);
    },
    saveEndpoint() {
      this.$refs["region"].validate((valid) => {
        if (valid) {
          let properties = {
            lat_end_point: this.location.latitude,
            lon_end_point: this.location.longitude,
            wilayah_id: this.wilayah_id,
          };

          axios
            .post(
              route("api.presale.endpoint.store"),
              _.merge(this.region, properties)
            )
            .then((response) => {
              this.Toast({
                icon: "success",
                title: response.data.message,
              });
              $("#addEndpoint").modal("hide");
              setTimeout(() => {
                this.handleClose();
              }, 500);
              this.$emit("handleSuccess", response.data.data);
            })
            .catch((err) => {
              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title"),
              });
            });
        }
      });
    },
    checkEndpointName: _.debounce(function() {
      let properties = {
        company_id: this.company_id,
        name: this.region.nama_end_point,
      };

      if (!this.region.nama_end_point) return (this.isValidName = false);

      axios
        .post(route("api.presale.endpoint.check-name"), properties)
        .then((response) => {
          this.isValidName = response.data.valid;
          this.$refs["region"].validateField("nama_end_point");
        });
    }, 500),
  },
  watch: {
    location: function(value) {
      this.region.address = value.address;
    },
  },
};
</script>

<style></style>
