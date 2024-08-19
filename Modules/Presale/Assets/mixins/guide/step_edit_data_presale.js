export default {
  data() {
    return {
      edit_data_presale: [
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.edit data presale.title"),
          },
          content: this.trans(
            "presales.tour.edit data presale.step.step-1.content"
          ),
        },
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.edit data presale.title"),
          },
          content: this.trans(
            "presales.tour.edit data presale.step.step-2.content"
          ),
        },
        {
          target: "#divider_add_presale",
          header: {
            title: this.trans("presales.tour.edit data presale.title"),
          },
          content: this.trans(
            "presales.tour.edit data presale.step.step-3.content"
          ),
        },
      ],
    };
  },
};
