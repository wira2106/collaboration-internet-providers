<template>
  <bs-modal id="modal-list-guide" @onClose="onClose()">
    <span slot="header"> {{ trans("presales.modal.list guide.title") }} </span>

    <el-table
      :data="
        guide_computed.filter(data => !search_guide || data.name.toLowerCase().includes(search_guide.toLowerCase()))
      "
      style="width: 100%"
      height="400"
    >
      <el-table-column label="Panduan" prop="name" :min-width="70"> </el-table-column>
      <el-table-column align="right" :min-width="30">
        <template slot="header" slot-scope="scope">
          <el-input
            v-model="search_guide"
            size="mini"
            :placeholder="trans('presales.cari panduan')">
          </el-input>
        </template>
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="start(scope.row.type)">
            {{ trans("presales.button.start") }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </bs-modal>
</template>

<script>
import TranslationHelper from "../../../../../public/modules/core/js/mixins/TranslationHelper";
import PermissionsHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";
import BsModalVue from "./BsModal.vue";

export default {
  mixins: [TranslationHelper, PermissionsHelper],
  components: {
    "bs-modal": BsModalVue,
  },
  data() {
    return {
      search_guide: "",
      guide: [
        {
          name: this.trans("endpoint.tour.tambah endpoint.title"),
          type: "tambah_endpoint",
          permission: "presale.endpoints.create",
        },
        {
          name: this.trans("endpoint.tour.edit data endpoint.title"),
          type: "edit_data_endpoint",
          permission: "presale.endpoints.edit",
        },
        {
          name: this.trans("endpoint.tour.edit location endpoint.title"),
          type: "edit_location_endpoint",
          permission: "presale.endpoints.edit",
        },
        {
          name: this.trans("endpoint.tour.hapus endpoint.title"),
          type: "hapus_endpoint",
          permission: "presale.endpoints.delete",
        },
        {
          name: this.trans("endpoint.tour.konfirmasi endpoint.title"),
          type: "konfirmasi_endpoint",
          permission: "presale.endpoints.create",
        },
        {
          name: this.trans("presales.tour.tambah presale.title"),
          type: "tambah_presale",
          permission: "presale.presales.create",
        },
        {
          name: this.trans("presales.tour.edit data presale.title"),
          type: "edit_data_presale",
          permission: "presale.presales.edit",
        },
        {
          name: this.trans("presales.tour.edit location presale.title"),
          type: "edit_location_presale",
          permission: "presale.presales.edit",
        },
        {
          name: this.trans("presales.tour.hapus presale.title"),
          type: "hapus_presale",
          permission: "presale.presales.delete",
        },
        {
          name: this.trans("presales.tour.tampilkan jalur kabel presale.title"),
          type: "tampilkan_jalur_kabel_presale",
          permission: "presale.presales.index",
        },
        {
          name: this.trans(
            "presales.tour.tampilkan jalur kabel endpoint ke presale.title"
          ),
          type: "tampilkan_jalur_kabel_endpoint_ke_presale",
          permission: "presale.endpoints.index",
        },
        {
          name: this.trans("presales.tour.hide marker presale.title"),
          type: "hide_marker_presale",
        },
        {
          name: this.trans("endpoint.tour.hide marker endpoint.title"),
          type: "hide_marker_endpoint",
        },
        {
          name: this.trans(
            "presales.tour.menampilkan presale yang dicari.title"
          ),
          type: "menampilkan_presale_yang_dicari",
        },
        {
          name: this.trans("presales.tour.konfirmasi presale.title"),
          type: "konfirmasi_presale",
          permission: "presale.presales.confirm",
        },
        {
          name: this.trans("presales.tour.order pelanggan.title"),
          type: "order_pelanggan",
          permission: "Admin ISP",
        },
      ],
    };
  },
  methods: {
    onClose() {
      $("#modal-list-guide").modal("hide");
    },
    start(type) {
      $("#modal-list-guide").modal("hide");
      this.$emit("start", type);
    },
  },
  computed: {
    guide_computed() {
      return this.guide.filter((data) => {
        if(data.permission === this.user_roles.name) return true;
        if (!data.hasOwnProperty("permission")) return true;
        return this.hasAccess(data.permission);
      });
    },
  },
};
</script>

<style></style>
