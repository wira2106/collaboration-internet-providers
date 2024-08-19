export default {
  data() {
    return {
      order_pelanggan: [
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.order pelanggan.title")
          },
          content: this.trans(
            "presales.tour.order pelanggan.step.step-1.content"
          )
        },
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.order pelanggan.title")
          },
          content: this.trans(
            "presales.tour.order pelanggan.step.step-2.content"
          )
        }
      ]
    };
  }
};
