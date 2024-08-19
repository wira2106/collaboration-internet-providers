import axios from "axios";
import { gmapApi } from "vue2-google-maps";
import { mapGetters } from "vuex";
import PermissionsHelper from "../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../Core/Assets/js/mixins/Toast";
import TranslationHelper from "../../../Core/Assets/js/mixins/TranslationHelper";
import generate_icon_endpoint from "./generate_icon_endpoint";

export default {
  mixins: [TranslationHelper, PermissionsHelper, Toast, generate_icon_endpoint],
  data() {
    return {
      end_point_id: null,
      process: "",
      company_id: "",
      wilayah_id: "",
      activeWilayah: {},
      companies: [],
      base_url: window.location.origin,
      region: [],
      searchQueryRegion: "",
      currentMidx: null,
      maximumCoverage: 0.5,
      infowindow: {
        add: {
          showed: false,
          iconAdd: "",
          html: "",
          render: false
        }
      },
      icon: {
        odp: window.location.origin + "/modules/presale/img/odp-marker.ico",
        presale_add:
          window.location.origin + "/modules/presale/img/presale-add.ico"
      },
      button: {
        group: {
          showed: false
        }
      },
      map: null,
      geocoder: null,
      direction_service: null,
      wilayah_name: "",
      endpoint: [],
      location: {
        latitude: -8.630067813021054,
        longitude: 115.26384524187176,
        address: ""
      },
      endpoint_selected_to_pay: [],
      center: { lat: -8.630067813021054, lng: 115.26384524187176 },
      infoWindowPos: null,
      infoWinOpen: false,
      infoOptions: {
        content: `<a href="#">Loading . . .</>`,
        //optional: offset infowindow so it visually sits nicely on top of our marker
        pixelOffset: {
          width: 0,
          height: -60
        }
      },
      cancelTokenSourceFindRegion: null,
      request_endpoint: null,
      request_marker_endpoint_click: null
    };
  },

  mounted() {
    this.$store.dispatch("fetchConfig");
    this.$refs.map.$mapPromise.then(map => {
      this.geolocate();
      this.map = map;
      this.geocoder = new google.maps.Geocoder();
      this.direction_service = new google.maps.DirectionsService();
      // this.$refs.map.$on('center_changed', (location) => {
      //   this.position
      // })
      this.fetchRegion(this.$route.query.wilayah).then(response => {
        var user_role = this.user_roles.name;

        if (response.data.wilayah.length == 1) {
          return this.handleChangeRegion(
            parseInt(response.data.wilayah[0].wilayah_id)
          );
        }
        
        if (response.data.wilayah.length == 0) {
          if (user_role === "Admin ISP") {
            this.$router.push({ name: "admin.wilayah.pengajuan.create" });
          } else if (user_role === "Admin OSP") {
            this.$router.push({ name: "admin.wilayah.wilayahs.create" });
          }
        } else if (!this.$route.query.wilayah) {
          $("#changeWilayah").modal("show");
          this.swal.close()
        } else {
          this.handleChangeRegion(parseInt(this.$route.query.wilayah));
        }
      });
    });
  },
  methods: {
    showAddButtonGroup() {
      let condition =
        this.tour_type == "tambah_endpoint" ||
        this.tour_type == "tambah_presale";
      document.querySelectorAll(".btn-child-add").forEach(element => {
        if (condition && this.button.group.showed) return;
        let marginLeftValue = !this.button.group.showed ? "0px" : "-55px";
        $(element).css("margin-left", marginLeftValue);
      });

      if (condition) {
        this.nextStep();
      }
      if (!condition) this.button.group.showed = !this.button.group.showed;
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
    handleCloseMarkerAddEndpoint() {
      this.closeInfowindow();
      if (this.tour_type === "tambah_endpoint") this.finishStep();
    },
    closeInfowindow() {
      this.infowindow.add.showed = false;
      this.infowindow.add.render = false;
    },
    closeInfowindowAddPresale(cancel = false) {
      this.infowindow.add.showed = false;
      this.infowindow.add.render = false;
      if (cancel && this.tour_type === "tambah_endpoint") this.finishStep();
    },
    closeInfowindowAndShowMarker() {
      this.closeInfowindow();
      let marker = this.findMarkerByEndPointId(this.end_point_id);
      marker.visibility = true;
      this.clearProcess();
    },
    handleButtonPengaturan() {
      if (
        this.tour_type === "hide_marker_presale" ||
        this.tour_type === "hide_marker_endpoint"
      )
        this.nextStep();
    },
    handleShowMarkerRumah() {
      if (this.tour_type === "hide_marker_presale") this.finishStep();
    },
    handleCloseEditLocationEndpoint() {
      this.closeInfowindowAndShowMarker();
      if (this.tour_type == "edit_location_endpoint") this.finishStep();
    },
    handleSubmitEditLocationEndpoint() {
      this.changeLocation();
    },
    closeInfowindowGoogle() {
      this.infoWinOpen = false;
    },
    setContentInfoWindowGoogle(content, position) {
      this.infoWinOpen = true;
      this.infoOptions.content = content;
      this.infoWindowPos = position;
    },
    modalAddEndpointClosed() {
      if (this.tour_type == "tambah_endpoint") this.prevStep();
    },
    handleUpdatedMarker(position) {
      let marker = this.findMarkerByEndPointId(this.end_point_id);
      marker.position.lat = position.latitude;
      marker.position.lng = position.longitude;
      this.closeInfowindowAndShowMarker();
    },
    closePopover(event) {
      this.infowindow.add.showed = false;
    },
    openOLChangeWilayah() {
      $("#changeWilayah").modal("show");
    },
    fetchRegion(wilayah_id = null) {
      return new Promise(resolve => {
        let properties = {
          company_id: this.company_id,
          wilayah: wilayah_id
        };

        axios
          .post(route("api.presale.region.index"), properties)
          .then(response => {
            this.region = response.data.wilayah;
            this.companies = response.data.companies;
            this.company_id = response.data.selected.company_id;
            if (response.data.selected.wilayah) {
              this.wilayah_id = response.data.selected.wilayah.wilayah_id;
              this.wilayah_name = response.data.selected.wilayah.name;
              let wilayah = this.findRegion(this.wilayah_id);
              if (wilayah) {
                this.activeWilayah = wilayah;
              }
            }
            resolve(response);
          });
      });
    },
    fetchEndpoint(wilayah_id) {
      const cancelSource = axios.CancelToken.source();
      if (this.request_endpoint) this.request_endpoint.cancel();
      this.request_endpoint = {
        cancel: cancelSource.cancel
      };

      axios
        .post(
          route("api.presale.endpoint.location"),
          { wilayah_id: wilayah_id },
          {
            cancelToken: cancelSource.token
          }
        )
        .then(response => {
          if (response) {
            this.endpoint = response.data.data;
            const bounds = new google.maps.LatLngBounds();
            response.data.data.map(endpoint => {
              bounds.extend(endpoint.position)
            })
            this.map.fitBounds(bounds)
            this.Toast({
              icon: "success",
              title: this.trans("endpoint.message.wilayah changed")
            });
          }
        })
        .catch(err => {
          this.Toast({
            icon: "error",
            title: this.trans("core.error 500 title")
          });
        });
    },
    handleSwitchEndpoint(check) {
      this.endpoint = this.endpoint.map(item => {
        item.visibility = check;
        return item;
      });

      if (this.tour_type === "hide_marker_endpoint") this.finishStep();
    },
    async handleChangeRegion(wilayah_id = null) {
      $("#changeWilayah").modal("hide");
      this.swal.loading();

      let wilayah = await this.findRegion(wilayah_id);
      if (wilayah) {
        // Fetch biaya paket berlangganan sesuai request wilayah
        this.fetchBiayaPaketBerlangganan(wilayah_id);
        if (this.company_id != wilayah.company_id) {
          this.company_id = wilayah.company_id;
          this.fetchRegion(wilayah.wilayah_id);
        }

        this.activeWilayah = wilayah;
        this.closeInfowindowGoogle();

        this.wilayah_id = wilayah_id;
        this.wilayah_name = wilayah.name;
        this.fetchEndpoint(wilayah_id);
        this.fetchPresale();
        this.removeAllPresaleMarker();
      }
    },
    findRegion(id) {
      if (this.cancelTokenSourceFindRegion)
        this.cancelTokenSourceFindRegion.cancel();
      return new Promise(resolve => {
        this.cancelTokenSourceFindRegion = axios.CancelToken.source();
        axios
          .post(
            route("api.wilayah.wilayah.find", {
              wilayah: id
            }),
            {
              cancelToken: this.cancelTokenSourceFindRegion.token
            }
          )
          .then(response => {
            return resolve(response.data.data);
          })
          .catch(err => {
            resolve(null);
          });
      });
    },
    geolocate() {
      navigator.geolocation.getCurrentPosition(position => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
      });
    },
    handleMarkerEndpointClicked(m, index) {
      this.clearProcess();
      this.infoWindowPos = m.position;
      this.center = m.position;

      this.infoOptions.content = "loading . . .";
      if (this.currentMidx == index) {
        this.infoWinOpen = !this.infoWinOpen;
      }
      //if different marker set infowindow to open and reset current marker index
      else {
        this.infoWinOpen = true;
        this.currentMidx = index;
      }
      this.closeInfowindow();

      if (this.infoWinOpen) {
        const cancelSource = axios.CancelToken.source();
        if (this.request_marker_endpoint_click)
          this.request_marker_endpoint_click.cancel();

        this.request_marker_endpoint_click = {
          cancel: cancelSource.cancel
        };

        axios
          .post(
            route("api.presale.endpoint.detail.html", {
              endpoint: m.end_point_id
            }),
            {},
            {
              cancelToken: cancelSource.token
            }
          )
          .then(response => {
            if (response) {
              this.infoOptions.content = response.data;
              let isSelectedConfirmation = this.endpoint_selected_to_pay.find(item => item.end_point_id === m.end_point_id) ? true : false;
              this.setContentInfoWindowGoogle(this.getHtmlDetailEndpoint(response.data, isSelectedConfirmation) , m.position)
              if (
                this.tour_type === "hapus_endpoint" ||
                this.tour_type === "edit_data_endpoint" ||
                this.tour_type === "edit_location_endpoint" ||
                this.tour_type === "tampilkan_jalur_kabel_endpoint_ke_presale"
              ) {
                setTimeout(() => {
                  this.$tours[this.tour_type].start(1);
                }, 600);
              }

              if (m.disable && this.tour_type === "konfirmasi_endpoint") {
                setTimeout(() => {
                  this.$tours[this.tour_type].start(1);
                }, 600);
              }
            }
          });
      }
    },
    handleCloseEditEndpoint() {
      if (this.tour_type == "edit_data_endpoint") this.finishStep();
    },
    handleSuccessEditEndpoint() {
      this.closeInfowindowGoogle();
      if (this.tour_type == "edit_data_endpoint") this.finishStep();
    },
    handleMarkerPresaleAddClicked(m) {
      this.infoWindowPos = m.position;
      this.center = m.position;
      this.infoWinOpen = !this.infoWinOpen;
    },
    findMarkerByEndPointId(id) {
      return this.endpoint.find((item, index) => item.end_point_id === id);
    },
    findIndexMarkerByEndpointId(id) {
      return this.endpoint.findIndex((item, index) => item.end_point_id === id);
    },
    hideMarkerEndpoint(id) {
      let marker = this.findMarkerByEndPointId(id);
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
    showMarkerEndpoint() {
      let marker = this.findMarkerByEndPointId(this.end_point_id);
      if (marker) {
        marker.visibility = true;
        this.end_point_id = null;
      }
    },
    onMapDragend() {
      this.location.latitude = this.map.getCenter().lat();
      this.location.longitude = this.map.getCenter().lng();
      this.changeLocation();
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
    pilihEndpoint(endpoint_id) {
      if (
        !this.endpoint_selected_to_pay.find(
          item => item.end_point_id === endpoint_id
        )
      ) {
        this.endpoint_selected_to_pay.push(
          this.findMarkerByEndPointId(endpoint_id)
        );
        let endpoint = this.findMarkerByEndPointId(endpoint_id);

        $('.btn-action-marker-close-selected').show();
        $('.btn-action-marker-select').hide();

        if (endpoint) {
          endpoint.icon = this.generate_icon_endpoint(endpoint.tipe);
          if (endpoint.disable && this.tour_type === "konfirmasi_endpoint")
            this.nextStep();
        }
      } else {
        // hapus dari endpoint terpilih
        let endpoint = this.endpoint_selected_to_pay.findIndex(
          (item, index) => item.end_point_id === endpoint_id
        );

        let endpoint_1 = this.findMarkerByEndPointId(endpoint_id);
        if (endpoint_1) {
          endpoint_1.icon = this.generate_icon_endpoint(endpoint_1.tipe, false);
        }

        $('.btn-action-marker-close-selected').hide();
        $('.btn-action-marker-select').show();

        if (endpoint > -1) {
          this.endpoint_selected_to_pay.splice(endpoint, 1);
        }
      }
    },
    pilihSemuaEndpoint() {
      this.endpoint.map(endpoint => {
        if (endpoint.disable) {
          if (
            !this.endpoint_selected_to_pay.find(
              item => item.end_point_id === endpoint.end_point_id
            )
          ) {
            this.endpoint_selected_to_pay.push(endpoint);
            endpoint.icon = this.generate_icon_endpoint(endpoint.tipe);
          }
        }
      });

      $('.btn-action-marker-close-selected').show();
      $('.btn-action-marker-select').hide();
    },
    clearProcess() {
      this.process = "";
      this.presale_id = null;
      this.end_point_id = null;
    },
    handleUpdatedMarker(position) {
      let marker = this.findMarkerByEndPointId(this.end_point_id);
      marker.position.lat = position.latitude;
      marker.position.lng = position.longitude;
      marker.visibility = true;
      this.clearProcess();
      this.closeInfowindow();
      if (this.tour_type == "edit_location_endpoint") this.finishStep();
    },
    handleSuccessAddEndpoint(data) {
      if (this.tour_type === "tambah_endpoint") {
        this.finishStep();
      }
      this.endpoint.push(data);
    },
    deleteEndpoint(id) {
      let indexMarker = this.findIndexMarkerByEndpointId(id);
      this.endpoint.splice(indexMarker, 1);
      this.closeInfowindowGoogle();
    },
    gotoMarkerEndpoint(id) {
      let marker_index = this.findIndexMarkerByEndpointId(id);
      if (marker_index > -1) {
        let marker = this.endpoint[marker_index];
        $("#modal-list-endpoint").modal("hide");
        this.setPlaceOnMap(marker.position.lat, marker.position.lng);
        this.handleMarkerEndpointClicked(marker, marker_index);
      }
    },
    konfirmasiEndpoint() {
      if (this.tour_type === "konfirmasi_endpoint") this.finishStep();
      $("#modal-cart").modal("show");
    },
    cancelKonfirmasiEndpoint() {
      this.endpoint_selected_to_pay.forEach((endpoint, index) => {
        let endpoint_1 = this.findMarkerByEndPointId(endpoint.end_point_id);
        if (endpoint_1) {
          endpoint_1.icon = this.generate_icon_endpoint(endpoint_1.tipe, false);
        }
      });
      $('.btn-action-marker-close-selected').hide();
      $('.btn-action-marker-select').show();
      this.endpoint_selected_to_pay = [];
    },
    handleEndpointPay() {
      Swal.fire({
        title: this.trans("core.messages.confirmation-form"),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel")
      }).then(result => {
        if (result.value) {
          let properties = {
            endpoints: this.endpoint_selected_to_pay
          };

          axios
            .post(
              route("api.presale.endpoint.bayar", {
                wilayah: this.wilayah_id
              }),
              properties
            )
            .then(response => {
              this.endpoint_selected_to_pay = [];
              this.closeInfowindowGoogle();
              this.$store.dispatch("getSaldo");
              this.Toast({
                icon: "success",
                title: response.data.message
              });
              this.handleRefresh();
            })
            .catch(err => {
              if (err.response.status === 417) {
                return this.Toast({
                  icon: "info",
                  title: err.response.data.message
                });
              }
              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title")
              });
            });
        }
      });
    }
  },
  computed: {
    ...mapGetters(["config"]),
    carts() {
      if (!this.endpoint_selected_to_pay) return [];
      return this.endpoint_selected_to_pay.map(item => {
        return {
          name: item.name,
          qty: 1,
          harga: this.config.biaya_ep
        };
      });
    },
    endpoint_disable() {
      let endpoint = this.endpoint.find(item => item.disable);
      if (endpoint) return true;
      return false;
    },
    regionFilter: function() {
      if (this.searchQueryRegion) {
        return this.region.filter(item => {
          return this.searchQueryRegion
            .toLowerCase()
            .split(" ")
            .every(v => item.name.toLowerCase().includes(v));
        });
      } else {
        return this.region;
      }
    },
    google: gmapApi,
    distanceToEndpoint: function() {
      let arrayDistanceToEndpoint = [];
      if (
        this.process == "presale" ||
        this.process == "edit_location_marker_presale" ||
        this.process == "edit_location_marker_presale_change_endpoint"
      ) {
        this.endpoint.forEach(ep => {
          ep.distance = this.getDistanceFromLatLonInKm(
            this.map.getCenter().lat(),
            this.map.getCenter().lng(),
            ep.position.lat,
            ep.position.lng
          );
          if (ep.distance < this.maximumCoverage) {
            arrayDistanceToEndpoint.push(ep);
          }
        });

        arrayDistanceToEndpoint.sort(function(a, b) {
          if (a.distance < b.distance) {
            return -1;
          }

          if (a.distance == b.distance) {
            return 0;
          }

          if (a.distance > b.distance) {
            return 1;
          }
        });
      }

      return arrayDistanceToEndpoint;
    }
  }
};
