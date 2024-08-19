import axios from "axios";

export default {
    state: {
        saldo: 0,
    },
    getters: {
        saldo: state => {
            return state.saldo;
        }
    },
    mutations: {
        setSaldo(state, data) {
            state.saldo = data;
        }
    },
    actions: {
        getSaldo({commit}) {
            return new Promise((resolve, reject) => {
                axios.get(route('api.saldo.saldo'))
                .then(response => {
                    commit('setSaldo', response.data)
                })
            })
        }
    }
}