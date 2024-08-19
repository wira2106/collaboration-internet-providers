import CompaniesTable from "./components/company/CompaniesTable.vue";
import CompaniesForm from "./components/company/CompaniesForm.vue";
import { meta } from "../../Core/Assets/js/vueProgressMeta";
const locales = window.AsgardCMS.locales;

export default [
  {
    path: "/company/companies",
    name: "admin.company.company.index",
    component: CompaniesTable,
    meta: meta
  },
  {
    path: "/company/companies/create",
    name: "admin.company.company.create",
    component: CompaniesForm,
    props: {
      locales,
      pageTitle: "title.create company"
    }
  },
  {
    path: "/company/companies/:company/edit",
    name: "admin.company.company.edit",
    component: CompaniesForm,
    props: {
      locales,
      pageTitle: "title.edit company"
    }
  },
  {
    path: "/company/profile",
    name: "admin.company.profile.index",
    component: CompaniesForm,
    props: {
      locales,
      pageTitle: "title.profile",
      pageType: "profile"
    }
  }
];
