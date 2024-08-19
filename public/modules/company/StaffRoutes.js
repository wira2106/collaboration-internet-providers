import CompaniesTable from "./components/company/CompaniesTable.vue";

const locales = window.AsgardCMS.locales;

export default [
    {
        path: '/company/staff',
        name: 'admin.company.staff.index',
        component:CompaniesTable
    },
];