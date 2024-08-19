<template>
  <Card class="card card-outline-info">
    <div class="card-header text-white">
      {{ trans("pengajuans.list resource") }}
      <div class="card-actions">
        <a data-action="collapse">
          <i class="ti-minus text-white"></i>
        </a>
      </div>
    </div>
    <div class="card-body collapse show">
      <div class="sc-table">
        <div class="tool-bar row" style="padding-bottom: 20px">
          <div class="col-12">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <div
                    class="col-md-6"
                    v-if="user_role != '' && user_role == 'Super Admin'"
                  >
                    <el-select
                      v-if="companies.length > 0"
                      v-model="company_id"
                      placeholder="Select"
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
                  <div class="col-md-6">
                    <el-select
                      v-model="status_list"
                      @change="fetchData"
                      placeholder="Select"
                      filterable
                      size="small"
                    >
                      <el-option
                        :label="trans('pengajuans.table.select all')"
                        value=""
                        data-id=""
                      >
                      </el-option>
                      <el-option
                        label="Request"
                        value="request"
                        data-id="1"
                      ></el-option>
                      <el-option
                        label="Confirmed"
                        value="confirmed"
                        data-id="2"
                      ></el-option>
                      <el-option
                        label="Disagree"
                        value="disagree"
                        data-id="2"
                      ></el-option>
                      <el-option
                        label="Accepted"
                        value="accepted"
                        data-id="3"
                      ></el-option>
                      <el-option
                        label="Rejected"
                        value="rejected"
                        data-id="4"
                      ></el-option>
                    </el-select>
                  </div>
                </div>
              </div>
              <div class="search col-md-6">
                <div class="col-md-8 pull-right">
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
          v-loading.body="tableIsLoading"
          @sort-change="handleSortChange"
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
            prop="nama_wilayah"
            :label="trans('pengajuans.table.wilayah name')"
            sortable="custom"
          >
            <template slot-scope="scope">
              <a href="#!" @click="goToDetail(scope)">
                <text-highlight
                  :queries="highlight"
                  highlightClass="highlight-text"
                >
                  {{ scope.row.nama_wilayah }}
                </text-highlight>
              </a>
            </template>
          </el-table-column>
          <el-table-column
            prop="nama_osp"
            v-if="user_role != '' && user_role != 'Admin OSP'"
            :label="trans('pengajuans.table.osp name')"
            sortable="custom"
          >
            <template slot-scope="scope">
              <!-- <a href="#!" @click="goToDetail(scope)"> -->
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.nama_osp }}
              </text-highlight>

              <!-- </a> -->
            </template>
          </el-table-column>
          <el-table-column
            prop="nama_isp"
            :label="trans('pengajuans.table.isp name')"
            sortable="custom"
          >
            <template slot-scope="scope">
              <!-- <a href="#!" @click="goToDetail(scope)"> -->
              <text-highlight
                :queries="highlight"
                highlightClass="highlight-text"
              >
                {{ scope.row.nama_isp }}
              </text-highlight>

              <!-- </a> -->
            </template>
          </el-table-column>
          <el-table-column
            prop="created_at"
            :label="trans('core.table.created at')"
            sortable="custom"
          >
          </el-table-column>
          <el-table-column
            prop="status"
            :label="trans('pengajuans.table.status')"
            sortable="custom"
          >
            <template slot-scope="scope">
              <a
                href="#!"
                class="btn btn-sm btn-warning"
                v-if="scope.row.status == 'request'"
              >
                {{ scope.row.status }}
              </a>
              <a
                href="#!"
                class="btn btn-sm btn-info"
                v-if="scope.row.status == 'confirmed'"
              >
                {{ scope.row.status }}
              </a>
              <a
                href="#!"
                class="btn btn-sm btn-info"
                v-if="scope.row.status == 'disagree'"
              >
                {{ scope.row.status }}
              </a>
              <a
                href="#!"
                class="btn btn-sm btn-success"
                v-if="scope.row.status == 'accepted'"
              >
                {{ scope.row.status }}
              </a>
              <a
                href="#!"
                class="btn btn-sm btn-danger"
                v-if="scope.row.status == 'rejected'"
              >
                {{ scope.row.status }}
              </a>
            </template>
          </el-table-column>
          <el-table-column
            :label="trans('pengajuans.table.sisa kontrak')"
            width="130px"
          >
            <template slot-scope="scope" class="col-md-12">
              <div
                v-if="scope.row.status == 'accepted'"
                style="font-weight: bold; width: 80px; text-align: center"
              >
                <p
                  v-if="
                    scope.row.end_date_day <= 30 && scope.row.end_date_day >= 0
                  "
                  class="text-danger"
                >
                  {{ scope.row.end_date_day }}
                  {{ trans("pengajuans.table.action kontrak.hari") }}<br />
                </p>
                <p
                  v-if="
                    scope.row.end_date_day <= 90 && scope.row.end_date_day >= 31
                  "
                  class="text-warning"
                >
                  {{ scope.row.end_date_month }}
                  {{ trans("pengajuans.table.action kontrak.bulan") }} <br />
                </p>
                <p
                  v-if="
                    scope.row.end_date_day <= 180 &&
                      scope.row.end_date_day >= 91
                  "
                  class="text-success"
                >
                  {{ scope.row.end_date_month }}
                  {{ trans("pengajuans.table.action kontrak.bulan") }} <br />
                </p>
                <p v-if="scope.row.end_date_day > 180" class="text-success">
                  {{ scope.row.end_date_month }}&nbsp;{{
                    trans("pengajuans.table.action kontrak.bulan")
                  }}
                </p>
                <p v-if="scope.row.end_date_day < 0" class="text-danger">
                  {{ trans("pengajuans.table.action kontrak.expired") }} <br />
                </p>
              </div>
              <div v-else style="width: 80px; text-align: center">
                <p>-</p>
              </div>
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
  </Card>
</template>

<script>
import ShortcutHelper from "../../../../../Core/Assets/js/mixins/ShortcutHelper";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";
import TranslationHelper from "../../../../../Core/Assets/js/mixins/TranslationHelper";
import UserRolesPermission from "../../../../../Core/Assets/js/mixins/UserRolesPermission";
import PengajuanIndex from "../../mixins/PengajuanIndex";

export default {
  mixins: [
    ShortcutHelper,
    TranslationHelper,
    Toast,
    UserRolesPermission,
    PengajuanIndex,
  ],
};
</script>
<style>
.highlight-text {
  background-color: #007bff !important;
  color: white !important;
}
</style>
