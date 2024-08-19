<template>
  <div>
    <Card class="card card-outline-info">
      <div class="card-header" style="border-bottom: 0px;">
        <div class="card-actions">
          <div class="row">
            <a
              data-action="collapse"
              class="d-flex align-items-center mx-3 text-white"
              ><i class="ti-minus"></i
            ></a>
          </div>
        </div>
        <h5 class="text-white">{{ trans("salesorders.title.salesorders") }}</h5>
      </div>
      <div class="card-body collapse show">
        <table class="table" style="width: 100%;">
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.site_id") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">
              {{ form.site_id }}
            </td>
          </tr>
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.nama_pelanggan") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">
              {{ form.nama_pelanggan }}
            </td>
          </tr>
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.telepon") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">
              {{ form.telepon | table }}
            </td>
          </tr>
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.email") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">
              {{ form.email | table }}
            </td>
          </tr>
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.nomor_id") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">
              {{ form.nomor_identitas }}
            </td>
          </tr>
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.jenis_id") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">
              {{ form.jenis_identitas }}
              <a
                :href="photo.foto_identitas_pw"
                class="fancybox"
                @click="showFotoUp(photo.foto_identitas_pw)"
              >
                <el-button type="primary" class="el-icon-picture" size="small">
                </el-button
              ></a>
            </td>
          </tr>
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.status_pemilik") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">
              {{ form.status_kepemilikan }}
            </td>
          </tr>
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.alamat") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">
              {{ form.address }}
            </td>
          </tr>
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.paket_berlangganan") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">
              {{ form.namaPaket }}
            </td>
          </tr>
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.biaya_mrc") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">{{ form.biaya_mrc | harga }}</td>
          </tr>
          <tr class="py-5">
            <th style="width: 250px;">
              {{ trans("salesorders.form.biaya_otc") }}
            </th>
            <td style="width: 10px;">:</td>
            <td colspan="2">{{ form.biaya_otc | harga }}</td>
          </tr>
        </table>

        <div class="container row">
          <div class="col-md-8">
            <figure class="figure w-100">
              <div id="mapSalesOrderInfo" style="width:100%;height:300px"></div>
              <figcaption class="figure-caption text-center">
                {{ trans("salesorders.form.jalur endpoint") }}
              </figcaption>
            </figure>
          </div>
          <div class="col-md-4">
            <figure class="figure w-100">
              <a :href="photo.foto_rumah" class="fancybox">
                <el-image
                  style="width: 300px; height: 300px"
                  :src="photo.foto_rumah"
                  fit="contain"
                >
                  <div slot="error" class="image-slot">
                    <i class="el-icon-picture-outline"></i>
                  </div>
                </el-image>
              </a>
              <figcaption class="figure-caption text-center">
                {{ trans("salesorders.form.foto_rumah") }}
              </figcaption>
            </figure>
          </div>
        </div>
        <!-- <el-dialog :visible.sync="showFotoIdentitas" width="30%">
          <img
            class="img-fluid"
            :src="url_foto"
            alt="foto identitas pelanggan"
          />
        </el-dialog> -->
        <div class="p-2">
          <ButtonCancel
            v-if="form.cancel != 1 && form.status_pelanggan == 'so'"
          />
          <ButtonAktifkanKembali
            v-if="form.cancel == 1 && form.status_pelanggan == 'so'"
          />
        </div>
      </div>
      <div
        class="card-footer d-flex justify-content-center"
        v-if="role.name == 'Admin ISP'"
      >
        <el-button type="primary" @click="editHasilSO" icon="fa fa-edit">
          {{ trans("salesorders.button.edit sales order") }}
        </el-button>
      </div>
    </Card>
  </div>
</template>
<script>
import ButtonAktifkanKembali from "../../ButtonAktifkanKembali.vue";
import ButtonCancel from "../../ButtonCancel.vue";

// import step_so from "../../mixins/step_so";

export default {
  // mixins: [step_so],
  props: ["form", "photo", "role", "mapInfo"],
  components: {
    ButtonCancel,
    ButtonAktifkanKembali,
  },
  data() {
    return {
      showFotoIdentitas: false,
      url_foto: null,
      map: null,
      infoWindow: null,
    };
  },
  methods: {
    showFotoUp(url) {
      this.url_foto = url;
      this.showFotoIdentitas = true;
    },
    editHasilSO() {
      this.$emit("edit", true);
    },
    initializeMap() {
      this.map = new google.maps.Map(
        document.getElementById("mapSalesOrderInfo"),
        {
          center: { lat: parseFloat(0), lng: parseFloat(0) },
          zoom: 8,
          streetViewControl: false,
        }
      );
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
  },
  watch: {
    mapInfo: function() {
      let self = this;
      this.$gmapApiPromiseLazy().then(() => {
        self.initializeMap();
        self.infoWindow = new google.maps.InfoWindow({ content: "" });
        self.setMarkerMap(self.mapInfo);
      });
    },
  },
};
</script>
<style type="text/css">
.el-form-item__label {
  margin-bottom: 0px !important;
}
.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}
</style>
