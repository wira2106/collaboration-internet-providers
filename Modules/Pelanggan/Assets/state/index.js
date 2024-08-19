export default {
    state: {
        jadwal_instalasi: {
            slot_instalasi: '',
            tanggal_instalasi: '',
            teknisi: [],
        },
    },
    getters: {
        jadwal_instalasi: state => {
            return state.jadwal_instalasi
        }
    },
    mutations: {
        setJadwalInstalasi(state, data) {
            state.jadwal_instalasi = data
        }
    },
}