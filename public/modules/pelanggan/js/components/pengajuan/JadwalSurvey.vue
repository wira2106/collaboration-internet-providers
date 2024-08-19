<template>
  <div>
<<<<<<< HEAD
  <el-form ref="form"
    :model="form"
    label-width="120px"
    label-position="top"
    v-loading.body="loadingPengajuan"
    @keydown="form.errors.clear($event.target.name);"
    :rules="rules">
    <Card class="card card-outline-info">
      <div class="card-header" style="border-bottom: 0px;">
        <div class="card-actions">
          <a v-show="history.length >1" href="#!" class="text-info" @click="seeHistoryPengajuanSurvey"><u>{{trans('pengajuanjadwal.see history')}}</u></a>
          <a data-action="collapse"><i class="ti-minus text-white"></i></a>          
        </div>
        <h5 class="text-white">
          {{trans('pengajuanjadwal.survey.title')}} &nbsp;&nbsp;
          <b class="text-warning" v-if="form.status == 'pending'"><i>Pending</i></b>
          <b class="text-success" v-if="form.status == 'accept'"><i>Accept</i></b>
          <b class="text-info" v-if="form.status == 'reschedule'"><i>Rechedule</i></b>
        </h5>
      </div>
      <div class="card-body collapse show">
        <div class="row">
          <!-- input keterangan dan rekomendasi -->
          <div class="col-md-12" v-if="form.status == '' || (user_company == 'osp' && form.status == '')">
            <div class="row">
              <div class="col-md-12">
                 <el-form-item  :label="trans('pengajuanjadwal.keterangan')" prop="keterangan">
                    <el-input type="textarea" placeholder="Keterangan.." v-model="form.keterangan" size='small'>
                    </el-input>
                  </el-form-item>   
              </div>
              <div class="col-md-12" v-show="user_company.type == 'isp'">
                <el-checkbox v-model="form.rekomendasiExist">{{trans('pengajuanjadwal.is rekomendasi')}}</el-checkbox>
              </div>
              <div class="col-md-12" v-show="form.rekomendasiExist">
                <div class="row">
                  <div class="col-md-6">
                    <el-form-item  :label="trans('pengajuanjadwal.tgl rekomendasi')" prop="tgl_rekomendasi">
                       <el-date-picker
                        v-model="form.tgl_rekomendasi"
                        type="date"
                        value-format="yyyy-MM-dd"
                        placeholder="Pick a day"
                        :picker-options="pickerOptions"
                        size='small'>
                      </el-date-picker>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item  :label="trans('pengajuanjadwal.jam rekomendasi')" prop="jam_rekomendasi">
                      <el-time-select
                          v-model="form.jam_rekomendasi"
                          placeholder="Select time"
                          :picker-options="{
                            start: selectableRangePicker[0],
                            step: '00:30',
                            end: selectableRangePicker[1]
                          }"
                          size='small'>
                      </el-time-select>
                    </el-form-item>
                  </div>                  
                </div>
              </div>
            </div>
          </div>
          <!-- informasi rekomendasi dan keterangan -->
         <div class="col-md-12" v-else>
            <table class="table" style="width: 100%;">
              <tr>
                <th style="width: 250px;">{{trans('pengajuanjadwal.tgl rekomendasi')}}</th>
                <td style="width: 10px;">:</td>
                <td v-if="form.tgl_rekomendasi != null">
                  {{form.tgl_rekomendasi_c}} {{form.jam_rekomendasi_c}}
                  <button type="button" class="btn btn-sm btn-info" 
                    v-if="user_company.type == 'osp' && form.status == 'pending' && form.list.length == 0"
                    @click="pilihTanggalRekomendasi(form.pengajuan_id)">
                        {{trans('pengajuanjadwal.pilih tanggal')}}
                  </button>
                </td>
                <td v-else> - </td>
              </tr>
              <tr v-if="form.keterangan != null && form.keterangan != ''">
                <th style="width: 250px;">{{trans('pengajuanjadwal.keterangan')}}</th>
                <td style="width: 10px;">:</td>
                <td>{{form.keterangan}}</td>
              </tr>
            </table>
          </div>
          <!-- pilihan tanggal survey -->
          <div class="col-md-12" v-if="form.status != '' || user_company.type == 'osp'">
            <div class="row">
              <div class="col-md-12">
                <hr>
                <h5>{{trans('pengajuanjadwal.survey.pilihan tgl')}}</h5>
              </div>
              <div class="col-md-12">
                <center v-if="form.list.length == 0">{{trans('pengajuanjadwal.survey.jadwal belum')}}</center>
                <!-- tabel pilihan tanggal survey -->
                <table class="table table-bordered table-pengajuan-survey">
                  <thead :hidden="form.list.length == 0">
                    <tr>
                      <td>{{trans('pengajuanjadwal.table survey.no')}}</td>
                      <td>{{trans('pengajuanjadwal.table survey.tgl')}}</td>
                      <td>{{trans('pengajuanjadwal.table survey.waktu')}}</td>
                      <div v-if="form.status != 'accept'">
                        <td>{{trans('pengajuanjadwal.table survey.action')}}</td>
                      </div>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(val,key) in form.list" :class="(val.status == 'active'?'active':'')" :key="key">
                      <td style="width: 30px;">{{key+1}}</td>
                      <td>
                        <!-- select tanggal survey -->
                        <div v-if="user_company.type == 'osp' && form.status != 'accept'">
                           <el-date-picker
                              v-model="val.tgl_survey"
                              type="date"
                              value-format="yyyy-MM-dd"
                              placeholder="Pick a day"
                              :picker-options="pickerOptions"
                              size='small'>
                            </el-date-picker>
                        </div>
                        <!-- informasi tanggal survey -->
                        <div v-else>
                          {{val.tgl_survey}}
                        </div>
                      </td>
                      <td>
                        <!-- select jam survey -->
                        <div v-if="user_company.type == 'osp' && form.status != 'accept'">
                          <el-time-select
                            v-model="val.jam_survey"
                            placeholder="Select time"
                            :picker-options="{
                              start: selectableRangePicker[0],
                              step: '00:30',
                              end: selectableRangePicker[1]
                            }"
                            size='small'>
                          </el-time-select>
                        </div>
                        <!-- informasi jam survey -->
                        <div v-else>
                          {{val.jam_survey}}                          
                        </div>
                      </td>
                      <td v-if="user_company.type == 'isp' && form.status == 'pending'" align="center">
                        <button v-if="val.status == 'not_active'" type="button" class="btn btn-sm btn-info" @click="pickTanggalSurvey(key,'active')">
                          {{trans('pengajuanjadwal.button.pilih')}}
                        </button>
                        <button v-else type="button" class="btn btn-sm btn-danger" @click="pickTanggalSurvey(key,'not_active')">
                          {{trans('pengajuanjadwal.button.batal pilih')}}
                        </button>
                      </td>
                      <td v-if="user_company.type == 'osp' && form.status != 'accept'">
                        <a href="#!" @click="removeTanggal(key)">
                          <i class="fa fa-trash text-danger"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <br>
                <!-- button tambah jadwal -->
                <div class="pull-right" v-if="user_company.type == 'osp' && (form.status == 'pending' || form.status == '')">
                  <button type="button" class="btn btn-sm btn-info" @click="addJadwal()">
                      <i class="fa fa-plus"></i> Tambah Jadwal
                  </button>
                </div>

              </div>   
=======
    <InformasiJadwalSurvey
      v-if="response != null && form.status == 'accept'"
      :response="response"
      :pelanggan_id="pelanggan_id_send"
      @rechedule="recheduleJadwal()"
    >
    </InformasiJadwalSurvey>
>>>>>>> revisi-instalasi-adi-5Mei

    <RecheduleJadwalSurvey
      v-if="response != null && isReschedule"
      :response="response"
      :pelanggan_id="pelanggan_id_send"
      @unrechedule="recheduleJadwal(false)"
    >
    </RecheduleJadwalSurvey>

    <PengajuanJadwalSurvey
      v-if="response != null && form.status == 'pending' && !isReschedule"
      :response="response"
      :pelanggan_id="pelanggan_id_send"
      @rechedule="recheduleJadwal()"
    >
    </PengajuanJadwalSurvey>

<<<<<<< HEAD
      <!-- card footer and button save -->
      <div class="card-footer" v-if="(form.list.length > 0 && form.status != 'accept') || (user_company.type == 'isp' && form.status == '')">
        <div>
          <el-form-item>
            <el-button type="primary" @click="onSubmit('form')" :loading="loadingPengajuan">
                {{ trans('core.save') }}
            </el-button>
          </el-form-item>
        </div>
      </div>
    </Card>
  </el-form>
    <div class="modal fade history-pengajuan-survey" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
=======
    <!-- MODAL HISTORY -->
    <div
      class="modal fade history-pengajuan-survey"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
>>>>>>> revisi-instalasi-adi-5Mei
      <div class="modal-dialog modal-lg">
        <div class="modal-content" v-loading="loadingModal">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ trans("pengajuanjadwal.survey.history pengajuan") }}
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
            <div class="row">
              <div class="col-md-12" v-for="(val, key) in history" :key="key">
                <template v-if="key != history.length - 1">
                  <table style="width: 100%">
                    <tr>
                      <td style="width: 200px">
                        {{ trans("pengajuanjadwal.tgl request") }}
                      </td>
                      <td style="width: 5px">:</td>
                      <td>{{ val.tgl_rekomendasi_c }}</td>
                    </tr>
                    <tr>
                      <td>{{ trans("pengajuanjadwal.keterangan") }}</td>
                      <td>:</td>
                      <td>{{ val.keterangan }}</td>
                    </tr>
                  </table>

                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>{{ trans("pengajuanjadwal.table history.no") }}</th>
                        <th>
                          {{ trans("pengajuanjadwal.table history.tgl") }}
                        </th>
                        <th>
                          {{ trans("pengajuanjadwal.table history.jam") }}
                        </th>
                        <th>
                          {{ trans("pengajuanjadwal.table history.status") }}
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(v, k) in val.list" :key="k">
                        <td>{{ k + 1 }}</td>
                        <td>{{ v.tgl_survey }}</td>
                        <td>{{ v.jam_survey }}</td>
                        <td>
                          <b class="text-success" v-if="v.status == 'active'">{{
                            trans("pengajuanjadwal.dipilih")
                          }}</b>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <hr />
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
import axios from "axios";
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";
import RecheduleJadwalVue from "./partials/RecheduleJadwalSurvey.vue";
import PengajuanJadwalVue from "./partials/PengajuanJadwalSurvey.vue";
import InformasiJadwalSurveyVue from "./partials/InformasiJadwalSurvey.vue";

export default {
  mixins: [Toast, PermissionsHelper],
  props: {
    pelanggan_id: {
      // type: String,
      default: null,
    },
    buttonRechedule: {
      type: Boolean,
      default: true,
    },
  },
  components: {
    RecheduleJadwalSurvey: RecheduleJadwalVue,
    PengajuanJadwalSurvey: PengajuanJadwalVue,
    InformasiJadwalSurvey: InformasiJadwalSurveyVue,
  },
  data() {
    return {
      pelanggan_id_send: null,
      form: {
        rekomendasiExist: false,
        jam_rekomendasi: "08:00",
        tgl_rekomendasi: "",
        keterangan: "",
        status: "",
        list: [],
      },
      history: [],
      response: null,
      loadingModal: false,
      isReschedule: false,
    };
  },
  methods: {
    fetchPengajuanSurvey() {
      if (parseInt(this.pelanggan_id) != 0) {
        axios
          .get(
            route("api.pelanggan.form.jadwal.survey", {
              pelanggan_id: this.pelanggan_id,
            })
          )
          .then((response) => {
            this.response = response;
            this.response.buttonRechedule = this.buttonRechedule;
            let data = response.data.data;
            this.history = data;
            if (data.length != 0) {
              this.form = data[data.length - 1];
            }
          });
      }
    },
    recheduleJadwal(reschedule = true) {
      this.isReschedule = reschedule;
    },
  },
  mounted() {
    this.pelanggan_id_send = this.pelanggan_id;
    this.fetchPengajuanSurvey();
  },
};
</script>
