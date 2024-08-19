
import BiayaKabelForm from './components/biayakabel/BiayaKabelForm.vue';

const locales = window.AsgardCMS.locales;

export default [
    {
        path: '/company/biaya-kabel',
        name: 'admin.company.biayakabel.index',
        component: BiayaKabelForm,
        props: {
            pageTitle: 'title.biaya-kabel'
        }
    }
];