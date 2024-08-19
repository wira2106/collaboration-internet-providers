<template>
  <div
    style="height:100%;width:100%;position:relative"
    class="d-flex justify-content-center align-items-center"
  >
    <gmap-map
      :center="center"
      :zoom="12"
      style="width:100%;  height: 100%;"
      ref="map"
      :options="{
        rotateControl: false,
        zoomControl: true,
      }"
      @dragend="onMapDragend"
    >
      <gmap-marker
        :key="index"
        v-for="(m, index) in endpoint_transformer"
        :position="m.position"
        :icon="{
          url: m.icon,
          scaledSize: new google.maps.Size(50, 60),
        }"
        :visible="m.visibility"
        @click="handleMarkerEndpointClicked(m, index)"
      ></gmap-marker>
      <gmap-info-window
        :options="infoOptions"
        :position="infoWindowPos"
        :opened="infoWinOpen"
        @closeclick="infoWinOpen = false"
      >
      </gmap-info-window>
    </gmap-map>
    <div class="center-map">
      <el-popover
        placement="top"
        trigger="manual"
        v-model="infowindow.add.showed"
        v-if="infowindow.add.render"
      >
        <div>
          <img
            :src="closeImg"
            class="hover-pointer"
            style="
                    right: 0;
                    top: 0;
                    position: absolute;
                    opacity: 0.5;
                    height: 20px;
                    "
            @click="infowindow.add.showed = false"
          />
        </div>
        <div style="padding-top: 5px;">
          <iw-add-endpoint
            :location="location"
            :handleClose="closeInfowindow"
            :handleClosePopover="closePopover"
            @handleSuccess="handleSuccessAddEndpoint"
            @addButtonClicked="changeLocation"
            :endpoint="endpoint"
            :endpoint_index="endpoint_index"
            v-if="process == 'endpoint'"
          >
          </iw-add-endpoint>
          <iw-edit-location-endpoint
            v-if="process == 'edit_location_marker_endpoint'"
            :handleClosePopover="closePopover"
            :location="location"
            :handleClose="closeInfowindowAndShowMarker"
            @handleSubmit="changeLocation"
            @updated="handleUpdatedMarker"
          />
        </div>
        <img
          slot="reference"
          :src="infowindow.add.iconAdd"
          id="imageAdd"
          alt=""
          width="50px"
          @click="infowindow.add.showed = !infowindow.add.showed"
        />
      </el-popover>
    </div>
    <div class="map-button-add-group">
      <buttonAddEndpoint @click="processOnAddEndPoint" />
    </div>
    <edit-endpoint
      :endpoint="endpoint"
      :endpoint_selected="endpointSelected"
      :endpoint_index="endpoint_index"
      @handleSuccess="handleSuccessEditEndpoint"
    >
    </edit-endpoint>
  </div>
</template>

<script>
import buttonAddEndpoint from "../atoms/button-add-endpoint.vue";
import { gmapApi } from "vue2-google-maps";
import IwAddEndpointVue from "./endpoint/IwAddEndpoint.vue";
import html from "../../mixins/html";
import EditEndpointVue from "./modal/EditEndpoint.vue";
import EditLocationEndpointVue from "./Infowindow/EditLocationEndpoint.vue";

export default {
  mixins: [html],
  components: {
    buttonAddEndpoint: buttonAddEndpoint,
    "iw-add-endpoint": IwAddEndpointVue,
    "edit-endpoint": EditEndpointVue,
    "iw-edit-location-endpoint": EditLocationEndpointVue,
  },
  props: ["idAutoComplete"],
  data() {
    return {
      endpointSelected: {
        nama_end_point: "",
        tipe: "",
        address: "",
      },
      endpoint_index: null,
      center: { lat: -8.630067813021054, lng: 115.26384524187176 },
      process: "",
      closeImg: window.location.origin + "/modules/presale/img/close.svg",
      infowindow: {
        add: {
          showed: false,
          iconAdd: "",
          html: "",
          render: false,
        },
      },

      autocomplete: null,
      icon: {
        odp: window.location.origin + "/modules/presale/img/odp-marker.ico",
      },
      infoWinOpen: false,
      location: {
        latitude: -8.630067813021054,
        longitude: 115.26384524187176,
        address: "",
      },
      infoWindowPos: null,
      endpoint: [],
      map: null,
      geocoder: null,
      direction_service: null,
      infoOptions: {
        content: `<a href="#">Loading . . .</>`,
        //optional: offset infowindow so it visually sits nicely on top of our marker
        pixelOffset: {
          width: 0,
          height: -60,
        },
      },
      end_point_id: null,
    };
  },
  methods: {
    handleUpdatedMarker(position) {
      let marker = this.findMarkerByEndPointIndex(this.endpoint_index);
      marker.position.lat = position.latitude;
      marker.position.lng = position.longitude;
      this.closeInfowindowAndShowMarker();
    },
    closeInfowindowAndShowMarker() {
      this.closeInfowindow();
      let marker = this.findMarkerByEndPointIndex(this.endpoint_index);
      marker.visibility = true;
      this.clearProcess();
    },
    handleSuccessEditEndpoint(endpoint, index) {
      this.endpoint[index] = endpoint;
      this.closeInfowindowGoogle();
    },
    geolocate() {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
      });
    },
    changeLocation() {
      this.location.latitude = this.map.getCenter().lat();
      this.location.longitude = this.map.getCenter().lng();
      this.geocoder.geocode(
        { location: this.map.getCenter() },
        (results, status) => {
          if (status === "OK") {
            if (results[0]) {
              this.location.address = results[0].formatted_address;
            }
          }
        }
      );
    },
    handleSuccessAddEndpoint(data) {
      this.endpoint.push(data);
      setTimeout(() => {
        this.closeInfowindow();
        this.process = "";
      }, 1000);
    },
    closePopover(event) {
      this.infowindow.add.showed = false;
    },
    closeInfowindow() {
      this.infowindow.add.showed = false;
      this.infowindow.add.render = false;
    },
    showInfoWindow(icon, type) {
      this.infowindow.add.render = true;
      this.infowindow.add.iconAdd = icon;

      let checkImageIsLoaded = setInterval(() => {
        if ($("#imageAdd").get(0).complete) {
          this.infowindow.add.showed = true;
          clearInterval(checkImageIsLoaded);
        }
      }, 100);
    },
    closeInfowindowGoogle() {
      this.infoWinOpen = false;
    },
    processOnAddEndPoint() {
      this.process = "endpoint";

      this.showInfoWindow(this.icon.odp, "odp");
      this.closeInfowindowGoogle();
    },
    handleMarkerEndpointClicked(m, index) {
      this.clearProcess();
      this.closeInfowindow();
      this.infoWindowPos = m.position;
      this.center = m.position;

      this.infoOptions.content = this.getHtmlInfoWindow(m, index);
      if (this.currentMidx == index) {
        this.infoWinOpen = !this.infoWinOpen;
      }
      //if different marker set infowindow to open and reset current marker index
      else {
        this.infoWinOpen = true;
        this.currentMidx = index;
      }
    },
    clearProcess() {
      this.process = "";
    },
    editDataMarker(id) {
      this.process = "edit_data_marker_endpoint";
      this.endpointSelected = this.endpoint[id];
      this.endpoint_index = id;
      $("#modal-form-odp").modal("show");
    },
    hapusDataMarker(id) {
      Swal.fire({
        title: this.trans("core.modal.title"),
        text: this.trans("core.modal.confirmation-message"),
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then((result) => {
        if (result.value) {
          this.endpoint.splice(id, 1);
          this.closeInfowindowGoogle();
        }
      });
    },
    editLokasiMarker(id) {
      this.hideMarkerEndpoint(id);
      this.endpoint_index = id;
      this.process = "edit_location_marker_endpoint";
      this.changeLocation();
    },
    hideMarkerEndpoint(id) {
      let marker = this.findMarkerByEndPointIndex(id);
      console.log(marker);
      if (marker) {
        let latLng = new google.maps.LatLng(
          marker.position.lat,
          marker.position.lng
        );
        this.map.setCenter(latLng);
        this.infoWinOpen = false;
        marker.visibility = false;
        this.clearProcess();
        this.showInfoWindow(marker.icon);
      }
    },
    findMarkerByEndPointIndex(index) {
      return this.endpoint.find((item, i) => {
        return i == index;
      });
    },
    onMapDragend() {
      this.location.latitude = this.map.getCenter().lat();
      this.location.longitude = this.map.getCenter().lng();
      this.changeLocation();
    },
  },
  mounted() {
    this.$refs.map.$mapPromise.then((map) => {
      this.geolocate();
      this.map = map;
      this.geocoder = new google.maps.Geocoder();
      this.direction_service = new google.maps.DirectionsService();
    });
    this.$gmapApiPromiseLazy().then(() => {
      this.autocomplete = new google.maps.places.Autocomplete(
        document.getElementById(this.idAutoComplete)
      );

      this.autocomplete.addListener("place_changed", () => {
        const place = this.autocomplete.getPlace();
        let addressLatLng = new google.maps.LatLng(
          place.geometry.location.lat(),
          place.geometry.location.lng()
        );
        this.map.setCenter(addressLatLng);
        this.map.setZoom(16);
        this.location.latitude = place.geometry.location.lat();
        this.location.longitude = place.geometry.location.lng();
      });
    });
  },
  computed: {
    google: gmapApi,
    endpoint_transformer: function() {
      return this.endpoint.map((item) => {
        let icon = "";
        switch (item.tipe) {
          case "JB":
            icon =
              window.location.origin + "/modules/presale/img/jb-marker.ico";
            break;

          case "Fixing Slack":
            icon =
              window.location.origin +
              "/modules/presale/img/fixing-slack-marker.ico";
            break;

          default:
            icon =
              window.location.origin + "/modules/presale/img/odp-marker.ico";
            break;
        }

        item.icon = icon;
        return item;
      });
    },
  },
  watch: {
    endpoint: {
      handler: function(val, oldVal) {
        this.$emit("input", this.endpoint);
      },
      deep: true,
    },
  },
  created() {
    window.editDataMarker = this.editDataMarker;
    window.hapusDataMarker = this.hapusDataMarker;
    window.editLokasiMarker = this.editLokasiMarker;
  },
};
</script>

<style>
.center-map {
  z-index: 11;
  position: absolute;
  transform: translateY(-50%);
}
.map-button-add-group {
  z-index: 11;
  position: absolute;
  bottom: 20px;
  left: 75px;
  display: flex;
  flex-direction: column;
}
.swal2-container {
  z-index: 3010 !important;
}
.btn-action-marker {
  border-radius: 50px;
  width: 40px;
  height: 40px;
}
</style>
