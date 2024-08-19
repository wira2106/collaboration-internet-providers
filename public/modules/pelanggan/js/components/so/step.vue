<template>
  <div>
<<<<<<< HEAD
   <el-form ref="form"
      :model="form"
      label-width="120px"
      label-position="top"
      v-loading.body="loading"
      @keydown="form.errors.clear($event.target.name);"
      :rules="rules">
  	<Card class="card">
      <div class="card-header" style="border-bottom: 0px;">
        <div class="card-actions">
          <a data-action="collapse"><i class="ti-minus"></i></a>          
        </div>
        <h5>{{trans('salesorders.title.salesorders')}}</h5>
      </div>
      <div class="card-body collapse show">
         <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <input type="hidden" name="presale_id" :value="form.presale_id">
                  <input type="hidden" name="pelanggan_id" :value="form.pelanggan_id">
                </div>
                <div class="col-md-6">
                  <el-form-item  :label="trans('salesorders.form.site_id')" prop="site_id"
                      :rules="rules.required_field">
                      <el-input type="text" :readonly="true" v-model="form.site_id" size='small'></el-input>
                  </el-form-item>
                </div>
                <div class="col-md-6">
                  <el-form-item  :label="trans('salesorders.form.nama_pelanggan')" prop="nama_pelanggan"
                      :rules="rules.required_field">
                      <el-input type="text" :placeholder="trans('salesorders.placeholder.nama_pelanggan')" v-model="form.nama_pelanggan" size='small'></el-input>
                  </el-form-item>
                </div>
                <div class="col-md-6">
                  <el-form-item  :label="trans('salesorders.form.telepon')" prop="telepon"
                      :rules="rules.input_phone">
                      <el-input type="text" :placeholder="trans('salesorders.placeholder.telepon')" v-model="form.telepon" size='small'></el-input>
                  </el-form-item>
                </div>
                <div class="col-md-6">
                  <el-form-item  :label="trans('salesorders.form.email')" prop="email"
                      :rules="rules.input_email">
                      <el-input type="email" :placeholder="trans('salesorders.placeholder.email')" v-model="form.email" size='small'></el-input>
                  </el-form-item>
                </div>
                <div class="col-md-6">
                  <el-form-item  :label="trans('salesorders.form.jenis_id')" prop="jenis_identitas"
                    :rules="rules.required_field">
                    <el-select v-model="form.jenis_identitas" placeholder="Select" filterable size="small">
=======
    <div v-show="!finishSO">
      <el-form
        ref="form"
        :model="form"
        label-width="120px"
        label-position="top"
        v-loading.body="loading"
        @keydown="form.errors.clear($event.target.name)"
        :rules="rules"
      >
        <div class="card">
          <div class="card-header" style="border-bottom: 0px;">
            <div class="card-actions">
              <a data-action="collapse"><i class="ti-minus"></i></a>
            </div>
            <h5>{{ trans("salesorders.title.salesorders") }}</h5>
          </div>
          <div class="card-body collapse show">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <input
                      type="hidden"
                      name="presale_id"
                      :value="form.presale_id"
                    />
                    <input
                      type="hidden"
                      name="pelanggan_id"
                      :value="form.pelanggan_id"
                    />
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.site_id')"
                      prop="site_id"
                      :rules="rules.required_field"
                    >
                      <el-input
                        type="text"
                        :readonly="true"
                        v-model="form.site_id"
                        size="small"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.nama_pelanggan')"
                      prop="nama_pelanggan"
                      :rules="rules.required_field"
                    >
                      <el-input
                        type="text"
                        :placeholder="
                          trans('salesorders.placeholder.nama_pelanggan')
                        "
                        v-model="form.nama_pelanggan"
                        size="small"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.telepon')"
                      prop="telepon"
                      :rules="rules.telepon"
                    >
                      <el-input
                        type="text"
                        :placeholder="trans('salesorders.placeholder.telepon')"
                        v-model="form.telepon"
                        size="small"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.email')"
                      prop="email"
                      :rules="rules.input_email"
                    >
                      <el-input
                        type="email"
                        :placeholder="trans('salesorders.placeholder.email')"
                        v-model="form.email"
                        size="small"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.jenis_id')"
                      prop="jenis_identitas"
                      :rules="rules.required_field"
                    >
                      <el-select
                        v-model="form.jenis_identitas"
                        placeholder="Select"
                        filterable
                        size="small"
                      >
>>>>>>> revisi-instalasi-adi-5Mei
                        <el-option label="KTP" value="KTP"></el-option>
                        <el-option label="SIM" value="SIM"></el-option>
                        <el-option label="Lainnya" value="Lainnya"></el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.nomor_id')"
                      prop="nomor_identitas"
                      :rules="rules.nomor_id"
                    >
                      <el-input
                        type="number"
                        :placeholder="trans('salesorders.placeholder.nomor_id')"
                        v-model="form.nomor_identitas"
                        size="small"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.foto_id')"
                      prop="foto_identitas"
                      :rules="
                        photo.foto_identitas_old == null
                          ? rules.required_field
                          : null
                      "
                    >
                      <div class="input-group">
                        <input
                          type="file"
                          accept="image/*"
                          class="form-control"
                          @change="
                            setInputFoto(
                              $event,
                              'photo.foto_identitas_pw',
                              'form.foto_identitas'
                            )
                          "
                        />
                        <div
                          class="input-group-append"
                          v-show="photo.foto_identitas_pw != null"
                        >
                          <a
                            :href="photo.foto_identitas_pw"
                            class="btn btn-primary btn-sm fancybox"
                          >
                            <i class="fa fa-image"></i>
                          </a>
                        </div>
                      </div>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.status_pemilik')"
                      prop="status_kepemilikan"
                      :rules="rules.required_field"
                    >
                      <el-select
                        v-model="form.status_kepemilikan"
                        placeholder="Select"
                        filterable
                        size="small"
                      >
                        <el-option label="Pemilik" value="pemilik"></el-option>
                        <el-option label="Penyewa" value="penyewa"></el-option>
                        <el-option label="Lainnya" value="lainnya"></el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <div
                      id="mapSalesOrder"
                      style="width:100%;height:300px"
                    ></div>
                    <br />
                  </div>
                  <div class="col-md-12">
                    <el-form-item
                      :label="trans('salesorders.form.alamat')"
                      prop="address"
                      :rules="rules.required_field"
                    >
                      <el-input
                        type="textarea"
                        :placeholder="trans('salesorders.placeholder.alamat')"
                        v-model="form.address"
                        size="small"
                        :readonly="true"
                      >
                      </el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item
                      :label="trans('salesorders.form.paket_berlangganan')"
                      prop="paket_id"
                      :rules="rules.required_paket"
                    >
                      <el-select
                        v-model="form.paket_id"
                        placeholder="Select"
                        filterable
                        size="small"
                      >
                        <el-option
                          v-for="val in paket"
                          :value="val.paket_id"
                          :label="
                            val.nama_paket + ' (' + val.harga_paket_rp + ')'
                          "
                          :key="val.paket_id"
                        >
                        </el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.biaya_mrc')"
                      prop="biaya_mrc"
                      :rules="rules.amount"
                    >
                      <InputCurrency
                        v-model="form.biaya_mrc"
                        :readonly="true"
                      ></InputCurrency>
                      <!-- <el-input type="number" :placeholder="trans('salesorders.placeholder.biaya_mrc')" v-model="form.biaya_mrc" size='small' :readonly="readonlyMrcOtc"></el-input> -->
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item
                      :label="trans('salesorders.form.biaya_otc')"
                      prop="biaya_otc"
                      :rules="rules.amount"
                    >
                      <InputCurrency
                        v-model="form.biaya_otc"
                        :readonly="true"
                      ></InputCurrency>
                      <!-- <el-input type="number" :placeholder="trans('salesorders.placeholder.biaya_otc')" v-model="form.biaya_otc" size='small' :readonly="readonlyMrcOtc"></el-input> -->
                    </el-form-item>
                  </div>
                  <div class="col-md-12" v-show="pelanggan_id == null">
                    <div class="row">
                      <div class="col-md-12">
                        <hr />
                      </div>
                      <div class="col-md-12">
                        <h5>{{ trans("pengajuanjadwal.survey.title") }}</h5>
                      </div>
                      <div class="col-md-12">
                        <el-form-item
                          :label="trans('pengajuanjadwal.keterangan')"
                          prop="keterangan"
                        >
                          <el-input
                            type="textarea"
                            placeholder="Keterangan.."
                            v-model="form.keterangan"
                            size="small"
                          >
                          </el-input>
                        </el-form-item>
                      </div>
                      <div class="col-md-12">
                        <el-checkbox v-model="form.rekomendasiExist">
                          {{ trans("pengajuanjadwal.is rekomendasi") }}
                        </el-checkbox>
                      </div>
                      <div class="col-md-12" v-show="form.rekomendasiExist">
                        <div class="row">
                          <div class="col-md-6">
                            <el-form-item
                              :label="trans('pengajuanjadwal.tgl rekomendasi')"
                              prop="tgl_rekomendasi"
                            >
                              <el-date-picker
                                v-model="form.tgl_rekomendasi"
                                type="date"
                                value-format="yyyy-MM-dd"
                                placeholder="Pick a day"
                                :picker-options="pickerOptions"
                                size="small"
                              >
                              </el-date-picker>
                            </el-form-item>
                          </div>
                          <div class="col-md-6">
                            <el-form-item
                              :label="trans('pengajuanjadwal.jam rekomendasi')"
                              prop="jam_rekomendasi"
                            >
                              <el-time-select
                                v-model="form.jam_rekomendasi"
                                placeholder="Select time"
                                :picker-options="{
                                  start: selectableRangePicker[0],
                                  step: '00:30',
                                  end: selectableRangePicker[1],
                                }"
                                size="small"
                              >
                              </el-time-select>
                              <!-- <el-time-picker
                                 v-model="form.jam_rekomendasi"
                                 format="HH:mm"
                                 value-format="HH:mm"
                                 :picker-options="{
                                     selectableRange: selectableRangePicker,
                                     step:'00:15'
                                 }"
                                 placeholder="Select time"
                                 size='small'>
                             </el-time-picker> -->
                            </el-form-item>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <hr />
                  </div>
                  <div class="col-md-12" v-if="user_company.type == 'isp'">
                    <el-form-item>
                      <el-button
                        type="primary"
                        @click="onSubmit('form')"
                        :loading="loading"
                      >
                        {{ trans("core.save") }}
                      </el-button>
                    </el-form-item>
                  </div>
                </div>
              </div>
            </div>
<<<<<<< HEAD
         </div>
       </div>
  	</Card>
    </el-form>
    
    <JadwalSurvey v-if="pelanggan_id != null && stepSekarang == 0" :pelanggan_id="pelanggan_id"/>
=======
          </div>
        </div>
      </el-form>

      <JadwalSurvey
        v-if="pelanggan_id != null && stepSekarang == 0"
        :pelanggan_id="pelanggan_id"
      />
>>>>>>> revisi-instalasi-adi-5Mei

      <div class="card">
        <div class="card-body">
          <el-button type="danger" @click="$router.go(-1)">
            {{ trans("core.back") }}
          </el-button>
        </div>
      </div>
    </div>
    <div v-show="finishSO" v-loading.body="loading">
      <info
        :mapInfo="mapInfo"
        :form="form"
        :photo="photo"
        :role="user_roles"
        v-on:changeFinishSO="changeFinishSO($event)"
      ></info>
    </div>
  </div>
</template>
<script>
import step_so from "../../mixins/step_so";
import info from "./isp/info";
export default {
  mixins: [step_so],
  components: {
    info: info,
  },
  data() {
    return {};
  },
  methods: {
    changeFinishSO(event) {
      this.loading = true;
      this.finishSO = event;
      return (this.loading = false);
    },
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
</style>
