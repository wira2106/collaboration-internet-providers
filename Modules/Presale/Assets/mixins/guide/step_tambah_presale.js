export default {
  data() {
    return {
      tambah_presale: [
        {
          target: "#v-step-0",
          header: {
            title: this.trans("presales.tour.tambah presale.title"),
          },
          content: this.trans(
            "presales.tour.tambah presale.step.step-1.content"
          ),
        },
        {
          target: "#btn_add_presale",
          header: {
            title: this.trans("presales.tour.tambah presale.title"),
          },
          content: this.trans(
            "presales.tour.tambah presale.step.step-2.content"
          ),
        },
        {
          target: "undefined",
          header: {
            title: this.trans("presales.tour.tambah presale.title"),
          },
          content: this.trans(
            "presales.tour.tambah presale.step.step-3.content"
          ),
        },
        {
          target: ".el-drawer__body",
          header: {
            title: this.trans("presales.tour.tambah presale.title"),
          },
          content: this.trans(
            "presales.tour.tambah presale.step.step-4.content"
          ),
        },
        {
          target: ".swal2-modal",
          header: {
            title: this.trans("presales.tour.tambah presale.title"),
          },
          content: this.trans(
            "presales.tour.tambah presale.step.step-5.content"
          ),
        },
        {
          target: ".pilih-odp-fix",
          header: {
            title: this.trans("presales.tour.tambah presale.title"),
          },
          content: this.trans(
            "presales.tour.tambah presale.step.step-6.content"
          ),
        },
        {
          target: "#divider_add_presale",
          header: {
            title: this.trans("presales.tour.tambah presale.title"),
          },
          content: this.trans(
            "presales.tour.tambah presale.step.step-7.content"
          ),
        },
      ],
    };
  },
};
