export default {
  data() {
    return {
      hapus_presale: [
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.hapus presale.title"),
          },
          content: this.trans(
            "presales.tour.hapus presale.step.step-1.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.hapus presale.title"),
          },
          content: this.trans(
            "presales.tour.hapus presale.step.step-2.content"
          ),
        },
      ],
    };
  },
};
