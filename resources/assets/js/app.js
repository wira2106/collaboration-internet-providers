require("./bootstrap");
require("vue2-animate/dist/vue2-animate.min.css");

import "babel-polyfill";
import Vue from "vue";
import VueI18n from "vue-i18n";
import VueRouter from "vue-router";
import ElementUI from "element-ui";
import VueEvents from "vue-events";
import locale from "element-ui/lib/locale/lang/en";
import VueSimplemde from "vue-simplemde";
import PageRoutes from "../../../Modules/Page/Assets/js/PageRoutes";
import MediaRoutes from "../../../Modules/Media/Assets/js/MediaRoutes";
import UserRoutes from "../../../Modules/User/Assets/js/UserRoutes";
import CompaniesRoutes from "../../../Modules/Company/Assets/CompaniesRoutes";
import BiayaKabelRoutes from "../../../Modules/Company/Assets/BiayaKabelRoutes";
import SlotInstalasiRoutes from "../../../Modules/Company/Assets/SlotInstalasiRoutes";
import ConfigRoutes from "../../../Modules/Configuration/Assets/js/ConfigRoutes";
import PaketBerlanggananRoutes from "../../../Modules/Company/Assets/PaketBerlanggananRoutes";
import PresaleRoutes from "../../../Modules/Presale/Assets/PresaleRoutes";
import WilayahRoutes from "../../../Modules/Wilayah/Assets/js/WilayahRoutes";
import AlatRoutes from "../../../Modules/Peralatan/Assets/AlatRoutes";
import BarangRoutes from "../../../Modules/Peralatan/Assets/BarangRoutes";
import PerangkatRoutes from "../../../Modules/Peralatan/Assets/PerangkatRoutes";
import SaldoRoutes from "../../../Modules/Saldo/Assets/saldoRoutes";
import PelangganRoutes from "../../../Modules/Pelanggan/Assets/js/PelangganRoutes";
import TextHighlight from "vue-text-highlight";
import InvoiceRoutes from "../../../Modules/Invoice/Assets/js/InvoiceRoutes";
import TicketRoutes from "../../../Modules/Ticket/Assets/js/TicketRoutes";
import AnalyticRoutes from "../../../Modules/Analytic/Assets/js/AnalyticRoutes";
import Vuex from "vuex";
import DashboardRoutes from "../../../Modules/Dashboard/Assets/js/DashboardRoutes";

import * as VueGoogleMaps from "vue2-google-maps";

// State Import
import Saldo from "../../../Modules/Saldo/Assets/state";
import Pelanggan from "../../../Modules/Pelanggan/Assets/state";
import Config from "../../../Modules/Configuration/Assets/js/state";
import Company from "../../../Modules/Company/Assets/state";




import VueTour from "vue-tour";
import VueClipboard from "vue-clipboard2";
import VueProgressBar from "vue-progressbar";
import { meta } from "../../../Modules/Core/Assets/js/vueProgressMeta";

require("vue-tour/dist/vue-tour.css");

Vue.use(VueTour);
Vue.use(VueClipboard);
Vue.use(Vuex);
Vue.use(ElementUI, { locale });
// Vue.use(TextHighlight);
Vue.use(VueI18n);
Vue.use(VueRouter);
Vue.use(require("vue-shortkey"), { prevent: ["input", "textarea"] });
Vue.use(VueEvents);
Vue.use(VueSimplemde);
Vue.use(VueGoogleMaps, {
  load: {
    key: "AIzaSyAJRyIHISCimoNj8hkhQiSpqnuKS5nju8w",
    libraries: ["places", "geometry"]
  }
});
require("./mixins");
require("./filters");
Vue.component("text-highlight", TextHighlight);
Vue.component(
  "ckeditor",
  require("../../../Modules/Core/Assets/js/components/CkEditor.vue")
);
Vue.component(
  "HtmlFragment",
  require("../../../Modules/Core/Assets/js/components/HtmlFragment.vue")
);
Vue.component(
  "SidebarItem",
  require("../../../Modules/Core/Assets/js/components/SidebarItem.vue")
);
Vue.component(
  "SidebarUl",
  require("../../../Modules/Core/Assets/js/components/SidebarUl.vue")
);

Vue.component(
  "SidebarChild",
  require("../../../Modules/Core/Assets/js/components/SidebarChild.vue")
);
Vue.component(
  "VueButton",
  require("../../../Modules/Core/Assets/js/components/VueButton.vue")
);
Vue.component(
  "UploadAvatar",
  require("../../../Modules/Core/Assets/js/components/UploadAvatar.vue")
);
Vue.component(
  "DeleteButton",
  require("../../../Modules/Core/Assets/js/components/DeleteComponent.vue")
);
Vue.component(
  "SelectProvinces",
  require("../../../Modules/Core/Assets/js/components/SelectProvinces.vue")
);
Vue.component(
  "SmallImage",
  require("../../../Modules/Core/Assets/js/components/ImageComponent.vue")
);
Vue.component(
  "SelectRegencies",
  require("../../../Modules/Core/Assets/js/components/SelectRegencies.vue")
);
Vue.component(
  "SelectDistricts",
  require("../../../Modules/Core/Assets/js/components/SelectDistricts.vue")
);
Vue.component(
  "SelectVillages",
  require("../../../Modules/Core/Assets/js/components/SelectVillages.vue")
);
Vue.component(
  "EditButton",
  require("../../../Modules/Core/Assets/js/components/EditButtonComponent.vue")
);
Vue.component(
  "TagsInput",
  require("../../../Modules/Tag/Assets/js/components/TagInput.vue")
);
Vue.component(
  "SingleMedia",
  require("../../../Modules/Media/Assets/js/components/SingleMedia.vue")
);
Vue.component(
  "MediaManager",
  require("../../../Modules/Media/Assets/js/components/MediaManager.vue")
);
Vue.component(
  "BsModal",
  require("../../../Modules/Presale/Assets/components/modal/BsModal.vue")
);
Vue.component(
  "IconStatus",
  require("../../../Modules/Core/Assets/js/components/IconStatus.vue")
);
Vue.component(
  "ListWilayahWidget",
  require("../../../Modules/Wilayah/Assets/js/components/widgets/ListWilayahWidget.vue")
);
Vue.component(
  "ListPengajuanWilayahWidget",
  require("../../../Modules/Wilayah/Assets/js/components/widgets/ListPengajuanWilayahWidget.vue")
);
Vue.component(
  "ListWilayahIspWidget",
  require("../../../Modules/Wilayah/Assets/js/components/widgets/ListWilayahISPWidget")
);
Vue.component(
  "saldo",
  require("../../../Modules/Saldo/Assets/components/Saldo/Saldo.vue")
);
Vue.component(
  "Card",
  require("../../../Modules/Core/Assets/js/components/Card.vue")
);

const currentLocale = window.AsgardCMS.currentLocale;
const adminPrefix = window.AsgardCMS.adminPrefix;

function getTitle(vm) {
  const { title } = vm.$options;
  if (title) {
    return typeof title === "function" ? title.call(vm) : title;
  }
}

function makeBaseUrl() {
  if (window.AsgardCMS.hideDefaultLocaleInURL == 1) {
    return adminPrefix;
  }
  return `${currentLocale}/${adminPrefix}`;
}

const router = new VueRouter({
  mode: "history",
  base: makeBaseUrl(),
  routes: [
    ...PageRoutes,
    ...MediaRoutes,
    ...UserRoutes,
    ...CompaniesRoutes,
    ...ConfigRoutes,
    ...PaketBerlanggananRoutes,
    ...WilayahRoutes,
    ...BiayaKabelRoutes,
    ...SlotInstalasiRoutes,
    ...AlatRoutes,
    ...BarangRoutes,
    ...PerangkatRoutes,
    ...PresaleRoutes,
    ...SaldoRoutes,
    ...PelangganRoutes,
    ...InvoiceRoutes,
    ...TicketRoutes,
    ...DashboardRoutes,
    ...AnalyticRoutes
  ]
});

router.beforeEach((to, from, next) => {
  $("#sidebarnav").metisMenu();

  next();
});

const messages = {
  [currentLocale]: window.AsgardCMS.translations
};

const i18n = new VueI18n({
  locale: currentLocale,
  messages
});

const store = new Vuex.Store({
  modules: {
    Saldo,
    Config,
    Pelanggan,
    Company
  }
});

store.dispatch("getSaldo");

const options = {
  color: "#bffaf3",
  failedColor: "#874b4b",
  thickness: "5px",
  transition: {
    speed: "0.2s",
    opacity: "0.6s",
    termination: 300
  },
  autoRevert: true,
  inverse: false
};

Vue.use(VueProgressBar, options);

const app = new Vue({
  el: "#app",
  store,
  router,
  i18n,
  mounted() {
    this.$Progress.finish();
    $("#sidebarnav").metisMenu();
  },
  created() {
    this.$Progress.start();
    this.$router.beforeEach((to, from, next) => {
      this.$Progress.parseMeta(meta.progress);
      this.$Progress.start();
      next();
    });
    this.$router.afterEach((to, from) => {
      this.$Progress.finish();
    });
  }
});

window.axios.interceptors.response.use(null, error => {
  if (error.response === undefined) {
    // console.log(error);
    return;
  }
  if (error.response.status === 403) {
    app.$notify.error({
      title: app.$t("core.unauthorized"),
      message: app.$t("core.unauthorized-access")
    });
    window.location = route("dashboard.index");
  }
  if (error.response.status === 401) {
    app.$notify.error({
      title: app.$t("core.unauthenticated"),
      message: app.$t("core.unauthenticated-message")
    });
    window.location = route("login");
  }

  return Promise.reject(error);
});
