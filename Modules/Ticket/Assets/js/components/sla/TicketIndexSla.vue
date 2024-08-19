<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("ticketsSla.list resource") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/backend/ticket">{{ trans("tickets.title.tickets") }}</a>
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="card card-outline-info">
        <div class="card-header text-white">
          {{ trans("ticketsSla.list resource") }}
          <el-button
            v-if="user_roles.name != 'Admin OSP'"
            plain
            size="small"
            icon="el-icon-plus"
            class="pull-right"
            @click.prevent="goToCreate()"
          >
            {{ trans("tickets.button.create ticket") }}
          </el-button>
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
                          :placeholder="trans('tickets.form.select')"
                          filterable
                          size="small"
                          @change="fetchData"
                        >
                          <el-option
                            :label="trans('pengajuans.table.select all')"
                            value=""
                            data-id=""
                          >
                          </el-option>
                          <el-option
                            v-for="item in companies_computed"
                            :key="item.value"
                            :label="setLabel(item)"
                            :value="item.value"
                            :data-id="item.value"
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
              size="small"
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
              <el-table-column width="30px">
                <template slot-scope="scope">
                  <i
                    class="fas fa-circle text-success"
                    v-if="
                      scope.row.accept_isp_by != null &&
                        scope.row.accept_osp_by != null
                    "
                  ></i>
                </template>
              </el-table-column>
              <el-table-column
                prop="nama_pelanggan"
                :label="trans('tickets.table.nama pelanggan')"
              >
                <template slot-scope="scope">
                  <a @click.prevent="goToEdit(scope)" href="#">
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
                    {{ scope.row.nama_wilayah }}
                  </text-highlight>
                  <br />
                  <span style="font-size: 11px">
                    <i>
                      <text-highlight
                        :queries="highlight"
                        highlightClass="highlight-text"
                      >
                        {{ scope.row.nama_osp }}
                      </text-highlight>
                    </i>
                  </span>
                </template>
              </el-table-column>
              <el-table-column prop="isp" :label="trans('tickets.table.isp')">
                <template slot-scope="scope">
                  <text-highlight
                    :queries="highlight"
                    highlightClass="highlight-text"
                  >
                    {{ scope.row.nama_isp }}
                  </text-highlight>
                </template>
              </el-table-column>
              <el-table-column
                prop="mati_koneksi"
                :label="trans('tickets.table.mati koneksi')"
                width="120"
              >
                <template slot-scope="scope">
                  <text-highlight
                    :queries="highlight"
                    highlightClass="highlight-text"
                  >
                    {{ scope.row.lama_mati }}
                  </text-highlight>
                </template>
              </el-table-column>
              <el-table-column
                prop="status"
                :label="trans('tickets.table.status')"
                width="100"
              >
                <template slot-scope="scope">
                  <span
                    v-if="scope.row.status == 'reply_isp'"
                    class="bg-info text-white span-status"
                    >{{ trans("tickets.span status.reply isp") }}</span
                  >
                  <span
                    v-if="scope.row.status == 'reply_admin'"
                    class="bg-primary text-white span-status"
                    >{{ trans("tickets.span status.reply admin") }}</span
                  >
                  <span
                    v-if="scope.row.status == 'reply_osp'"
                    class="bg-warning span-status"
                    >{{ trans("tickets.span status.reply osp") }}</span
                  >
                  <span
                    v-if="scope.row.status == 'open'"
                    class="bg-success text-white span-status"
                    >{{ trans("tickets.span status.open") }}</span
                  >
                  <span
                    v-if="scope.row.status == 'closed'"
                    class="bg-danger text-white span-status"
                    >{{ trans("tickets.span status.closed") }}</span
                  >
                </template>
              </el-table-column>
              <el-table-column
                prop="actions"
                :label="trans('core.table.actions')"
                width="120"
              >
                <template slot-scope="scope">
                  <el-button-group>
                    <el-button
                      size="mini"
                      @click.prevent="goToEdit(scope)"
                      v-if="scope.row.urls.edit_url"
                      icon="el-icon-edit"
                    >
                    </el-button>
                    <delete-button
                      :scope="scope"
                      :rows="data"
                      v-on:onDelete="tableIsLoading = $event"
                      v-on:onDeleteSuccess="tableIsLoading = !$event"
                    ></delete-button>
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
          <div>
            <i class="fas fa-circle text-success"></i> &nbsp;Approve &nbsp;
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";

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
        if (item.value != 1) return item;
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
        .get(route("admin.api.ticket.data"), properties)
        .then((response) => {
          if (typeof response !== "undefined") {
            this.highlight = this.searchQuery;
            this.tableIsLoading = false;
            this.data = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.companies = response.data.companies.map((item) => {
              return {
                label: item.name,
                value: item.company_id,
                type: item.type,
              };
            });
            if (response.data.selected != null) {
              this.company_id = parseInt(response.data.selected);
            } else {
              this.company_id = "";
            }
          }
        })
        .catch((error) => {});
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
    goToEdit(scope) {
      this.$router.push({
        name: "admin.ticket.sla.session",
        params: {
          id: scope.row.ticket_id,
        },
      });
    },
    goToCreate() {
      this.$router.push({
        name: "admin.ticket.sla.create",
      });
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
    setLabel(item) {
      let val = "";
      val += item.label;
      if (item.type != null) {
        val += " ( " + item.type.toUpperCase() + " )";
      }
      return val;
    },
  },
  mounted() {
    this.fetchData();
    // this.searchFunction();
  },
};
</script>
<style>
.span-status {
  padding: 0px 10px;
  border-radius: 5px;
  font-size: 11px;
}
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
