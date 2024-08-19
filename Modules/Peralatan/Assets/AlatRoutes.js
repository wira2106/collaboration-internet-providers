import AlatTable from './components/alat/AlatTable.vue';
import AlatForm from './components/alat/AlatForm.vue';

const locales = window.AsgardCMS.locales;

export default [
    {
        path:'/peralatan/alats',
        name:'admin.peralatan.alat.index',
        component:AlatTable
    },
    {
        path: '/peralatan/alats/create',
        name: 'admin.peralatan.alat.create',
        component: AlatForm,
        props: {
            locales,
            pageTitle:'title.create alat'
        }
    },
    {
        path: '/peralatan/alats/:alat/edit',
        name: 'admin.peralatan.alat.edit',
        component: AlatForm,
        props: {
            locales,
            pageTitle:'title.edit alat'
        }
    },
];