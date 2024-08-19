<template>
  <div class="sc-table">
   
    <div class="tool-bar row" style="padding-bottom: 20px;">
      <div class="col-12">
        <div class="row d-flex justify-content-between">
          <div class="col-md-4">
            <el-select
              v-if="companies.length > 0"
              v-model="company_id"
              placeholder="Select"
              filterable
              size="small"
              @change="fetchData"
            >
              <el-option
                :label="trans('wilayahs.table.all osp')"
                value=""
                data-id=""
              >
              </el-option>
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
          <div class="search col-md-4 col-12 pull-right">
            <el-input
              prefix-icon="el-icon-search"
              size="small"
              @keyup.enter="fetchData"
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
        :label="trans('wilayahs.table.no')"
        sortable="custom"
      >
      </el-table-column>
      <el-table-column
        prop="name"
        :label="trans('wilayahs.table.name')"
        sortable="custom"
      >
        <template slot-scope="scope">
          <a @click.prevent="goToEdit(scope)" href="#">
            {{ scope.row.name }}
          </a>
        </template>
      </el-table-column>
      <el-table-column
        prop="nama_company"
        :label="trans('wilayahs.table.nama company')"
        sortable="custom"
      >
        <template slot-scope="scope">
          <a @click.prevent="goToEdit(scope)" href="#">
            {{ scope.row.nama_company }}
          </a>
        </template>
      </el-table-column>
      <el-table-column
        prop="created_at"
        :label="trans('core.table.created at')"
        sortable="custom"
      >
      </el-table-column>
      <el-table-column :label="trans('pengajuans.table.open.status')" width="80">
        <template slot-scope="scope">
          <i
            class="text-success"
            style="font-weight:bold"
            v-if="scope.row.open == 1"
          >
            {{ trans("pengajuans.table.open.open") }}
          </i>
          <i
            class="text-danger"
            style="font-weight:bold"
            v-if="scope.row.open == 0"
          >
            {{ trans("pengajuans.table.open.close") }}
          </i>
        </template>
      </el-table-column>
      <el-table-column
        :label="trans('pengajuans.table.status_presale')"
        width="80"
      >
        <template slot-scope="scope">
          <i
            :class="[classStatusPresale(scope.row.status_presale)]"
            style="font-weight:bold"
          >
            {{ scope.row.readable_status_presale }}
          </i>
        </template>
      </el-table-column>
      <el-table-column prop="actions" :label="trans('core.table.actions')">
        <template slot-scope="scope">
          <el-button-group>
            <el-button
              size="mini"
              @click.prevent="goToPresale(scope)"
              icon="mdi mdi-map-marker"
            >
            </el-button>
            <delete-button
              :scope="scope"
              :rows="data"
              v-if="scope.row.urls.delete_url != null"
              v-on:onDelete="tableIsLoading = $event"
              v-on:onDeleteSuccess="tableIsLoading = !$event"
            ></delete-button>
            <el-button
              type="warning"
              v-if="scope.row.status_presale != 'request'"
              size="mini"
              @click.prevent="goToRequestPresale(scope)"
              icon="mdi mdi-google-maps"
            >
            </el-button>
          </el-button-group>
        </template>
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
import ShortcutHelper from "../../../../../../Core/Assets/js/mixins/ShortcutHelper";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";
import TranslationHelper from "../../../../../../Core/Assets/js/mixins/TranslationHelper";
import WilayahTable from "../../../mixins/WilayahTable";
export default {
  mixins: [ShortcutHelper, TranslationHelper, WilayahTable, Toast],
};
</script>
