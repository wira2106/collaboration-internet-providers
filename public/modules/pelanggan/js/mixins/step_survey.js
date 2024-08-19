import axios from "axios";
import Form from "form-backend-validation";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import SingleFileSelector from "../../../../Media/Assets/js/mixins/SingleFileSelector";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import UserRolesPermission from "../../../../Core/Assets/js/mixins/UserRolesPermission";
import UploadPreview from "../../../../Core/Assets/js/mixins/UploadPreview";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import JadwalInstalasi from "../components/pengajuan/JadwalInstalasi";
import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";
import CardDetailPelangganInstalasi from "../components/instalasi/components/CardDetailPelangganInstalasi.vue";
import PerubahanHarga from "../components/instalasi/components/PerubahanHarga";
import SelectPerangkatVue from "../../../../Core/Assets/js/components/SelectPerangkat.vue";

export default {
  props: ["jumlahPelanggan", "stepSekarang"],
  mixins: [
    ShortcutHelper,
    SingleFileSelector,
    TranslationHelper,
    UserRolesPermission,
    UploadPreview,
    Toast,
  ],
  components: {
    JadwalInstalasi: JadwalInstalasi,
    InputCurrency: InputCurrencyVue,
    CardDetailPelangganInstalasi: CardDetailPelangganInstalasi,
    PerubahanHarga: PerubahanHarga,
    SelectPerangkat: SelectPerangkatVue,
  },
  data() {
    return {
      surveyFinish: null,
      pickerOptions: {
        disabledDate(time) {
          let date = new Date();
          date.setDate(date.getDate() - 1);
          return time.getTime() < date.getTime();
        },
      },
      slot_instalasi: [],
      user_company: {
        type: null,
      },
      presale_id: null,
      pelanggan_id: null,
      fieldPerangkat: [],
      fieldBarang: [],
      surveyForm: {
        survey_id: "",
        pelanggan_id: "",
        keterangan: "",
        teknisi: [],
        teknisiInstalasi: [],
        status: null,
        survey_finish: false,
        foto_signal_kabel: [],
        foto_jalur_kabel: [],
        upload_signal_kabel: [],
        upload_jalur_kabel: [],
        perangkat_tambahan: [],
        jadwalInstalasi: [],

      },
      previewHasil_foto_signal_kabel: [],
      previewHasil_foto_jalur_kabel: [],
      foto_signal_kabel_ready: [],
      foto_jalur_kabel_ready: [],
      noc: [],
      listJenisPerangkat: [
        {
          value: "ONT",
          label: "ONT",
        },
        {
          value: "akses point",
          label: "Access Point",
        },
      ],
      dataSurveyLoading: false,
      hasilSurveyLoading: false,
      select_teknisi: true,
      dialogImageUrl: "",
      dialogVisible: false,
      disabled: false,
      fileList: [],
      ajukanUlang: true,
      change_status: "survey",
      status_pelanggan: "",
      list_teknisi: [],
      tanggal_survey: "",
      surveyNow: false,
      rules: {
        required_field: [
          {
            required: true,
            trigger: ["change"],
            message: this.trans("core.form.required"),
          },
        ],
        teknisi: [
          {
            required: true,
            message: this.trans("surveys.validation.required", {
              field: this.trans("surveys.form.teknisi"),
            }),
            trigger: "change",
          },
          {
            validator: this.changeTeknisi,
            trigger: "change",
          },
        ],
        status: [
          {
            required: true,
            message: this.trans("surveys.validation.required", {
              field: this.trans("surveys.form.status"),
            }),
            trigger: "change",
          },
        ],
        tinggi_bangunan: [
          {
            required: true,
            message: this.trans("surveys.validation.required", {
              field: this.trans("surveys.form.tinggi"),
            }),
            trigger: "change",
          },
        ],
        foto_signal_kabel: [
          {
            required: true,
            message: this.trans("surveys.validation.required", {
              field: this.trans("surveys.form.signal"),
            }),
            trigger: "change",
          },
        ],
        foto_jalur_kabel: [
          {
            required: true,
            message: this.trans("surveys.validation.required", {
              field: this.trans("surveys.form.kabel"),
            }),
            trigger: "change",
          },
        ],
        barang_id: [
          {
            required: true,
            message: this.trans("surveys.validation.required", {
              field: this.trans("surveys.form.namaBarang"),
            }),
            trigger: "change",
          },
        ],
        harga_per_pcs: [
          {
            required: true,
            message: this.trans("surveys.validation.required", {
              field: this.trans("surveys.form.harga per pcs"),
            }),
            trigger: "change",
          },
        ],
        qty: [
          {
            required: true,
            message: this.trans("surveys.validation.required", {
              field: this.trans("surveys.form.qty"),
            }),
            trigger: "change",
          },
        ],
        // harga:[
        //      { required: true, message: this.trans('surveys.validation.required',{field:this.trans('surveys.form.harga')}), trigger: 'change' }
        // ],
        perangkat_id: [
          {
            required: true,
            message: this.trans("surveys.validation.required", {
              field: this.trans("surveys.form.namaPerangkat"),
            }),
            trigger: "change",
          },
        ],
        jenis_perangkat: [
          {
            required: true,
            message: this.trans("surveys.validation.required", {
              field: this.trans("surveys.form.jenis_perangkat"),
            }),
            trigger: "change",
          },
        ],
      },
    };
  },
  methods: {
    fetchData() {
      this.dataSurveyLoading = true;
      this.hasilSurveyLoading = true;
      this.surveyForm.foto_jalur_kabel = [];
      this.foto_jalur_kabel_ready = [];
      this.surveyForm.foto_signal_kabel = [];
      this.foto_signal_kabel_ready = [];
      this.formJadwalInstalasi = this.$refs.form;
      axios
        .get(
          route("api.perangkat.survey.index", {
            pelanggan_id: this.pelanggan_id,
          })
        )
        .then((response) => {
          // set option select teknisi
          this.list_teknisi = response.data[6];
          // set status
          this.surveyNow = response.data[0].surveyNow;
          this.surveyForm.survey_finish = response.data[0].surveyFinish;
          // perangkat
          this.fieldPerangkat = response.data[1];
          // survey
          this.surveyForm.survey_id = response.data[2].id_survey;
          this.surveyForm.pelanggan_id = response.data[2].pelanggan_id;
          this.surveyForm.keterangan = response.data[2].keterangan;
          this.status_pelanggan = response.data[2].status_pelanggan;
          this.surveyForm.status = response.data[2].status;
          console.log(response.data[2].foto_signal_kabel);
          this.surveyForm.foto_jalur_kabel =  response.data[2].foto_jalur_kabel
          this.surveyForm.foto_signal_kabel =  response.data[2].foto_signal_kabel
          if (this.surveyForm.status != "finish") {
            this.surveyForm.jadwalInstalasi.push({
              tgl_instalasi: "",
              slot_id: "",
              status: "not_active",
            });
          }
          if (this.surveyForm.status == "active") {
            this.statusEditFormSurvey = true;
          }
          // slot instalasi
          this.slot_instalasi = response.data[5];
         

          // teknisi
          this.noc = response.data[3];
          this.surveyForm.teknisi = [];

          if (response.data[3] !== null) {
            response.data[3].forEach((element) => {
              this.surveyForm.teknisi.push(element.nama_teknisi);
            });
          }

          if (response.data[4].length > 0) {
            this.surveyForm.perangkat_tambahan = [];
            response.data[4].forEach((element) => {
              this.surveyForm.perangkat_tambahan.push(element);
            });
          }
          this.dataSurveyLoading = false;
          this.hasilSurveyLoading = false;
          setTimeout(() => {
            $(".fancybox").fancybox();
          }, 2000);
        });
    },
    setPresalesAndPelangganId() {
      let presales = this.$route.query.presales;
      this.presale_id = presales !== undefined && presales != "" ? presales : 0;
      let pelanggan = this.$route.query.pelanggan;
      this.pelanggan_id =
        pelanggan !== undefined && pelanggan != "" ? pelanggan : 0;

      this.fetchData();
    },
    
    previewBeforeSubmit() {
      $(".modal-preview").modal("show");
    },
    send() {
       let teknisi = '',
            foto_jalur_kabel='',
            foto_jalur_signal='';
          this.noc.forEach((element) => {
            teknisi +=`<span style="
                  font-size:12px;
                  display: inline-block;
                  padding: 0px 10px;
                  background: var(--primary);
                  color: white;
                  border-radius: 2px;
                  margin-top: 3px;">
                  ${element.nama_teknisi}
            </span>`
          });
          Swal.fire({
            title: this.trans("surveys.messages.simpan"),
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: this.trans("core.button.confirm"),
            cancelButtonText: this.trans("core.button.cancel"),
            })
            .then((result) => {
              if (result.value) {
                $(".modal-preview").modal("hide");
                this.hasilSurveyLoading = true;
                this.dataSurveyLoading = true;
                // console.log(this.form);
                this.form = new Form(this.surveyForm);
                this.form
                  .post(route("api.pelanggan.submit.survey"))
                  .then((response) => {
                    this.fetchData();
                    this.hasilSurveyLoading = false;
                    this.dataSurveyLoading = false;
                    this.Toast({
                      icon: "success",
                      title: response.message,
                    });
                    this.$router.go();
                  })
                  .catch((error) => {
                    this.hasilSurveyLoading = false;
                    this.dataSurveyLoading = false;
                    this.Toast({
                      icon: "error",
                      title: this.trans("core.error 500 title"),
                    });
                  });
              }
           });
    },
    onSubmit() {
      this.$refs["surveyForm"].validate((valid) => {
        let fields = this.$refs["surveyForm"].fields;
        for (let i = 0; i < fields.length; i++) {
          if (fields[i].validateState == "error") {
            $(fields[i].$el)
              .find("input")[0]
              .focus();
            return false;
          }
        }
        if (valid) {
          this.previewBeforeSubmit();
        } else {
          console.log(valid)
          this.onSubmit();
        }
      });
    },
    changeTeknisi(rule, value, callback) {
      if (value.includes("new")) {
        this.select_teknisi = false;
        Swal.fire({
          title: this.trans("pelanggans.input nama teknisi"),
          input: "text",
          inputAttributes: {
            autocapitalize: "off",
          },
          showCancelButton: true,
          confirmButtonText: "Simpan",
          allowOutsideClick: false,
        }).then((result) => {
          if (result.value) {
            this.select_teknisi = true;
            if (this.list_teknisi.indexOf(result.value) == -1) {
              var format_insert_teknisi = {
                nama_teknisi: result.value,
              };
              this.list_teknisi.push(format_insert_teknisi);
            }
            if (this.surveyForm.teknisi.indexOf(result.value) == -1) {
              this.surveyForm.teknisi.splice(
                this.surveyForm.teknisi.length - 1,
                1,
                result.value
              );
            } else {
              this.surveyForm.teknisi.splice(
                this.surveyForm.teknisi.length - 1,
                1
              );
            }
          } else {
            this.select_teknisi = true;
            this.surveyForm.teknisi.splice(
              this.surveyForm.teknisi.length - 1,
              1
            );
          }
        });
      }
      callback();
    },
    addInput(data) {
      // console.log("testing");
      if (data == "barang") {
        this.surveyForm.barang_tambahan.push({
          id: "",
          barang_id: "",
          harga_per_pcs: "",
          qty: "",
          harga: "",
        });
      }
      if (data == "perangkat") {
        this.surveyForm.perangkat_tambahan.push({
          id: "",
          perangkat_id: "",
          qty: "",
          jenis_perangkat: "",
        });
      }
    },
    removeInput(item, data) {
      if (data == "barang") {
        var index = this.surveyForm.barang_tambahan.indexOf(item);
        if (index !== -1) {
          this.surveyForm.barang_tambahan.splice(index, 1);
        }
      }
      if (data == "perangkat") {
        var index = this.surveyForm.perangkat_tambahan.indexOf(item);
        if (index !== -1) {
          this.surveyForm.perangkat_tambahan.splice(index, 1);
        }
      }
    },
    loadFetch(value) {
      this.fetchData();
    },
    ajukanFalse() {
      this.ajukanUlang = false;
    },
    handleChangeHargaBarang(index) {
      this.surveyForm.barang_tambahan[index].harga =
        this.surveyForm.barang_tambahan[index].qty *
        this.surveyForm.barang_tambahan[index].harga_per_pcs;
    },
    handleRemoveSignal(file, fileList) {
      this.surveyForm.upload_signal_kabel.forEach((element, index) => {
        if (element.name == file.name) {
          this.surveyForm.upload_signal_kabel.splice(index, 1);
        }
      });
      this.surveyForm.foto_signal_kabel.forEach((element, index) => {
        if (element.uid == file.uid) {
          this.surveyForm.foto_signal_kabel.splice(index, 1);
        }
      });
      
      
      // console.log(file);
      // console.log(this.surveyForm.upload_signal_kabel);
    },
    handleRemoveKabel(file, fileList) {
      this.surveyForm.upload_jalur_kabel.forEach((element, index) => {
        if (element.name == file.name) {
          this.surveyForm.upload_jalur_kabel.splice(index, 1);
        }
      });
      this.surveyForm.foto_jalur_kabel.forEach((element, index) => {
        if (element.uid == file.uid) {
          this.surveyForm.foto_jalur_kabel.splice(index, 1);
        }
      });
    },
    handleSignalPreview(file) {
      console.log(file)
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    beforeUploadSignal(file) {
      const isJPG = file.type === "image/jpeg" || file.type === "image/png";
      
      
      if (!isJPG) {
        Swal.fire(this.trans("surveys.messages.format"), "", "warning");
        return false;
      }
      
      this.surveyForm.upload_signal_kabel.push(file);
      return isJPG;
    },
    beforeRemove(file) {
      return this.$confirm(`do you really want to delete ${file.name}？`);
    },
    beforeUploadKabel(file) {
      const isJPG = file.type === "image/jpeg" || file.type === "image/png";
      

      if (!isJPG) {
        Swal.fire(this.trans("surveys.messages.format"), "", "warning");
        return false;
      }
      
      
      this.surveyForm.upload_jalur_kabel.push(file);
      return isJPG;
    },
    addJadwal() {
      let push = {
        tgl_instalasi: "",
        slot_id: "",
        status: "not_active",
      };
      this.surveyForm.jadwalInstalasi.push(push);
    },
    removeTanggal(key) {
      this.surveyForm.jadwalInstalasi.splice(key, 1);
    },
    successSignal(res, file) {
      this.surveyForm.foto_signal_kabel.push({
        name: file.name,
        url: file.url,
        new: true
      });
      console.log(this.surveyForm.foto_signal_kabel)
    },
    successKabel(res, file) {
      this.surveyForm.foto_jalur_kabel.push({
        name: file.name,
        url: file.url,
        new: true
      });
    }

  },
  mounted() {
    $(".fancybox").fancybox();
    this.setPresalesAndPelangganId();
  },
};
