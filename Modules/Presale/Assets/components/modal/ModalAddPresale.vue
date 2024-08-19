<template>
  <bs-modal id="modal-add-presale" size="large" @onClose="onCancel()">
    <div slot="header">
      Form Presale
    </div>
    <div class="container">
      <el-form
        ref="presale"
        :model="presale"
        v-loading.body="loading"
        :rules="rules"
      >
        <div class="row">
          <div class="col-12 d-flex justify-content-center">
            <el-form-item :label="trans('presales.form.foto_rumah')">
              <upload-avatar
                :onSuccess="handleAvatarSuccess"
                :beforeUpload="beforeAvatarUpload"
                :image="presale.presale_img"
              >
              </upload-avatar>
            </el-form-item>
          </div>

          <div class="col-12">
            <p style="font-size:10pt;text-align:center">
              {{ presale.edited_text }}
            </p>
          </div>
          <el-divider id="divider_add_presale"> </el-divider>
          <div class="col-12 " v-if="presale_id && process == 'edit_data_marker_presale'">
            <el-button
              type="primary"
              icon="el-icon-edit"
              size="small"
              class="pull-right"
              @click="change_location"
            >
              Edit Lokasi
            </el-button>
          </div>
          <div class="col-12 col-md-6">
            <label > {{trans('presales.form.lat')}}</label>
            <el-form-item prop="lat">
              <el-input v-model="presale.lat" size="small" disabled></el-input>
            </el-form-item>
          </div>

          <div class="col-12 col-md-6">
            <label > {{ trans('presales.form.lon') }}</label>
            <el-form-item prop="lon">
              <el-input v-model="presale.lon" size="small" disabled></el-input>
            </el-form-item>
          </div>

          <div class="col-12 col-md-6">
            <label > {{ trans('presales.form.end_point_name') }} </label>
            <el-form-item
              prop="end_point_name"
            >
              <el-input
                v-model="presale.endpoint_name"
                size="small"
                disabled
              ></el-input>
            </el-form-item>
          </div>

          <div class="col-12 col-md-6">
            <label> {{ trans('presales.form.panjang_kabel') }} </label>
            <el-form-item
              prop="panjang_kabel"
            >
              <el-input
                type="number"
                min="0"
                v-model="presale.panjang_kabel"
                size="small"
                @keyup.native="handleChangePanjangKabel"
              ></el-input>
            </el-form-item>
          </div>

          <div class="col-12 col-md-12">
            <label >{{ trans('presales.form.biaya_kabel') }}</label>
            <el-form-item
              prop="biaya_kabel"
            >
              <input-currency v-model="presale.biaya_kabel"> </input-currency>
            </el-form-item>
          </div>

          <el-divider></el-divider>

          <div class="col-12">
            <label >{{trans('presales.form.provinces')}}</label>
            <el-form-item
              prop="provinces_id"
              :rules="rules.provinces_id"
            >
              <SelectProvinces v-model="presale.provinces_id"></SelectProvinces>
            </el-form-item>
          </div>

          <div class="col-12">
            <label > {{trans('presales.form.regencies')}} </label>
            <el-form-item
              prop="regencies_id"
              :rules="rules.regencies_id"
            >
              <SelectRegencies
                v-model="presale.regencies_id"
                :provinces_id="presale.provinces_id"
              ></SelectRegencies>
            </el-form-item>
          </div>

          <div class="col-12">
            <label > {{trans('presales.form.districts')}} </label>
            <el-form-item
              prop="districts_id"
              :rules="rules.districts_id"
            >
              <SelectDistricts
                v-model="presale.districts_id"
                :regencies_id="presale.regencies_id"
              ></SelectDistricts>
            </el-form-item>
          </div>

          <div class="col-12">
            <label> {{trans('presales.form.villages')}} </label>
            <el-form-item
              prop="villages_id"
              :rules="rules.villages_id"
            >
              <SelectVillages
                v-model="presale.villages_id"
                :districts_id="presale.districts_id"
              ></SelectVillages>
            </el-form-item>
          </div>

          <div class="col-12 col-md-4">
            <label> {{trans('presales.form.nama_gang')}} </label>
            <el-form-item
              prop="nama_gang"
            >
              <el-input v-model="presale.nama_gang" size="small"> </el-input>
            </el-form-item>
          </div>

          <div class="col-12 col-md-4">
            <label> {{trans('presales.form.no_rumah')}} </label>
            <el-form-item
              prop="no_rumah"
            >
              <el-input v-model="presale.no_rumah" size="small"> </el-input>
            </el-form-item>
          </div>

          <div class="col-12 col-md-4">
            <label> {{trans('presales.form.kode_pos')}} </label>
            <el-form-item
              prop="kode_pos"
            >
              <el-input v-model="presale.kode_pos" size="small"> </el-input>
            </el-form-item>
          </div>

          <div class="col-12">
            <label> {{trans('presales.form.address')}} </label>
            <el-form-item
              prop="address"
              :rules="rules.address"
            >
              <el-input type="textarea" :rows="2" v-model="presale.address">
              </el-input>
            </el-form-item>
          </div>

          <div class="col-12 col-md-6">
            <label>{{ trans('presales.form.class') }}</label>
            <el-form-item
              prop="class_id"
              :rules="rules.class_id"
            >
              <el-select v-model="presale.class_id" filterable size="small">
                <el-option
                  v-for="item in class_option"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                >
                </el-option>
              </el-select>
            </el-form-item>
          </div>

          <div class="col-12 col-md-6">
            <label> {{trans('presales.form.status')}} </label>
            <el-form-item
              prop="status_id"
              :rules="rules.status_id"
            >
              <el-select v-model="presale.status_id" filterable size="small">
                <el-option
                  v-for="item in status_option"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                >
                </el-option>
              </el-select>
            </el-form-item>
          </div>

          <div class="col-12">
            <label> {{trans('presales.form.keterangan')}} </label>
            <el-form-item
              prop="keterangan"
              :rules="rules.keterangan"
            >
              <el-input type="textarea" :rows="2" v-model="presale.keterangan">
              </el-input>
            </el-form-item>
          </div>
        </div>
      </el-form>
    </div>

    <div slot="footer">
      <el-button @click="onCancel()" type="danger" size="small"
        >{{ trans("core.button.cancel") }}
      </el-button>
      <el-button type="primary" @click="onSubmit('presale')" size="small" :loading="loading" icon="el-icon-upload">
        {{ trans("core.button.save") }}
      </el-button>
    </div>
  </bs-modal>
</template>

<style></style>

<script>
import bsmodal from "../modal/BsModal";
import { gmapApi } from "vue2-google-maps";
import axios from "axios";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import Form from "form-backend-validation";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";
import GoogleMapHelper from "../../../../Core/Assets/js/mixins/GoogleMapHelper";

export default {
  name: "modal-add-presale",
  mixins: [Toast, GoogleMapHelper],
  components: {
    "bs-modal": bsmodal,
    "input-currency": InputCurrencyVue,
  },
  props: {
    preview: {
      default: "",
    },
    data_biaya_kabel: { default: {} },
    position: {
      default: () => {
        return {
          lat: 0,
          lng: 0,
        };
      },
    },
    wilayah: {
      default: "",
    },
    polyline: {
      default: null,
    },
    endpoint: {
      default: () => {
        return {
          name: "",
          end_point_id: null,
        };
      },
    },
    company_id: {
      default: null,
    },
    presale_id: {
      default: null,
    },
    process: {
      default:null
    }
  },
  data() {
    return {
      loading: false,
      class_option: [],
      status_option: [],
      rules: {
        provinces_id: [
          {
            required: true,
            message: this.trans("core.validation.required", {
              field: "province",
            }),
            trigger: "blur",
          },
        ],
        regencies_id: [
          {
            required: true,
            message: this.trans("core.validation.required", {
              field: "regency",
            }),
            trigger: "blur",
          },
        ],
        districts_id: [
          {
            required: true,
            message: this.trans("core.validation.required", {
              field: "district",
            }),
            trigger: "blur",
          },
        ],
        villages_id: [
          {
            required: true,
            message: this.trans("core.validation.required", {
              field: "village",
            }),
            trigger: "blur",
          },
        ],
        address: [
          {
            required: true,
            message: this.trans("core.validation.required", {
              field: "address",
            }),
            trigger: "change",
          },
        ],
        foto_rumah: "",
        class_id: [
          {
            required: true,
            message: this.trans("core.validation.required", {
              field: "Tipe rumah",
            }),
            trigger: "blur",
          },
        ],
        status_id: [
          {
            required: true,
            message: this.trans("core.validation.required", {
              field: "status",
            }),
            trigger: "blur",
          },
        ],
      },
      range_biaya_kabel: [],
      presale: {
        lat: 0,
        lon: 0,
        endpoint_name: "",
        end_point_id: "",
        panjang_kabel: 0,
        biaya_kabel: "0",
        biaya_instalasi: "0",
        total_biaya: "0",
        provinces_id: "",
        regencies_id: "",
        districts_id: "",
        villages_id: "",
        nama_gang: "",
        no_rumah: "",
        kode_pos: null,
        address: "",
        foto_rumah: null,
        keterangan: "",
        class_id: null,
        status_id: null,
        presale_img: "",
        edited_text: "",
      },
      default_presale: {
        foto_rumah: null,
        keterangan: "",
        class_id: null,
        status_id: null,
        presale_img: "",
        edited_text: "",
      },
    };
  },
  methods: {
    handleAvatarSuccess(res, file) {
      this.presale.presale_img = URL.createObjectURL(file.raw);
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === "image/jpeg" || file.type === "image/png";
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error("Avatar picture must be JPG or PNG format!");
      }
      if (!isLt2M) {
        this.$message.error("Avatar picture size can not exceed 2MB!");
      }
      this.presale.foto_rumah = file;
      return isJPG && isLt2M;
    },
    change_location() {
      $('#modal-add-presale').modal('hide');
      this.$emit('change_location', this.presale_id);
    },
    onSubmit(formName) {
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
          this.$refs[formName].validate((valid) => {
            if (valid) {
              this.loading = true;
              let properties = {};

              if (this.presale_id && this.process == 'edit_data_marker_presale') {
                properties = this.presale;
              } else {
                properties = {
                  ...this.presale,
                  wilayah_id: this.wilayah.wilayah_id,
                  pathline: this.convertPolyLineToArray(
                    this.polyline.getPath().getArray()
                  ),
                };
              }
              let form = new Form(properties);
              form
                .post(this.getRoute())
                .then((response) => {
                  this.loading = false;
                  this.Toast({
                    icon: "success",
                    title: response.message,
                  });
                  if (this.presale_id) {
                    this.$emit("handleUpdateSuccess", response.data);
                  } else {
                    this.$emit("handleSuccess", response.data);
                  }
                  this.resetForm();
                })
                .catch((err) => {
                  this.loading = false;
                  this.Toast({
                    icon: "error",
                    title: this.trans("core.error 500 title"),
                  });
                });
            }
          });
        }
      });
    },
    resetForm() {
      this.presale = {
        ...this.presale,
        ...this.default_presale,
      };
    },
    getRoute() {
      if (this.presale_id) {
        return route("api.presale.presales.update", {
          presale: this.presale_id,
        });
      }
      return route("api.presale.presales.store");
    },
    onCancel() {
      $("#modal-add-presale").modal("hide");
      this.$emit("onCancel", true);
    },
    setAlamatByLatLng(lat, lng) {
      var geocoder = new google.maps.Geocoder();
      var latLng = new google.maps.LatLng(lat, lng);
      geocoder.geocode(
        {
          latLng: latLng,
        },
        (results, status) => {
          if (status == google.maps.GeocoderStatus.OK) {
            var address = results[0];
            for (var i = 0; i < results.length; i++) {
              if (results[i].types[0] == "street_address") {
                address = results[i];
                break;
              }
            }
            if (address) {
              var alamatLengkap = address.formatted_address;
              this.presale.address = alamatLengkap;
              alamatLengkap = alamatLengkap.split(",");
              var nomorRumah = "";
              if (alamatLengkap[0].indexOf("No.") != -1) {
                nomorRumah = alamatLengkap[0].split("No.");
                if (nomorRumah.length > 1) {
                  nomorRumah = nomorRumah[1];
                }
              }
              var namaGang = "";
              if (alamatLengkap[0].indexOf("Gg.") != -1) {
                namaGang = alamatLengkap[0].split("Gg.");
                namaGang = namaGang[1].split("No.");
                if (namaGang.length > 1) {
                  namaGang = namaGang[0];
                }
              }
              this.presale.nama_gang = namaGang;

              this.presale.no_rumah = nomorRumah;
              var kodePOS = "";
              var size = results[0].address_components.length;
              for (var i = 0; i < size; i++) {
                if (
                  results[0].address_components[i].types[0] == "postal_code"
                ) {
                  kodePOS = results[0].address_components[i].long_name;
                  break;
                }
              }
              this.presale.kode_pos = kodePOS;
            }
          }
        }
      );
    },
    handleChangePanjangKabel(calculateTotal = true) {
      this.generateBiayaKabel();
    },
    fetchGetClassPresale() {
      axios
        .get(route("api.presale.class.index"))
        .then((response) => {
          this.class_option = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    fetchGetStatusPresale() {
      axios
        .get(route("api.presale.status.index"))
        .then((response) => {
          this.status_option = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    
    fetchGetDataPresale() {
      axios
        .get(route("api.presale.presale.show", { presale: this.presale_id }))
        .then((response) => {
          this.presale = response.data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    generateBiayaKabel() {
      if (Array.isArray(this.data_biaya_kabel)) return 0;

      let panjang =
        this.presale.panjang_kabel === "" ? 0 : this.presale.panjang_kabel;
      if (this.data_biaya_kabel.tipe == "precone") {
        this.presale.biaya_kabel =
          parseInt(this.data_biaya_kabel.precone) * parseInt(panjang);
      } else {
        let biaya_kabel = this.findBiayaKabel(panjang);
        this.presale.biaya_kabel = biaya_kabel;
      }
    },
    findBiayaKabel(panjang_kabel) {
      let harga_kabel = 0;
      for (var i = 0; i < this.data_biaya_kabel.dropcore.length; i++) {
        if (i != 0) {
          if (
            panjang_kabel >
              this.data_biaya_kabel.dropcore[i - 1].panjang_kabel &&
            panjang_kabel <= this.data_biaya_kabel.dropcore[i].panjang_kabel
          ) {
            harga_kabel = this.data_biaya_kabel.dropcore[i].biaya;
            break;
          }
        } else if (
          panjang_kabel <= this.data_biaya_kabel.dropcore[i].panjang_kabel
        ) {
          harga_kabel = this.data_biaya_kabel.dropcore[i].biaya;
        } else {
          harga_kabel = this.data_biaya_kabel.dropcore[i].biaya;
        }
      }

      return harga_kabel;
    },
  },
  computed: {
    google: gmapApi,
  },
  watch: {
    position: {
      handler: function(val) {
        this.presale.lat = val.lat;
        this.presale.lon = val.lng;

        this.setAlamatByLatLng(val.lat, val.lng);
      },
      deep: true,
    },
    endpoint: {
      handler: function(val) {
        this.presale.end_point_id = val.end_point_id;
        this.presale.endpoint_name = val.name;
      },
      deep: true,
    },
    wilayah: {
      handler: function(val) {
        this.presale.provinces_id = val.provinces_id;
        this.presale.regencies_id = val.regencies_id;
        this.presale.districts_id = val.districts_id;
        this.presale.villages_id = val.villages_id;
      },
      deep: true,
    },
    polyline: function(val) {
      this.presale.panjang_kabel = Math.round(
        this.google.maps.geometry.spherical.computeLength(
          this.polyline.getPath().getArray()
        )
      );
    },
    presale_id: function(id) {
      if (this.presale_id) {
        this.fetchGetDataPresale();
      }
    },
  },
  mounted() {
    this.fetchGetClassPresale();
    this.fetchGetStatusPresale();

    $("#modal-add-presale").on("show.bs.modal", () => {
      if (!this.presale_id) {
        this.generateBiayaKabel();
        this.resetForm();
      }
    });
  },
};
</script>

<style></style>
