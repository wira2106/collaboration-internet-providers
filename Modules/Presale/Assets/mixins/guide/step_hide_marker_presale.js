export default {
  data() {
    return {
      hide_marker_presale: [
        {
          target: ".footer",
          header: {
            title: this.trans("presales.tour.hide marker presale.title"),
          },
          content: this.trans(
            "presales.tour.hide marker presale.step.step-1.content"
          ),
        },
        {
          target: "#CheckBoxRumahSlider",
          header: {
            title: this.trans("presales.tour.hide marker presale.title"),
          },
          content: this.trans(
            "presales.tour.hide marker presale.step.step-2.content"
          ),
        },
      ],
    };
  },
};
