export default {
  data() {
    return {
      edit_location_presale: [
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.edit location presale.title"),
          },
          content: this.trans(
            "presales.tour.edit location presale.step.step-1.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.edit location presale.title"),
          },
          content: this.trans(
            "presales.tour.edit location presale.step.step-2.content"
          ),
        },
        {
          target: "#footer",
          header: {
            title: this.trans("presales.tour.edit location presale.title"),
          },
          content: this.trans(
            "presales.tour.edit location presale.step.step-3.content"
          ),
        },
        {
          target: ".swal2-modal",
          header: {
            title: this.trans("presales.tour.edit location presale.title"),
          },
          content: this.trans(
            "presales.tour.edit location presale.step.step-4.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.edit location presale.title"),
          },
          content: this.trans(
            "presales.tour.edit location presale.step.step-5.content"
          ),
        },
      ],
    };
  },
};
