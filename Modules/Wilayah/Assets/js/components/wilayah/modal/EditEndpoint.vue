<template>
  <bs-modal id="modal-form-odp" @onClose="onClose">
    <div slot="header">{{ trans("endpoint.edit resource") }}</div>
    <el-form
      :model="form_edit_odp"
      ref="form_edit_odp"
      v-loading="formIsLoading"
    >
      <el-form-item
        :label="trans('endpoint.form.name')"
        prop="nama_end_point"
        :rules="rules.nama_end_point"
        @keyup.native="checkEndpointName"
      >
        <el-input
          v-model="form_edit_odp.nama_end_point"
          size="small"
        ></el-input>
      </el-form-item>
      <el-form-item
        :label="trans('endpoint.form.type')"
        prop="tipe"
        :rules="rules.tipe"
      >
        <el-select
          v-model="form_edit_odp.tipe"
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
            <el-input v-model="form_edit_odp.address" size="small"></el-input>
          </el-form-item>
        </div>
      </div>
    </el-form>

    <div slot="footer">
      <el-button
        type="primary"
        size="small"
        @click="saveEndpoint"
        :loading="buttinSaveIsLoading"
        icon="el-icon-upload"
      >
        {{ trans("core.save") }}
      </el-button>

      <el-button size="small" data-dismiss="modal">
        {{ trans("core.button.close") }}
      </el-button>
    </div>
  </bs-modal>
</template>

<script>
import axios from "axios";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [Toast],
  props: {
    endpoint_index: {
      default: null,
    },
    endpoint_selected: {
      default: () => {
        return {
          nama_end_point: "",
          tipe: "",
          address: "",
        };
      },
    },
    endpoint: {
      default: () => {
        return [];
      },
    },
    location: {
      default: () => {
        return {
          latitude: 0,
          longitude: 0,
          address: "",
        };
      },
    },
    wilayah_id: {
      default: null,
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
      formIsLoading: false,
      isValidName: false,
      buttinSaveIsLoading: false,

      form_edit_odp: {
        nama_end_point: "",
        tipe: "",
        address: "",
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
      rules: {
        nama_end_point: [
          {
            required: true,
            validator: validationName,
            trigger: "change",
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
    };
  },
  methods: {
    checkEndpointName: _.debounce(function() {
      if (this.findEndpointByName(this.form_edit_odp.nama_end_point)) {
        this.isValidName = false;
      } else {
        this.isValidName = true;
      }
      this.$refs["form_edit_odp"].validateField("nama_end_point");
    }, 500),
    findEndpointByName(name) {
      return this.endpoint.find((item, index) => {
        console.log(
          index,
          this.endpoint_index,
          item.nama_end_point === name && index != this.endpoint_index
        );
        return item.nama_end_point === name && index != this.endpoint_index;
      });
    },
    saveEndpoint() {
      var vm = this;
      this.$refs["form_edit_odp"].validate((valid) => {
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
            if (valid) {
              $("#modal-form-odp").modal("hide");
              vm.$emit("handleSuccess", this.form_edit_odp, this.endpoint_index);
              setTimeout(() => {
                vm.$emit("handleClose", true);
              }, 500);
            }
          }
        });
      });
    },
    onClose() {
      $("#modal-form-odp").modal("hide");
    },
  },
  watch: {
    endpoint_selected: function() {
      this.form_edit_odp = this.endpoint_selected;
    },
    endpoint_index: function(value) {
        this.checkEndpointName()
    }
  },
  mounted() {
      
  }
};
</script>

<style></style>
