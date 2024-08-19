<template>
  <el-select filterable v-on:change="handleChange" v-bind:value="value" :ref="reference" :placeholder="trans('alats.placeholder.pilih alat')" size="small">
    <el-option
      :label="'+ ' + trans('alats.new alat')"
      value="code-new"
    ></el-option>
    <el-option
      v-for="item in alats"
      :key="item.alat_id"
      :label="item.nama_alat"
      :value="item.alat_id"
    ></el-option>
  </el-select>
</template>
<script>
import axios from 'axios';
import Toast from '../../../../../public/modules/core/js/mixins/Toast';
import TranslationHelper from '../mixins/TranslationHelper';
export default {
  props: {
    value : {
      default: ""
    },
    list_alat: {
      default: []
    },
    reference: {
      default : "alat"+Math.random()
    }
  },
  mixins: [Toast, TranslationHelper],
   data() {
    return {
        alats : []
    }
  },
  methods: {
    handleChange(val) {
      if (val == "code-new") {
        Swal.fire({
          title: this.trans('alats.title.enter alat'),
          input: "text",
          inputAttributes: {
            autocapitalize: "off",
          },
          showCancelButton: true,
          confirmButtonText: "Simpan",
          showLoaderOnConfirm: true,
        }).then((result) => {
          if (typeof result.value !== 'undefined') {
            let value = result.value;
            if(value == ""){
              this.handleChange("code-new");
            }else{
              let sendValue = null;
              this.alats.forEach(val => {
                if(val.nama_alat.toLowerCase() == value.toLowerCase()){
                  sendValue = val.alat_id;
                }
              });
              if(sendValue != null){
                this.$emit("input", sendValue);
              }else{
                axios.post(route('api.peralatan.alat.store'),{nama_alat:value})
                .then((response) => {
                  this.alats.push({alat_id : response.data.id, nama_alat : value});
                  this.$emit("update_list", this.alats);
                  this.$emit("input", response.data.id);
                }).catch((er) => {
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
  mounted(){
    this.alats = this.list_alat;
  }
};
</script>
