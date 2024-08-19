import ConfigForm from './components/ConfigForm.vue';
import BankIndex from './components/BankIndex.vue';


const locale = window.AsgardCMS.locales;

export default [
    // Route index
    {
        path:'/configuration/configurations',
        name:'admin.configuration.configuration.index',
        component:ConfigForm,
    },
    {
        path:'/bank',
        name:'admin.configuration.bank.index',
        component:BankIndex,
    },
];