import SaldoTable from './components/topup/SaldoTable.vue';
import WithdrawTable from './components/withdraw/WithdrawTable.vue';
import MutasiTable from './components/mutasi/MutasiTable.vue';
const locales = window.AsgardCMS.locales;

export default [
    {
        path:'/saldo/topups',
        name:'admin.saldo.topup.index',
        component: SaldoTable
    },
    {
        path:'/saldo/withdraws',
        name:'admin.saldo.withdraw.index',
        component: WithdrawTable
    },
    {
        path:'/saldo/mutasi',
        name:'admin.saldo.mutasi',
        component: MutasiTable
    }
];
