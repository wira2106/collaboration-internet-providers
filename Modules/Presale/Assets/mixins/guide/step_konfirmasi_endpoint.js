export default {
    data() {
        return {
            konfirmasi_endpoint: [
                {
                    target: ".footer",
                    header: {
                      title: this.trans("endpoint.tour.konfirmasi endpoint.title"),
                    },
                    content: this.trans(
                      "endpoint.tour.konfirmasi endpoint.step.step-1.content"
                    ),
                  },
                  {
                    target: ".footer",
                    header: {
                      title: this.trans("endpoint.tour.konfirmasi endpoint.title"),
                    },
                    content: this.trans(
                      "endpoint.tour.konfirmasi endpoint.step.step-2.content"
                    ),
                  },
                  {
                    target: "#konfirmasi_endpoint",
                    header: {
                      title: this.trans("endpoint.tour.konfirmasi endpoint.title"),
                    },
                    content: this.trans(
                      "endpoint.tour.konfirmasi endpoint.step.step-3.content"
                    ),
                  },
            ]
        }
    }
}