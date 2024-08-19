<template>
  <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-4 pb-2">
            <div>
              <el-select
                size="small"
                v-model="id_company"
                v-if="user_role == 'Super Admin'"
                @change="fetchData"
                filterable
                placeholder="Select OSP"
              >
                <el-option
                  v-for="osp in option"
                  :label="osp.name"
                  :value="osp.company_id"
                  :key="osp.company_id"
                >
                  {{ osp.name }}
                </el-option>
              </el-select>
            </div>
          </div>
          <div class="col-md-8 text-center pb-2">
            <div class="btn-group pull-right" style="margin: 0 15px 15px 0">
              <el-input
                prefix-icon="el-icon-search"
                size="small"
                @keyup.enter.native="fetchData"
                v-model="searchQuery"
                :placeholder="trans('paketberlangganans.placeholder.cari paket')"
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
    <div class="row">
      <div class="col-md-12">
        <el-table
          :data="paket"
          stripe
          style="width: 100%"
          v-loading.body="tableIsLoading"
          @sort-change="handleSortChange"
        >
          <el-table-column prop="paket_id" label="No" width="75">
            <template slot-scope="scope">
              {{ meta.per_page * (meta.current_page - 1) + scope.$index + 1 }}
            </template>
          </el-table-column>
          <el-table-column
            prop="nama_paket"
            :label="trans('paketberlangganans.table.nama paket')"
            sortable="custom"
          >
            <template slot-scope="scope">
              <a href="#" @click.privent="goToEdit(scope)">
                <text-highlight
                  :queries="highlight"
                  highlightClass="highlight-text"
                >
                  {{ scope.row.nama_paket }}
                </text-highlight>
              </a>
            </template>
          </el-table-column>
          <el-table-column
            prop="biaya_otc"
            :label="trans('paketberlangganans.table.biaya otc')"
            sortable="custom"
          >
            <template slot-scope="scope">
                Rp. {{ scope.row.biaya_otc }}
            </template>
          </el-table-column>
          <el-table-column
            prop="harga_paket"
            :label="trans('paketberlangganans.table.harga paket')"
            sortable="custom"
          >
            <template slot-scope="scope">
                Rp. {{ scope.row.harga_paket }}
            </template>
          </el-table-column>
          <el-table-column
            prop="sla"
            :label="trans('paketberlangganans.table.sla')"
            sortable="custom"
          >
            <template slot-scope="scope">
                {{ scope.row.sla }}%
            </template>
          </el-table-column>

          <el-table-column
            prop="actions"
            :label="trans('paketberlangganans.table.action')"
          >
            <template slot-scope="scope">
              <el-button-group>
                <el-tooltip
                  :content="trans('core.tooltip.paket.edit')"
                  placement="top"
                >
                  <edit-button
                    :to="{
                      name: 'admin.company.paketberlangganan.edit',
                      params: { paketberlangganan: scope.row.paket_id },
                    }"
                  ></edit-button>
                </el-tooltip>
                <el-tooltip
                  :content="trans('core.tooltip.paket.diskon')"
                  placement="top"
                >
                  <el-button
                    @click="paketdiskon(scope.row)"
                    type="success"
                    size="mini"
                    icon="fa fa-percent"
                  >
                  </el-button>
                </el-tooltip>
                <el-tooltip
                  :content="trans('core.tooltip.paket.hapus')"
                  placement="top"
                >
                  <delete-button
                    :scope="scope"
                    :rows="paket"
                    v-on:onDelete="tableIsLoading = $event"
                    v-on:onDeleteSuccess="tableIsLoading = !$event"
                  ></delete-button>
                </el-tooltip>
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
    </div>

    <div
      class="modal fade discount"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              {{ trans("diskon_paket.title.list") }}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" v-loading="modalLoading">
            <div class="pull-right">
              <el-tooltip
                :content="trans('core.tooltip.paket.tambah diskon')"
                placement="top"
              >
                <el-button
                  type="primary"
                  size="small"
                  @click="create_diskon(false, true, null)"
                  v-if="discountTable"
                >
                  <i class="fa fa-edit"></i>
                  {{ trans("paketberlangganans.button.create diskon") }}
                </el-button>
              </el-tooltip>
            </div>
            <el-tooltip
              :content="trans('core.tooltip.kembali')"
              placement="top"
              v-if="discountForm"
            >
              <el-button
                size="mini"
                icon="el-icon-back"
                @click="tableToForm(true, false, null)"
              ></el-button>
            </el-tooltip>
            <DiscountTable
              :paket_diskon="paket_diskon"
              v-on:change="edit_data_form($event)"
              v-if="discountTable"
              style="margin-top: 10px"
            >
            </DiscountTable>
            <DiscountForm
              :form_diskon="form_diskon"
              :edit_data="edit_data"
              v-if="discountForm"
              style="margin-top: 25px"
              @handleSuccess="handleSuccessUpsertDiskon"
            >
            </DiscountForm>
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
import ShortcutHelper from "../../../../Core/Assets/js/mixins/ShortcutHelper";
import UserRolesPermission from "../../../../Core/Assets/js/mixins/UserRolesPermission";
import DiscountTable from "./DiscountTable";
import DiscountForm from "./DiscountForm";

export default {
  mixins: [UserRolesPermission],
  components: {
    DiscountTable: DiscountTable,
    DiscountForm: DiscountForm,
  },
  data() {
    return {
      highlight: "",
      paket: [],
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
      paket_diskon: "",
      form_diskon: "",
      edit_data: "",
      option: [],
      modalLoading: false,
      discountForm: false,
      discountTable: true,
      id_company: null,
      user_role: "",
      request: null,
      requests: [],
    };
  },
  // watch:{
  //     edit_data:function(){
  //         this.tableToForm(false,true)
  //     }
  // },
  methods: {
    edit_data_form(id) {
      this.edit_data = id;
      this.tableToForm(false, true);
    },
    create_diskon() {
      this.edit_data = null;
      this.tableToForm(false, true);
    },
    tableToForm(table, form, data) {
      this.form_diskon = this.paket_diskon;
      this.discountForm = form;
      this.discountTable = table;
    },
    handleSuccessUpsertDiskon() {
      this.edit_data = null;
      this.discountForm = false;
      this.discountTable = true;
    },
    paketdiskon(data) {
      this.paket_diskon = data;
      this.edit_data = "";
      $(".discount").modal("show");
      this.discountForm = false;
      this.discountTable = true;
    },
    queryServer(customProperties) {
      const cancelSource = axios.CancelToken.source();
      const properties = {
        params: {
          id_company: this.id_company,
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
          ...customProperties,
        },
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(route("api.company.paketberlangganan.index"), properties)
        .then((response) => {
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.id_company = response.data.id;
            this.paket = response.data.data;
            this.option = response.data.osp;
            this.meta = response.data.meta;
            this.link = response.data.link;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.highlight = this.searchQuery;
          }
        });
    },
    fetchData() {
      this.tableIsLoading = true;
      if (this.request) this.cancel();
      this.queryServer();
    },
    handleSizeChange(event) {
      this.tableIsLoading = true;
      this.queryServer({
        per_page: event,
      });
    },
    handleCurrentChange(event) {
      this.tableIsLoading = true;
      this.queryServer({
        page: event,
      });
    },
    handleSortChange(event) {
      this.tableIsLoading = true;
      this.queryServer({
        order_by: event.prop,
        order: event.order,
      });
    },
    performSearch: _.debounce(function(query) {
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
        name: "admin.company.paketberlangganan.edit",
        params: {
          paketberlangganan: scope.row.paket_id,
        },
      });
    },
  },
  mounted() {
    this.fetchData();
    this.getRolesPermission();
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
