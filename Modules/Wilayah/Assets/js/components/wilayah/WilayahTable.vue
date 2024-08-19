<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("wilayahs.list resource") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">
              {{ trans("core.breadcrumb.home") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            <a href="/backend/wilayah">{{
              trans("wilayahs.title.wilayahs")
            }}</a>
          </li>
        </ol>
      </div>
    </div>
    <div class="container">
      <div class="card card-outline-info">
        <div class="card-header text-white">
          {{ trans("wilayahs.list resource") }}
          <el-button
            plain
            size="small"
            icon="el-icon-edit"
            class="pull-right"
            @click.prevent="goToCreate()"
          >
            {{ trans("wilayahs.button.create wilayah") }}
          </el-button>
        </div>
        <div class="card-body">
          <wilayah-table-body> </wilayah-table-body>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import WilayahTableBodyVue from "./table/WilayahTableBody.vue";

export default {
  components: {
    "wilayah-table-body": WilayahTableBodyVue,
  },

  methods: {
    goToCreate() {
      this.$router.push({
        name: "admin.wilayah.wilayahs.create",
      });
    },
  },
};
</script>
