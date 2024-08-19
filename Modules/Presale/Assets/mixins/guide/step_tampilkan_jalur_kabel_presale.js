export default {
  data() {
    return {
      tampilkan_jalur_kabel_presale: [
        {
          target: ".footer",
          header: {
            title: this.trans(
              "presales.tour.tampilkan jalur kabel presale.title"
            ),
          },
          content: this.trans(
            "presales.tour.tampilkan jalur kabel presale.step.step-1.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans(
              "presales.tour.tampilkan jalur kabel presale.title"
            ),
          },
          content: this.trans(
            "presales.tour.tampilkan jalur kabel presale.step.step-2.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans(
              "presales.tour.tampilkan jalur kabel presale.title"
            ),
          },
          content: this.trans(
            "presales.tour.tampilkan jalur kabel presale.step.step-3.content"
          ),
        },
      ],
    };
  },
};
