export default {
  data() {
    return {
      edit_data_endpoint: [
        {
          target: ".footer",
          header: {
            title: this.trans("endpoint.tour.edit data endpoint.title"),
          },
          content: this.trans(
            "endpoint.tour.edit data endpoint.step.step-1.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans("endpoint.tour.edit data endpoint.title"),
          },
          content: this.trans(
            "endpoint.tour.edit data endpoint.step.step-2.content"
          ),
        },
        {
          target: "#btnSaveEditEndpoint",
          header: {
            title: this.trans("endpoint.tour.edit data endpoint.title"),
          },
          content: this.trans(
            "endpoint.tour.edit data endpoint.step.step-3.content"
          ),
        },
      ],
    };
  },
};
