<template>
  <el-select
    filterable
    v-on:change="handleChange"
    v-bind:value="value"
    :ref="reference"
    :placeholder="trans('perangkats.placeholder.pilih perangkat')"
    size="small"
  >
    <el-option
      :label="'+ ' + trans('perangkats.new perangkat')"
      value="code-new"
    ></el-option>
    <el-option
      v-for="item in perangkats"
      :key="item.perangkat_id"
      :label="item.nama_perangkat"
      :value="item.perangkat_id"
    ></el-option>
  </el-select>
</template>
<script>
import axios from "axios";
import Toast from "../../../../../public/modules/core/js/mixins/Toast";
import TranslationHelper from "../mixins/TranslationHelper";
export default {
  props: {
    value: {
      default: "",
    },
    list_perangkat: {
      default: [],
    },
    reference: {
      default: "perangkat" + Math.random(),
    },
  },
  mixins: [Toast, TranslationHelper],
  data() {
    return {
      perangkats: [],
    };
  },
  methods: {
    handleChange(val) {
      if (val == "code-new") {
        Swal.fire({
          title: this.trans("perangkats.title.enter perangkat"),
          input: "text",
          inputAttributes: {
            autocapitalize: "off",
          },
          showCancelButton: true,
          confirmButtonText: "Simpan",
          showLoaderOnConfirm: true,
        }).then((result) => {
          if (typeof result.value !== "undefined") {
            let value = result.value;
            if (value == "") {
              this.handleChange("code-new");
            } else {
              let sendValue = null;
              this.perangkats.forEach((val) => {
                if (val.nama_perangkat.toLowerCase() == value.toLowerCase()) {
                  sendValue = val.perangkat_id;
                }
              });
              if (sendValue != null) {
                this.$emit("input", sendValue);
              } else {
                axios
                  .post(route("api.peralatan.perangkat.store"), {
                    nama_perangkat: value,
                  })
                  .then((response) => {
                    this.perangkats.push({
                      perangkat_id: response.data.id,
                      nama_perangkat: value,
                    });
                    this.$emit("update_list", this.perangkats);
                    this.$emit("input", response.data.id);
                  })
                  .catch((er) => {
                    this.Toast({
                      icon: "error",
                      title: this.trans("core.error 500 title"),
                    });
                  });
              }
            }
          }
        });
      } else {
        this.$emit("input", val);
      }
    },
  },
  mounted() {
    this.perangkats = this.list_perangkat;
  },
};
</script>
