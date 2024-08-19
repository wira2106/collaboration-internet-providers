import PaketBerlanggananForm from './components/paketberlangganan/PaketBerlanggananForm.vue';
import PaketBerlanggananTable from './components/paketberlangganan/PaketBerlanggananTable.vue';
import PaketForIspForm from './components/paketberlangganan/PaketForIspForm.vue';
// import DiskonPaketBerlangganan from './components/paketberlangganan/DiskonPaketBerlangganan.vue'


const locales = window.AsgardCMS.locale;

export default [
    {
        path: '/company/paketberlangganans',
        name: 'admin.company.paketberlangganan.index',
        component:PaketBerlanggananTable
    },
    {
        path: '/company/paketberlangganans/create',
        name: 'admin.company.paketberlangganan.create',
        component: PaketBerlanggananForm,
        props: {
            locales,
            pageTitle:'title.create paketberlangganan',
            formTitle: 'form.create paket'
        }

    },
    {
        path: '/company/paketberlangganans/:paketberlangganan/edit',
        name: 'admin.company.paketberlangganan.edit',
        component: PaketBerlanggananForm,
        props: {
            locales,
            pageTitle: 'title.edit paketberlangganan',
            formTitle: 'form.edit paket'
        }

    },
    {
        path: '/company/paket/isp/create',
        name: 'admin.company.paketforisp.create',
        component: PaketForIspForm,
        props: {
            locales,
            pageTitle: 'title.create paket isp',
            formTitle: 'form.create isp form'
        }

    },
];