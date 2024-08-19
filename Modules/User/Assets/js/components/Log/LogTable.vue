<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("users.title.log") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/backend">
              {{ trans("core.breadcrumb.home") }}
            </a>
          </li>
          <li class="breadcrumb-item">{{ trans("users.title.log") }}</li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline-info">
            <div class="card-header text-white">
              {{ trans("users.title.log") }}
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3 pb-3">
                  <el-select
                    class=""
                    size="mini"
                    v-model="filterKet"
                    filterable
                    @change="filterLog"
                  >
                    <el-option
                      v-for="item in filter"
                      :value="item.value"
                      :label="item.label"
                      :key="item.value"
                    ></el-option>
                  </el-select>
                </div>
                <div class="col-md-4">
                  <el-select
                    v-if="user_role == 'Super Admin'"
                    v-model="companiesKet"
                    filterable
                    size="mini"
                    @change="filterLog"
                  >
                    <el-option label="Seluruh" value="all"></el-option>
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
                <div class="col-md-5">
                  <div
                    class="btn-group float-right"
                    style="margin: 0 15px 15px 0;"
                  >
                    <el-input
                      prefix-icon="el-icon-search"
                      size="small"
                      @keyup.enter.native="search"
                      v-model="searchQuery"
                      :placeholder="trans('logs.placeholder.cari log')"
                    >
                      <!-- @keyup.native="performSearch" -->
                      <template slot="append">
                        <el-button size="small" @click="search">
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
                    :data="logTable"
                    height="420"
                    style="width: 100%"
                    ref="logTable"
                    v-loading.body="tableIsLoading"
                  >
                    <!-- <el-table-column :label="trans('users.table.no')" width="50" fixed>
                                        <template slot-scope="scope">
                                            {{scope.$index+1}}
                                        </template>
                                    </el-table-column> -->
                    <el-table-column
                      fixed
                      prop="created_at"
                      :label="trans('users.table.tgl')"
                      width="200"
                    >
                      <template slot-scope="scope">
                        <span style="font-size:11px;">
                          {{ scope.row.tanggal }}<br />{{ scope.row.waktu }}
                        </span>
                      </template>
                    </el-table-column>

                    <el-table-column :label="trans('users.table.ket')">
                      <template slot-scope="scope">
                        <div style="padding-top:5px;">
                          <span
                            style="font-size: 12px;font-weight: 700;display: block;"
                          >
                            {{ scope.row.tipe }}
                          </span>
                          <span
                            class="search-regex"
                            style="font-size:10px;display:block;"
                          >
                            <b>
                              <text-highlight
                                :queries="highlight"
                                highlightClass="highlight-text"
                              >
                                {{ scope.row.nama_user }}
                              </text-highlight>
                              &nbsp;
                            </b>
                            <span v-if="user_role == 'Super Admin'">
                              <text-highlight
                                :queries="highlight"
                                highlightClass="highlight-text"
                              >
                                ({{ scope.row.nama_perusahaan }})
                              </text-highlight>
                            </span>
                          </span>
                          <span style="font-size:10px;">
                            <text-highlight
                              :queries="highlight"
                              highlightClass="highlight-text"
                            >
                              {{ scope.row.deskripsi }}
                            </text-highlight>
                          </span>
                        </div>
                      </template>
                    </el-table-column>
                  </el-table>
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
import UserRolesPermission from "../../../../../Core/Assets/js/mixins/UserRolesPermission";

export default {
  mixins: [UserRolesPermission],
  data() {
    return {
      highlight: "",
      logTable: [],
      data: [],
      companiesKet: "all",
      filterKet: "all",
      searchQuery: "",
      tableIsLoading: false,
      scrollTop: "",
      heightTable: "",
      heightScroll: "",
      user_role: "",
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      filter: [
        {
          value: "all",
          label: "Seluruh",
        },
      ],
      companies: [
        {
          value: "all",
          label: "Seluruh",
        },
      ],
      company_id: null,
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
          type: this.filterKet,
          company_name: this.companiesKet,
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };

      this.tableIsLoading = true;
      axios
        .get(route("api.user.log.index"), _.merge(properties, customProperties))
        .then((response) => {
          if (typeof response !== "undefined") {
            this.logTable = [...this.logTable, ...response.data.data];
            this.meta = response.data.meta;
            this.link = response.data.link;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.tableIsLoading = false;
            this.highlight = this.searchQuery;
          }
        })
        .catch((error) => {});
    },

    fetchData() {
      if (this.request) this.cancel();
      this.queryServer();
      this.handleScroll();
    },
    filterLog() {
      this.logTable = [];
      this.meta.current_page = 1;
      this.fetchData();
    },
    search() {
      this.logTable = [];
      this.queryServer();
    },
    handleScroll() {
      this.$refs.logTable.bodyWrapper.addEventListener("scroll", (list) => {
        this.scrollTop = list.target.scrollTop;
        this.heightTable = list.target.clientHeight;
        this.heightScroll = list.target.scrollHeight;
        let totalHeight = this.heightTable + this.scrollTop,
          lengthDataLog = this.logTable.length,
          selisih = 0.5;
        // console.log(this.meta.total);
        // console.log([totalHeight, this.heightScroll]);
        if (
          totalHeight >= this.heightScroll - selisih &&
          lengthDataLog < this.meta.total
        ) {
          this.tableIsLoading = true;
          this.handleCurrentChange(this.meta.current_page + 1);
        }
      });
    },
    handleCurrentChange(event) {
      this.tableIsLoading = true;
      this.queryServer({
        page: event,
      });
    },
    performSearch: _.debounce(function(query) {
      // console.log(`searching:${query.target.value}`);
      this.tableIsLoading = true;
      this.logTable = [];
      this.meta.current_page = 1;
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
          self.search();
        }
      });
    },

    fetchTipeLog() {
      axios.get(route("api.user.log.tipe")).then((response) => {
        console.log(response);
        this.filter = [...this.filter, ...response.data];
      });
    },
    fetchDataCompanies() {
      axios.get(route("api.user.staff.pagination")).then((response) => {
        this.companies = response.data.companies.map((item) => {
          return {
            label: item.name,
            value: item.company_id,
          };
        });
      });
    },
  },
  mounted() {
    this.fetchData();
    this.fetchTipeLog();
    // this.searchFunction();
    this.getRolesPermission();
    this.fetchDataCompanies();
  },
};
</script>
<style>
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
