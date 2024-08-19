import BarangTable from './components/barang/BarangTable.vue';
import BarangForm from './components/barang/BarangForm.vue';

const locales = window.AsgardCMS.locales;


export default [
    {
        path:'/peralatan/barangs',
        name:'admin.peralatan.barang.index',
        component:BarangTable
    }, 
    {
        path: '/peralatan/barangs/create',
        name: 'admin.peralatan.barang.create',
        component: BarangForm,
        props: {
            locales,
            pageTitle:'title.create barang'
        }
    },
    {
        path: '/peralatan/barangs/:barang/edit',
        name: 'admin.peralatan.barang.edit',
        component: BarangForm,
        props: {
            locales,
            pageTitle:'title.edit barang'
        }
    },
];