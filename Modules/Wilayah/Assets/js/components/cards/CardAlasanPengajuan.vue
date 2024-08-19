<template>
  <div class="card card-outline-info">
    <div class="card-header">
      <h5 class="card-title text-white">
        {{ trans("pengajuans.title.alasan") }}
      </h5>
    </div>
    <div class="card-body">
      <el-table
        :data="data"
        stripe
        style="width: 100%"
        ref="pageTable"
        v-loading.body="tableIsLoading"
      >
        <el-table-column
          :index="indexMethod"
          type="index"
          prop="nomor"
          :label="trans('pengajuans.table.no')"
          sortable="custom"
        >
        </el-table-column>
        <el-table-column
          prop="readable_status"
          :label="trans('pengajuans.table.status')"
        >
          <template slot-scope="scope">
            <a
              href="#!"
              :class="['btn btn-sm', button_by_status(scope.row.status)]"
            >
              {{ scope.row.readable_status }}
            </a>
          </template>
        </el-table-column>

        <el-table-column
          prop="alasan"
          :label="trans('pengajuans.table.alasan')"
        >
        </el-table-column>

        <el-table-column
          prop="created_at"
          :label="trans('pengajuans.table.pada')"
        >
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
</template>

<script>
import axios from "axios";
export default {
  props: ["request_wilayah_id"],
  data() {
    return {
      data: [],
      tableIsLoading: true,
      meta: {
        current_page: 1,
        per_page: 10,
        total: 0,
      },
      order_meta: {
        order_by: "",
        order: "ascending",
      },
      links: {},
    };
  },
  methods: {
    indexMethod(index) {
      return (this.meta.current_page - 1) * this.meta.per_page + index + 1;
    },
    button_by_status(status) {
      if (status == "request") return "btn-warning";

      if (status == "confirmed") return "btn-info";
      if (status == "accepted") return "btn-success";
      if (status == "disagree" || status == "rejected") return "btn-danger";

      return "btn-second";
    },
    fetchData() {
      this.tableIsLoading = true;
      const properties = {
        params: {
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
        },
      };
      axios
        .get(
          route("api.wilayah.pengajuan.alasan", {
            request_wilayah: this.request_wilayah_id,
          }),
          properties
        )
        .then((response) => {
          this.tableIsLoading = false;
          this.data = response.data.data;
          this.links = response.data.links;
          this.meta = response.data.meta;
          this.order_meta.order_by = properties.order_by;
          this.order_meta.order = properties.order;
        })
        .catch((err) => {
          this.tableIsLoading = false;
        });
    },
    handleSizeChange(event) {
      console.log(`per page :${event}`);
      this.tableIsLoading = true;
      this.fetchData({
        per_page: event,
      });
    },
    handleCurrentChange(event) {
      console.log(`current page :${event}`);
      this.tableIsLoading = true;
      this.fetchData({
        page: event,
      });
    },
  },

  mounted() {
    if (this.request_wilayah_id) {
      this.fetchData();
    }
  },
  watch: {
    request_wilayah_id: function() {
      this.fetchData();
    },
  },
};
</script>

<style></style>
