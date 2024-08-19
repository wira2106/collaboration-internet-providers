<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("users.title.users") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">
              {{ trans("core.breadcrumb.home") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'admin.user.users.index' }">
              {{ trans("users.title.users") }}
            </router-link>
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="card">
        <div class="card-body">
          <div class="sc-table">
            <div class="tool-bar row" style="padding-bottom: 20px;">
              <div class="actions col-12">
                <router-link :to="{ name: 'admin.user.users.create' }">
                  <el-button type="primary" size="small"
                    ><i class="el-icon-edit"></i>
                    {{ trans("users.button.new-user") }}
                  </el-button>
                </router-link>
              </div>
              <div class="search col-12 py-2">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-6">
                        <el-select
                          v-if="companies.length > 0"
                          v-model="company.company_id"
                          placeholder="Select"
                          filterable
                          size="small"
                          @change="fetchData"
                        >
                          <el-option
                            v-for="item in companies"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                            :data-id="item.value"
                          >
                          </el-option>
                        </el-select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <el-input
                      prefix-icon="el-icon-search"
                      size="small"
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

            <el-table
              :data="data"
              stripe
              style="width: 100%"
              ref="pageTable"
              v-loading.body="tableIsLoading"
              @sort-change="handleSortChange"
            >
              <!--  <el-table-column prop="id" label="No" width="75" >
                                <template slot-scope="scope">
                                    <div :load="loadConsole(scope)">{{(meta.per_page * (meta.current_page - 1)) + scope.$index+1}}</div>
                                </template>
                            </el-table-column> -->
              <el-table-column
                :index="indexMethod"
                type="index"
                prop="logo_perusahaan"
                label="No"
                sortable="custom"
                width="75"
              >
              </el-table-column>
              <el-table-column
                prop="full_name"
                :label="trans('users.table.full-name')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    {{ scope.row.full_name }}
                  </a>
                </template>
              </el-table-column>
              <el-table-column
                prop="phone"
                :label="trans('users.table.phone')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    {{ scope.row.phone }}
                  </a>
                </template>
              </el-table-column>
              <el-table-column
                prop="email"
                :label="trans('users.table.email')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    {{ scope.row.email }}
                  </a>
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
                    <edit-button
                      :to="{
                        name: 'admin.user.users.edit',
                        params: { userId: scope.row.id },
                      }"
                    ></edit-button>
                    <delete-button :scope="scope" :rows="data"></delete-button>
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
      data: [],
      company: {
        company_id: null,
      },
      companies: [],
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
            "api.user.user.index",
            _.merge(properties, customProperties, this.company)
          )
        )
        .then((response) => {
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.data = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.companies = response.data.companies.map((item) => {
              return {
                label: item.name,
                value: item.company_id,
              };
            });
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            console.log(response.data);
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
        name: "admin.user.users.edit",
        params: {
          userId: scope.row.id,
        },
      });
    },
    consoleLoad(scope) {
      console.log(scope);
    },
    loadConsole(scope) {
      console.log(scope);
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
