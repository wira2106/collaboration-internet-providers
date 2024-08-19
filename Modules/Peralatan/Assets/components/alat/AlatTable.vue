<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("alats.title.alats") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">{{
              trans("core.breadcrumb.home")
            }}</router-link>
          </li>
          <li class="breadcrumb-item">{{ trans("alats.title.alats") }}</li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div
                    class="btn-group pull-left"
                    style="margin: 0 15px 15px 0;"
                  >
                    <router-link :to="{ name: 'admin.peralatan.alat.create' }">
                      <el-button type="primary">
                        <i class="fa fa-edit"></i>
                        {{ trans("alats.button.create alat") }}
                      </el-button>
                    </router-link>
                  </div>
                  <div
                    class="btn-group pull-right"
                    style="margin: 0 15px 15px 0;"
                  >
                    <el-input
                      prefix-icon="el-icon-search"
                      @keyup.enter.native="fetchData"
                      v-model="searchQuery"
                    >
                      <!-- @keyup.native="performSearch" -->
                      <template slot="append">
                        <el-button @click="fetchData">
                          <span class="fa fa-search"></span>
                        </el-button>
                      </template>
                    </el-input>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <el-table
                    :data="alat"
                    stripe
                    style="width: 100%"
                    v-loading.body="tableIsLoading"
                    @sort-change="handleSortChange"
                  >
                    <el-table-column
                      :index="indexMethod"
                      type="index"
                      prop="nomor"
                      label="No"
                      width="75"
                      sortable="custom"
                    ></el-table-column>
                    <el-table-column
                      prop="nama_alat"
                      :label="trans('alats.table.nama alat')"
                      sortable="custom"
                    >
                      <template slot-scope="scope">
                        <a href="#" @click.privent="goToEdit(scope)">
                          <text-highlight
                            :queries="highlight"
                            highlightClass="highlight-text"
                          >
                            {{ scope.row.nama_alat }}
                          </text-highlight>
                        </a>
                      </template>
                    </el-table-column>
                    <el-table-column
                      prop="created_at"
                      :label="trans('alats.table.created')"
                      sortable="custom"
                    >
                      <template slot-scope="scope">
                        <a href="#" @click.privent="goToEdit(scope)">
                          {{ scope.row.created_at }}
                        </a>
                      </template>
                    </el-table-column>
                    <el-table-column
                      prop="updated_at"
                      :label="trans('alats.table.updated')"
                      sortable="custom"
                    >
                      <template slot-scope="scope">
                        <a href="#" @click.privent="goToEdit(scope)">
                          {{ scope.row.updated_at }}
                        </a>
                      </template>
                    </el-table-column>
                    <el-table-column
                      prop="actions"
                      :label="trans('alats.table.action')"
                      sortable="custom"
                    >
                      <template slot-scope="scope">
                        <el-button-group>
                          <el-tooltip
                            class="item"
                            effect="dark"
                            :content="trans('alats.tooltip.button.hapus')"
                            placement="top"
                          >
                            <edit-button
                              :to="{
                                name: 'admin.peralatan.alat.edit',
                                params: { alat: scope.row.alat_id },
                              }"
                            ></edit-button>
                          </el-tooltip>
                          <el-tooltip
                            class="item"
                            effect="dark"
                            :content="trans('alats.tooltip.button.hapus')"
                            placement="top"
                          >
                            <delete-button
                              :scope="scope"
                              :rows="alat"
                              v-on:onDelete="tableIsLoading = $event"
                              v-on:onDeleteSuccess="tableIsLoading = !$event"
                            ></delete-button>
                          </el-tooltip>
                        </el-button-group>
                      </template>
                    </el-table-column>
                  </el-table>
                  <div
                    class="pagination-wrap"
                    style="text-align: center; padding-top: 20px;"
                  >
                    <el-pagination
                      @size-change="handleSizeChange"
                      @current-change="handleCurrentChange"
                      :current-page.sync="meta.current_page"
                      :page-sizes="[10, 20, 50, 100]"
                      :page-size="parseInt(meta.per_page)"
                      layout="total, sizes, prev, pager, next, jumper"
                      :total="meta.total"
                    >
                    </el-pagination>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import _ from "lodash";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";

export default {
  mixins: [ShortcutHelper],
  data() {
    return {
      highlight: "",
      alat: [],
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      links: {},
      searchQuery: "",
      tableIsLoading: false,
      request: null,
      requests: [],
    };
  },
  methods: {
    queryServer(customProperties) {
      const cancelSource = axios.CancelToken.source();
      const properties = {
        params: {
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(
          route(
            "api.peralatan.alat.index",
            _.merge(properties, customProperties)
          ),
          properties
        )
        .then((response) => {
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.alat = response.data.data;
            this.meta = response.data.meta;
            this.link = response.data.link;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.highlight = this.searchQuery;
          }
        });
    },
    fetchData() {
      this.tableIsLoading = true;
      if (this.request) this.cancel();
      this.queryServer();
    },
    handleSizeChange(event) {
      console.log(`per page :${event}`);
      this.tableIsLoading = true;
      this.queryServer({
        per_page: event,
      });
    },
    handleCurrentChange(event) {
      console.log(`current page :${event}`);
      this.tableIsLoading = true;
      this.queryServer({
        page: event,
      });
    },
    handleSortChange(event) {
      console.log("sorting", event);
      this.tableIsLoading = true;
      this.queryServer({
        order_by: event.prop,
        order: event.order,
      });
    },
    performSearch: _.debounce(function(query) {
      console.log(`searching:${query.target.value}`);
      this.tableIsLoading = true;
      this.queryServer({
        search: query.target.value,
      });
    }, 300),
    cancel() {
      this.request.cancel();
    },
    searchFunction() {
      var self = this;
      window.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
          self.fetchData();
        }
      });
    },
    goToEdit(scope) {
      this.$router.push({
        name: "admin.peralatan.alat.edit",
        params: {
          alat: scope.row.alat_id,
        },
      });
    },
    indexMethod(index) {
      return (this.meta.current_page - 1) * this.meta.per_page + index + 1;
    },
  },
  mounted() {
    this.fetchData();
    // this.searchFunction();
  },
};
</script>
<style>
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
