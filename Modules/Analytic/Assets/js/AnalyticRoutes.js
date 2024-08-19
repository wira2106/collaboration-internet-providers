import DetailPelanggan from "./components/pelanggan/DetailPelanggan.vue";
import IndexPelanggan from "./components/pelanggan/IndexPelanggan.vue";

const locales = window.AsgardCMS.locales;

export default [
    {
        path: '/analytic/pelanggan/detail/:pelanggan',
        name: 'admin.analytic.pelanggan.detail',
        component: DetailPelanggan
    },
    {
        path: '/analytic/pelanggan',
        name: 'admin.analytic.pelanggan.index',
        component: IndexPelanggan
    },
];