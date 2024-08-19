import axios from "axios";
import { gmapApi } from "vue2-google-maps";
import Form from "form-backend-validation";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import SingleFileSelector from "../../../../Media/Assets/js/mixins/SingleFileSelector";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import PermissionsHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";
import UserRolesPermission from "../../../../Core/Assets/js/mixins/UserRolesPermission";
import UploadPreview from "../../../../Core/Assets/js/mixins/UploadPreview";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import JadwalSurvey from "../components/pengajuan/JadwalSurvey";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";
import Cart from "../../../../Core/Assets/js/components/Cart";

export default {
  props: ["stepSekarang"],
  mixins: [
    ShortcutHelper,
    SingleFileSelector,
    TranslationHelper,
    UserRolesPermission,
    UploadPreview,
    Toast,
    PermissionsHelper,
  ],
  components: {
    JadwalSurvey: JadwalSurvey,
    InputCurrency: InputCurrencyVue,
    Cart,
  },
  data() {
    return {
      pickerOptions: {
        disabledDate(time) {
          let date = new Date();
          date.setDate(date.getDate() - 1);
          return time.getTime() < date.getTime();
        },
      },
      presale_id: null,
      pelanggan_id: null,
      map: null,
      mapInfo: null,
      form: {
        presale_id: null,
        pelanggan_id: null,
        site_id: "",
        nama_pelanggan: "",
        telepon: null,
        email: "",
        jenis_identitas: "",
        nomor_identitas: "",
        status_kepemilikan: null,
        address: "",
        paket_id: null,
        namaPaket: "",
        biaya_mrc: 0,
        biaya_otc: 0,
        biaya_mrc_with_format: "",
        biaya_otc_with_format: "",
        foto_identitas: null,
        foto_jalur_ep: null,
        rekomendasiExist: false,
        tgl_rekomendasi: null,
        jam_rekomendasi: new Date(2016, 9, 10, 18, 40),
        keterangan: null,
        status_pelanggan: "",
        foto_identitas_pw: null,
      },
      finishSO: false,
      paket: [],
      photo: {
        foto_identitas_old: null,
        foto_identitas_pw: null,
        foto_rumah: null,
      },
      infoWindow: null,
      infoWindowAktif: null,
      loading: false,

      rules: {
        tgl_rekomendasi: [
          {
            required: true,
            message: this.trans("core.form.required"),
            trigger: ["blur"],
          }
        ],
        jam_rekomendasi: [
          {
            required: true,
            message: this.trans("core.form.required"),
            trigger: ["blur"],
          }
        ],
        nama_pelanggan: [
          {
            required: true,
            message: this.trans("core.form.required"),
            trigger: ["blur"],
          },
        ],
        foto_identitas_pw: [
          {
            required: true,
            ref: "foto_identitas_pw",
            message: this.trans("core.form.required"),
            trigger: ["change"],
          },
        ],
        required_paket: [
          {
            required: true,
            message: this.trans("core.form.required"),
            trigger: ["change"],
          },
          { validator: this.validatorPaket, trigger: ["blur", "change"] },
        ],
        amount: [
          { validator: this.validAmount, required: true, trigger: "change" },
        ],
        telepon: [
          {
            required: true,
            trigger: ["blur"],
            message: this.trans("core.form.required"),
          },
          { validator: this.validPhone, required: true, trigger: "change" },
        ],
        jenis_identitas: [
          {
            required: true,
            trigger: ["change"],
            message: this.trans("core.form.required"),
          },
        ],
        status_kepemilikan: [
          {
            required: true,
            trigger: ["change"],
            message: this.trans("core.form.required"),
          },
        ],
        nomor_id: [
          {
            required: true,
            trigger: ["blur"],
            message: this.trans("core.form.required"),
          },
          {
            validator: this.validOnlyNumber,
            trigger: "blur",
            required: true,
          },
        ],
      },
      selectableRange: ["00:00", "00:00"],
    };
  },
  computed: {
    google: gmapApi,
    selectableRangePicker: function() {
      return [
        this.selectableRange[0].substring(0, 5),
        this.selectableRange[1].substring(0, 5),
      ];
      // return `${this.selectableRange[0]} - ${this.selectableRange[1]}`;
    },
    carts: function() {
      return [
        {
          name: this.trans("salesorders.form.biaya_mrc"),
          qty: 1,
          harga: this.form.biaya_mrc,
        },
        {
          name: this.trans("salesorders.form.biaya_otc"),
          qty: 1,
          harga: this.form.biaya_otc,
        },
      ];
    },
  },
  watch: {
    "photo.foto_identitas_pw": {
      handler: function(after, before) {
        if (after == null && this.photo.foto_identitas_old != null) {
          this.photo.foto_identitas_pw = this.photo.foto_identitas_old;
          $(".fancybox").fancybox();
        }
        this.form.foto_identitas_pw = this.photo.foto_identitas_pw;
      },
    },
  },
  methods: {
    onConfirm() {
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
              route("api.pelanggan.form.submit.so"),
              this.jsonToFormData(this.form),
              config
            )
            .then((response) => {
              let data = response.data;
              this.Toast({
                icon: "success",
                title: data.message,
              });
              window.location =
                route("admin.pelanggan.form.step") +
                "?pelanggan=" +
                data.pelanggan;
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
    },
    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        let fields = this.$refs[formName].fields;
        for (let i = 0; i < fields.length; i++) {
          if (fields[i].validateState == "error") {
            console.log(fields[i]);
            $(fields[i].$el)
              .find("input")
              .focus();
            return false;
          }
        }
        if (valid) {
          if (this.pelanggan_id) {
            return this.onConfirm();
          }
          $("#cart-sales-order").modal();
        }
      });
    },
    validAmount(rule, value, callback) {
      if (value <= 0 || isNaN(value))
        return callback(
          new Error(this.trans("configurations.validation.invalid number"))
        );
      callback();
    },
    validPhone(rule, value, callback) {
      if (isNaN(value)) {
        this.form.telepon = 0;
      } else if (value <= 0) {
        return callback(new Error(this.trans("core.validation.phone")));
      }
      callback();
    },
    validOnlyNumber(rule, value, callback) {
      if (value <= 0 || isNaN(value))
        return callback(new Error(this.trans("core.validation.only number")));
      callback();
    },
    validatorPaket(rule, value, callback) {
      if (value == "" || value == null) {
        return callback(new Error(this.trans("core.form.required")));
      } else {
        this.setBiayaMRCOTC();
        callback();
      }
    },
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
        .post(route("api.pelanggan.form.detail.so"), {
          presales: this.presale_id,
          pelanggan: this.pelanggan_id,
        })
        .then((response) => {
          let data = response.data.data;
          this.form = {
            ...data,
            osp_id: response.data.osp,
            foto_identitas_pw: null,
          };
          this.mapInfo = data;
          this.presale_id = data.presale_id;
          this.pelanggan_id = data.pelanggan_id;
          this.photo.foto_identitas_old = data.foto_identitas;
          this.photo.foto_identitas_pw = data.foto_identitas;
          this.photo.foto_rumah = data.foto_rumah;
          this.loadPaketBerlangganan(response.data.paket);
          this.setMarkerMap(data);
          this.fetchDateRangeSlot(response.data.osp);
          this.loading = false;
          this.form.biaya_mrc_with_format =
            response.data.data.biaya_mrc_with_format;
          this.form.biaya_otc_with_format =
            response.data.data.biaya_otc_with_format;
          this.status_pelanggan = response.data.status_pelanggan;
          if (
            response.data.data.status_pelanggan !== "so" &&
            response.data.data.status_pelanggan !== null
          ) {
            this.finishSO = true;
          }
          let paket = response.data.paket.find(
            (paket) => paket.paket_id === this.form.paket_id
          );
          if (paket) this.form.namaPaket = paket.nama_paket;
        });
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
      vm.google.maps.event.addListenerOnce(vm.map, "tilesloaded", function() {
        vm.map.fitBounds(bounds);
        vm.map.panToBounds(bounds);
        console.log(bounds, vm.map.getZoom())
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

    setBiayaMRCOTC() {
      /// perlu diubah
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
          this.form.biaya_otc = parseInt(data.biaya_otc);
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

    setPresalesAndPelangganId() {
      let presales = this.$route.query.presales;
      this.presale_id = presales !== undefined && presales != "" ? presales : 0;
      let pelanggan = this.$route.query.pelanggan;
      this.pelanggan_id =
        pelanggan !== undefined && pelanggan != "" ? pelanggan : 0;
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
    onCancel() {},
    fetchDateRangeSlot(osp_id) {
      axios
        .get(route("api.company.slotinstalasi.get-range-time"), {
          company_id: osp_id,
        })
        .then((response) => {
          this.selectableRange = response.data.data;
        })
        .catch((err) => {
          setTimeout(() => {
            this.fetchDateRangeSlot(osp_id);
          }, 2000);
        });
    },
  },
  mounted() {
    let self = this;
    this.setPresalesAndPelangganId();
    this.$gmapApiPromiseLazy().then(() => {
      self.initializeMap();
      self.infoWindow = new google.maps.InfoWindow({ content: "" });
      self.fetchData();
    });
    $(".fancybox").fancybox();
  },
};
