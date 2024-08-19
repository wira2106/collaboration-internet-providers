<template>
  <div>
    <div class="row" v-if="dataHistory !== null">
      <div class="col-md-12">
        <div class="btn-group pull-right" style="margin: 0 15px 15px 0">
          <el-input
            prefix-icon="el-icon-search"
            size="small"
            v-model="searchQuery"
            @keyup.enter.native="fetchData"
          >
            <!-- @keyup.native="performSearch" -->
            <template slot="append">
              <el-button size="small" @click="fetchData">
                <span class="fa fa-search"></span>
              </el-button>
            </template>
          </el-input>
        </div>
        <el-table
          ref="dataHistory"
          :data="dataHistory"
          stripe
          style=""
          v-loading="loadingTable"
          @sort-change="handleSortChange"
        >
          <el-table-column prop="biaya_id" label="No" width="40" fixed>
            <template slot-scope="scope">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ meta.per_page * (meta.current_page - 1) + scope.$index + 1 }}
              </text-highlight>
            </template>
          </el-table-column>

          <el-table-column
            prop="bulan"
            :label="trans('pelanggans.table.bulan')"
            fixed
          >
            <template slot-scope="scope">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.bulan }}
              </text-highlight>
            </template>
          </el-table-column>
          <el-table-column
            prop="nama_paket"
            :label="trans('pelanggans.table.nama paket')"
            fixed
          >
            <template slot-scope="scope">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.nama_paket }}
              </text-highlight>
            </template>
          </el-table-column>

          <el-table-column
            prop="sla_paket"
            :label="trans('pelanggans.table.sla')"
            fixed
          >
            <template slot-scope="scope">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.sla_paket }}
              </text-highlight>
            </template>
          </el-table-column>

          <el-table-column
            prop="biaya_mrc"
            :label="trans('pelanggans.table.biaya')"
            fixed
          >
            <template slot-scope="scope">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                Rp. {{ scope.row.biaya_mrc }}
              </text-highlight>
            </template>
          </el-table-column>
          <el-table-column
            prop="persentase_toleransi"
            :label="trans('pelanggans.table.persen toleransi')"
            fixed
          >
            <template slot-scope="scope">
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.persentase_toleransi }}
              </text-highlight>
            </template>
          </el-table-column>
          <el-table-column
            prop="persentase_total_koneksi_mati"
            :label="trans('pelanggans.table.persen total')"
            fixed
          >
            <template slot-scope="scope">
              {{ scope.row.persentase_total_koneksi_mati }}
              <el-tooltip
                class="item"
                effect="dark"
                :content="trans('pelanggans.tooltip.penggunaan')"
                placement="top"
              >
                <a @click.prevent="modalUp(scope.row.bulan)"
                  ><i class="fa fa-exclamation-circle"></i
                ></a>
              </el-tooltip>
            </template>
          </el-table-column>
          <el-table-column
            prop="total_koneksi_mati"
            :label="trans('pelanggans.table.total koneksi mati')"
            fixed
          >
            <template slot-scope="scope">
              {{ scope.row.total_koneksi_mati }}
            </template>
          </el-table-column>
          <el-table-column
            prop="total_pengurangan_tagihan"
            :label="trans('pelanggans.table.total pengurangan tagihan')"
            fixed
          >
            <template slot-scope="scope">
              Rp.{{ scope.row.total_pengurangan_tagihan }}
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

        <!-- The Modal -->
        <div class="modal fade modal-history-sla" id="myModal">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <modalHistory
                ref="dataSLA"
                v-if="showModal"
                :pelanggan_id="pelanggan_id"
                :bulan="bulan"
              ></modalHistory>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import step_survey from '../../mixins/step_survey';
// import HasilSurveyInfo from './isp/HasilSurveyInfo.vue';
// import HasilSurveyForm from './osp/HasilSurveyForm.vue';
// import TeknisiInfo from './isp/TeknisiInfo.vue';
import axios from "axios";
import modalHistorySLA from "./modal/modalHistorySLA.vue";

export default {
  props: ["pelanggan_id"],
  components: {
    modalHistory: modalHistorySLA,
  },
  data() {
    return {
      historyLoading: false,
      dataHistory: [],
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      searchQuery: "",
      loadingTable: false,
      showModal: false,
      bulan: "",
      highlight: "",
    };
  },
  methods: {
    fetchData() {
      this.loadingTable = true;
      this.queryServer();
    },
    queryServer(customProperties) {
      const cancelSource = axios.CancelToken.source();
      const properties = {
        params: {
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
          ...customProperties,
        },
        cancelToken: cancelSource.token,
      };

      axios
        .get(
          route("api.pelanggan.history.sla", {
            pelanggan_id: this.pelanggan_id,
          }),
          properties
        )
        .then((response) => {
          if (this.searchQuery == null) {
            this.highlight = "";
          } else {
            this.highlight = this.searchQuery;
          }
          this.dataHistory = response.data.data;
          this.loadingTable = false;
        });
    },
    handleSizeChange(event) {
      // console.log(`per page :${event}`);
      this.loadingTable = true;
      this.queryServer({ per_page: event });
    },
    handleCurrentChange(event) {
      // console.log(`current page :${event}`);
      this.loadingTable = true;
      this.queryServer({ page: event });
    },
    handleSortChange(event) {
      // console.log("sorting", event);
      this.loadingTable = true;
      this.queryServer({ order_by: event.prop, order: event.order });
    },
    performSearch: _.debounce(function(query) {
      // console.log(`searching:${query.target.value}`);
      this.loadingTable = true;
      this.queryServer({ search: query.target.value });
    }, 300),
    modalUp(bulan) {
      this.bulan = bulan;
      this.showModal = true;
      $(".modal-history-sla").modal("show");
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>
<style type="text/css">
.el-form-item__label {
  margin-bottom: 0px !important;
}
.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}
.el-table .success-row {
  background: #f5faff;
}
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
