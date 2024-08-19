import axios from "axios";

export default {
  state: {
    paket_request_wilayah: []
  },
  mutations: {
    setPaketRequestWilayah(state, data) {
      state.paket_request_wilayah = data;
    }
  },
  getters: {
    paket_request_wilayah: state => {
      return state.paket_request_wilayah;
    }
  },

  actions: {
    fetchPaketRequestWilayah({ commit }, wilayah) {
      return new Promise((resolve, reject) => {
        axios.get(route("api.company.paketberlangganan.wilayah.list", {wilayah:wilayah})).then(response => {
          commit("setPaketRequestWilayah", response.data);
        });
      });
    }
  }
};
