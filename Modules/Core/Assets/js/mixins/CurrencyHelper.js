export default {
  methods: {
    rupiah(data) {
      if (data == null) return "";
      return (
        "Rp. " + data.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
      );
    },
  },
};
