<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("staff.title.staff") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">
              {{ trans("core.breadcrumb.home") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'admin.user.staff.index' }">
              {{ trans("staff.title.staff") }}
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
              <div class="col-12">
                <div class="actions pb-2 pull-right">
                  <el-button
                    type="primary"
                    size="small"
                    @click.prevent="goToCreate()"
                    ><i class="el-icon-edit"></i>
                    {{ trans("staff.create staff") }}
                  </el-button>
                </div>
              </div>
              <div class="col-12">
                <div class="row d-flex justify-content-between">
                  <div class="col-3">
                    <el-select
                      v-if="user_role == 'Super Admin'"
                      v-model="company_id"
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
                  <div class="search col-6">
                    <div class="row">
                      <div class="col-4">
                        <el-select
                          v-model="role_id"
                          placeholder="Select"
                          filterable
                          size="small"
                          @change="filterByRule"
                        >
                          <el-option
                            v-for="role in roles_computed"
                            :key="role.id"
                            :label="role.name"
                            :value="role.id"
                          >
                          </el-option>
                        </el-select>
                      </div>
                      <div class="col-8 ">
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
                :label="trans('staff.table.no')"
                sortable="custom"
              >
              </el-table-column>
              <el-table-column
                prop="full_name"
                :label="trans('staff.table.full-name')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    <text-highlight
                      :queries="highlight"
                      highlightClass="highlight-text"
                    >
                      {{ scope.row.full_name }}
                    </text-highlight>
                  </a>
                </template>
              </el-table-column>
              <el-table-column
                prop="email"
                :label="trans('staff.table.email')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    <text-highlight
                      :queries="highlight"
                      highlightClass="highlight-text"
                    >
                      {{ scope.row.email }}
                    </text-highlight>
                  </a>
                </template>
              </el-table-column>
              <el-table-column
                prop="phone"
                :label="trans('staff.table.phone')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
                    {{ scope.row.phone }}
                  </a>
                </template>
              </el-table-column>
              <el-table-column
                prop="role_name"
                :label="trans('staff.table.role')"
                sortable="custom"
              >
                <template slot-scope="scope">
                  {{ scope.row.role_name | role_name }}
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
                    <el-button
                      size="mini"
                      @click.prevent="goToEdit(scope)"
                      v-if="scope.row.urls.edit_url"
                    >
                      <i class="fa fa-edit"></i>
                    </el-button>
                    <delete-button
                      :scope="scope"
                      :rows="data"
                      v-if="scope.row.urls.delete_url != null"
                      v-on:onDelete="tableIsLoading = $event"
                      v-on:onDeleteSuccess="tableIsLoading = !$event"
                    ></delete-button>
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
  </div>
</template>

<script>
import axios from "axios";
import _ from "lodash";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import UserRolesPermission from "../../../../Core/Assets/js/mixins/UserRolesPermission";
import PermissionsHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";

export default {
  mixins: [ShortcutHelper, UserRolesPermission, PermissionsHelper],
  data() {
    return {
      highlight: "",
      data: [],
      companies: [],
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
      company_id: null,
      user_role: "",
      request: null,
      requests: [],
      roles: [],
      role_id: "",
    };
  },
  methods: {
    goToCreate() {
      this.$router.push({
        name: "admin.user.staff.create",
      });
    },
    queryServer(customProperties) {
      this.tableIsLoading = true;
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
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(route("api.user.staff.pagination"), properties)
        .then((response) => {
          this.tableIsLoading = false;
          if (response) {
            this.data = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.company_id = response.data.selected;
            this.highlight = this.searchQuery;
            this.companies = response.data.companies.map((item) => {
              return {
                label: item.name,
                value: item.company_id,
              };
            });
          }
        })
        .catch((error) => {
          this.tableIsLoading = false;
        });
    },
    filterByRule(event) {
      this.queryServer({
        role: event,
      });
    },
    getAllRole() {
      axios
        .get(route("api.user.role.all"))
        .then((response) => {
          this.roles = [
            {
              name: this.trans("core.semua"),
              id: "",
            },
            ...response.data.data,
          ];
        })
        .catch((error) => {
          console.log(error);
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
        name: "admin.user.staff.edit",
        params: {
          staff: scope.row.id_staff,
        },
      });
    },
    capitalizeLetter(string) {
      return string.toUpperCase();
    },
    indexMethod(index) {
      return (this.meta.current_page - 1) * this.meta.per_page + index + 1;
    },
  },
  mounted() {
    this.fetchData();
    this.getRolesPermission();
    // this.searchFunction();
    this.getAllRole();
  },
  computed: {
    roles_computed: function() {
      let roles = [];

      this.roles.forEach((role) => {
        if (this.user_roles.name == "Admin ISP") {
          if (role.name === "Admin OSP" || role.name === "Super Admin")
            return 0;

          if (role.name === "Admin ISP") {
            roles.push({
              name: "Admin",
              id: role.id,
            });
          } else {
            roles.push(role);
          }
        }

        if (this.user_roles.name == "Admin OSP") {
          if (role.name === "Admin ISP" || role.name === "Super Admin")
            return 0;

          if (role.name === "Admin OSP") {
            roles.push({
              name: "Admin",
              id: role.id,
            });
          } else {
            roles.push(role);
          }
        }

        if (this.user_roles.name == "Super Admin") {
          roles.push(role);
        }
      });
      return roles;
    },
  },
};
</script>
<style>
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
