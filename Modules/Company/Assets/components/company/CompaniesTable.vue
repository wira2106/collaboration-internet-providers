<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("companies.title.companies") }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">{{
              trans("core.breadcrumb.home")
            }}</router-link>
          </li>
          <li class="breadcrumb-item">
            {{ trans("companies.title.companies") }}
          </li>
        </ol>
      </div>
    </div>
    <div class="container">
      <div class="card card-outline-info">
        <div class="card-header text-white">
          {{ trans("companies.title.companies") }}

          <el-button
            plain
            size="small"
            icon="el-icon-plus"
            class="pull-right"
            @click.prevent="goToCreate()"
            >{{ trans("companies.button.create company") }}</el-button
          >
        </div>
        <div class="card-body">
          <div class="sc-table">
            <div class="tool-bar row" style="padding-bottom: 20px">
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-6">
                    <el-select
                      v-model="typeSelected"
                      @change="fetchData"
                      size="small"
                      placeholder="Select"
                    >
                      <el-option
                        v-for="item in companyType"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      >
                      </el-option>
                    </el-select>
                  </div>
                </div>
              </div>
              <div class="search col-md-4">
                <el-input
                  prefix-icon="el-icon-search"
                  size="small"
                  @keyup.enter.native="fetchData"
                  v-model="searchQuery"
                  :placeholder="trans('companies.placeholder.cari company')"
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

            <el-table
              :data="data"
              stripe
              style="width: 100%"
              ref="pageTable"
              v-loading.body="tableIsLoading"
              @sort-change="handleSortChange"
            >
              <el-table-column label="No" width="75">
                <template slot-scope="scope">
                  {{
                    meta.per_page * (meta.current_page - 1) + scope.$index + 1
                  }}
                </template>
              </el-table-column>
              <el-table-column
                prop="logo_perusahaan"
                :label="trans('companies.table.logo')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    <SmallImage :url="scope.row.logo"> </SmallImage>
                  </a>
                </template>
              </el-table-column>
              <el-table-column
                prop="name"
                :label="trans('companies.table.name')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    <text-highlight
                      :queries="highlight"
                      highlightClass="highlight-text"
                    >
                      {{ scope.row.name }}
                    </text-highlight>
                  </a>
                </template>
              </el-table-column>
              <el-table-column
                prop="type"
                :label="trans('companies.table.type')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    <text-highlight
                      :queries="highlight"
                      highlightClass="highlight-text"
                    >
                      {{ capitalizeLetter(scope.row.type) }}
                    </text-highlight>
                  </a>
                </template>
              </el-table-column>
              <el-table-column
                prop="rating"
                :label="trans('companies.table.rating.rating')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <el-rate
                    :value="scope.row.rating / 10"
                    disabled
                    text-color="#ff9900"
                    :max="1"
                    show-score
                    :score-template="scope.row.rating.toString()"
                  >
                  </el-rate>
                </template>
              </el-table-column>
              <el-table-column
                prop="created_at"
                :label="trans('core.table.created at')"
                sortable="custom"
              >
              </el-table-column>
              <el-table-column
                prop="actions"
                :label="trans('core.table.actions')"
              >
                <template slot-scope="scope">
                  <el-button-group>
                    <el-tooltip
                      :content="trans('core.tooltip.company.edit')"
                      placement="top"
                    >
                      <el-button
                        size="mini"
                        @click.prevent="goToEdit(scope)"
                        v-if="scope.row.urls.edit_url"
                        icon="el-icon-edit"
                      >
                      </el-button>
                    </el-tooltip>

                    <el-tooltip
                      :content="trans('core.tooltip.company.hapus')"
                      placement="top"
                    >
                      <delete-button
                        :scope="scope"
                        :rows="data"
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
    <button
      v-shortkey="['c']"
      @shortkey="pushRoute({ name: 'admin.user.users.create' })"
      v-show="false"
    ></button>
  </div>
</template>

<script>
import axios from "axios";
import _ from "lodash";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";

export default {
  mixins: [ShortcutHelper],
  data() {
    return {
      highlight: "",
      data: [],
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
      typeSelected: "",
      companyType: [
        {
          value: "",
          label: "Seluruh",
        },
        {
          value: "ISP",
          label: "ISP",
        },
        {
          value: "OSP",
          label: "OSP",
        },
      ],
      requests: [],
      request: null,
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
          type: this.typeSelected,
          ...customProperties,
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(route("api.company.company.pagination"), properties)
        .then((response) => {
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.data = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.highlight = this.searchQuery;
          }
        })
        .catch((error) => {});
    },
    fetchData() {
      this.meta.current_page = 1;
      this.meta.per_page = 10;
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
      // window.addEventListener("keyup", function(event) {
      //   if (event.keyCode === 13) {
      //     self.fetchData();
      //   }
      // });
    },
    goToEdit(scope) {
      this.$router.push({
        name: "admin.company.company.edit",
        params: {
          company: scope.row.company_id,
        },
      });
    },
    goToCreate() {
      this.$router.push({
        name: "admin.company.company.create",
      });
    },
    capitalizeLetter(string) {
      return string.toUpperCase();
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
