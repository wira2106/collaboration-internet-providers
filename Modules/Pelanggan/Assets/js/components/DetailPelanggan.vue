<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("pelanggans.title.pelanggans") }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">{{
              trans("core.breadcrumb.home")
            }}</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'admin.pelanggan.pelanggans.index' }">
              {{ trans("pelanggans.title.pelanggans") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            {{ trans(`pelanggans.title.pelanggans`) }}
          </li>
        </ol>
      </div>
    </div>
    <div class="container">
      <div class="card">
        <div class="card-body">
          <div class="float-right">
            <router-link
              :to="{
                name: 'admin.pelanggan.form.step',
                query: { pelanggan: pelanggan_id },
              }"
              class="btn btn-info btn-sm"
            >
              {{ trans("pelanggans.title.lihat riwayat") }}
            </router-link>
          </div>
        </div>
      </div>
      <el-form
        ref="form"
        :model="form"
        label-width="120px"
        label-position="top"
        v-loading.body="loading"
        @keydown="form.errors.clear($event.target.name)"
      >
        <div class="card">
          <div class="card-body">
            <el-tabs v-model="activeTab">
              <el-tab-pane
                :label="trans('pelanggans.tab.detail')"
                name="detail"
              >
                <div class="row">
                  <div class="col-md-12">
                    <input
                      type="hidden"
                      name="pelanggan_id"
                      :value="form.pelanggan_id"
                    />
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.site_id')"
                      prop="site_id"
                    >
                      <el-input
                        type="text"
                        :readonly="true"
                        v-model="form.site_id"
                        size="small"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.nama_pelanggan')"
                      prop="nama_pelanggan"
                    >
                      <el-input
                        type="text"
                        :placeholder="
                          trans('salesorders.placeholder.nama_pelanggan')
                        "
                        v-model="form.nama_pelanggan"
                        size="small"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.telepon')"
                      prop="telepon"
                    >
                      <el-input
                        type="text"
                        :placeholder="trans('salesorders.placeholder.telepon')"
                        v-model="form.telepon"
                        size="small"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.email')"
                      prop="email"
                    >
                      <el-input
                        type="email"
                        :placeholder="trans('salesorders.placeholder.email')"
                        v-model="form.email"
                        size="small"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.jenis_id')"
                      prop="jenis_identitas"
                    >
                      <el-select
                        v-model="form.jenis_identitas"
                        placeholder="Select"
                        filterable
                        size="small"
                      >
                        <el-option label="KTP" value="KTP"></el-option>
                        <el-option label="SIM" value="SIM"></el-option>
                        <el-option label="Lainnya" value="Lainnya"></el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.nomor_id')"
                      prop="nomor_identitas"
                    >
                      <el-input
                        type="text"
                        :placeholder="trans('salesorders.placeholder.nomor_id')"
                        v-model="form.nomor_identitas"
                        size="small"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.foto_id')"
                      prop="foto_identitas"
                    >
                      <div class="input-group">
                        <input
                          type="file"
                          accept="image/*"
                          class="form-control"
                          @change="
                            setInputFoto(
                              $event,
                              'photo.foto_identitas_pw',
                              'form.foto_identitas'
                            )
                          "
                        />
                        <div
                          class="input-group-append"
                          v-show="photo.foto_identitas_pw != null"
                        >
                          <a
                            :href="photo.foto_identitas_pw"
                            class="btn btn-primary btn-sm fancybox"
                          >
                            <i class="fa fa-image"></i>
                          </a>
                        </div>
                      </div>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.status_pemilik')"
                      prop="status_kepemilikan"
                    >
                      <el-select
                        v-model="form.status_kepemilikan"
                        placeholder="Select"
                        filterable
                        size="small"
                      >
                        <el-option label="Pemilik" value="pemilik"></el-option>
                        <el-option label="Penyewa" value="penyewa"></el-option>
                        <el-option label="Lainnya" value="lainnya"></el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <div
                      id="mapSalesOrder"
                      style="width:100%;height:300px"
                    ></div>
                    <br />
                  </div>
                  <div class="col-md-12">
                    <el-form-item
                      :label="trans('salesorders.form.alamat')"
                      prop="address"
                    >
                      <el-input
                        type="textarea"
                        :placeholder="trans('salesorders.placeholder.alamat')"
                        v-model="form.address"
                        size="small"
                        :readonly="true"
                      >
                      </el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item
                      :label="trans('salesorders.form.paket_berlangganan')"
                      prop="paket_id"
                    >
                      <el-select
                        v-model="form.paket_id"
                        @change="setBiayaMRCOTC"
                        placeholder="Select"
                        filterable
                        size="small"
                      >
                        <el-option
                          v-for="val in paket"
                          :value="val.paket_id"
                          :label="
                            val.nama_paket + ' (' + val.harga_paket_rp + ')'
                          "
                          :key="val.paket_id"
                        >
                        </el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.biaya_mrc')"
                      prop="biaya_mrc"
                    >
                      <InputCurrency
                        v-model="form.biaya_mrc"
                        :readonly="true"
                      ></InputCurrency>
                      <!-- <el-input type="number" :placeholder="trans('salesorders.placeholder.biaya_mrc')" v-model="form.biaya_mrc" size='small' :readonly="readonlyMrcOtc"></el-input> -->
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.biaya_otc')"
                      prop="biaya_otc"
                    >
                      <InputCurrency
                        v-model="form.biaya_otc"
                        :readonly="true"
                      ></InputCurrency>
                      <!-- <el-input type="number" :placeholder="trans('salesorders.placeholder.biaya_otc')" v-model="form.biaya_otc" size='small' :readonly="readonlyMrcOtc"></el-input> -->
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <hr />
                  </div>
                  <div class="col-md-12">
                    <el-form-item>
                      <el-button
                        type="primary"
                        @click="onSubmit('form')"
                        :loading="loading"
                      >
                        {{ trans("core.save") }}
                      </el-button>
                      <el-button @click="$router.push({name: 'admin.pelanggan.pelanggans.index'})" type="danger">
                        {{ trans("core.button.cancel") }}
                      </el-button>
                    </el-form-item>
                  </div>
                </div>
              </el-tab-pane>
              <el-tab-pane :label="trans('pelanggans.tab.sla')" name="sla">
                <HistorySla
                  v-if="activeTab == 'sla'"
                  :pelanggan_id="pelanggan_id"
                />
              </el-tab-pane>
            </el-tabs>
          </div>
        </div>
      </el-form>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";
import { gmapApi } from "vue2-google-maps";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import HistorySla from "./HistorySla.vue";

export default {
  mixins: [ShortcutHelper, TranslationHelper, Toast],
  components: {
    InputCurrency: InputCurrencyVue,
    HistorySla: HistorySla,
  },
  data() {
    return {
      loading: false,
      pelanggan_id: null,
      activeTab: "detail",
      form: {
        presale_id: null,
        pelanggan_id: null,
        site_id: "",
        nama_pelanggan: "",
        telepon: "",
        email: "",
        jenis_identitas: "",
        nomor_identitas: "",
        status_kepemilikan: null,
        address: "",
        paket_id: "",
        biaya_mrc: "",
        biaya_otc: "",
        foto_identitas: null,
        foto_jalur_ep: null,
        keterangan: null,
      },
      photo: {
        foto_identitas_old: null,
        foto_identitas_pw: null,
      },
      paket: [],
      infoWindow: null,
      infoWindowAktif: null,
    };
  },
  compute: {
    google: gmapApi,
  },
  methods: {
    initializeMap() {
      this.map = new google.maps.Map(document.getElementById("mapSalesOrder"), {
        center: { lat: parseFloat(0), lng: parseFloat(0) },
        zoom: 8,
        streetViewControl: false,
      });
    },
    fetchData() {
      this.loading = true;
      axios
        .get(
          route("api.pelanggan.form.detail", {
            pelanggan_id: this.pelanggan_id,
          })
        )
        .then((response) => {
          let data = response.data.data;
          this.form = data;
          this.pelanggan_id = data.pelanggan_id;
          this.photo.foto_identitas_old = data.foto_identitas;
          this.photo.foto_identitas_pw = data.foto_identitas;
          this.loadPaketBerlangganan(response.data.paket);
          this.setMarkerMap(data);
          this.loading = false;
        });
    },
    setPresalesAndPelangganId() {
      let pelanggan = this.$route.params.pelanggan_id;
      this.pelanggan_id =
        pelanggan !== undefined && pelanggan != "" ? pelanggan : 0;
    },
    loadPaketBerlangganan(paket) {
      this.paket = paket;
    },
    setMarkerMap(data) {
      let vm = this;
      var bounds = new google.maps.LatLngBounds();
      let point = new google.maps.LatLng(data.lat, data.lon);
      // MARKER RUMAH
      let icon = {
        url: data.icon,
        scaledSize: new google.maps.Size(50, 50),
      };
      let markerRumah = new google.maps.Marker({
        position: point,
        map: vm.map,
        icon: icon,
      });
      bounds.extend(point);
      google.maps.event.addListener(markerRumah, "click", function(event) {
        if (vm.infoWindowAktif) {
          vm.infoWindowAktif.close();
        }
        vm.infoWindow.setContent(data.site_id);
        vm.infoWindow.open(vm.map, markerRumah);
        vm.infoWindowAktif = vm.infoWindow;
      });
      // MARKER END POIN
      icon = {
        url: data.icon_ep,
        scaledSize: new google.maps.Size(50, 60),
      };
      point = new google.maps.LatLng(data.lat_ep, data.lon_ep);
      let markerODP = new google.maps.Marker({
        position: point,
        map: vm.map,
        icon: icon,
      });
      bounds.extend(point);
      google.maps.event.addListener(markerODP, "click", function(event) {
        if (vm.infoWindowAktif) {
          vm.infoWindowAktif.close();
        }
        vm.infoWindow.setContent(data.type_ep + " : " + data.endpoint_name);
        vm.infoWindow.open(vm.map, markerODP);
        vm.infoWindowAktif = vm.infoWindow;
      });

      // get path jalur
      var path = "";
      var pathLine = [];
      $.each(data.path, (key, val) => {
        if (path != "") {
          path += "|";
        }
        path += `${val.lat},${val.lng}`;
        let myLtLng = new google.maps.LatLng({
          lat: parseFloat(val.lat),
          lng: parseFloat(val.lng),
        });
        bounds.extend(myLtLng);
        pathLine.push(myLtLng);
      });

      // buat jalur pada map presales
      let line = new google.maps.Polyline({
        path: pathLine,
        strokeColor: "#007bff",
        strokeOpacity: 0.8,
        strokeWeight: 5,
        geodesic: true,
        map: vm.map,
        editable: false,
      });

      vm.map.fitBounds(bounds);
      let centerMap = `${vm.map.getCenter().lat()},${vm.map.getCenter().lng()}`;
      let zoom = vm.map.getZoom();
      // convert photo jalur ke gambar
      var url_image_odp = data.icon_ep;
      var linkMaps = `https://maps.googleapis.com/maps/api/staticmap?center=${centerMap}&zoom=${zoom}&key=AIzaSyAJRyIHISCimoNj8hkhQiSpqnuKS5nju8w&size=800x500&markers=${data.lat},${data.lon}&markers=icon:${url_image_odp}|${data.lat_ep},${data.lon_ep}&path=${path}`;
      var resizedImage;
      vm.getBase64Image(linkMaps, function(data) {
        resizedImage = vm.b64toBlob(data, "image/png");
        vm.form.foto_jalur_ep = resizedImage;
      });
    },
    getBase64Image(imgUrl, callback) {
      var img = new Image();
      // onload fires when the image is fully loadded, and has width and height
      img.onload = function() {
        var canvas = document.createElement("canvas");
        canvas.width = img.width;
        canvas.height = img.height;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0);
        var dataURL = canvas.toDataURL("image/png"),
          dataURL = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
        callback(dataURL); // the base64 string
      };
      // set attributes and src
      img.setAttribute("crossOrigin", "anonymous"); //
      img.src = imgUrl;
    },

    b64toBlob(b64Data, contentType, sliceSize) {
      contentType = contentType || "";
      sliceSize = sliceSize || 512;

      var byteCharacters = atob(b64Data);
      var byteArrays = [];

      for (
        var offset = 0;
        offset < byteCharacters.length;
        offset += sliceSize
      ) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);
        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
          byteNumbers[i] = slice.charCodeAt(i);
        }
        var byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
      }
      var blob = new Blob(byteArrays, { type: contentType });
      return blob;
    },
    buildFormData(formData, data, parentKey) {
      let vm = this;
      if (
        data &&
        typeof data === "object" &&
        !(data instanceof Date) &&
        !(data instanceof File) &&
        !(data instanceof Blob)
      ) {
        Object.keys(data).forEach((key) => {
          vm.buildFormData(
            formData,
            data[key],
            parentKey ? `${parentKey}[${key}]` : key
          );
        });
      } else {
        const value = data == null ? "" : data;

        formData.append(parentKey, value);
      }
    },
    jsonToFormData(data) {
      const formData = new FormData();

      this.buildFormData(formData, data);

      return formData;
    },
    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
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
              this.loading = true;
              let config = {
                header: {
                  "Content-Type": "multipart/form-data",
                },
              };
              axios
                .post(
                  route("api.pelanggan.form.submit"),
                  this.jsonToFormData(this.form),
                  config
                )
                .then((response) => {
                  let data = response.data;
                  this.Toast({
                    icon: "success",
                    title: data.message,
                  });
                  this.fetchData();
                })
                .catch((error) => {
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
    setBiayaMRCOTC() {
      // perlu diubah
      let vm = this;
      let paket_id = this.form.paket_id;
      // Swal.fire({
      //   title: 'Loading..',
      //   html: '',
      //   allowOutsideClick: false,
      //   onOpen: () => {
      //     swal.showLoading()
      //   }
      // });
      axios
        .post(
          route("api.pelanggan.get_biaya.paket", { paket_id: paket_id }),
          _.merge({ pelanggan_id: this.pelanggan_id })
        )
        .then((response) => {
          let data = response.data;
          this.form.biaya_mrc = parseInt(data.biaya_mrc);
          // this.form.biaya_otc = parseInt(data.biaya_otc);
          // Swal.close();
        })
        .catch((er) => {
          // Swal.close();
          this.Toast({
            icon: "error",
            title: this.trans("core.error 500 title"),
          });
        });
    },
  },
  mounted() {
    this.setPresalesAndPelangganId();
    let self = this;
    this.$gmapApiPromiseLazy().then(() => {
      self.initializeMap();
      self.infoWindow = new google.maps.InfoWindow({ content: "" });
      self.fetchData();
    });
    $(".fancybox").fancybox();
  },
};
</script>
