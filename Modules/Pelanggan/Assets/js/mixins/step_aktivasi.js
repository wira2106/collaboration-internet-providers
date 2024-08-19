import axios from "axios";
import Form from "form-backend-validation";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import PermissionsHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import CardDetailPelangganInstalasi from "../components/instalasi/components/CardDetailPelangganInstalasi";
import Cart from "../../../../Core/Assets/js/components/Cart";

export default {
  components: {
    "Card-detail": CardDetailPelangganInstalasi,
    Cart,
  },
  props: ["jumlahPelanggan", "stepSekarang"],
  mixins: [ShortcutHelper, TranslationHelper, Toast, PermissionsHelper],
  data() {
    return {
      ajukanUlang: true,
      loading: false,
      pelanggan_id: null,
      status_checkbox: false,
      form_edit: false,
      form: {
        pelanggan_id: null,
        tgl_aktivasi: null,
        status: null,
        keterangan: null,
        noc: [],
        status_checkbox: false,
        noc_aktivasi: [],
      },
      list_noc: [],
      select_noc: true,
      loadingBayarPelanggan: false,
      rules: {
        required_field: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
        ],
      },
      pickerOptions: {
        disabledDate(time) {
          let date = new Date();
          date.setDate(date.getDate() - 1);
          return time.getTime() < date.getTime();
        },
      },
      loading: false,
      disable_form: false,
      status_aktivasi_now: null,
      biaya: {
        mrc: 0,
        otc: 0,
      },
    };
  },
  computed: {
    carts: function() {
      return [
        {
          name: "Biaya MRC",
          qty: 1,
          harga: this.biaya.mrc,
        },
        {
          name: "Biaya OTC",
          qty: 1,
          harga: this.biaya.otc,
        },
      ];
    },
    alertApprove : function() {
      return  this.status_checkbox == true &&
              this.form.status_step != 'aktif' &&
              this.status_aktivasi_now != null &&
              this.user_roles.name == 'Admin OSP'
    }
  },
  methods: {
    editButton(param) {
      this.form_edit = param;
    },
    changeNOC(value) {
      if (value.includes("new")) {
        this.select_noc = false;
        Swal.fire({
          icon: "warning",
          type: "warning",
          title: this.trans("core.messages.confirmation-form"),
          text: this.trans("pelangganinstalasi.alert.alert_pengalihan"),
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: this.trans("core.button.confirm"),
          cancelButtonText: this.trans("core.button.cancel"),
        }).then((result) => {
          if (result.value) {
            this.select_noc = true;
            this.$router.push({
              name: "admin.user.staff.create",
            });
            this.form.noc.splice(this.form.noc.length - 1, 1);
          } else {
            this.select_noc = true;
            this.form.noc.splice(this.form.noc.length - 1, 1);
          }
        });
      }
    },
    ajukanFalse() {
      this.ajukanUlang = false;
    },
    checkSaldoSudahCukup() {
      return new Promise((resolve) => {
        this.loadingPengajuan = true;
        axios
          .get(
            route("api.pelanggan.saldo.check", {
              pelanggan_id: this.pelanggan_id,
            })
          )
          .then((response) => {
            return resolve(response.data);
          });
      });
    },
    fetchDataAktivasi() {
      axios
        .get(
          route("api.pelanggan.form.detail.aktivasi", {
            pelanggan_id: this.pelanggan_id,
          })
        )
        .then((response) => {
          let data = response.data.data;
          let noc = response.data.noc;
          let pelanggan = response.data.pelanggan;
          if (
            data.aktivasi_id &&
            this.user_roles.name === "Admin OSP" &&
            data.status === "unapprove"
          ) {
            this.form_edit = true;
          } else if (
            this.user_roles.name === "Admin OSP" &&
            data.aktivasi_id == null
          ) {
            this.form_edit = true;
          }
          this.form = data;
          this.form.noc_aktivasi = this.form.noc;
          this.form.noc = this.form.noc.map((noc) => {
            return noc.user_id;
          });
          this.status_aktivasi_now = data.status;

          if (data.status == "selesai" || data.status == "approve") {
            this.status_checkbox = true;
          } else {
            this.status_checkbox = false;
          }

          this.list_noc = noc;

          if (pelanggan.cancel == 1 || pelanggan.status == "aktif") {
            this.disable_form = true;
          } else {
            this.disable_form = false;
          }
        });
    },
    fetchNOC() {
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
    setPresalesAndPelangganId() {
      let pelanggan = this.$route.query.pelanggan;
      this.pelanggan_id =
        pelanggan !== undefined && pelanggan != "" ? pelanggan : 0;
    },
    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if (this.form.noc.length == 0) {
            Swal.fire(
              this.trans("activations.messages.empty noc"),
              "",
              "warning"
            );
            return false;
          }

          if (
            this.form.status == "cancel" &&
            (this.form.keterangan == null || this.form.keterangan == "")
          ) {
            Swal.fire(
              this.trans("activations.messages.empty keterangan"),
              "",
              "warning"
            );
            this.$refs.keterangan.focus();
            return false;
          }

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
              this.submitForm();
            }
          });
        }
      });
    },
    submitForm(status = null) {
      this.loading = true;
      if (status != null) {
        this.form.status = "approve";
      }
      this.form.status_checkbox = this.status_checkbox;
      axios
        .post(route("api.pelanggan.form.submit.activation"), this.form)
        .then((response) => {
          let data = response.data;
          this.Toast({
            icon: "success",
            title: data.message,
          });
          // return false;
          if (data.redirect_detail) {
            window.location = route("admin.pelanggan.form.detail", {
              pelanggan_id: this.pelanggan_id,
            });
          } else {
            window.location =
              route("admin.pelanggan.form.step") +
              "?pelanggan=" +
              this.pelanggan_id;
          }
        })
        .catch((error) => {
          this.loading = false;
          this.Toast({
            icon: "error",
            title: this.trans("core.error 500 title"),
          });
        });
    },
    approveInstalasi() {
      $('#modal-rating-pelanggan').modal('show')
    },
    onApprove() {
      Swal.fire({
        title: this.trans("core.messages.confirmation-form"),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then(async (result) => {
        if (result.value) {
          this.loading = true;
          let check = await this.checkSaldoSudahCukup();
          if (check.cukup) {
            this.submitForm("approve");
          } else {
            this.bayarBiayaPelanggan(null, check.data);
          }
        }
      });
    }, 
    bayarBiayaPelanggan(form, data) {
      this.biaya.mrc = data.mrc;
      this.biaya.otc = data.otc;
      $("#cart-aktivasi").modal();
    },
    bayar_biaya_pelanggan() {
      Swal.fire({
        title: this.trans("core.messages.confirmation-form"),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then(async (result) => {
        if (result.value) {
          this.loadingBayarPelanggan = true;
          axios
            .post(
              route("api.pelanggan.saldo.submit", {
                pelanggan_id: this.pelanggan_id,
              })
            )
            .then((response) => {
              if (response.data.errors) {
                Swal.fire(response.data.message, "", "error");
                this.loading = false;
              } else {
                this.submitForm("approve");
              }
              this.loadingBayarPelanggan = false;
            })
            .catch((er) => {
              console.log(er);
              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title"),
              });
              this.loading = false;
              this.loadingBayarPelanggan = false;
            });
        } else {
          this.loadingPengajuan = false;
        }
      });
    },
    tolakApproveInstalasi() {
      Swal.fire({
        title: this.trans("activations.messages.alasan unapprove"),
        input: "text",
        inputAttributes: {
          autocapitalize: "off",
        },
        showCancelButton: true,
        confirmButtonText: "Simpan",
        allowOutsideClick: false,
      }).then((result) => {
        if (typeof result.value !== "undefined") {
          if (result.value == "" || result.value == null) {
            this.tolakApproveInstalasi();
          } else {
            this.loading = true;
            let send = {
              pelanggan_id: this.pelanggan_id,
              alasan: result.value,
            };
            axios
              .post(
                route("api.pelanggan.form.submit.activation.unapprove"),
                send
              )
              .then((response) => {
                this.loading = false;
                let data = response.data;
                this.Toast({
                  icon: "success",
                  title: data.message,
                });
                window.location =
                  route("admin.pelanggan.form.step") +
                  "?pelanggan=" +
                  this.pelanggan_id;
              })
              .catch((error) => {
                this.loading = false;
                this.Toast({
                  icon: "error",
                  title: this.trans("core.error 500 title"),
                });
              });
          }
        }
      });
    },
  },
  mounted() {
    this.setPresalesAndPelangganId();
    this.fetchDataAktivasi();
  },
};
