export default {
  data() {
    return {
      tambah_endpoint: [
        {
          target: "#v-step-0",
          header: {
            title: this.trans("presales.tour.tambah endpoint.title"),
          },
          content: this.trans(
            "presales.tour.tambah endpoint.step.step-1.content"
          ),
        },
        {
          target: "#btn_add_endpoint",
          header: {
            title: this.trans("presales.tour.tambah endpoint.title"),
          },
          content: this.trans(
            "presales.tour.tambah endpoint.step.step-2.content"
          ),
        },
        {
          target: "undefined",
          header: {
            title: this.trans("presales.tour.tambah endpoint.title"),
          },
          content: this.trans(
            "presales.tour.tambah endpoint.step.step-3.content"
          ),
        },
        {
          target: "#btnSaveAddEndpoint",
          header: {
            title: this.trans("presales.tour.tambah endpoint.title"),
          },
          content: this.trans(
            "presales.tour.tambah endpoint.step.step-4.content"
          ),
        },
      ],
    };
  },
};
