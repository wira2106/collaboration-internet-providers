<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("analytics.title.analytics pelanggan") }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">
              {{ trans("core.breadcrumb.home") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            <a href="/backend">{{ trans("core.breadcrumb.home") }}</a>
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="card card-outline-info">
        <div class="card-header text-white" @click="fetchData">
          {{ trans("analytics.list resource") }}
        </div>
        <div class="card-body">
          <div class="sc-table">
            <div class="tool-bar row" style="padding-bottom: 20px">
              <div class="col-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div
                        class="col-md-6"
                        v-if="
                          user_roles.name != '' &&
                            user_roles.name == 'Super Admin'
                        "
                      >
                        <el-select
                          v-if="companies.length > 0"
                          v-model="company_id"
                          placeholder="Select"
                          filterable
                          size="small"
                          @change="fetchData"
                        >
                          <el-option label="Seluruh ISP" value="" data-id="">
                          </el-option>
                          <el-option
                            v-for="(item, index) in companies_computed"
                            :key="index"
                            :label="item.name"
                            :value="item.company_id"
                          >
                          </el-option>
                        </el-select>
                      </div>
                    </div>
                  </div>
                  <div class="search col-md-6">
                    <div class="pull-right">
                      <el-input
                        size="small"
                        prefix-icon="el-icon-search"
                        @keyup.enter.native="fetchData"
                        v-model="searchQuery"
                      >
                        <!-- @keyup.native="performSearch" -->
                        <template slot="append">
                          <el-button size="small" @click="fetchData">
                            <span class="fa fa-search"></span>
                          </el-button>
                        </template>
                      </el-input>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <el-table
              :data="data"
              stripe
              style="width: 100%"
              ref="pageTable"
              v-loading.body="tableIsLoading"
              @sort-change="handleSortChange"
            >
              <el-table-column
                :index="indexMethod"
                type="index"
                prop="nomor"
                label="No"
                sortable="custom"
              >
              </el-table-column>
              <el-table-column
                prop="nama_pelanggan"
                :label="trans('tickets.table.nama pelanggan')"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToDetail(scope)" href="#">
                    <text-highlight
                      :queries="highlight"
                      highlightClass="highlight-text"
                    >
                      {{ scope.row.nama_pelanggan }}
                    </text-highlight>
                  </a>
                  <br />
                  <span style="font-size: 11px"
                    ><i>
                      <text-highlight
                        :queries="highlight"
                        highlightClass="highlight-text"
                      >
                        {{ scope.row.site_id }}
                      </text-highlight>
                    </i></span
                  >
                </template>
              </el-table-column>
              <el-table-column
                prop="wilayah"
                :label="trans('tickets.table.wilayah')"
              >
                <template slot-scope="scope">
                  <text-highlight
                    :queries="highlight"
                    highlightClass="highlight-text"
                  >
                    {{ scope.row.wilayah_name }}
                  </text-highlight>
                  <br />
                  <span style="font-size: 11px">
                    <i>
                      <text-highlight
                        :queries="highlight"
                        highlightClass="highlight-text"
                      >
                        {{ scope.row.osp_name }}
                      </text-highlight>
                    </i>
                  </span>
                </template>
              </el-table-column>
              <el-table-column
                prop="isp"
                :label="trans('tickets.table.isp')"
                v-if="company_id == ''"
              >
                <template slot-scope="scope">
                  <text-highlight
                    :queries="highlight"
                    highlightClass="highlight-text"
                  >
                    {{ scope.row.isp_name }}
                  </text-highlight>
                </template>
              </el-table-column>
              <el-table-column
                prop="standar"
                :label="trans('analytics.table.standar')"
              >
                <template slot-scope="scope">
                  <text-highlight
                    :queries="highlight"
                    highlightClass="highlight-text"
                  >
                    {{ scope.row.standar }} </text-highlight
                  ><br />
                  <span style="font-size:11px;">
                    End Poin : {{ scope.row.tipe_ep }}
                  </span>
                </template>
              </el-table-column>
              <el-table-column
                prop="estimase"
                :label="trans('analytics.table.estimasi')"
              >
                <template slot-scope="scope">
                  <text-highlight
                    :queries="highlight"
                    highlightClass="highlight-text"
                  >
                    {{ scope.row.estimasi }}
                  </text-highlight>
                </template>
              </el-table-column>
            </el-table>
            <div
              class="pagination-wrap"
              style="text-align: center; padding-top: 20px"
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
    </div>
  </div>
</template>
<script>
import axios from "axios";
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";

export default {
  mixins: [PermissionsHelper],
  data() {
    return {
      highlight: "",
      company_id: "",
      companies: [],
      data: [],
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "ascending",
      },
      links: {},
      searchQuery: "",
      tableIsLoading: false,
      request: null,
    };
  },
  computed: {
    companies_computed: function() {
      return this.companies.filter((item) => {
        if (item.type == "isp") {
          return item;
        }
      });
    },
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
          company_id: this.company_id,
          ...customProperties,
        },
        cancelToken: cancelSource.token,
        ...customProperties,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(route("admin.api.analytic.pelanggan.index"), properties)
        .then((response) => {
          if (typeof response !== "undefined") {
            this.highlight = this.searchQuery;
            this.tableIsLoading = false;
            this.data = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            if (response.data.selected != null) {
              this.company_id = parseInt(response.data.selected);
            } else {
              this.company_id = "";
            }
            console.log(this.company_id);
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
    indexMethod(index) {
      return (this.meta.current_page - 1) * this.meta.per_page + index + 1;
    },
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
    fetchCompany() {
      axios.post(route("api.company.company.list")).then((response) => {
        this.companies = response.data;
      });
    },
    goToDetail(scope) {
      this.$router.push({
        name: "admin.analytic.pelanggan.detail",
        params: {
          pelanggan: scope.row.pelanggan_id,
        },
      });
    },
  },
  mounted() {
    // this.searchFunction();
    this.fetchCompany();
    this.fetchData();
  },
};
</script>
<style>
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
