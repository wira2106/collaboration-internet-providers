export default {
  data() {
    return {
      hide_marker_endpoint: [
        {
          target: ".footer",
          header: {
            title: this.trans("endpoint.tour.hide marker endpoint.title"),
          },
          content: this.trans(
            "endpoint.tour.hide marker endpoint.step.step-1.content"
          ),
        },
        {
          target: "#CheckBoxEndpointSlider",
          header: {
            title: this.trans("endpoint.tour.hide marker endpoint.title"),
          },
          content: this.trans(
            "endpoint.tour.hide marker endpoint.step.step-2.content"
          ),
        },
      ],
    };
  },
};
