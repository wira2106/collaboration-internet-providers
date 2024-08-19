import InvoiceTable from './components/InvoiceTable.vue';
import InvoiceDetail from './components/InvoiceDetail.vue';
import InvoicePrint from './components/InvoicePrint.vue';

export default [
    {
        path: '/invoice/invoices',
        name : 'admin.invoice.invoices.index',
        component: InvoiceTable
    },
    {
        path: '/invoice/invoices/:invoice/detail',
        name: 'admin.invoice.invoices.detail',
        component: InvoiceDetail
    },
    {
        path: '/invoice/invoices/:invoice/print',
        name: 'admin.invoice.invoices.print',
        component: InvoicePrint
    }
]