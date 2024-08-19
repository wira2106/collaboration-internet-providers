import axios from "axios";

export default {
  state: {
    config: {}
  },
  mutations: {
    setConfig(state, data) {
      state.config = data;
    }
  },
  getters: {
    config: state => {
      return state.config;
    }
  },

  actions: {
    fetchConfig({ commit }) {
      return new Promise((resolve, reject) => {
        axios.get(route("api.configuration.index")).then(response => {
          // console.log(response);
          commit("setConfig", response.data.data);
        });
      });
    }
  }
};
