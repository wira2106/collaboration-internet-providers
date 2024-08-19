<template>
  <div>
    <div class="container mt-3">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-3" v-if="user_roles.name != 'Admin ISP'">
              <el-select
                v-model="company_id"
                placeholder="Select"
                filterable
                size="small"
                @change="fetchData"
              >
                <el-option label="Seluruh ISP" value="" data-id=""> </el-option>
                <el-option
                  v-for="(item, index) in companies_computed"
                  :key="index"
                  :label="item.name"
                  :value="item.company_id"
                >
                </el-option>
              </el-select>
            </div>
            <div class="col-md-3">
              <el-select
                v-model="wilayah_id"
                placeholder="Select"
                filterable
                size="small"
                @change="fetchData"
              >
                <el-option label="Seluruh Wilayah" value="" data-id="">
                </el-option>
                <el-option-group
                  v-for="group in wilayahs"
                  :key="group.label"
                  :label="group.label"
                >
                  <el-option
                    v-for="(item, index) in group.options"
                    :key="index"
                    :label="item.name"
                    :value="item.wilayah_id"
                  >
                  </el-option>
                </el-option-group>
              </el-select>
            </div>
            <div class="col-md-2">
              <el-select v-model="status_list" size="small" @change="fetchData">
                <el-option label="Seluruh" value="seluruh"></el-option>
                <el-option label="Aktif" value="aktif"></el-option>
                <el-option label="Cancel" value="cancel"></el-option>
              </el-select>
            </div>
          </div>
        </div>
        <div class="col-md-12 mt-2">
          <el-input
            size="small"
            :placeholder="trans('pelanggans.placeholder.cari pelanggan')"
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
    <div class="mt-2">
      <hr />
      <el-table
        size="small"
        :data="data"
        :row-class-name="tableRowClassName"
        v-loading.body="tableIsLoading"
      >
        <el-table-column
          width="50"
          type="index"
          prop="nomor"
          :label="trans('pelanggans.table.no')"
          sortable="custom"
        ></el-table-column>
        <el-table-column width="60">
          <template slot-scope="scope">
            <status-instalasi :row="scope.row" />
          </template>
        </el-table-column>
        <el-table-column
          min-width="120"
          :label="trans('pelanggans.table.pelanggan')"
          prop="pelanggan"
        >
          <template slot-scope="scope">
            <a href="#!" @click.prevent="goToEdit(scope)">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.nama_pelanggan }}
              </text-highlight>
            </a>
            <br />
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.isp_name }}
            </text-highlight>
            <br />
            <i style="font-size:11px;" v-show="checkIsShowing(scope)">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.address }}
              </text-highlight>
            </i>
          </template>
        </el-table-column>
        <el-table-column width="110" :label="trans('pelanggans.table.site id')">
          <template slot-scope="scope">
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.site_id }}
            </text-highlight>
            <br />
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.wilayah_name }}
            </text-highlight>
          </template>
        </el-table-column>
        <el-table-column :label="trans('pelanggans.table.tlp mail')">
          <template slot-scope="scope">
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.telepon }}
            </text-highlight>
            <br />
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.email }}
            </text-highlight>
          </template>
        </el-table-column>
        <el-table-column :label="trans('pelanggans.table.paket')">
          <template slot-scope="scope">
            <text-highlight
              :queries="highlight"
              highlightClass="highlight-text"
            >
              {{ scope.row.nama_paket }}
            </text-highlight>
            <br /><i>{{ scope.row.harga_paket }}</i>
          </template>
        </el-table-column>
        <el-table-column width="75" :label="trans('pelanggans.table.actions')">
          <template slot-scope="scope">
            <el-button-group>
              <el-tooltip
                :content="trans('core.tooltip.detail')"
                placement="top"
              >
                <el-button size="mini" @click.prevent="goToEdit(scope)">
                  <i class="fa fa-edit"></i>
                </el-button>
              </el-tooltip>
              <!-- <delete-button></delete-button> -->
            </el-button-group>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-wrap" style="text-align: center; padding-top: 20px;">
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
    <div class="container mt-2 text-small">
      <i class="fas fa-circle text-info"></i> &nbsp;
      {{ trans("pelanggans.status.active") }} &nbsp;

      <i class="fas fa-circle text-success"></i> &nbsp;
      {{ trans("pelanggans.status.penentuan jadwal instalasi") }} &nbsp;

      <i class="fas fa-circle text-purple"></i> &nbsp;
      {{ trans("pelanggans.status.request perubahan harga") }} &nbsp;

      <i class="fas fa-circle text-danger"></i> &nbsp;
      {{ trans("pelanggans.status.cancel") }} &nbsp;
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Form from "form-backend-validation";
import ShortcutHelper from "../../../../../Core/Assets/js/mixins/ShortcutHelper";
import SingleFileSelector from "../../../../../Media/Assets/js/mixins/SingleFileSelector";
import TranslationHelper from "../../../../../Core/Assets/js/mixins/TranslationHelper";
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";
import StatusInstalasi from "./StatusInstalasi.vue";

export default {
  components: { StatusInstalasi },
  mixins: [
    ShortcutHelper,
    SingleFileSelector,
    TranslationHelper,
    PermissionsHelper,
  ],
  props: ["company", "wilayah"],
  data() {
    return {
      data: [],
      highlight: "",
      searchQuery: "",
      tableIsLoading: false,
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "ascending",
      },
      status_list: "aktif",
      request: null,
      company_id: "",
      wilayah_id: "",
      companies: [],
      wilayahs: [],
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
    fetchData() {
      this.tableIsLoading = true;
      if (this.request) this.cancel();
      this.queryServer();
    },
    queryServer(customProperties) {
      this.data = [];
      const cancelSource = axios.CancelToken.source();
      const properties = {
        params: {
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
          company_id: this.company_id,
          wilayah_id: this.wilayah_id,
          type: "instalasi",
          status: this.status_list,
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(route("api.pelanggan.list"), _.merge(properties, customProperties))
        .then((response) => {
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.data = response.data.data;
            this.meta = response.data.meta;
            this.links = response.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.highlight = this.searchQuery;
          }
        });
    },
    performSearch: _.debounce(function(query) {
      this.meta.current_page = 1;
      this.tableIsLoading = true;
      console.log(`searching:${query.target.value}`);
      this.queryServer({
        search: query.target.value,
      });
    }, 300),
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
    tableRowClassName({ row, rowIndex }) {
      if (rowIndex % 2 === 1) {
        return "success-row";
      }
    },
    searchFunction() {
      var self = this;
      window.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
          self.fetchData();
        }
      });
    },
    cancel() {
      this.request.cancel();
    },
    checkIsShowing(scope) {
      let str = this.highlight;
      if (scope.row.address != null) {
        if (str != "" && scope.row.address.toLowerCase().indexOf(str) != -1) {
          return true;
        }
      }
      return false;
    },
    goToEdit(scope) {
      this.$router.push({
        name: "admin.pelanggan.form.step",
        query: {
          pelanggan: scope.row.pelanggan_id,
        },
      });
    },
  },
  mounted() {
    if (this.user_roles.name == "Admin ISP") {
      this.company_id = this.user_company.company_id;
    }
    this.fetchData();
    // this.searchFunction();
    this.companies = this.company;
    this.wilayahs = this.wilayah;
  },
};
</script>
<style>
.el-table .success-row {
  background: #f5faff;
}
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
.span-new {
  font-size: 11px;
  background: #007bff;
  color: white;
  padding: 0px 8px;
  border-radius: 3px;
}
</style>
