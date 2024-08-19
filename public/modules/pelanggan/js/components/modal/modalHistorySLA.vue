<template>
  <div>
    <!-- Modal Header -->
    <div class="modal-header">
      <h4 class="modal-title">
        {{ trans("pelanggans.modal.modal sla") }}
      </h4>
      <button type="button" class="close" data-dismiss="modal">
        &times;
      </button>
    </div>

    <!-- Modal body -->
    <div class="modal-body">
      <el-table
        ref="dataSLA"
        :data="dataSLA"
        stripe
        style="width: 100%"
        v-loading="loadingTable"
        @sort-change="handleSortChange"
      >
        <el-table-column prop="biaya_id" label="No" width="40" fixed>
          <template slot-scope="scope">
            {{ meta.per_page * (meta.current_page - 1) + scope.$index + 1 }}
          </template>
        </el-table-column>
        <el-table-column
          prop="start_gangguan"
          :label="trans('pelanggans.modal.start')"
          fixed
          width="180"
        >
          <template slot-scope="scope">
            {{ scope.row.start_gangguan }}
          </template>
        </el-table-column>
        <el-table-column
          prop="end_gangguan"
          :label="trans('pelanggans.modal.end')"
          fixed
          width="180"
        >
          <template slot-scope="scope">
            {{ scope.row.end_gangguan }}
          </template>
        </el-table-column>
        <el-table-column
          prop="total_koneksi_mati"
          :label="trans('pelanggans.modal.koneksi mati')"
        >
          <template slot-scope="scope">
            {{ scope.row.total_koneksi_mati }}
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

    <!-- Modal footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">
        Close
      </button>
    </div>
  </div>
</template>

<script>
// import axios from "axios";
// import Form from "form-backend-validation";
// import _ from "lodash";
// import Toast from "../../../../Core/Assets/js/mixins/Toast";
// import InputCurrencyVue from "../../../../Core/Assets/js/components/InputCurrency.vue";
// import Vue from 'vue';
export default {
  //   mixins: [Toast],
  //   components: {
  //     InputCurrency: InputCurrencyVue,
  //   },
  props: ["pelanggan_id", "bulan"],
  data() {
    return {
      dataSLA: [],
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      loadingTable: false,
    };
  },
  watch: {
    bulan: function() {
      this.fetchData();
    },
    pelanggan_id: function() {
      this.fetchData();
    },
  },
  methods: {
    queryServer(customProperties) {
      const properties = {
        params: {
          bulan: this.bulan,
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
          ...customProperties,
        },
      };
      axios
        .get(
          route("api.pelanggan.history.ticket.support", {
            pelanggan_id: this.pelanggan_id,
          }),
          properties
        )
        .then((response) => {
          this.dataSLA = response.data.data;
          this.loadingTable = false;
        });
    },
    fetchData() {
      this.loadingTable = true;
      this.queryServer();
    },

    handleSizeChange(event) {
      this.loadingTable = true;
      this.queryServer({ per_page: event });
    },
    handleCurrentChange(event) {
      this.loadingTable = true;
      this.queryServer({ page: event });
    },
    handleSortChange(event) {
      this.loadingTable = true;
      this.queryServer({ order_by: event.prop, order: event.order });
    },
    performSearch: _.debounce(function(query) {
      this.loadingTable = true;
      this.queryServer({ search: query.target.value });
    }, 300),
  },
  mounted() {
    this.fetchData();
  },
};
</script>
<style>
@media screen and (min-width: 676px) {
  .modal-dialog {
    max-width: 600px; /* New width for default modal */
  }
}
</style>
