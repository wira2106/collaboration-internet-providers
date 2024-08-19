export default {
  data() {
    return {
      konfirmasi_presale: [
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.konfirmasi presale.title"),
          },
          content: this.trans(
            "presales.tour.konfirmasi presale.step.step-1.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.konfirmasi presale.title"),
          },
          content: this.trans(
            "presales.tour.konfirmasi presale.step.step-2.content"
          ),
        },
        {
          target: "#konfirmasi_presale",
          header: {
            title: this.trans("presales.tour.konfirmasi presale.title"),
          },
          content: this.trans(
            "presales.tour.konfirmasi presale.step.step-3.content"
          ),
        },
      ],
    };
  },
};
