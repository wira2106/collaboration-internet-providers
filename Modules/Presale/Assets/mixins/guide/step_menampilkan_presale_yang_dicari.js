export default {
  data() {
    return {
      menampilkan_presale_yang_dicari: [
        {
          target: ".btn-list-rumah",
          header: {
            title: this.trans(
              "presales.tour.menampilkan presale yang dicari.title"
            ),
          },
          content: this.trans(
            "presales.tour.menampilkan presale yang dicari.step.step-1.content"
          ),
        },
        {
          target: ".showSearched",
          header: {
            title: this.trans(
              "presales.tour.menampilkan presale yang dicari.title"
            ),
          },
          content: this.trans(
            "presales.tour.menampilkan presale yang dicari.step.step-2.content"
          ),
        },
      ],
    };
  },
};
