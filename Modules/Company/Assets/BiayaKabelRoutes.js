
import BiayaKabelForm from './components/biayakabel/BiayaKabelForm.vue';
import BiayaKabelTable from './components/biayakabel/BiayaKabelTable.vue';

const locales = window.AsgardCMS.locales;

export default [
    {
        path: '/company/biaya-kabel',
        name: 'admin.company.biayakabel.index',
        component: BiayaKabelTable,
        props: {
            pageTitle: 'title.biaya-kabel'
        }
    },
    {
        path: '/company/biaya-kabel/create',
        name: 'admin.company.biayakabel.create',
        component: BiayaKabelForm,
        props: {
            pageTitle: 'create resource'
        }
    }
];