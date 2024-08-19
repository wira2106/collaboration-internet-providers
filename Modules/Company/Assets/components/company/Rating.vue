<template>
  <div class="col-12">
    <div class="tool-bar row">
      <div class="actions col-8" style="font-size:14px;font-weight:400">
        {{ trans("rating.title.total") }}
        <el-rate
          :value="total_rating / 10"
          disabled
          text-color="#ff9900"
          :max="1"
          show-score
          :score-template="total_rating ? total_rating.toString() : '0'"
          style="display: inline-block;"
        >
        </el-rate>
      </div>

      <div class="search col-4">
        <el-input
          prefix-icon="el-icon-search"
          @keyup.enter.native="fetchRatingCompany"
          size="small"
          v-model="searchQuery"
        >
          <template slot="append">
            <el-button size="small" @click="fetchRatingCompany">
              <span class="fa fa-search"></span>
            </el-button>
          </template>
        </el-input>
      </div>
    </div>
    <el-table
      v-loading="tableIsLoading"
      :data="rating"
      stripe
      style="width: 100%"
      @sort-change="handleSortChange"
    >
      <el-table-column
        prop="full_name"
        :label="trans('companies.table.rating.rater_name')"
        width="180"
        sortable="custom"
      >
        <template slot-scope="scope">
          <text-highlight :queries="highlight" highlightClass="highlight-text">
            {{ scope.row.full_name }}
          </text-highlight>
        </template>
      </el-table-column>
      <el-table-column
        prop="name"
        :label="trans('companies.table.rating.company_name')"
        width="180"
        sortable="custom"
      >
        <template slot-scope="scope">
          <text-highlight :queries="highlight" highlightClass="highlight-text">
            {{ scope.row.name }}
          </text-highlight>
        </template>
      </el-table-column>
      <el-table-column
        prop="rating"
        width="180"
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
        prop="description"
        :label="trans('companies.table.rating.description')"
        sortable="custom"
      >
      </el-table-column>
      <el-table-column
        prop="created_at"
        :label="trans('companies.table.rating.created_at')"
        sortable="custom"
      >
      </el-table-column>
    </el-table>
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
  </div>
</template>

<script>
import _ from "lodash";

export default {
  props: ["company_id"],
  data() {
    return {
      highlight: "",
      rating: [],
      total_rating: 0,
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
    };
  },
  methods: {
    queryServer(customProperties) {
      const cancelSource = axios.CancelToken.source();
      const properties = {
        page: this.meta.current_page,
        per_page: this.meta.per_page,
        order_by: this.order_meta.order_by,
        order: this.order_meta.order,
        search: this.searchQuery,
        company: this.company_id,
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .post(
          route("api.company.company.rating", { company: this.company_id }),
          _.merge(properties, customProperties)
        )
        .then((response) => {
          if (typeof response !== "undefined") {
            this.rating = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.total_rating = response.data.total_rating;
            this.highlight = this.searchQuery;
          }

          this.tableIsLoading = false;
        });
    },
    fetchRatingCompany() {
      this.tableIsLoading = true;
      if (this.request) this.cancel();
      this.queryServer();
    },
    handleSizeChange(event) {
      console.log(`per page :${event}`);
      this.tableIsLoading = true;
      this.queryServer({ per_page: event });
    },
    handleCurrentChange(event) {
      console.log(`current page :${event}`);
      this.tableIsLoading = true;
      this.queryServer({ page: event });
    },
    handleSortChange(event) {
      console.log("sorting", event);
      this.tableIsLoading = true;
      this.queryServer({ order_by: event.prop, order: event.order });
    },
    performSearch: _.debounce(function(query) {
      console.log(`searching:${query.target.value}`);
      this.tableIsLoading = true;
      this.queryServer({ search: query.target.value });
    }, 300),
    searchFunction() {
      var self = this;
      window.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
          self.fetchRatingCompany();
        }
      });
    },
    cancel() {
      this.request.cancel();
    },
  },
  mounted() {
    this.fetchRatingCompany();
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
