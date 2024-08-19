import WilayahTable from "./components/wilayah/WilayahTable.vue";
import WilayahForm from "./components/wilayah/WilayahForm.vue";
import PengajuanCreate from "./components/pengajuan/PengajuanCreate.vue";
import PengajuanIndex from "./components/pengajuan/PengajuanIndex.vue";
import PengajuanDetail from "./components/pengajuan/PengajuanDetail.vue";

const locales = window.AsgardCMS.locales;

export default [
    {
        path: '/wilayah',
        name: 'admin.wilayah.wilayah.index',
        component:WilayahTable
    },

  {
    path: "/wilayah/create",
    name: "admin.wilayah.wilayahs.create",
    component: WilayahForm,
    props: {
      pageTitle: "title.create wilayah",
    },
  },

  {
    path: "/wilayah/:wilayah/edit",
    name: "admin.wilayah.wilayahs.edit",
    component: WilayahForm,
    props: {
      pageTitle: "title.edit wilayah",
    },
  },

  // pengajuan
  {
    path: "/pengajuan/create",
    name: "admin.wilayah.pengajuan.create",
    component: PengajuanCreate,
  },

  {
    path: "/pengajuan",
    name: "admin.wilayah.pengajuan.index",
    component: PengajuanIndex,
  },

  {
    path: "/pengajuan/:id/detail",
    name: "admin.wilayah.pengajuan.detail",
    component: PengajuanDetail,
  },
];
