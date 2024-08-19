import Form from "form-backend-validation";
import { makeArray } from "jquery";
import { forEach } from "lodash";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";

export default {
  mixins: [TranslationHelper],
  data() {
    return {
      endpoint: [],
      presales: [],
      markerWilayahTesting: [],
      markerWilayah: [],
      markerEndPoint: [],
      markerPresales: [],
      show_switch: false,
      show_ep: true,
      show_site: true,
      wilayah_selected: false,
      selected_detail: {},
    };
  },
  computed: {
    resultWilayah() {
      if (this.cari_wilayah) {
        return this.wilayah.filter((item) => {
          let condition1 = this.cari_wilayah
            .toLowerCase()
            .split(" ")
            .every((v) => item.name.toLowerCase().includes(v));
          let condition2 = this.cari_wilayah
            .toLowerCase()
            .split(" ")
            .every((v) => item.company_name.toLowerCase().includes(v));
          let condition3 = this.cari_wilayah
            .toLowerCase()
            .split(" ")
            .every((v) => item.kabupaten.toLowerCase().includes(v));
          let condition4 = this.cari_wilayah
            .toLowerCase()
            .split(" ")
            .every((v) => item.provinsi.toLowerCase().includes(v));
          if (condition1 || condition2 || condition3 || condition4) {
            return true;
          }
        });
      } else {
        return this.wilayah;
      }
    },
  },
  watch: {
    show_ep: function(value) {
      // console.log(this.endpoint);
      let vm = this;
      if (value) {
        this.markerEndPoint.forEach(function(val, index) {
          val.setMap(vm.map);
        });
      } else {
        this.markerEndPoint.forEach(function(val, index) {
          val.setMap(null);
        });
      }
    },
    show_site: function(value) {
      // console.log(value);
      // console.log(this.presales);
      let vm = this;
      if (value) {
        this.markerPresales.forEach(function(val, index) {
          val.setMap(vm.map);
        });
      } else {
        this.markerPresales.forEach(function(val, index) {
          val.setMap(null);
        });
      }
    },
    wilayah_selected: function(value) {
      let vm = this;
      if (value) {
        this.markerWilayah.forEach(function(val, index) {
          val.setMap(null);
        });
      } else {
        this.markerWilayah.forEach(function(val, index) {
          val.setMap(vm.map);
        });
      }
    },
  },
  methods: {
    indexMethod(index) {
      return index + 1;
    },
    geolocate: function() {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
      });
    },
    buildInfoWindow() {
      this.infoWindow = new google.maps.InfoWindow({
        content: "",
        minWidth: 250,
      });
    },
    fetchWilayah() {
      let self = this;
      axios
        .post(route("api.wilayah.wilayah.load_titik"))
        .then((response) => {
          let data = response.data;
          data.forEach(function(val, index) {
            let position = val.latLng;
            var latlng = new google.maps.LatLng(position.lat, position.lon);
            var newMarker = new google.maps.Marker({
              position: latlng,
              map: self.map,
              title: "wilayah-" + index,
              icon: {
                url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
                labelOrigin: new google.maps.Point(55, 10),
                scaledSize: new google.maps.Size(32, 38),
                size: new google.maps.Size(32, 32),
                anchor: new google.maps.Point(16, 32),
              },
              label: {
                text: val.name,
                fontSize: "14px",
                color: "Salmon",
                fontFamily: "montserrat",
              },
            });

            newMarker.metadata = { wilayah_id: val.wilayah_id };
            google.maps.event.addListener(newMarker, "click", function(event) {
              self.infoWilayah(val.wilayah_id);
            });

            self.markerWilayah.push(newMarker);
          });

          this.markerWilayah.forEach((value, index) => {
            this.namaWilayah.push(this.markerWilayah[index].label.text);
          });
          self.wilayah = data;
        })
        .catch((err) => {});
    },
    toggleSearch() {
      if (this.showList) {
        this.showList = !this.showList;
      }
      this.showSearch = !this.showSearch;
    },
    toggleList() {
      if (this.showSearch) {
        this.showSearch = !this.showSearch;
      }
      this.showList = !this.showList;
    },
    findingMarkerByWilayahId(wilayah_id) {
      for (var i = 0; i < this.markerWilayah.length; i++) {
        if (this.markerWilayah[i].metadata.wilayah_id == wilayah_id) {
          return this.markerWilayah[i];
        }
      }
      return false;
    },
    async infoWilayah(wilayah_id) {
      let marker = this.findingMarkerByWilayahId(wilayah_id);
      let position = marker.position;
      this.map.setCenter(
        new google.maps.LatLng(position.lat(), position.lng())
      );
      this.map.setZoom(18);
      this.infoWindow.setContent(
        `<center><div class="spinner-border text-info" role="status"></div></center>`
      );
      this.infoWindow.open(this.map, marker);
      this.showList = false;
      await this.detailWilayah(wilayah_id).then((val) => {
        this.selected_detail = val;
        // console.log(this.markerWilayah);
        let content = `<div>`;
        if (val.status == "request") {
          content += `<button type="button" class="btn btn-warning btn-sm" style="width:100%;">Request</button>`;
        } else if (val.status == "confirmed") {
          content += `<button type="button" class="btn btn-info btn-sm" style="width:100%;">Confirmed</button>`;
        } else if (val.status == "accepted") {
          content += `<button type="button" class="btn btn-success btn-sm" style="width:100%;">Accepted</button>`;
        }
        content += `<center><b><h3>${val.name}</h3></b><span>${
          val.company_name
        }</span></center>
                              <hr style="margin:5px 0px">
                              <div>
                                  <table style="width:100%;text-align:center;">
                                      <tbody>
                                        <tr>
                                          <td style="wdith:50%"><h3>${
                                            val.end_point
                                          }</h3><span>${this.trans(
          "pengajuans.title.end point"
        )}</span></td>
                                          <td><h3>${
                                            val.site
                                          }</h3><span>${this.trans(
          "pengajuans.title.cover site"
        )}</span></td>
                                        </tr>
                                      </tbody>
                                  </table>
                              </div><br>
                              <div style="width:100%;">
                                <center>
                                  <button type="button" onclick="showCoverage(${
                                    val.wilayah_id
                                  })" class="btn btn-info btn-circle">
                                    <i class="fa fa-map-marker"></i>
                                  </button>
                                  <button type="button" onclick="detailPengajuan(${
                                    val.wilayah_id
                                  })" class="btn-pengajuan-marker btn btn-success btn-circle">
                                    <i class="fa fa-edit"></i>
                                  </button>
                                </center>
                              </div>
                          </div>`;

        this.infoWindow.setContent(content);
      });
    },
    showDetailPengajuan(wilayah_id) {
      this.showContentModal();
      this.modalPengajuan.loading = true;
      let properties = {
        detail: "2",
      };
      axios
        .post(
          route("api.wilayah.wilayah.find", { wilayah: wilayah_id }),
          _.merge(properties)
        )
        .then((response) => {
          this.modalPengajuan.data = response.data.data;
          this.modalPengajuan.loading = false;
        })
        .catch((err) => {});
      $(".modal-pengajuan").modal();
    },
    fetchEndPoint(wilayah_id) {
      this.loading_map = true;
      axios
        .post(route("api.presale.endpoint.location"), {
          wilayah_id: wilayah_id,
        })
        .then((response) => {
          this.endpoint = response.data.data;
          this.showMarkerEndPoint();
        })
        .catch((err) => {});
    },
    fetchPresales(wilayah_id) {
      this.loading_map = true;
      axios
        .post(route("api.presale.presales.location"), {
          wilayah_id: wilayah_id,
        })
        .then((response) => {
          this.loading_map = false;
          this.presales = response.data.data;
          this.showMarkerPresales();
          this.wilayah_selected = true;
        })
        .catch((err) => {});
    },
    showMarkerPresales() {
      let vm = this;
      this.markerPresales = [];
      this.presales.forEach(function(val, index) {
        let icon = {
          url: val.icon,
          scaledSize: new google.maps.Size(50, 60),
        };
        var position = val.position;
        var latlng = new google.maps.LatLng(position.lat, position.lng);
        var newMarker = new google.maps.Marker({
          position: latlng,
          map: vm.map,
          title: "presale-" + index,
          icon: icon,
        });
        if (!vm.show_site) {
          newMarker.setMap(null);
        }
        vm.markerPresales.push(newMarker);
      });
    },
    showMarkerEndPoint() {
      let vm = this;
      this.markerEndPoint = [];
      this.endpoint.forEach(function(val, index) {
        let icon = {
          url: val.icon,
          scaledSize: new google.maps.Size(50, 60),
        };
        var position = val.position;
        var latlng = new google.maps.LatLng(position.lat, position.lng);
        var newMarker = new google.maps.Marker({
          position: latlng,
          map: vm.map,
          title: "ep-" + index,
          icon: icon,
        });
        if (!vm.show_ep) {
          newMarker.setMap(null);
        }
        vm.markerEndPoint.push(newMarker);
      });
    },
    detailWilayah(wilayah_id) {
      return new Promise((resolve, reject) => {
        let properties = {
          detail: "1",
        };
        axios
          .post(
            route("api.wilayah.wilayah.find", { wilayah: wilayah_id }),
            _.merge(properties)
          )
          .then((response) => {
            resolve(response.data.data);
          })
          .catch((err) => {});
      });
    },
    closeSelectedWilayah(id) {
      let vm = this;
      this.markerWilayah.forEach(function(marker, index) {
        if (marker.metadata.wilayah_id == id) {
          vm.wilayah_selected = false;
          marker.setMap(vm.map);
          vm.infoWindow.open(vm.map, marker);
          vm.closeAllMarkerEndPointAndSite();
          return false;
        }
      });
    },
    closeAllMarkerEndPointAndSite() {
      this.markerEndPoint.forEach(function(marker, index) {
        marker.setMap(null);
      });
      this.markerPresales.forEach(function(marker, index) {
        marker.setMap(null);
      });

      this.endpoint.length = 0;
      this.presales.length = 0;
      this.markerEndPoint.length = 0;
      this.markerPresales.length = 0;
    },
    showDetailCompany(company_id) {
      this.modalPengajuan.loading = true;
      axios
        .post(route("api.company.company.find", { company: company_id }))
        .then((response) => {
          this.modalPengajuan.loading = false;
          this.modalPengajuan.company_detail = response.data.data;
        });
      this.showContentModal("company");
    },
    showContentModal(type) {
      this.modalPengajuan.show.company = false;
      this.modalPengajuan.show.form = false;
      this.modalPengajuan.show.informasi = false;
      if (type == "company") {
        this.modalPengajuan.show.company = true;
      } else if (type == "form") {
        this.modalPengajuan.form.agree_syarat = false;
        this.modalPengajuan.show.form = true;
      } else {
        this.modalPengajuan.show.informasi = true;
      }
    },
    fetchCompany() {
      let properties = { type: "isp" };
      let routeUri = route("api.company.company.list", _.merge(properties));
      axios
        .post(routeUri)
        .then((response) => {
          this.companies = response.data;
        })
        .catch((error) => {
          if (error.response.status === 403) {
            this.$notify.error({
              title: this.trans("core.unauthorized"),
              message: this.trans("core.unauthorized-access"),
            });
          }
        });
    },
    submitPengajuan(formName) {
      let self = this;
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
              let wilayah_id = this.modalPengajuan.data.wilayah_id;
              this.modalPengajuan.loading = true;
              let form = new Form(this.modalPengajuan.form);
              form
                .post(
                  route("api.wilayah.pengajuan.submit", {
                    wilayah_id: wilayah_id,
                  })
                )
                .then((response) => {
                  self.modalPengajuan.loading = false;
                  self.modalPengajuan.form.agree_syarat = false;
                  self.showContentModal();
                  if (response.errors) {
                    self.Toast({
                      icon: "error",
                      title: response.message,
                    });
                  } else {
                    self.Toast({
                      icon: "success",
                      title: response.message,
                    });
                  }
                  $(".modal-pengajuan").modal("hide");
                  self.wilayah_selected = false;
                  self.closeAllMarkerEndPointAndSite();
                })
                .catch((error) => {
                  if (error.response.status === 403) {
                    self.Toast({
                      icon: "error",
                      title: response.message,
                    });
                    window.location = route("dashboard.index");
                  }
                  self.modalPengajuan.loading = false;
                });
            }
          });
        }
      });
    },
  },
  created() {
    const vm = this;
    window.detailPengajuan = function(id) {
      vm.showDetailPengajuan(id);
    };

    window.showCoverage = function(id) {
      vm.fetchEndPoint(id);
      vm.fetchPresales(id);
    };
  },
};
