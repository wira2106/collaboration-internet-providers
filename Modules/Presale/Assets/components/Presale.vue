<template>
  <div class="map-wrapper" v-loading="loading_map">
    <!-- Search Component -->
    <transition
      name="bounce"
      enter-active-class="bounceInLeft"
      leave-active-class="bounceOutLeft"
    >
      <div v-show="showSearch" class="input-search form-material">
        <input
          type="text"
          id="search_map_presale"
          class="form-control"
          :placeholder="trans('pengajuans.placeholder.address')"
        />
        <a href="#" @click="toggleSearch()" class="close-search"
          ><i class="el-icon-close"></i
        ></a>
      </div>
    </transition>

    <!-- End Search Component -->
    <div
      id="map-wrapper-center"
      class="d-flex justify-content-center align-items-center"
      style="height:100%;width:100%;"
    >
      <gmap-map
        :center="center"
        :zoom="12"
        style="width:100%;  height: 100%;"
        ref="map"
        :options="{
          rotateControl: false,
          zoomControl: true,
        }"
        @dragend="onMapDragend"
      >
        <gmap-info-window
          :options="infoOptions"
          :position="infoWindowPos"
          :opened="infoWinOpen"
          @closeclick="infoWinOpen = false"
        >
        </gmap-info-window>
        <gmap-marker
          :key="index"
          v-for="(m, index) in endpoint"
          :position="m.position"
          :icon="{
            url: m.icon,
            scaledSize: new google.maps.Size(50, 60),
          }"
          :visible="m.visibility"
          @click="handleMarkerEndpointClicked(m, index)"
        ></gmap-marker>

        <gmap-marker
          :key="10002"
          :position="presale_position.position"
          :icon="{
            url: presale_position.icon,
            scaledSize: google && new google.maps.Size(50, 50),
          }"
          :visible="presale_position.show"
          @click="handleMarkerPresaleAddClicked(presale_position)"
        ></gmap-marker>
      </gmap-map>
      <div class="center-map">
        <el-popover
          placement="top"
          trigger="manual"
          v-model="infowindow.add.showed"
          v-if="infowindow.add.render"
        >
          <div>
            <img
              :src="closeImg"
              class="hover-pointer"
              style="
                  right: 0;
                  top: 0;
                  position: absolute;
                  opacity: 0.5;
                  height: 20px;
                "
              @click="infowindow.add.showed = false"
            />
          </div>
          <div style="padding-top: 5px;">
            <iw-add-endpoint
              :location="location"
              @handleClose="handleCloseMarkerAddEndpoint"
              :handleClosePopover="closePopover"
              :wilayah_id="wilayah_id"
              :company_id="company_id"
              @handleSuccess="handleSuccessAddEndpoint"
              :map="map"
              @addButtonClicked="addButtonEndpointClicked"
              v-if="process == 'endpoint'"
              @modal_closed="modalAddEndpointClosed"
            >
            </iw-add-endpoint>

            <iw-edit-location-endpoint
              v-if="process == 'edit_location_marker_endpoint'"
              :handleClosePopover="closePopover"
              :location="location"
              :end_point_id="end_point_id"
              :handleClose="handleCloseEditLocationEndpoint"
              @handleSubmit="handleSubmitEditLocationEndpoint"
              @updated="handleUpdatedMarker"
            >
            </iw-edit-location-endpoint>

            <iw-add-presale
              :location="location"
              :handleClose="closeInfowindowAddPresale"
              :handleClosePopover="closePopover"
              :wilayah_id="wilayah_id"
              :company_id="company_id"
              :map="map"
              :endpoint="endpoint"
              @addButtonClicked="handleButtonAddPresaleClicked"
              v-if="process == 'presale'"
            >
            </iw-add-presale>

            <iw-edit-location-presale
              v-if="process == 'edit_location_marker_presale'"
              :location="location"
              @handlePositionFixed="handlePositionFixedPresale"
              @handleClose="batalEditPresale"
            >
            </iw-edit-location-presale>

            <iw-edit-location-presale
              v-if="process == 'edit_location_marker_presale_change_endpoint'"
              :location="location"
              @handlePositionFixed="handlePositionFixedPresaleChangeEndpoint"
              @handleClose="batalEditPresale"
            >
            </iw-edit-location-presale>
          </div>
          <img
            slot="reference"
            :src="infowindow.add.iconAdd"
            id="imageAdd"
            alt=""
            width="50px"
            @click="infowindow.add.showed = !infowindow.add.showed"
          />
        </el-popover>
      </div>
    </div>

    <button
      type="button"
      @click="resetLinesPreview()"
      style="position: absolute;bottom: 280px;right: 10px;width: 40px;"
      class="btn btn-danger"
      v-if="linesPreview.length > 0"
    >
      <i class="fas fa-level-up-alt"></i>
    </button>
    <el-tooltip
      class="item"
      :effect="tooltip.effect"
      :content="trans('presales.tooltip.button.user location')"
      placement="left"
    >
      <button
        class="btn btn-primary"
        style="position: absolute;bottom: 185px;right: 10px;"
        @click="setCurrentPosition"
      >
        <i class="fas fa-user"></i>
      </button>
    </el-tooltip>

    <el-tooltip
      class="item"
      :effect="tooltip.effect"
      :content="trans('presales.tooltip.button.guide')"
      placement="left"
    >
      <button
        class="btn btn-secondary"
        style="position: absolute;bottom: 233px;right: 10px;"
        @click="showGuideList"
      >
        <i class="fas fa-book"></i>
      </button>
    </el-tooltip>
    <el-tooltip
      class="item"
      :effect="tooltip.effect"
      :content="trans('presales.tooltip.button.pindah wilayah')"
      placement="right"
    >
      <div
        id="btn-wilayah"
        class="hover-pointer btn-wilayah"
        @click="openOLChangeWilayah"
      >
        <i class="mdi mdi-map-marker"></i>
        {{ wilayah_name }}
      </div>
    </el-tooltip>

    <div class="map-button-add-group">
      <el-tooltip
        class="item"
        :effect="tooltip.effect"
        :content="trans('presales.tooltip.button.tambah presale')"
        placement="right"
      >
        <el-button
          type="primary"
          icon="el-icon-s-home"
          :style="{
            height: '50px',
            width: '50px',
            'margin-bottom': '10px',
            'margin-left': '-55px !important',
            transition: '0.25s',
          }"
          id="btn_add_presale"
          class="btn btn-child-add"
          circle
          @click="processOnAddPresale"
          v-if="canAddPresale"
        >
        </el-button>
      </el-tooltip>
      <el-tooltip
        class="item"
        :effect="tooltip.effect"
        :content="trans('presales.tooltip.button.tambah endpoint')"
        placement="right"
      >
        <el-button
          type="primary"
          :style="{
            height: '50px',
            width: '50px',
            'margin-bottom': '10px',
            'margin-left': '-55px !important',
            transition: '0.5s',
          }"
          class="btn btn-child-add"
          circle
          @click="processOnAddEndPoint"
          v-if="canAddEndpoint"
          id="btn_add_endpoint"
        >
          <span
            v-html="
              `<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 27.93 31.35&quot; style=&quot;width: 15px;height: 20px;&quot;><title>odp</title><g id=&quot;Layer_2&quot; data-name=&quot;Layer 2&quot;><g id=&quot;Layer_1-2&quot; data-name=&quot;Layer 1&quot;><path d=&quot;M0,31.35H8.5v-4H0v4ZM26.92,0H27a.78.78,0,0,1,.21,0l0,0h.07l0,0h.08l0,0h0l.07,0h0a.75.75,0,0,1,.15.16,1.1,1.1,0,0,1,.17.32,1.55,1.55,0,0,1,0,.21V1h0a.25.25,0,0,1,0,.08v0s0,.07,0,.11L24.74,17A16.57,16.57,0,0,1,8.5,30.35h0v-2a14.56,14.56,0,0,0,14-10.44A16.56,16.56,0,0,1,9.11,24.68H8.5v1H0v-4H8.5v1h.61A14.55,14.55,0,0,0,22.84,13,16.56,16.56,0,0,1,10.05,19H8.5v1H0V16H8.5v1h1.55A14.56,14.56,0,0,0,23.24,8.6a16.54,16.54,0,0,1-11.6,4.74H8.5v1H0v-4H8.5v1h3.14A14.58,14.58,0,0,0,23.53,5.19a16.54,16.54,0,0,1-8.71,2.48H8.5v1H0v-4H8.5v1h6.32A14.51,14.51,0,0,0,25.11,1.4L26.21.31a.52.52,0,0,1,.11-.1A1.1,1.1,0,0,1,26.64,0l.21,0h.07Z&quot; style=&quot;fill:#fff;fill-rule:evenodd&quot;></path></g></g>
              </svg>`
            "
          ></span>
        </el-button>
      </el-tooltip>
    </div>
    <el-tooltip
      class="item"
      :effect="tooltip.effect"
      :content="trans('presales.tooltip.button.tambah presale & endpoint')"
      placement="right"
    >
      <el-button
        id="v-step-0"
        class="btn btn-parent-add"
        type="primary"
        icon="el-icon-plus"
        circle
        style="height:50px;width:50px"
        @click="showAddButtonGroup"
        v-if="canAddEndpoint || canAddPresale"
      >
      </el-button>
    </el-tooltip>
    <el-tooltip
      class="item"
      :effect="tooltip.effect"
      :content="trans('presales.tooltip.button.list endpoint')"
      placement="left"
    >
      <button
        type="button"
        class="btn btn-primary btn-list-odp"
        data-toggle="modal"
        data-target="#modal-list-endpoint"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="d-flex align-items-center"
          viewBox="0 0 27.93 31.35"
        >
          <title>ODP</title>
          <g id="Layer_2" data-name="Layer 2">
            <g id="Layer_1-2" data-name="Layer 1">
              <path
                d="M0,31.35H8.5v-4H0v4ZM26.92,0H27a.78.78,0,0,1,.21,0l0,0h.07l0,0h.08l0,0h0l.07,0h0a.75.75,0,0,1,.15.16,1.1,1.1,0,0,1,.17.32,1.55,1.55,0,0,1,0,.21V1h0a.25.25,0,0,1,0,.08v0s0,.07,0,.11L24.74,17A16.57,16.57,0,0,1,8.5,30.35h0v-2a14.56,14.56,0,0,0,14-10.44A16.56,16.56,0,0,1,9.11,24.68H8.5v1H0v-4H8.5v1h.61A14.55,14.55,0,0,0,22.84,13,16.56,16.56,0,0,1,10.05,19H8.5v1H0V16H8.5v1h1.55A14.56,14.56,0,0,0,23.24,8.6a16.54,16.54,0,0,1-11.6,4.74H8.5v1H0v-4H8.5v1h3.14A14.58,14.58,0,0,0,23.53,5.19a16.54,16.54,0,0,1-8.71,2.48H8.5v1H0v-4H8.5v1h6.32A14.51,14.51,0,0,0,25.11,1.4L26.21.31a.52.52,0,0,1,.11-.1A1.1,1.1,0,0,1,26.64,0l.21,0h.07Z"
                style="fill:#fff;fill-rule:evenodd"
              ></path>
            </g>
          </g>
        </svg>
      </button>
    </el-tooltip>
    <el-tooltip
      class="item"
      :effect="tooltip.effect"
      :content="trans('presales.tooltip.button.list presales')"
      placement="left"
    >
      <button
        type="button"
        class="btn btn-warning btn-list-rumah"
        @click="handleShowListPresale"
      >
        <i class="fas fa-home"></i>
      </button>
    </el-tooltip>
    <el-tooltip
      class="item"
      :effect="tooltip.effect"
      :content="trans('presales.tooltip.button.search')"
      placement="left"
    >
      <el-button
        @click="toggleSearch()"
        icon="el-icon-search"
        class="btn-search-top"
      ></el-button>
    </el-tooltip>
    <el-tooltip
      class="item"
      :effect="tooltip.effect"
      :content="trans('presales.tooltip.button.refresh')"
      placement="left"
    >
      <button
        type="button"
        class="btn btn-secondary btn-refresh"
        @click="handleRefresh"
      >
        <i class="fas fa-sync-alt"></i>
      </button>
    </el-tooltip>
    <el-tooltip
      class="item"
      :effect="tooltip.effect"
      :content="trans('presales.tooltip.button.pengaturan')"
      placement="left"
    >
      <button-pengaturan
        :showed="showedPresale"
        :marker="presale"
        :map="map"
        :wilayah_id="wilayah_id"
        @handleSwitchEndpoint="handleSwitchEndpoint"
        @show_marker_rumah="handleShowMarkerRumah"
        @click="handleButtonPengaturan"
      >
      </button-pengaturan>
    </el-tooltip>

    <div
      class="modal fade"
      id="changeWilayah"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      aria-labelledby="changeWilayahLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changeWilayahLabel">
              {{ trans("endpoint.change region") }}
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
          <div class="modal-body">
            <div class="col-md-12">
              <el-select
                v-model="company_id"
                filterable
                placeholder="Select"
                size="small"
                v-if="companies.length > 0"
                @change="fetchRegion"
              >
                <el-option
                  v-for="item in companies"
                  :key="item.company_id"
                  :label="item.name"
                  :value="item.company_id"
                >
                </el-option>
              </el-select>
              <el-input
                :placeholder="trans('presales.form.search')"
                v-model="searchQueryRegion"
                size="small"
                class="pt-2"
              ></el-input>
              <el-divider content-position="center">{{
                trans("presales.table.region")
              }}</el-divider>
              <table-region :region="regionFilter" :change="handleChangeRegion">
              </table-region>
            </div>
          </div>
          <div class="modal-footer">
            <el-button size="small" data-dismiss="modal">
              Close
            </el-button>
          </div>
        </div>
      </div>
    </div>

    <!-- modal -->
    <edit-endpoint
      :company_id="company_id"
      :end_point_id="end_point_id"
      @handleSuccess="handleSuccessEditEndpoint"
      @handleClose="handleCloseEditEndpoint"
    >
    </edit-endpoint>
    <modal-add-presale
      :preview="srcPreviewPresale"
      :endpoint="presale_position.endpoint"
      :position="presale_position.position"
      :wilayah="activeWilayah"
      :company_id="company_id"
      :polyline="polyLineEndpointToPresale"
      :presale_id="presale_id"
      :data_biaya_kabel="data_biaya_kabel"
      :process="process"
      @handleSuccess="handleSuccessAddPresale"
      @handleUpdateSuccess="handleSuccessUpdatePresale"
      @onCancel="handleCancelPresale"
      @change_location="changeLocationPresale"
    >
    </modal-add-presale>

    <modal-list-endpoint
      :wilayah_id="wilayah_id"
      @gotoMarker="gotoMarkerEndpoint"
    >
    </modal-list-endpoint>

    <modal-list-presale
      :wilayah_id="wilayah_id"
      @gotoMarker="gotoMarkerPresale"
      @handleShowSearched="handleShowSearched"
    >
    </modal-list-presale>

    <custom-drawer-presale
      v-if="custom_drawer.show"
      :end_point_name="presale_position.endpoint.name"
      @handleChoose="showFormAddPresale"
      @handleCancel="backToSelectEndpoint"
    >
    </custom-drawer-presale>

    <div
      style="
        position: absolute;
        bottom: 20px;
        transform: translateX(-50%);
        margin: 0px auto;
        left: 50%;
        background: white;
        padding: 10px 20px;"
      v-if="list_confirmation_presale.length > 0"
      id="konfirmasi_presale"
    >
      <p style="text-align:center;font-weight:600">
        {{ trans("presales.konfirmasi.title") }}
      </p>
      <div class="w-100 d-flex justify-content-between">
        <el-button class="mx-1" type="primary" @click="konfirmasiPresale">
          {{ list_confirmation_presale.length }} {{ trans("core.selected") }}
        </el-button>

        <el-button type="info" @click="pilihSemuaPresale" class="mx-1">
          {{ trans("presales.pilih semua") }}
        </el-button>

        <el-button type="danger" @click="cancelKonfirmasiPresale" class="mx-1">
          {{ trans("core.button.cancel") }}
        </el-button>
      </div>
    </div>

    <modal-list-konfirmasi
      :list_konfirmasi="list_confirmation_presale"
      :data_biaya_kabel="data_biaya_kabel"
      :configuration="configuration"
      @handleSuccess="handleSuccessKonfirmasi"
      :endpoint_disable="endpoint_disable"
    >
    </modal-list-konfirmasi>

    <el-drawer
      :visible.sync="drawerCoverageEndpoint"
      :size="drawerCoverage.size"
      :before-close="beforeCloseDrawer"
      direction="btt"
      id="drawer_endpoint_list"
    >
      <a
        href="#!"
        v-for="(endpoint, index) in distanceToEndpoint"
        :key="index"
        class="btn btn-outline-primary my-1"
        style="width: 100%;"
        @click="chooseEndpoint(endpoint)"
      >
        {{ endpoint.name }} ({{ Math.round(endpoint.distance * 1000) }} meter)
      </a>

      <p v-if="distanceToEndpoint.length == 0" style="text-align:center">
        {{
          trans("presales.messages.not-found", {
            data: trans("endpoint.title.sidebar"),
          })
        }}
      </p>
    </el-drawer>

    <div
      style="
      position: absolute;
      bottom: 20px;
      transform: translateX(-50%);
      margin: 0px auto;
      left: 50%;
      background: white;
      padding: 10px 20px;"
      v-if="endpoint_selected_to_pay.length > 0"
      id="konfirmasi_endpoint"
    >
      <p style="text-align:center;font-weight:600">
        {{ trans("endpoint.title.pembayaran endpoint") }}
      </p>
      <div class="w-100 d-flex justify-content-between">
        <el-button class="mx-1" type="primary" @click="konfirmasiEndpoint">
          {{ endpoint_selected_to_pay.length }} {{ trans("core.selected") }}
        </el-button>

        <el-button type="info" @click="pilihSemuaEndpoint" class="mx-1">
          {{ trans("endpoint.pilih semua") }}
        </el-button>

        <el-button type="danger" @click="cancelKonfirmasiEndpoint" class="mx-1">
          {{ trans("core.button.cancel") }}
        </el-button>
      </div>
    </div>

    <!-- Tour -->
    <PresaleTour />
    <cart v-model="carts" @submit="handleEndpointPay()" :action="false" />
    <ModalListGuideVue @start="startGuide" />
  </div>
</template>

<script>
import IWAddEndpoint from "./Infowindow/AddEndpoint";
import TableRegion from "./TableRegion";
import endpoint from "../mixins/endpoint";
import presale from "../mixins/presale";
import IWEditLocationEndpoint from "./Infowindow/EditLocationEndpoint";
import EditEndpoint from "./modal/EditEndpoint.vue";
import AddPresale from "./Infowindow/AddPresale.vue";
import ModalAddPresaleVue from "./modal/ModalAddPresale.vue";
import IWEditLocationPresale from "./Infowindow/EditLocationPresale.vue";
import Toast from "../../../Core/Assets/js/mixins/Toast";
import GoogleMapHelper from "../../../Core/Assets/js/mixins/GoogleMapHelper";
import ClipboardHelper from "../../../Core/Assets/js/mixins/ClipboardHelper";
import ModalListEndpointVue from "./modal/ModalListEndpoint.vue";
import ModalListPresaleVue from "./modal/ModalListPresale.vue";
import ButtonPengaturanVue from "./button/ButtonPengaturan.vue";
import CustomDrawerPresaleVue from "./CustomDrawerPresale.vue";
import PermissionsHelper from "../../../Core/Assets/js/mixins/PermissionsHelper";
import ModalListKonfirmasiVue from "./modal/ModalListKonfirmasi.vue";
import CartVue from "../../../Core/Assets/js/components/Cart.vue";
import Constants from "../../../Core/Assets/js/mixins/Constants";
import guide from "../mixins/guide";
import ModalListGuideVue from "./modal/ModalListGuide.vue";
import PresaleTour from "./PresaleTour.vue";
import CurrencyHelper from "../../../Core/Assets/js/mixins/CurrencyHelper";

export default {
  mixins: [
    endpoint,
    presale,
    Toast,
    GoogleMapHelper,
    ClipboardHelper,
    PermissionsHelper,
    Constants,
    guide,
    CurrencyHelper,
  ],
  components: {
    "edit-endpoint": EditEndpoint,
    "iw-add-presale": AddPresale,
    "iw-add-endpoint": IWAddEndpoint,
    "iw-edit-location-presale": IWEditLocationPresale,
    "iw-edit-location-endpoint": IWEditLocationEndpoint,
    "table-region": TableRegion,
    "modal-add-presale": ModalAddPresaleVue,
    "modal-list-endpoint": ModalListEndpointVue,
    "modal-list-presale": ModalListPresaleVue,
    "button-pengaturan": ButtonPengaturanVue,
    "custom-drawer-presale": CustomDrawerPresaleVue,
    "modal-list-konfirmasi": ModalListKonfirmasiVue,
    cart: CartVue,
    ModalListGuideVue,
    PresaleTour,
  },
  data() {
    return {
      closeImg: window.location.origin + "/modules/presale/img/close.svg",
    };
  },
  mounted() {
    // $(window).on("resize", function() {
    //   $('.page-wrapper').height($('.page-wrapper').css('height'))
    // });
  },
  methods: {
    beforeCloseDrawer(params) {
      if (this.tour_type) this.prevStep();
      this.drawerCoverageEndpoint = false;
    },
    processOnAddPresale() {
      if (this.presale_id) {
        this.batalEditPresale();
        this.closeInfowindow();
      }
      this.process = "presale";
      this.showInfoWindow(this.icon.presale_add, "presale");
      this.showAddButtonGroup();
      if (this.end_point_id) this.showMarkerEndpoint();
      this.closeInfowindowGoogle();
    },
    processOnAddEndPoint() {
      if (this.presale_id) {
        this.batalEditPresale();
        this.closeInfowindow();
      }

      this.process = "endpoint";
      this.showInfoWindow(this.icon.odp, "odp");
      this.showAddButtonGroup();
      if (this.end_point_id) this.showMarkerEndpoint();
      this.closeInfowindowGoogle();
    },
    backToSelectEndpoint() {
      this.custom_drawer.show = false;
      this.closeInfowindowGoogle();
      this.polyLineEndpointToPresale.setMap(null);
      this.presale_position.show = false;
      this.drawerCoverageEndpoint = true;
      this.infowindow.add.render = true;
      if (this.tour_type) {
        setTimeout(() => {
          this.$tours[this.tour_type].start(3);
        }, 600);
      }
    },
    handleShowListPresale() {
      if (this.tour_type === "menampilkan_presale_yang_dicari") this.nextStep();
      $("#modal-list-presale").modal("show");
    },
  },
  created() {
    var vm = this;
    window.editLokasiMarker = function(el) {
      let id = $(el).data("id");
      let type = $(el).data("type");

      if (type == 1) {
        vm.hideMarkerEndpoint(id);
        vm.end_point_id = id;
        vm.process = "edit_location_marker_endpoint";
        vm.changeLocation();
      } else {
        vm.hideMarkerPresale(id);
        vm.presale_id = id;
        vm.process = "edit_location_marker_presale";
        vm.changeLocation();
      }
      if (
        vm.tour_type == "edit_location_endpoint" ||
        vm.tour_type == "edit_location_presale"
      )
        vm.nextStep();
    };
    window.editDataMarker = function(id, type) {
      if (type === 1) {
        vm.process = "edit_data_marker_endpoint";
        vm.end_point_id = id;
        $("#modal-form-odp").modal("show");
      } else {
        vm.process = "edit_data_marker_presale";
        vm.presale_id = id;
        $("#modal-add-presale").modal("show");
      }
      if (
        vm.tour_type == "edit_data_presale" ||
        vm.tour_type == "edit_data_endpoint"
      )
        vm.nextStep();
    };
    window.hapusDataMarker = function(id, type) {
      Swal.fire({
        title: vm.trans("core.modal.title"),
        text: vm.trans("core.modal.confirmation-message"),
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: vm.trans("core.button.confirm"),
        cancelButtonText: vm.trans("core.button.cancel"),
        onClose: () => {
          vm.$emit("onDeleteSuccess", true);
        },
      }).then((result) => {
        if (result.value) {
          if (type === 1) {
            axios
              .delete(route("api.presale.endpoint.delete", { endpoint: id }))
              .then((response) => {
                vm.deleteEndpoint(id);
                vm.Toast({
                  icon: "success",
                  title: response.data.message,
                });
                if (vm.tour_type) vm.finishStep();
              })
              .catch((error) => {
                vm.Toast({
                  icon: "error",
                  title: "ups, something errors",
                });
              });
          } else {
            axios
              .delete(route("api.presale.presales.delete", { presale: id }))
              .then((response) => {
                vm.deletePresale(id);
                vm.Toast({
                  icon: "success",
                  title: response.data.message,
                });
                if (vm.tour_type === "hapus_presale") vm.finishStep();
              })
              .catch((error) => {
                vm.Toast({
                  icon: "error",
                  title: "ups, something errors",
                });
              });
          }
        }
      });

      $(".swal2-container").attr("style", "z-index: 10001 !important");
    };
    window.setStatusRumah = (id) => {
      Swal.fire({
        title: "Loading..",
        html: "",
        allowOutsideClick: false,
        onOpen: () => {
          swal.showLoading();
        },
      });

      axios
        .get(route("api.presale.presale.show", { presale: id }))
        .then((response) => {
          let data = response.data.data;
          Swal.fire({
            title: "Pilih Status",
            html: `
              <button class="btn btn-${
                data.status_id == 2 ? "primary" : "default"
              }" onclick="prosesStatusRumah(2,${id},${
              data.status_id
            })" style="width:100%;">Aktif</button>
              <button class="btn btn-${
                data.status_id == 3 ? "primary" : "default"
              }" onclick="prosesStatusRumah(3,${id},${
              data.status_id
            })" style="width:100%;">Pernah Berlangganan</button>
              <button class="btn btn-${
                data.status_id == 4 ? "primary" : "default"
              }" onclick="prosesStatusRumah(4,${id},${
              data.status_id
            })" style="width:100%;">Belum Tercover</button>
              <button class="btn btn-${
                data.status_id == 5 ? "primary" : "default"
              }" onclick="prosesStatusRumah(5,${id},${
              data.status_id
            })" style="width:100%;">Berminat & Belum Tercover</button>
              <button class="btn btn-${
                data.status_id == 6 ? "primary" : "default"
              }" onclick="prosesStatusRumah(6,${id},${
              data.status_id
            })" style="width:100%;">Blacklist</button>
              <button class="btn btn-${
                data.status_id == 7 ? "primary" : "default"
              }" onclick="prosesStatusRumah(7,${id},${
              data.status_id
            })" style="width:100%;">VIP</button>
            `,
            showCloseButton: true,
          });
        })
        .catch((err) => {
          console.log(err);
        });
    };
    window.prosesStatusRumah = async (status, id, statusSebelum) => {
      var html = `
        <div class="form-group">
        <textarea name="keterangan" id="keterangan" class="form-control form-control-sm keteranganTypeArea" style="display: none;"></textarea>
        <div class="row keteranganTypePilihan" style="">
        ${
          status === 4
            ? `
          <div class="col-12">
            <label class="container_radio">
            <a>Tidak Ada Tiang</a>
            <input type="radio" name="pilihanKeterangan" value="Tidak Ada Tiang" class="pilihanKeterangan">
            <span class="checkmark_radio"></span>
            </label>
          </div>
          <div class="col-12">
            <label class="container_radio">
            <a>Melebihi Standar Penggunaan Kabel</a>
            <input type="radio" name="pilihanKeterangan" value="Melebihi Standar Penggunaan Kabel" class="pilihanKeterangan">
            <span class="checkmark_radio"></span>
            </label>
          </div>
          <div class="col-12">
            <label class="container_radio">
            <a>Tidak ada Tiang dan Melebihi Standar Penggunaan Kabel</a>
            <input type="radio" name="pilihanKeterangan" value="Tidak ada Tiang dan Melebihi Standar Penggunaan Kabel" class="pilihanKeterangan">
            <span class="checkmark_radio"></span>
            </label>
          </div>
        `
            : ""
        }
          
          <div class="col-12">
            <div class="row">
            <div class="col-12">
              <label class="container_radio">
              <a>Lainnya</a>
              <input type="radio" name="pilihanKeterangan" value="lainnya" class="pilihanKeterangan" ${
                status !== 4 ? "checked" : ""
              }>
              <span class="checkmark_radio"></span>
              </label>
            </div>  
            <div class="col-12" style="padding-left: 45px;">
              <textarea placeholder="Masukkan keterangan..." name="keteranganLainnya" class="form-control form-control-sm keteranganLainnya" style="${
                status === 4 ? "display: none" : ""
              };"></textarea>
            </div>
            </div>
          </div>
          </div>
        </div>`;

      if (status == statusSebelum) {
        status = 1;
      }
      Swal.close();

      if (status === 4 || status === 5 || status === 2) {
        setTimeout(() => {
          $(".swal2-popup .pilihanKeterangan").change(function() {
            if ($(this).val() === "lainnya") {
              $(".keteranganLainnya").show();
            } else {
              $(".keteranganLainnya").hide();
            }
          });
        }, 100);
        const { value: keterangan } = await Swal.fire({
          title: "Keterangan",
          html: html,
          preConfirm: () => {
            if (
              typeof $(".swal2-popup .pilihanKeterangan:checked").val() !==
              "undefined"
            ) {
              if (
                $(".swal2-popup .pilihanKeterangan:checked").val() === "lainnya"
              ) {
                if ($(".swal2-popup .keteranganLainnya").val() == "") {
                  Swal.showValidationMessage(`Masukkan Keterangan...`);
                }
                return $(".swal2-popup .keteranganLainnya").val();
              } else {
                return $(".swal2-popup .pilihanKeterangan:checked").val();
              }
            } else {
              Swal.showValidationMessage(`Masukkan Keterangan...`);
            }
          },
        });

        if (keterangan) {
          setStatusRumah2(id, status, keterangan);
        }
      } else {
        Swal.fire({
          title: "Loading..",
          html: "",
          allowOutsideClick: false,
          onOpen: () => {
            swal.showLoading();
          },
        });
        setStatusRumah2(id, status);
      }
    };
    window.setStatusRumah2 = (id, status, keterangan = "") => {
      axios
        .post(
          route("api.presale.presale.update.status", {
            presale: id,
            status_id: status,
            keterangan: keterangan,
          })
        )
        .then((response) => {
          Swal.fire(response.data.message, "", "success");
          vm.addMarkerPresale(response.data.data);
          new google.maps.event.trigger(this.presale[id], "click");
        });
    };
    window.copyToClipboard = vm.copyToClipboard;
    window.pilihEndpoint = vm.pilihEndpoint;
    window.pilihSemuaEndpoint = vm.pilihSemuaEndpoint;
    window.cancelKonfirmasiEndpoint = vm.cancelKonfirmasiEndpoint;
  },
};
</script>

<style>
#map-wrapper-center {
  position: absolute;
}
.el-drawer__body {
  height: 100%;
  overflow-y: scroll;
}
.center-map {
  z-index: 11;
  position: absolute;
  transform: translateY(-50%);
}
.map-button-add-group {
  z-index: 11;
  position: absolute;
  bottom: 85px;
  left: 5px;
  display: flex;
  flex-direction: column;
}
.btn-parent-add {
  z-index: 11;
  position: absolute;
  bottom: 25px;
  left: 5px;
}
.btn-add-marker {
  bottom: 25px;
  left: 15px;
  border-radius: 50px;
  width: 50px;
  height: 50px;
  font-size: 12px;
  font-size: 15px;
}
.btn-list-pengaturan {
  position: absolute;
  width: 40px;
  height: 40px;
  font-size: 15px;
  padding-left: 11px;
  background-color: white;
  box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px -1px;
}
.btn-group-pengaturan {
  position: absolute;
  right: 60px;
  top: 10px;
}
.hover-pointer:hover {
  cursor: pointer;
}
.btn-wilayah {
  background-color: white;
  position: absolute;
  top: 10px;
  left: 193px;
  padding: 8px 17px;
  min-height: 40px;
  box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px -1px;
}
.span-jumlah-rumah {
  font-size: 10px;
  font-weight: bold;
  width: 20px;
  height: 20px;
  position: absolute;
  left: 26px;
  border-radius: 50%;
  border: 1px solid;
  text-align: center;
  padding-top: 2px;
  bottom: 0px;
}
.btn-action-marker {
  border-radius: 50px;
  width: 40px;
  height: 40px;
}
.swal2-container {
  z-index: 3010 !important;
}
.container_radio {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 13px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  text-align: left;
}
.container_radio input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}
.btn-list-odp {
  position: absolute;
  top: 60px;
  right: 10px;
  width: 40px;
  height: 40px;
}

.btn-list-rumah {
  position: absolute;
  top: 110px;
  right: 10px;
  width: 40px;
  font-size: 15px;
  padding-left: 11px;
}
.btn-refresh {
  position: absolute;
  top: 160px;
  right: 10px;
  width: 40px;
  font-size: 15px;
  padding-left: 11px;
}
/* Create a custom radio button */
.checkmark_radio {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}
.btn-search-top {
  position: absolute;
  top: 10px;
  right: 110px;
  background-color: white;
  box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px -1px;
  z-index: 1;
}

.wilayah-list-odp {
  font-size: 10px;
}

/* On mouse-over, add a grey background color */
.container_radio:hover input ~ .checkmark_radio {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container_radio input:checked ~ .checkmark_radio {
  background-color: #2196f3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark_radio:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.container_radio input:checked ~ .checkmark_radio:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.container_radio .checkmark_radio:after {
  top: 9px;
  left: 9px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: white;
}
input:checked + .slider {
  background-color: #2196f3;
}
.slider.round-2 {
  border-radius: 34px;
}
.switch {
  position: relative;
  display: inline-block;
  width: 54px;
  height: 25px;
}
.slider.round-2:before {
  border-radius: 50%;
}
.slider:before {
  position: absolute;
  content: "";
  height: 19px;
  width: 20px;
  left: 4px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}
.input-search {
  position: absolute;
  top: 0;
  width: 300px;
  z-index: 2;
  height: 55px;
  width: 100%;
  background: white;
  box-shadow: 10px 3px 10px #ababab;
  padding: 5px 15px;
}

.close-search {
  position: absolute;
  right: 20px;
  top: 10px;
  font-size: 20px;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}
.map-wrapper {
  position: absolute;
  height: calc(100% - 120px);
  width: calc(100% - 240px);
}

@media (max-width: 475px) {
  .btn-wilayah {
    background-color: white;
    position: absolute;
    top: 55px;
    left: 10px;
    padding: 8px 17px;
    min-height: 40px;
    box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px -1px;
  }
}

@media (max-width: 767px) {
  .map-wrapper {
    width: 100%;
  }
}
@media (min-width: 768px) {
  .map-wrapper {
    width: calc(100% - 60px);
  }
}
/*@media (min-width: 1024px) {
    .map-wrapper{
      width: calc( 100% - 60px);
    } 
  }*/
@media (min-width: 1170px) {
  .map-wrapper {
    width: calc(100% - 240px);
  }
}
</style>
