export default {
  data() {
    return {
      edit_location_endpoint: [
        {
          target: ".footer",
          header: {
            title: this.trans("endpoint.tour.edit location endpoint.title"),
          },
          content: this.trans(
            "endpoint.tour.edit location endpoint.step.step-1.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans("endpoint.tour.edit location endpoint.title"),
          },
          content: this.trans(
            "endpoint.tour.edit location endpoint.step.step-2.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans("endpoint.tour.edit location endpoint.title"),
          },
          content: this.trans(
            "endpoint.tour.edit location endpoint.step.step-3.content"
          ),
        },
      ],
    };
  },
};
