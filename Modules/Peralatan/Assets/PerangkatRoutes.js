import PerangkatTable from './components/perangkat/PerangkatTable.vue';
import PerangkatForm from './components/perangkat/PerangkatForm.vue';

const locales = window.AsgardCMS.locales;


export default [
    {
        path:'/peralatan/perangkats',
        name:'admin.peralatan.perangkat.index',
        component:PerangkatTable
    }, 
    {
        path: '/peralatan/perangkats/create',
        name: 'admin.peralatan.perangkat.create',
        component: PerangkatForm,
        props: {
            locales,
            pageTitle:'title.create perangkat'
        }
    },
    {
        path: '/peralatan/perangkats/:perangkat/edit',
        name: 'admin.peralatan.perangkat.edit',
        component: PerangkatForm,
        props: {
            locales,
            pageTitle:'title.edit perangkat'
        }
    },
]