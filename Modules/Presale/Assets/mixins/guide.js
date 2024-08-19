import step_edit_data_endpoint from "./guide/step_edit_data_endpoint";
import step_edit_data_presale from "./guide/step_edit_data_presale";
import step_edit_location_endpoint from "./guide/step_edit_location_endpoint";
import step_edit_location_presale from "./guide/step_edit_location_presale";
import step_hapus_endpoint from "./guide/step_hapus_endpoint";
import step_hapus_presale from "./guide/step_hapus_presale";
import step_hide_marker_endpoint from "./guide/step_hide_marker_endpoint";
import step_hide_marker_presale from "./guide/step_hide_marker_presale";
import step_konfirmasi_endpoint from "./guide/step_konfirmasi_endpoint";
import step_konfirmasi_presale from "./guide/step_konfirmasi_presale";
import step_menampilkan_presale_yang_dicari from "./guide/step_menampilkan_presale_yang_dicari";
import step_order_pelanggan from "./guide/step_order_pelanggan";
import step_tambah_endpoint from "./guide/step_tambah_endpoint";
import step_tambah_presale from "./guide/step_tambah_presale";
import step_tampilkan_jalur_kabel_endpoint_ke_presale from "./guide/step_tampilkan_jalur_kabel_endpoint_ke_presale";
import step_tampilkan_jalur_kabel_presale from "./guide/step_tampilkan_jalur_kabel_presale";

export default {
  mixins: [
    step_tampilkan_jalur_kabel_presale,
    step_tambah_endpoint,
    step_tambah_presale,
    step_hapus_endpoint,
    step_edit_data_endpoint,
    step_edit_location_endpoint,
    step_edit_data_presale,
    step_edit_location_presale,
    step_hapus_presale,
    step_tampilkan_jalur_kabel_endpoint_ke_presale,
    step_hide_marker_presale,
    step_hide_marker_endpoint,
    step_menampilkan_presale_yang_dicari,
    step_konfirmasi_presale,
    step_konfirmasi_endpoint,
    step_order_pelanggan
  ],
  data() {
    return {
      tour_type: null,
      callbacks_tour: {
        onSkip: this.reset_tour,
        onFinish: this.reset_tour
      },
      tour_options: {
        enabledButtons: { buttonNext: false, buttonPrevious: false }
      }
    };
  },
  methods: {
    showGuideList() {
      $("#modal-list-guide").modal("show");
    },
    startGuide(type) {
      if(this.tour_type) this.finishStep();
      this.tour_type = type;
      this.$tours[type].start();
    },
    nextStep() {
      setTimeout(() => {
        this.$tours[this.tour_type].nextStep();
      }, 600);
    },
    prevStep() {
      setTimeout(() => {
        this.$tours[this.tour_type].previousStep();
      }, 600);
    },
    finishStep() {
      this.$tours[this.tour_type].finish();
      this.tour_type = null;
    },
    checkStepIsRunning() {
      if (this.tour_type && !this.$tours[this.tour_type].isRunning)
        this.tour_type = null;
    },
    reset_tour() {
      this.tour_type = null;
    }
  }
};
