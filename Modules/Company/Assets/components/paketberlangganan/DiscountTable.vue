<template>
  <div>
     
    <el-table
      :data="discount"
      stripe
      style="width: 100%"
      v-loading="loadingTable"
      @sort-change="handleSortChange"
    >
      <el-table-column prop="biaya_id" label="No" width="75">
        <template slot-scope="scope">
          {{ meta.per_page * (meta.current_page - 1) + scope.$index + 1 }}
        </template>
      </el-table-column>
      <el-table-column
        prop="diskon"
        :label="trans('paketberlangganans.table.diskon')"
        width="100"
        sortable="custom"
      >
        <template slot-scope="scope"> {{ scope.row.diskon }} % </template>
      </el-table-column>
      <el-table-column
        prop="start_pelanggan"
        :label="trans('paketberlangganans.table.pelanggan')"
        sortable="custom"
      >
        <template slot-scope="scope">
          {{ scope.row.start_pelanggan }} - {{ scope.row.end_pelanggan }} orang
          <i class="fa fa-child"></i>
        </template>
      </el-table-column>
      <el-table-column
        prop="harga_paket"
        :label="trans('paketberlangganans.table.harga diskon')"
      >
        <template slot-scope="scope">
          <a href="#" @click.prevent="editData(scope.row.biaya_id)">
            Rp. {{ scope.row.harga_paket }}
          </a>
        </template>
      </el-table-column>

      <el-table-column
        prop="actions"
        :label="trans('paketberlangganans.table.action')"
      >
        <template slot-scope="scope">
          <el-button-group>
            <el-tooltip
              :content="trans('core.tooltip.paket.edit diskon')"
              placement="top"
            >
              <el-button
                size="mini"
                icon="el-icon-edit"
                @click="editData(scope.row.biaya_id)"
              >
              </el-button>
            </el-tooltip>
            <el-tooltip
              :content="trans('core.tooltip.paket.hapus diskon')"
              placement="top"
            >
              <delete-button
                :scope="scope"
                :rows="discount"
                v-on:onDelete="loadingTable = $event"
                v-on:onDeleteSuccess="loadingTable = !$event"
              ></delete-button>
            </el-tooltip>
          </el-button-group>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-wrap" style="text-align: center; padding-top: 20px">
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
import axios from "axios";
import Form from "form-backend-validation";
import _ from "lodash";
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";

export default {
  props: ["paket_diskon"],
  data() {
    return {
      discount: [],

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
      loadingTable: false,
      edit_data: "",
    };
  },
  watch: {
    paket_diskon: function() {
      this.fetchData();
    },
  },
  methods: {
    queryServer(customProperties) {
       
      const properties = {
        params: {
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
          route("api.company.diskonpaketberlangganan.index", {
            diskon: this.paket_diskon.paket_id,
          }),
          properties
        )
        .then((response) => {
          this.loadingTable = false;
          this.discount = response.data.data;
          this.meta = response.data.meta;
          this.link = response.data.link;
          this.order_meta.order_by = properties.order_by;
          this.order_meta.order = properties.order;
           
        })
        .catch((error) => {
           
        });
    },
    editData(id) {
      this.$emit("change", id);
    },
    fetchData() {
      if (this.paket_diskon !== "") {
        this.loadingTable = true;
        this.queryServer();
      }
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
    goToEdit(scope) {
      this.$router.push({
        name: "admin.company.paketberlangganan.edit",
        params: { paketberlangganan: scope.row.paket_id },
      });
    },
  },
  mounted() {
    this.loadingTable = true;
    this.fetchData();
  },
};
</script>
