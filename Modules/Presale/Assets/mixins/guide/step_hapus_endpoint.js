export default {
  data() {
    return {
      hapus_endpoint: [
        {
          target: ".footer",
          header: {
            title: this.trans("endpoint.tour.hapus endpoint.title"),
          },
          content: this.trans(
            "endpoint.tour.hapus endpoint.step.step-1.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans("endpoint.tour.hapus endpoint.title"),
          },
          content: this.trans(
            "endpoint.tour.hapus endpoint.step.step-2.content"
          ),
        },
      ],
    };
  },
};
