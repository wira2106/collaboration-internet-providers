<template>
  <div>
    <p style="margin-bottom:0px"><b>latitude</b> : {{ location.latitude }}</p>
    <p><b>longitude</b> : {{ location.longitude }}</p>
    <p style="margin-bottom:0px" class="d-flex justify-content-center">
      <el-button-group>
        <el-button
          type="primary"
          icon="el-icon-plus"
          circle
          @click="openFormEndpoint"
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

    <div
      class="modal fade"
      id="addEndpoint"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      aria-labelledby="addEndpointLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addEndpointLabel">
              {{ trans("endpoint.create resource") }}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <el-form :model="region" ref="region">
              <el-form-item
                :label="trans('endpoint.form.name')"
                prop="nama_end_point"
                :rules="rules.nama_end_point"
                @keyup.native="checkEndpointName"
              >
                <el-input
                  v-model="region.nama_end_point"
                  size="small"
                ></el-input>
              </el-form-item>
              <el-form-item
                :label="trans('endpoint.form.type')"
                prop="tipe"
                :rules="rules.tipe"
              >
                <el-select
                  v-model="region.tipe"
                  placeholder="Select"
                  size="small"
                >
                  <el-option
                    v-for="item in optionType"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  >
                  </el-option>
                </el-select>
              </el-form-item>

              <div class="row">
                <div class="col-12 controls">
                  <el-form-item
                    :label="trans('endpoint.form.address')"
                    prop="address"
                    :rules="rules.address"
                  >
                    <el-input v-model="region.address" size="small"></el-input>
                  </el-form-item>
                </div>
              </div>
            </el-form>
          </div>
          <div class="modal-footer">
            <el-button
              size="small"
              icon="el-icon-upload"
              type="primary"
              :loading="btnSaveLoading"
              @click="saveEndpoint('addEndpoint')"
              >Save</el-button>
            <el-button size="small" data-dismiss="modal">
              Close
            </el-button>
          </div>
        </div>
      </div>
    </div>
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
  },
  data() {
    let validationName = (rules, value, cb) => {
      if (value == "")
        return cb(
          new Error(
            this.trans("endpoint.validation.required", { field: "name" })
          )
        );
      if (!this.isValidName)
        return cb(new Error(this.trans("endpoint.validation.name")));
      cb();
    };
    return {
      isValidName: false,
      btnSaveLoading: false,
      region: {
        nama_end_point: "",
        tipe: "",
        address: this.location.address,
      },
      rules: {
        nama_end_point: [
          {
            required: true,
            validator: validationName,
            trigger: "blur",
          },
        ],
        tipe: [
          {
            required: true,
            message: this.trans("endpoint.validation.required", {
              field: "type",
            }),
            trigger: "change",
          },
        ],
        address: [
          {
            required: true,
            message: this.trans("endpoint.validation.required", {
              field: "address",
            }),
            trigger: "blur",
          },
        ],
      },
      optionType: [
        {
          value: "ODP",
          label: "ODP",
        },
        {
          value: "JB",
          label: "Joint Box",
        },
        {
          value: "Fixing Slack",
          label: "Fixing Slack",
        },
      ],
    };
  },
  methods: {
    openFormEndpoint() {
      // console.log('executed')
      $("#addEndpoint").modal("show");
      this.$emit("addButtonClicked", true);
      // this.handleClosePopover(false);
    },
    saveEndpoint() {
      this.$refs["region"].validate((valid) => {
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
              this.btnSaveLoading = true;
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
                  this.btnSaveLoading = false;
                })
                .catch((err) => {
                  if (err.response.status === 417) {
                    return this.Toast({
                      icon: "info",
                      title: err.response.data.message,
                    });
                  }
                  this.Toast({
                    icon: "error",
                    title: "something error",
                  });
                });
            }
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
