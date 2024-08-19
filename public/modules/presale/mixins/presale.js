import _, { map } from "lodash";
import axios from "axios";
import html from "./html";
import { gmapApi } from "vue2-google-maps";

export default {
  mixins: [html],
  data() {
    return {
      showSearch: false,
      presale_active_line: [],
      presale: {},
      presale_id: null,
      presale_meta: {
        current_page: 1,
      },
      showedPresale: [],
      drawerCoverageEndpoint: false,
      drawerCoverage: {
        maximize: false,
        size: "30%",
      },
      polyLineEndpointToPresale: null,
      linesPreview: [],
      presale_position: {
        show: false,
        position: {
          lat: 0,
          lng: 0,
        },
        old_position: {
          lat: 0,
          lng: 0,
        },
        endpoint: {
          position: {
            lat: 0,
            lng: 0,
          },
        },
      },
      srcPreviewPresale: "",
      markerCurrentPosition: null,
      deltaLat: 0,
      deltaLng: 0,
      numDeltas: 100,
      data_biaya_kabel: {},

      custom_drawer: {
        show: false,
      },
      saldo: {
        saldo: 0,
      },
      configuration: {},
      list_confirmation_presale: [],
      autocomplete: null,
      request_presale: null,
      request_marker_presale_clicked: null,
    };
  },
  methods: {
    toggleSearch() {
      if (this.showList) {
        this.showList = !this.showList;
      }
      this.showSearch = !this.showSearch;
    },
    handleButtonAddPresaleClicked() {
      this.drawerCoverageEndpoint = true;
    },
    fetchPresale(url = route("api.presale.presales.index"), cancel = true) {
      const cancelSource = axios.CancelToken.source();
      if (cancel && this.request_presale) this.request_presale.cancel();
      this.request_presale = {
        cancel: cancelSource.cancel,
      };

      if (this.wilayah_id) {
        axios
          .post(
            url,
            {
              wilayah_id: this.wilayah_id,
            },
            {
              cancelToken: cancelSource.token,
            }
          )
          .then((response) => {
            if (response) {
              this.addMarkerPresales(response.data.data);
              let links = response.data.links;
              if (links.next) {
                setTimeout(() => {
                  this.fetchPresale(links.next, false);
                }, 1000);
              }
            }
          });
      }
    },
    addMarkerPresales(data) {
      data.forEach((presale) => {
        this.addMarkerPresale(presale);
      });
    },
    addMarkerPresale(presale) {
      this.presale[presale.presale_id] &&
        this.presale[presale.presale_id].setMap(null);
      let icon = {
        url: presale.icon,
        scaledSize: new google.maps.Size(50, 50),
      };

      let marker = new google.maps.Marker({
        position: presale.position,
        map: this.map,
        icon: icon,
        end_point_id: presale.end_point_id,
        panjang_kabel: presale.panjang_kabel,
        presale_id: presale.presale_id,
        site_id: presale.site_id,
        hasAccess: presale.hasAccess,
      });

      google.maps.event.addListener(marker, "click", (event) => {
        this.map.setCenter(marker.position);
        let position = {
          lat: marker.position.lat(),
          lng: marker.position.lng(),
        };
        this.batalTambahPresale();
        this.setInfoWindowRumah(presale, position);
      });

      this.presale[presale.presale_id] = marker;
    },
    handleSuccessKonfirmasi(presales) {
      this.addMarkerPresales(presales);
      this.list_confirmation_presale = [];
      this.closeInfowindowGoogle();
    },
    setInfoWindowRumah(presale, position) {
      if (this.process == "edit_location_marker_presale") {
        this.setContentInfoWindowGoogle(
          this.getHtmlEditPresale(position.lat(), position.lng()),
          position
        );
      } else {
        this.setContentInfoWindowGoogle("Loading. . . ", position);

        if (this.request_marker_presale_clicked)
          this.request_marker_presale_clicked.cancel();
        const cancelSource = axios.CancelToken.source();
        this.request_marker_presale_clicked = {
          cancel: cancelSource.cancel,
        };
        axios
          .post(
            route("api.presale.presales.html", { presale: presale.presale_id }),
            {},
            {
              cancelToken: cancelSource.token,
            }
          )
          .then((response) => {
            if (response) {
              this.setContentInfoWindowGoogle(response.data, position);
            }
          })
          .catch((err) => {});
      }
    },
    handleDrawerCoverageClickMaxMin(state) {
      this.drawerCoverage.maximize = state;
      this.drawerCoverage.size = state ? "60%" : "30%";
    },
    chooseEndpoint(endpoint) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          // title: 'Are you sure?',
          text: "Pilih Metode Jalur",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Otomatis",
          cancelButtonText: "Manual",
          reverseButtons: true,
        })
        .then(async (result) => {
          if (result.dismiss === "backdrop") return null;

          if (result.value) {
            await this.createPolyLineAndGetDistance(
              parseFloat(this.location.latitude),
              parseFloat(this.location.longitude),
              parseFloat(endpoint.position.lat),
              parseFloat(endpoint.position.lng),
              true
            );
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            await this.createPolyLineAndGetDistance(
              parseFloat(this.location.latitude),
              parseFloat(this.location.longitude),
              parseFloat(endpoint.position.lat),
              parseFloat(endpoint.position.lng),
              false
            );
          }
          this.presale_position.position.lat = parseFloat(
            this.location.latitude
          );
          this.presale_position.position.lng = parseFloat(
            this.location.longitude
          );
          this.presale_position.endpoint = endpoint;
          this.presale_position.show = true;
          this.drawerCoverageEndpoint = false;
          this.custom_drawer.show = true;
          this.closeInfowindow();
          this.setContentInfoWindowGoogle(
            this.getHtmlAddPresale(
              this.presale_position.position.lat,
              this.presale_position.position.lng
            ),
            this.presale_position.position
          );
          this.map.setZoom(19);
        });
    },
    async createPolyLineAndGetDistance(lat1, long1, lat2, long2, auto = true) {
      let result = {};
      result = await this.getDirectionRoute(lat1, long1, lat2, long2, auto);
      this.polyLineEndpointToPresale = new google.maps.Polyline({
        path: result.path,
        strokeColor: "#007bff",
        strokeOpacity: 0.8,
        strokeWeight: 5,
        geodesic: true,
        map: this.map,
        editable: true,
      });
    },
    pilihSemuaPresale() {
      Object.entries(this.presale).forEach(([key, presale]) => {
        if (!this.findListPresale(presale.presale_id)) {
          if (!presale.hasAccess) {
            let icon = {
              url:
                window.location.origin +
                "/modules/presale/img/pengajuan-selected.ico",
              scaledSize: new google.maps.Size(50, 50),
            };
            presale.setIcon(icon);
            this.list_confirmation_presale.push(presale);
          }
        }
      });
    },
    getDirectionRoute(lat1, long1, lat2, long2, auto = true) {
      return new Promise((resolve) => {
        let arrayPath = [],
          pathLine = [],
          result = {},
          distance = 0;
        this.direction_service.route(
          {
            origin: { lat: lat1, lng: long1 },
            destination: { lat: lat2, lng: long2 },
            travelMode: google.maps.TravelMode["WALKING"],
          },
          (response, status) => {
            if (status == "OK") {
              if (auto) {
                arrayPath = response.routes[0].overview_path;
              }
              distance = response.routes[0].legs[0].distance.value;

              let originLatLng = new google.maps.LatLng({
                lat: lat1,
                lng: long1,
              });
              var destLatLng = new google.maps.LatLng({
                lat: lat2,
                lng: long2,
              });
              pathLine = [originLatLng, ...arrayPath, destLatLng];

              result.path = pathLine;
              result.distance = distance;
              resolve(result);
            }
          }
        );
      });
    },
    closeCustomDrawerPresale() {
      this.custom_drawer.show = false;
    },
    center_changed(position) {
      this.location.latitude = position.lat();
      this.location.longitude = position.lng();
    },
    handleSuccessAddPresale(data) {
      this.clearProcess();
      $("#modal-add-presale").modal("hide");
      this.polyLineEndpointToPresale.setMap(null);
      this.presale_position.show = false;
      this.closeInfowindowGoogle();
      this.closeCustomDrawerPresale();
      this.addMarkerPresale(data);
    },
    handleSuccessUpdatePresale(presale) {
      console.log(presale);
      this.addMarkerPresale(presale);
      this.clearProcess();
      this.presale_id = null;
      $("#modal-add-presale").modal("hide");
    },
    handleCancelPresale() {
      if (this.presale_id) {
        this.clearProcess();
        this.presale_id = null;
      }
      $("#modal-add-presale").modal("hide");
    },
    batalTambahPresale() {
      this.presale_position.show = false;
      if (this.polyLineEndpointToPresale)
        this.polyLineEndpointToPresale.setMap(null);
      this.closeInfowindowGoogle();
      this.clearProcess();
      this.closeCustomDrawerPresale();
    },
    batalEditPresale() {
      this.presale[this.presale_id].setPosition(
        this.presale_position.old_position
      );
      this.presale[this.presale_id].setMap(this.map);
      this.polyLineEndpointToPresale
        ? this.polyLineEndpointToPresale.setMap(null)
        : "";
      this.closeInfowindowGoogle();
      this.closeInfowindow();
      this.clearProcess();
    },
    removeAllPresaleMarker() {
      Object.entries(this.presale).forEach(([index, marker]) => {
        marker.setMap(null);
      });
    },
    hideMarkerPresale(id) {
      let marker = this.presale.hasOwnProperty(id) ? this.presale[id] : false;
      if (marker) {
        this.presale_position.old_position = {
          lat: marker.position.lat(),
          lng: marker.position.lng(),
        };

        let latLng = new google.maps.LatLng(
          marker.position.lat(),
          marker.position.lng()
        );
        this.map.setCenter(latLng);
        this.infoWinOpen = false;
        marker.setMap(null);
        this.clearProcess();
        this.showInfoWindow(marker.icon.url);
      }
    },
    hidePolyLine() {},
    handlePositionFixedPresale() {
      let presale = this.presale[this.presale_id];
      let endpoint = this.findMarkerByEndPointId(presale.end_point_id);
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          // title: 'Are you sure?',
          text: "Pilih Metode Jalur",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Otomatis",
          cancelButtonText: "Manual",
          reverseButtons: true,
        })
        .then(async (result) => {
          if (result.value) {
            await this.createPolyLineAndGetDistance(
              parseFloat(this.location.latitude),
              parseFloat(this.location.longitude),
              parseFloat(endpoint.position.lat),
              parseFloat(endpoint.position.lng),
              true
            );
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            await this.createPolyLineAndGetDistance(
              parseFloat(this.location.latitude),
              parseFloat(this.location.longitude),
              parseFloat(endpoint.position.lat),
              parseFloat(endpoint.position.lng),
              false
            );
          }

          this.presale_position.position.lat = parseFloat(
            this.location.latitude
          );
          this.presale_position.position.lng = parseFloat(
            this.location.longitude
          );

          this.presale_position.endpoint.position.lat = parseFloat(
            endpoint.position.lat
          );
          this.presale_position.endpoint.position.lng = parseFloat(
            endpoint.position.lng
          );

          this.closeInfowindow();
          this.presale[this.presale_id].setPosition(
            this.presale_position.position
          );
          this.presale[this.presale_id].setMap(this.map);
          this.setContentInfoWindowGoogle(
            this.getHtmlEditPresale(
              this.presale_position.position.lat,
              this.presale_position.position.lng
            ),
            this.presale_position.position
          );
          this.map.setZoom(19);
          this.presale_position.endpoint = endpoint;

          this.resetLinesPreview();
        });
    },
    deletePresale(id) {
      this.closeInfowindowGoogle();
      this.presale[id].setMap(null);
    },

    setPlaceOnMap(lat, lng) {
      this.map.setZoom(19);
      this.map.setCenter({ lat: lat, lng: lng });
    },

    setCurrentPosition() {
      if (this.markerCurrentPosition == null) {
        this.setCurrent();
      } else {
        let lat = this.markerCurrentPosition.getPosition().lat();
        let long = this.markerCurrentPosition.getPosition().lng();
        this.setPlaceOnMap(lat, long);
      }
    },
    setCurrent() {
      let vm = this;
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            vm.setPlaceOnMap(
              position.coords.latitude,
              position.coords.longitude
            );
            // Place a marker
          },
          function(error) {
            if (error.code == error.PERMISSION_DENIED)
              vm.setPlaceOnMap(-2.548926, 118.0148634);
          }
        );

        let optn = {
          enableHighAccuracy: true,
          timeout: Infinity,
          maximumAge: 0,
        };
        navigator.geolocation.watchPosition(
          (position) => {
            // Place a marker
            let point = new google.maps.LatLng(
              position.coords.latitude,
              position.coords.longitude
            );
            if (this.markerCurrentPosition != null) {
              this.markerCurrentPosition.setPosition(point);
            } else {
              var icon = {
                url:
                  window.location.origin +
                  "/modules/presale/img/current-location.ico",
              };
              this.markerCurrentPosition = new google.maps.Marker({
                position: point,
                map: vm.map,
                icon: icon,
              });
            }
          },
          function(error) {},
          optn
        );
      }
    },
    transition(result) {
      this.deltaLat =
        (result[0] - this.markerCurrentPosition.getPosition().lat()) /
        this.numDeltas;
      this.deltaLng =
        (result[1] - this.markerCurrentPosition.getPosition().lng()) /
        this.numDeltas;
      this.moveMarker();
    },
    moveMarker() {
      let i = 0;
      let position1 =
        this.markerCurrentPosition.getPosition().lat() + this.deltaLat;
      let position2 =
        this.markerCurrentPosition.getPosition().lng() + this.deltaLng;
      var latlng = new google.maps.LatLng(position1, position2);
      this.markerCurrentPosition.setPosition(latlng);
      if (i != this.numDeltas) {
        i++;
        setTimeout(this.moveMarker(), 2);
      }
    },
    resetLinesPreview() {
      for (var i = 0; i < this.linesPreview.length; i++) {
        this.linesPreview[i].setMap(null);
      }
      this.presale_active_line = [];
      this.linesPreview = [];
      this.idPresaleAktifLine = [];
    },
    gotoMarkerPresale(id) {
      this.map.setZoom(20);
      new google.maps.event.trigger(this.presale[id], "click");
    },
    showFormPelanggan(presale_id) {
      this.$router.push({
        name: "admin.pelanggan.form.step",
        query: { presales: presale_id },
      });
    },
    handleShowSearched(data) {
      this.showedPresale = [];
      Object.entries(this.presale).forEach(([key, presale]) =>
        presale.setMap(null)
      );
      data.forEach((presale) => {
        if (!this.presale.hasOwnProperty(presale.presale_id))
          this.addMarkerPresale(presale);
        if (this.presale[presale.presale_id]) {
          this.presale[presale.presale_id].setMap(this.map);
          this.showedPresale.push(presale.presale_id);
        }
      });

      Swal.fire("Berhasil", "", "success");
      $("#modal-list-presale").modal("hide");
    },
    addListConfirmation(presale_id) {
      if (this.findListPresale(presale_id)) {
        this.Toast({
          icon: "info",
          title: this.trans("presales.selected"),
        });
      } else {
        if (this.presale[presale_id]) {
          let icon = {
            url:
              window.location.origin +
              "/modules/presale/img/pengajuan-selected.ico",
            scaledSize: new google.maps.Size(50, 50),
          };
          this.presale[presale_id].setIcon(icon);
          this.list_confirmation_presale.push(this.presale[presale_id]);
        }
      }
    },
    findListPresale(presale_id) {
      return this.list_confirmation_presale.find(
        (item) => item.presale_id == presale_id
      );
    },
    setSidebarToggleWidthMap() {
      document
        .querySelector(".sidebartoggler")
        .addEventListener("click", function() {
          setTimeout(function() {
            let p = document.querySelector("#app");
            var style = p.currentStyle || window.getComputedStyle(p);
            style = style.marginLeft;
            document.querySelector(".map-wrapper").style.width =
              "calc( 100% - " + style + ")";
          }, 500);
        });
    },
    showFormAddPresale() {
      this.modalPresaleKey++;
      setTimeout(() => {
        $("#modal-add-presale").modal("show");
      }, 500);
    },
    konfirmasiPresale() {
      $("#modal-list-konfirmasi").modal("show");
    },
    cancelKonfirmasiPresale() {
      this.list_confirmation_presale.map((presale, key) => {
        let icon = {
          url: window.location.origin + "/modules/presale/img/pengajuan.ico",
          scaledSize: new google.maps.Size(50, 50),
        };

        presale.setIcon(icon);
      });
      this.list_confirmation_presale = [];
    },
    fetchBiayaKabel() {
      axios
        .get(route("api.company.biayakabel.index.presale"), {
          params: {
            company_id: this.company_id,
          },
        })
        .then((response) => {
          this.data_biaya_kabel = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    fetchBiayaKonfirmasi() {
      axios.get(route("api.configuration.index")).then((response) => {
        this.configuration = response.data.data;
      });
    },
    async handleRefresh() {
       

      if (this.request_endpoint) this.request_endpoint.cancel();
      await this.fetchPresale();
      await this.fetchEndpoint(this.wilayah_id);

       
    },
  },
  computed: {
    google: gmapApi,
    canAddPresale: function() {
      console.log(this.hasAccess("presale.presales.create"));
      return this.hasAccess("presale.presales.create");
    },
    canAddEndpoint: function() {
      console.log(this.hasAccess("presale.endpoints.create"));
      return this.hasAccess("presale.endpoints.create");
    },
  },
  watch: {
    company_id: function() {
      this.fetchBiayaKabel();
    },
  },
  mounted() {
    this.$refs.map.$mapPromise.then((map) => {
      this.setSidebarToggleWidthMap();
      this.fetchPresale();
      this.setCurrent();
      this.fetchBiayaKonfirmasi();
      this.autocomplete = new google.maps.places.Autocomplete(
        document.getElementById("search_map_presale")
      );

      google.maps.event.addListener(this.map, "click", (event) => {
        if(this.infoWinOpen){
          setTimeout(() => {
            this.closeInfowindowGoogle();
          }, 2000);
        }
      });

      google.maps.event.addListener(this.map, "dragend", (event) => {
        if(this.infoWinOpen){
          setTimeout(() => {
            this.closeInfowindowGoogle();
          }, 2000);
        }
      });

      this.autocomplete.addListener("place_changed", () => {
        const place = this.autocomplete.getPlace();
        let addressLatLng = new google.maps.LatLng(
          place.geometry.location.lat(),
          place.geometry.location.lng()
        );
        this.map.setCenter(addressLatLng);
        this.map.setZoom(16);
        $("#search_map_presale").val("");
        $("#search_map_presale")
          .parent()
          .find(".srh-btn")
          .trigger("click");
      });
    });
  },
  created() {
    const vm = this;
    (window.addListConfirmation = vm.addListConfirmation),
      (window.batalTambahPresale = vm.batalTambahPresale),
      (window.batalEditPresale = vm.batalEditPresale),
      (window.saveLocationPresale = function() {
        let properties = {
          lat: vm.presale_position.position.lat,
          lon: vm.presale_position.position.lng,
          path: vm.polyLineEndpointToPresale.getPath().getArray(),
        };

        axios
          .post(
            route("api.presale.presale.location.update", {
              presale: vm.presale_id,
            }),
            properties
          )
          .then((response) => {
            vm.closeInfowindowGoogle();
            vm.polyLineEndpointToPresale.setMap(null);
            vm.clearProcess();
            vm.Toast({
              icon: "success",
              title: response.data.message,
            });
          })
          .catch((err) => {
            vm.Toast({
              icon: "error",
              title: "something error",
            });
            vm.clearProcess();
            console.log(err);
          });
      });
    (window.showRoutes = async function(id, loading = 1) {
      let pathLine = [];
      if (vm.presale_active_line.indexOf(id) == -1) {
        if (loading == 1) {
          Swal.fire({
            title: "Loading..",
            html: "",
            allowOutsideClick: false,
            onOpen: () => {
              swal.showLoading();
            },
          });
        }
        await axios
          .get(route("api.presale.presale.routes", { presale: id }))
          .then((response) => {
            response.data.forEach((path) => {
              pathLine.push(
                new google.maps.LatLng({
                  lat: path.lat,
                  lng: path.lon,
                })
              );
            });

            var line = new google.maps.Polyline({
              path: pathLine,
              strokeColor: "#007bff",
              strokeOpacity: 0.8,
              strokeWeight: 5,
              geodesic: true,
              map: vm.map,
              editable: false,
            });

            vm.presale_active_line.push(id);
            vm.showedPresale.push(id);
            vm.linesPreview.push(line);
          });
      } else {
        vm.linesPreview[vm.presale_active_line.indexOf(id)].setMap(null);
        vm.presale_active_line[vm.presale_active_line.indexOf(id)] = 0;
      }

      Swal.close();
    }),
      (window.showInterkoneksiPresale = function(id_endpoint) {
        Swal.fire({
          title: "Loading..",
          html: "",
          allowOutsideClick: false,
          onOpen: () => {
            swal.showLoading();
          },
        });
        axios
          .get(route("api.presale.endpoint.routes", { endpoint: id_endpoint }))
          .then((response) => {
            response.data.data.forEach((presale) => {
              if (vm.presale[presale.presale_id])
                vm.presale[presale.presale_id].setMap(vm.map);
              let pathLine = [];
              presale.routes.forEach((pathline) => {
                pathLine.push(
                  new google.maps.LatLng({
                    lat: pathline.lat,
                    lng: pathline.lon,
                  })
                );
              });

              var line = new google.maps.Polyline({
                path: pathLine,
                strokeColor: "#007bff",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                geodesic: true,
                map: vm.map,
              });

              vm.linesPreview.push(line);
              vm.presale_active_line.push(presale.presale_id);

              vm.showedPresale.push(presale.presale_id);
            });

            Swal.close();
          });
      }),
      (window.showFormPelanggan = vm.showFormPelanggan);
  },
};
