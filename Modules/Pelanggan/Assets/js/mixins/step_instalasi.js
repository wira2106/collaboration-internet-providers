import axios from "axios";
import Form from "form-backend-validation";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import SingleFileSelector from "../../../../Media/Assets/js/mixins/SingleFileSelector";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import PermissionsHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";
import JadwalInstalasi from "../components/pengajuan/JadwalInstalasi";
import PerubahanHarga from "../components/instalasi/components/PerubahanHarga";
import HasilInstalasi from "../components/instalasi/components/HasilInstalasi";
import CardDetailPelangganInstalasi from "../components/instalasi/components/CardDetailPelangganInstalasi";
import Toast from "../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [
    ShortcutHelper,
    SingleFileSelector,
    TranslationHelper,
    PermissionsHelper,
    Toast,
  ],
  props: {
    action: "",
  },
  components: {
    HasilInstalasi: HasilInstalasi,
    JadwalInstalasi: JadwalInstalasi,
    PerubahanHarga: PerubahanHarga,
    CardDetailPelangganInstalasi: CardDetailPelangganInstalasi,
  },
  data() {
    return {
      info: true,
      pelanggan_id: null,
      ajukanUlang: true,
      status_instalasi_props: "",
      disable_form_hasil_instalasi: false,
    };
  },
  methods: {
    ajukanFalse() {
      this.ajukanUlang = false;
    },
    status_instalasi(value) {
      this.status_instalasi_props = value;
    },
    changeStatusStep(value) {
      if (value == "cancel") {
        this.disable_form_hasil_instalasi = true;
      } else if (value == "activate") {
        this.disable_form_hasil_instalasi = false;
      }
    },
    fetchInfoPelanggan() {
      let pelanggan = this.$route.query.pelanggan;
      axios
        .get(
          route("api.pelanggan.form.data.instalasi", {
            pelanggan: pelanggan,
          })
        )
        .then((response) => {
          if (response.data.status_instalasi == "reschedule") {
            this.info == true;
          } else {
            this.info = false;
          }
        });
    },
  },

  mounted() {
    this.fetchInfoPelanggan();
    this.loadDatabasePerubahanHarga;
    let pelanggan = this.$route.query.pelanggan;
    this.pelanggan_id =
      pelanggan !== undefined && pelanggan != "" ? pelanggan : 0;
  },
};
