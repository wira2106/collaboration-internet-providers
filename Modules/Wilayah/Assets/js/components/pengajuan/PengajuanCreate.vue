<template>
  <div class="map-wrapper" v-loading="loading_map">
    <gmap-map
      id="map"
      ref="map"
      :center="center"
      :zoom="zoom"
      style="width:100%;  height: 100%;"
    >
    </gmap-map>
    <transition
      name="bounce"
      enter-active-class="bounceInLeft"
      leave-active-class="bounceOutLeft"
    >
      <div v-if="showSearch" class="input-search form-material">
        <input
          type="text"
          id="autocomplete"
          ref="autocomplete"
          class="form-control"
          :placeholder="trans('pengajuans.placeholder.address')"
        />
        <a href="#" @click="toggleSearch()" class="close-search"
          ><i class="el-icon-close"></i
        ></a>
      </div>
    </transition>
    <!-- btn search -->
    <el-button
      @click="toggleSearch()"
      icon="el-icon-search"
      class="btn-search-top"
      circle
    ></el-button>
    <!-- btn list -->
    <button
      class="btn waves-effect waves-light btn-info btn-list-right"
      @click="toggleList()"
    >
      <i class="el-icon-tickets"></i>
    </button>
    <transition
      name="bounce"
      enter-active-class="bounceInRight"
      leave-active-class="bounceOutRight"
    >
      <div v-show="showList" class="list-right">
        <a
          style="font-size: 24px;"
          href="#!"
          @click="toggleList()"
          class="text-primary"
          ><i class="el-icon-close"></i
        ></a>
        <div style="height: calc( 100% - 35px );">
          <div class="row">
            <div class="col-md-12">
              <el-input
                :placeholder="trans('pengajuans.placeholder.search wilayah')"
                size="medium"
                v-model="cari_wilayah"
              ></el-input>
            </div>
            <div class="col-md-12">
              <hr />
            </div>
            <div class="col-md-12">
              <div class="list-group list-group-flush">
                <a
                  href="#"
                  class="list-group-item list-group-item-action pt-1"
                  v-for="(val, i) in resultWilayah"
                  @click="infoWilayah(val.wilayah_id)"
                  :key="i"
                  style="padding: 0px;"
                >
                  <div class="row">
                    <div class="col-7">
                      <text-highlight
                        :queries="cari_wilayah"
                        highlight-style="background:#007bff"
                      >
                        {{ val.name }}
                      </text-highlight>
                      <i class="company_list_item ">
                        <text-highlight
                          :queries="cari_wilayah"
                          highlight-style="background:#007bff"
                        >
                          {{ val.company_name }}
                        </text-highlight>
                      </i>
                      <i class="company_list_item ">
                        <text-highlight
                          :queries="cari_wilayah"
                          highlight-style="background:#007bff"
                        >
                          {{ val.kabupaten.toLowerCase() }} </text-highlight
                        >,
                        <text-highlight
                          :queries="cari_wilayah"
                          highlight-style="background:#007bff"
                        >
                          {{ val.provinsi.toLowerCase() }}
                        </text-highlight>
                      </i>
                    </div>
                    <div class="col-5" style="font-size:14px;">
                      <text-highlight
                        :queries="cari_wilayah"
                        highlight-style="background:#007bff"
                      >
                        (Site : {{ val.site }})
                      </text-highlight>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <div class="btn-configuration">
      <div style="position: relative;">
        <button
          class="btn waves-effect btn-default"
          @click="show_switch = !show_switch"
        >
          <i class="fa fa-cog"></i>
        </button>
        <div class="switch_ep" v-show="show_switch" v-loading="loading_map">
          <center>
            <label style="font-size: 10px;font-weight: bold;">{{
              trans("pengajuans.title.end point")
            }}</label>
            <el-switch v-model="show_ep"></el-switch>
            <label style="font-size: 10px;font-weight: bold;">{{
              trans("pengajuans.title.site")
            }}</label>
            <el-switch v-model="show_site"></el-switch>
          </center>
        </div>
      </div>
    </div>
    <transition
      name="wilayah_selected"
      enter-active-class="fadeUp"
      leave-active-class="fadeDown"
    >
      <div v-show="wilayah_selected" class="wilayah_selected">
        <div style="width: 100%">
          <a href="#!">
            <b>{{ selected_detail.name }}</b> -
            <span>{{ selected_detail.company_name }}</span>
          </a>
          <hr style="margin-bottom: 10px;" />
          <table style="width: 100%">
            <tr>
              <td style="width: 50%">
                <p class="mb-0">
                  <b
                    >{{ selected_detail.end_point }}
                    {{ trans("pengajuans.title.end point") }}</b
                  >
                </p>
              </td>
              <td style="width: 50%">
                <p class="mb-0">
                  <b
                    >{{ selected_detail.site }}
                    {{ trans("pengajuans.title.cover site") }}</b
                  >
                </p>
              </td>
            </tr>
          </table>

          <hr style="margin-bottom: 10px;" />
        </div>
        <div style="width: 100%">
          <div class="btn-group" style="width: 100%">
            <button
              class="btn btn-outline-info"
              @click="showDetailPengajuan(selected_detail.wilayah_id)"
              style="width: 50%;"
            >
              {{ trans("core.button.see detail") }}
            </button>
            <button
              class="btn btn-outline-danger"
              @click="closeSelectedWilayah(selected_detail.wilayah_id)"
              style="width: 50%;"
            >
              {{ trans("core.button.cancel") }}
            </button>
          </div>
        </div>
      </div>
    </transition>
    <div
      class="modal fade modal-pengajuan"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div
            class="modal-body"
            style="padding: 30px;"
            v-loading="modalPengajuan.loading"
          >
            <div class="row">
              <!-- informasi wilayah -->
              <div class="col-md-12">
                <h2 class="modal-title" style="display: inline-block;">
                  {{ modalPengajuan.data.name }}
                  <button
                    type="button"
                    class="btn btn-warning btn-sm"
                    v-if="this.modalPengajuan.data.status == 'request'"
                  >
                    {{ trans("pengajuans.button.request") }}
                  </button>
                  <button
                    type="button"
                    class="btn btn-info btn-sm"
                    v-if="this.modalPengajuan.data.status == 'confirmed'"
                  >
                    {{ trans("pengajuans.button.confirmed") }}
                  </button>
                  <button
                    type="button"
                    class="btn btn-success btn-sm"
                    v-if="this.modalPengajuan.data.status == 'accepted'"
                  >
                    {{ trans("pengajuans.button.accepted") }}
                  </button>
                </h2>
                <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div
                class="col-md-12 informasi-wilayah"
                v-show="modalPengajuan.show.informasi"
              >
                <div class="row">
                  <div class="col-md-12 pb-3">
                    {{ trans("pengajuans.form.by company") }} :
                    <a
                      href="#!"
                      @click="showDetailCompany(modalPengajuan.data.company_id)"
                      class="text-info"
                      >{{ modalPengajuan.data.company_name }}</a
                    >
                    <i style="font-size: 11px;display: block;"
                      >Bali, Denpasar, Denpasar Barat, Pemecutan</i
                    >
                  </div>
                  <div class="col-md-12 mt-3">
                    <div class="row">
                      <div class="col-md-6" style="padding-right: 7.5px;">
                        <div class="card-info-modal-pengajuan">
                          <h1>{{ modalPengajuan.data.end_point }}</h1>
                          <label>{{
                            trans("pengajuans.title.end point")
                          }}</label>
                        </div>
                      </div>
                      <div class="col-md-6" style="padding-left: 7.5px;">
                        <div class="card-info-modal-pengajuan">
                          <h1>{{ modalPengajuan.data.site }}</h1>
                          <label>{{
                            trans("pengajuans.title.cover site")
                          }}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 mt-4">
                    <div class="row">
                      <div class="col-md-12">
                        <h5>{{ trans("pengajuans.form.company join") }}</h5>
                      </div>
                      <div class="col-md-12">
                        <div class="table-company-participant">
                          <table class="table table-striped">
                            <tr
                              v-for="(item, index) in modalPengajuan.data
                                .participant"
                              :key="item.company_id"
                            >
                              <td valign="center" style="width: 30px;">
                                {{ indexMethod(index) }}
                              </td>
                              <td
                                valign="center"
                                style="width: 75px;height: 75px;"
                              >
                                <SmallImage :url="item.logo_url"> </SmallImage>
                              </td>
                              <td valign="center">
                                <a
                                  href="#!"
                                  class="text-info"
                                  @click="showDetailCompany(item.company_id)"
                                >
                                  {{ item.name }}
                                </a>
                              </td>
                            </tr>
                            <tr
                              v-if="modalPengajuan.data.participant.length == 0"
                            >
                              <td colspan="3">
                                <center>
                                  {{ trans("pengajuans.title.empty list") }}
                                </center>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div
                    class="col-md-12"
                    v-if="
                      this.modalPengajuan.data.status == '' ||
                        this.modalPengajuan.data.status == 'rejected'
                    "
                  >
                    <div class="btn-group float-right">
                      <button
                        type="button"
                        class="btn btn-info btn-sm"
                        @click="showContentModal('form')"
                      >
                        {{ trans("pengajuans.button.ajukan wilayah") }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- form pengajuan -->
              <div
                class="col-md-12 form-pengajuan"
                v-show="modalPengajuan.show.form"
              >
                <el-form
                  ref="pengajuanForm"
                  :model="modalPengajuan.form"
                  label-width="120px"
                  label-position="top"
                >
                  <div class="row">
                    <div class="col-md-12">
                      <hr />
                    </div>
                    <div class="col-md-12" v-if="user_role == 'Super Admin'">
                      <el-form-item
                        :label="trans('pengajuans.form.choose isp')"
                        prop="company_id"
                        :rules="[
                          {
                            required: true,
                            trigger: ['blur', 'change'],
                            message: trans('core.form.required'),
                          },
                        ]"
                      >
                        <el-select
                          v-model="modalPengajuan.form.company_id"
                          placeholder="Select"
                          filterable
                          size="small"
                        >
                          <el-option
                            v-for="item in companies"
                            :key="item.company_id"
                            :label="item.name"
                            :value="item.company_id"
                            :data-id="item.company_id"
                          >
                          </el-option>
                        </el-select>
                      </el-form-item>
                    </div>
                    <div class="col-md-12">
                      <p class="text-center">
                        {{ trans("pengajuans.syarat dan ketentuan") }}
                      </p>
                    </div>
                    <div class="col-md-12">
                      <div class="syarat_ketentuan">
                        Lorem, ipsum dolor sit amet consectetur adipisicing
                        elit. Ducimus, similique? Vel harum voluptatibus dicta
                        architecto, perspiciatis quasi distinctio molestias
                        adipisci, recusandae, placeat eos reprehenderit tempore
                        quisquam velit sit mollitia nostrum?<br />
                        Lorem, ipsum dolor sit amet consectetur adipisicing
                        elit. Ducimus, similique? Vel harum voluptatibus dicta
                        architecto, perspiciatis quasi distinctio molestias
                        adipisci, recusandae, placeat eos reprehenderit tempore
                        quisquam velit sit mollitia nostrum?<br />
                        Lorem, ipsum dolor sit amet consectetur adipisicing
                        elit. Ducimus, similique? Vel harum voluptatibus dicta
                        architecto, perspiciatis quasi distinctio molestias
                        adipisci, recusandae, placeat eos reprehenderit tempore
                        quisquam velit sit mollitia nostrum?<br />
                        Lorem, ipsum dolor sit amet consectetur adipisicing
                        elit. Ducimus, similique? Vel harum voluptatibus dicta
                        architecto, perspiciatis quasi distinctio molestias
                        adipisci, recusandae, placeat eos reprehenderit tempore
                        quisquam velit sit mollitia nostrum?<br />
                        Lorem, ipsum dolor sit amet consectetur adipisicing
                        elit. Ducimus, similique? Vel harum voluptatibus dicta
                        architecto, perspiciatis quasi distinctio molestias
                        adipisci, recusandae, placeat eos reprehenderit tempore
                        quisquam velit sit mollitia nostrum?<br />
                        Lorem, ipsum dolor sit amet consectetur adipisicing
                        elit. Ducimus, similique? Vel harum voluptatibus dicta
                        architecto, perspiciatis quasi distinctio molestias
                        adipisci, recusandae, placeat eos reprehenderit tempore
                        quisquam velit sit mollitia nostrum?<br />
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="float-right">
                        <button
                          type="button"
                          class="btn btn-info btn-sm"
                          @click="submitPengajuan('pengajuanForm')"
                        >
                          {{ trans("pengajuans.button.submit") }}
                        </button>
                        <div class="btn-group">
                          <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            @click="showContentModal()"
                          >
                            {{ trans("pengajuans.button.batal") }}
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </el-form>
              </div>
              <!-- informasi company -->
              <div
                class="col-md-12 informasi-company"
                v-show="modalPengajuan.show.company"
              >
                <div
                  class="row"
                  v-if="Object.keys(modalPengajuan.company_detail).length != 0"
                >
                  <div class="col-md-12">
                    <div class="text-center">
                      <img
                        :src="modalPengajuan.company_detail.company_img"
                        class="rounded"
                        alt="image"
                        style="width: 100px;"
                      />
                      <h3 class="mt-2">
                        {{ modalPengajuan.company_detail.name }}
                      </h3>
                      <p style="font-size: 14px;">
                        {{ modalPengajuan.company_detail.address }}
                      </p>
                      Rating : {{ modalPengajuan.company_detail.rating }}
                      <i class="fa fa-star text-warning"></i>
                    </div>
                  </div>
                  <!--   <div class="col-md-12">
                        <hr>
                      </div>
                      <div class="col-md-12">
                        <p>
                          <b>{{trans('pengajuans.title.company address')}}</b><br>
                          <span>{{modalPengajuan.company_detail.address}}</span>
                        </p>
                        <p>
                          <b>{{trans('pengajuans.title.pop address')}}</b><br>
                          <span>{{modalPengajuan.company_detail.pop_address}}</span>
                        </p>                          
                      </div> -->
                  <div class="col-md-12">
                    <div class="btn-group float-right">
                      <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="showContentModal()"
                      >
                        {{ trans("pengajuans.button.kembali") }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import _ from "lodash";
import Form from "form-backend-validation";
import ShortcutHelper from "../../../../../Core/Assets/js/mixins/ShortcutHelper";
import TranslationHelper from "../../../../../Core/Assets/js/mixins/TranslationHelper";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";
import UserRolesPermission from "../../../../../Core/Assets/js/mixins/UserRolesPermission";
import Pengajuan from "../../mixins/Pengajuan";
import { gmapApi } from "vue2-google-maps";

export default {
  name: "GoogleMap",
  mixins: [
    ShortcutHelper,
    TranslationHelper,
    Pengajuan,
    Toast,
    UserRolesPermission,
  ],
  data() {
    return {
      center: { lat: 45.508, lng: -73.587 },
      zoom: 12,
      inputSearch: "",
      showSearch: false,
      showList: false,
      map: null,
      loading_map: false,
      autocomplete: null,
      infoWindow: null,
      wilayah: [],
      cari_wilayah: "",
      companies: [],
      namaWilayah: [],
      form: new Form(),
      user_role: "Super Admin",
      modalPengajuan: {
        loading: false,
        data: {
          company_id: "",
          name: "",
          company_name: "",
          provinsi: "",
          kabupaten: "",
          kecamatan: "",
          kelurahan: "",
          site: "",
          end_point: "",
          participant: [],
          status: "",
        },
        company_detail: {},
        title: "Title",
        show: {
          informasi: true,
          form: false,
          company: false,
        },
        form: {
          agree_syarat: false,
          company_id: null,
          alasan: "",
        },
      },
    };
  },
  compute: {
    google: gmapApi,
  },
  watch: {
    showSearch: function() {
      let self = this;
      setTimeout(function() {
        self.autocomplete = new google.maps.places.Autocomplete(
          /** @type {HTMLInputElement} */ (document.querySelector(
            "#autocomplete"
          )),
          { types: ["geocode"] }
        );
        document.querySelector("#autocomplete").focus();

        google.maps.event.addListener(
          self.autocomplete,
          "place_changed",
          function() {
            var place = self.autocomplete.getPlace();
            self.center.lat = place.geometry.location.lat();
            self.center.lng = place.geometry.location.lng();
            self.zoom = 18;
          }
        );
      }, 500);
    },
  },
  methods: {
    setSidebarToggleWidthMap() {
      document
        .querySelector(".sidebartoggler")
        .addEventListener("click", function() {
          setTimeout(function() {
            let p = document.querySelector("#app");
            var style = p.currentStyle || window.getComputedStyle(p);
            style = style.marginLeft;
            document.querySelector(".map-wrapper").style.width =
              "calc( 100% - " + style + ")";
          }, 500);
        });
    },
    checkZoom() {
      var self = this,
        data = [],
        marker = self.markerWilayah;
      google.maps.event.addListener(self.map, "zoom_changed", function() {
        marker.forEach((value, index) => {
          if (self.map.getZoom() > 11) {
            value.setLabel({
              text: self.namaWilayah[index],
              className: "labelPosition",
            });
          } else {
            value.setLabel("");
          }
        });
      });
    },
  },
  mounted() {
    this.$refs.map.$mapPromise.then((map) => {
      this.map = map;
      this.geolocate();
      this.buildInfoWindow();
      this.fetchWilayah();
      this.setSidebarToggleWidthMap();
      this.getRolesPermission();
      this.fetchCompany();
      this.checkZoom();
      // $(".modal-pengajuan").modal();
    });
  },
};
</script>
<style type="text/css">
.btn-search-top {
  position: absolute;
  top: 10px;
  left: 190px;
}
.btn-list-right {
  position: absolute;
  bottom: 180px;
  right: 10px;
}
.map-wrapper {
  position: absolute;
  height: calc(100% - 120px);
  width: calc(100% - 240px);
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
.input-search {
  position: absolute;
  top: 0;
  width: 300px;
  z-index: 1;
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
.list-right {
  position: absolute;
  height: 100%;
  width: 300px;
  background: white;
  right: 0;
  top: 0;
  z-index: 5;
  padding: 10px 10px;
  box-sizing: border-box;
}
.company_list_item {
  font-size: 10px;
  display: block;
}
.card-info-modal-pengajuan {
  text-align: center;
  width: 100%;
  background: #f1f1f1;
  padding: 15px 25px;
}
.table-company-participant {
  max-height: 300px;
  overflow-y: auto;
}
.syarat_ketentuan {
  height: 400px;
  overflow-y: auto;
  margin-bottom: 10px;
}
.btn-configuration {
  position: absolute;
  top: 55px;
  right: 10px;
  background: white;
}
.switch_ep {
  position: absolute;
  right: 40px;
  background: white;
  padding: 5px 10px;
  top: 0px;
  width: 75px;
}
.wilayah_selected {
  position: absolute;
  bottom: 20px;
  background: white;
  width: 97%;
  max-width: 550px;
  left: 50%;
  transform: translateX(-50%);
  text-align: center;
  z-index: 1;
  box-shadow: grey 5px 9px 9px -5px;
  border: 1px solid #e4cfcf;
  padding: 10px;
}
.labelPosition {
  margin-left: 3rem;
}
</style>
