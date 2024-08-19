<template>
  <div>
  <el-form ref="form"
    :model="form"
    label-width="120px"
    label-position="top"
    v-loading.body="loadingPengajuan"
    @keydown="form.errors.clear($event.target.name);"
    :rules="rules">
    <div class="card card-outline-info">
      <div class="card-body collapse show">
         <div class="row p-0 mb-3">
          <div class="col-12">
            <label>{{trans('pengajuanjadwal.instalasi.title')}}</label>
          </div>
        </div>
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
            <table style="width: 100%;">
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
          <!-- pilihan tanggal instalasi -->
          <div class="col-md-12" v-if="form.status != '' || user_company.type == 'osp'">
            <div class="row">
              <div class="col-md-12">
                <hr>
                <h5>{{trans('pengajuanjadwal.instalasi.pilihan tgl')}}</h5>
              </div>
              <div class="col-md-12">
                <center v-if="form.list.length == 0">{{trans('pengajuanjadwal.instalasi.jadwal belum')}}</center>
                <!-- tabel pilihan tanggal survey -->
                <table class="table table-striped table-pengajuan-survey">
                  <tbody>
                    <tr 
                    v-for="(val,key) in form.list" 
                    :class="(val.status == 'active'?'active':'')"
                    :key="key">
                      <td style="width: 30px;">{{key+1}}</td>
                      <td>
                        <!-- select tanggal survey -->

                        <div v-if="user_company.type == 'osp' && form.status != 'accept'">
                          <el-form-item
                          :prop="'list.' + key + '.tgl_instalasi'"
                          :rules="rules.required_field"
                          >
                              <el-date-picker
                                  style="width:100%"
                                  v-model="val.tgl_instalasi"
                                  type="date"
                                  value-format="yyyy-MM-dd"
                                  placeholder="Pick a day"
                                  :picker-options="pickerOptions"
                                  size='small'>
                                </el-date-picker>
                          </el-form-item>
                        </div>
                        <!-- informasi tanggal survey -->
                        <div v-else>
                          {{val.tgl_instalasi}}
                        </div>
                      </td>
                      <td>
                        <!-- select slot instalasi -->
                        <div v-if="user_company.type == 'osp' && form.status != 'accept'">
                          <el-form-item
                          :prop="'list.' + key + '.slot_id'"
                          :rules="rules.required_field">

                            <el-select
                                v-model="val.slot_id"
                                size="small"
                            >
                            <el-option
                                v-for="item in slot_instalasi"
                                :key="item.slot_id"
                                :label="item.full_name"
                                :value="item.slot_id"
                                >
                                </el-option>
                            </el-select>
                          </el-form-item>
                        </div>
                        <!-- informasi slot instalasi -->
                        <div v-else>
                          {{val.full_name}}                          
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
                      <td v-if="user_company.type == 'osp' && form.status != 'accept' && key !==0">
                        <a href="#!" @click="removeTanggal(key)">
                          <i class="fa fa-trash text-danger"></i>
                        </a>
                      </td>
                      <td width="76" v-else></td>
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

              <!-- tombol ajukan reschedule -->
              <div class="col-md-12" v-if="(column_btn_schedule && user_company.type == 'isp') || (form.status == 'accept' && user_company.type == 'osp')">
                  <div class="row"> 
                    <div class="col-md-12">
                      <hr>
                    </div>
                    <div class="col-md-12">
                      <div class="pull-right">                      
                        <!-- <button v-show="status_step !== 'instalasi'" type="button" class="btn btn-primary btn-sm" @click="tambahSchedule()"> -->
                        <button  type="button" class="btn btn-primary btn-sm" @click="tambahSchedule()">
                          Ajukan Rechedule
                        </button>
                      </div>
                    </div>                
                  </div>
                </div>              
              </div>
            </div>

          <!-- tombol batal schedule -->
          <div class="col-md-12" v-if="btnbatalSchedule">
            <div class="row">
              <div class="col-md-12">
                <hr>
              </div>
              <div class="col-md-12">
                <div class="pull-right">                      
                  <button type="button" class="btn btn-danger btn-sm" @click="batalSchedule()">Batal Rechedule</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- card footer and button save -->
      <!-- <div class="card-footer" v-if="(form.list.length > 0 && form.status != 'accept') || (user_company.type == 'isp' && form.status == '')">
        <div>
          <el-form-item>
            <el-button type="primary" @click="onSubmit('form')" :loading="loadingPengajuan">
                {{ trans('core.save') }}
            </el-button>
          </el-form-item>
        </div>
      </div> -->

    </div>
  </el-form>
    <div class="modal fade history-pengajuan-instalasi" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" v-loading="loadingModal">
           <div class="modal-header">
              <h5 class="modal-title">{{trans('pengajuanjadwal.instalasi.history pengajuan')}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12" v-for="(val,key) in history" v-if="key != (history.length-1)" :key="key">
                  <table style="width: 100%">
                    <tr>
                      <td style="width: 150px">Tgl Rekomendasi</td>
                      <td style="width: 5px;">:</td>
                      <td>{{val.tgl_rekomendasi_c}}</td>
                    </tr>
                    <tr>
                      <td style="width: 150px">Keterangan</td>
                      <td style="width: 5px;">:</td>
                      <td>{{val.keterangan}}</td>
                    </tr>
                  </table>
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Tgl</th>
                        <th>Slot</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(v,k) in val.list" :key="k">
                        <td>{{k+1}}</td>
                        <td>{{v.tgl_instalasi}}</td>
                        <td>{{v.full_name}}</td>
                        <td><b class="text-success" v-if="v.status == 'active'">Dipilih</b></td>
                      </tr>
                    </tbody>
                  </table>
                  <hr>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</template>
<script type="text/javascript">
  import axios from 'axios';  
  import ShortcutHelper from '../../../../../Core/Assets/js/mixins/ShortcutHelper';
  import TranslationHelper from '../../../../../Core/Assets/js/mixins/TranslationHelper';
  import PermissionsHelper from '../../../../../Core/Assets/js/mixins/PermissionsHelper';
  import Toast from '../../../../../Core/Assets/js/mixins/Toast';
  import UserRolesPermission from '../../../../../Core/Assets/js/mixins/UserRolesPermission';

  export default {
    mixins: [Toast,PermissionsHelper,UserRolesPermission],
    props: ['pelanggan_id'],
    data() {
        return {
            status_step:'',
            pickerOptions: {
                disabledDate(time) {
                  let date = new Date();
                  date.setDate(date.getDate() - 1);
                  return time.getTime() < date.getTime();
                }
            },           
            tgl_instalasi: null,
            loadingPengajuan: false,
            form:{
              rekomendasiExist: false,
              jam_rekomendasi: '08:00',
              tgl_rekomendasi: '',
              keterangan: '',
              status: '',
              list: [{
                tgl_instalasi: '',
                 slot_id: '',
                 status: 'not_active'
              }]
            },
            slot_instalasi: [],
            history: [],
            rules : {
              required_field:[
                {required:true, trigger:['blur', 'change'], message: this.trans('core.form.required')}
              ],
            },
            column_btn_schedule: true,
            btnbatalSchedule: false,
            loadingModal: false,
            selectableRange: ['00:00','00:00'],
            messege:'',
            user_role:'',
        }
    },
   computed:{
      selectableRangePicker: function() {
          return [this.selectableRange[0].substring(0, 5),this.selectableRange[1].substring(0, 5)];
          // return `${this.selectableRange[0]} - ${this.selectableRange[1]}`;
      },
    },
    methods: {
      fetchPengajuanInstalasi(){
        this.loadingPengajuan = true;
        this.btnbatalSchedule = false;
        if (this.pelanggan_id != 0) {
          axios.get(route('api.pelanggan.form.jadwal.instalasi',{pelanggan_id:this.pelanggan_id}))
          .then((response) => {
            this.step_status = response.data.status;
            let data = response.data.data;
            let osp = response.data.osp;
            let slot_instalasi = response.data.slot_instalasi;
            this.fetchDateRangeSlot(osp);
            this.history = data;
            if (data.length != 0) {
              this.form = data[data.length-1];
            }
            this.slot_instalasi = slot_instalasi;            
            this.loadingPengajuan = false;
            this.checkDisplayButtonRechedule();
          });
        }
      },
      addJadwal(){
        let push = {
          tgl_instalasi: '',
          slot_id: '',
          status: 'not_active'
        };
        this.form.list.push(push);
      },
      removeTanggal(key)
      {
        this.form.list.splice(key, 1);
      },
      pickTanggalSurvey(key,type)
      {
        let tglSurvey = this.form.list[key].tgl_instalasi;
        let jamSurvey = this.form.list[key].slot_id;
        this.resetPickTanggalSurvey();
        this.form.list[key].status = type;
        this.checkDisplayButtonRechedule();
      },
      resetPickTanggalSurvey(){
        this.form.list.forEach( function(val, index) {
          val.status = 'not_active';
        });
      },
      checkTanggalIsEmpty(){
        // check apakah tanggalnya ada yang kosong atau tidak
        let emptyTanggal = false;
        this.form.list.forEach( function(val, index) {
          if (val.slot_id =='' || (val.tgl_instalasi == '' || val.tgl_instalasi == null)) {
            emptyTanggal = true;
          }
        });

        if (emptyTanggal) {          
          return true;
        }
        return false;
      },
      checkPilihTanggal(){
        if (this.user_company.type == 'isp' && this.form.list.length > 0 && this.form.status == 'pending') {
          let checkActive = true;
          this.form.list.forEach( function(val, index) {
            if (val.status == 'active') {
              checkActive = false;
            }
          });
          return checkActive;
        }
        return false;
      },
      async onSubmit(form){
        this.$refs[form].validate(valid => {
          if (this.checkTanggalIsEmpty()) {
            Swal.fire(this.trans('pengajuanjadwal.instalasi.messages.required jadwal'),'','warning');
            return false;
          }

          if (this.checkPilihTanggal()) {
            Swal.fire(this.trans('pengajuanjadwal.belum memilih'),'','warning');
            return false;
          }

          if(valid) {
             Swal.fire({
              title: this.trans('core.messages.confirmation-form'),
              text: "",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: this.trans('core.button.confirm'),
              cancelButtonText: this.trans('core.button.cancel')
            }).then(async (result) => {
              if (result.value) {
                this.loadingPengajuan = true;
                if (this.user_company.type == 'isp') {
                  let check = await this.checkSaldoSudahCukup();
                  if (check.cukup) {
                    this.submitForm(form);
                  }else{
                    this.bayarBiayaPelanggan(form,check.data);
                  }          
                }else{
                  this.submitForm(form);
                }
              }
            });
          }
        });
      },
      sendForm(){
        
        return axios.post(route('api.pelanggan.form.jadwal.instalasi.submit',{pelanggan_id: this.pelanggan_id}),this.form)
        
      },
      checkPengajuanTanggalInstallasi(){
        
        if (this.checkTanggalIsEmpty()) {
            Swal.fire(this.trans('pengajuanjadwal.instalasi.messages.required jadwal'),'','warning');
            return false;
          }

          if (this.checkPilihTanggal()) {
            Swal.fire(this.trans('pengajuanjadwal.belum memilih'),'','warning');
            return false;
          }
      },

      bayarBiayaPelanggan(form,data){        
        let total = parseInt(data.mrc)+parseInt(data.otc);
        let saldo = parseInt(data.saldo);
        let sisa_saldo = saldo-total;
        let linkSaldo = window.origin+'/backend/saldo/topups';
        let html = `
          <table style="width:100%">
            <tr><td>Saldo</td><td>:</td><td>Rp. ${this.number_format(data.saldo)}</td></tr>
            <tr><td colspan='3'><hr></td></tr>
            <tr><td>Biaya OTC</td><td>:</td><td>Rp. ${this.number_format(data.otc)}</td></tr>
            <tr><td>Biaya MRC</td><td>:</td><td>Rp. ${this.number_format(data.mrc)}</td></tr>`;
        
        if (data.terbayar != 0) {
          html += `<tr><th>Terbayar</th><td>:</td><td>- Rp. ${this.number_format(data.terbayar)}</td></tr>`;
          total = total-data.terbayar;
          sisa_saldo = sisa_saldo+data.terbayar;
        }

        html += `<tr><th>Total Biaya</th><td>:</td><td>Rp. ${this.number_format(total)}</td></tr>
            <tr><td colspan='3'><hr></td></tr>
            <tr><th>Sisa Saldo</th><td>:</td><td>Rp. ${this.number_format(sisa_saldo)}</td></tr>
          </table>
          <br>
          `;
        if(sisa_saldo < 0){
          html+=`<a href="${linkSaldo}">
              <button class="btn btn-sm btn-primary">Topup Sekarang</button>
            </a> `;
        }
        Swal.fire({
          title: '',
          icon: 'info',
          html: html,
          showCancelButton: true,
        }).then((result)=>{
          if (result.value) {
            if (sisa_saldo < 0) {
              Swal.fire('Saldo tidak mencukupi','','error');
              this.loadingPengajuan = false;
            }else{
              axios.post(route('api.pelanggan.saldo.submit',{pelanggan_id:this.pelanggan_id}))
              .then((response) => {
                if (response.data.errors) {
                  Swal.fire(response.data.message,'','error');
                  this.loadingPengajuan = false;
                }else{
                  this.submitForm(form);
                }
              }).catch((er)=>{
                console.log(er);
                this.Toast({
                  icon: "error",
                  title: "error"
                });
                this.loadingPengajuan = false;
              });
            }
          }else{
            this.loadingPengajuan = false;
          }
        });
      },
      submitForm(form){        
        
        this.sendForm()
        .then((response) => {
          let data = response.data;                      
          this.Toast({
            icon: "success",
            title: data.message
          });
          // this.fetchPengajuanInstalasi();
          // window.location = route('admin.pelanggan.form.step')+"?pelanggan="+this.pelanggan_id;
          this.$router.go();
        }).catch((error) => {
          this.loadingPengajuan = false;
          this.Toast({
            icon: "error",
            title: "error"
          });
        });
             
      },
      checkSaldoSudahCukup(){
         return new Promise((resolve) => {
            this.loadingPengajuan = true;
            axios.get(route('api.pelanggan.saldo.check',{pelanggan_id: this.pelanggan_id}))
            .then((response) => {
              return resolve(response.data);
            });
         });
      },      
      checkDisplayButtonRechedule(){
        // check kalo gaada pilihan tanggal, survey di hide
        if (this.form.list.length == 0) {        
          this.column_btn_schedule = false;
        }else{
          this.column_btn_schedule = true;
          let check = 0;
          this.form.list.forEach( function(val, index) {
            if (val.status == 'active') {
              check++;
            }
          });
          if (check > 0) {
            // kalo ada yang di check munculin tombol batal schecdule            
            this.column_btn_schedule = false;
          }
        }
      },
      tambahSchedule(){
        this.form = {
          rekomendasiExist: false,
          jam_rekomendasi: '08:00',
          tgl_rekomendasi: '',
          keterangan: '',
          status: '',
          list: []
        }                
        this.btnbatalSchedule = true;
      },
      batalSchedule(){
        this.btnbatalSchedule = false;
        this.form = this.history[this.history.length-1];
        this.checkDisplayButtonRechedule();
      },
      seeHistoryPengajuanSurvey(){        
        $(".history-pengajuan-instalasi").modal();
      },
     fetchDateRangeSlot(osp_id) {
          axios.get(route('api.company.slotinstalasi.get-range-time'), {company_id: osp_id})
          .then(response => {
              this.selectableRange = response.data.data;                
          })
          .catch(err => {
              setTimeout(() => {
                  this.fetchDateRangeSlot(osp_id)
              }, 2000);
          });
      },
      pilihTanggalRekomendasi(id){
         Swal.fire({
            title: this.trans('pengajuanjadwal.instalasi.messages.confirm rekomendasi'),
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: this.trans('core.button.confirm'),
            cancelButtonText: this.trans('core.button.cancel')
          }).then((result) => {
            if (result.value) {
              this.loadingPengajuan = true;
              let properties = {
                pelanggan_id: this.pelanggan_id,
                pengajuan_id: id
              };
              axios.post(route('api.pelanggan.form.jadwal.instalasi.pilih_rekomendasi',properties))
              .then((response) => {
                let data = response.data;
                if (data.errors) {
                  this.Toast({
                      icon: "error",
                      title: data.message
                  });                  
                  this.loadingPengajuan = false;
                }else{
                  this.Toast({
                      icon: "success",
                      title: data.message
                  });                  
                  this.loadingPengajuan = true;
                  this.$router.go();
                }
              }).catch((error) => {
                this.loadingPengajuan = false;
                this.Toast({
                  icon: "error",
                  title: "error"
                });
              });
            }
          });
      },
      reloadStep(){
        this.$emit("reload")
      },
       number_format(number, decimals, decPoint, thousandsSep) { 
         number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
         var n = !isFinite(+number) ? 0 : +number
         var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
         var sep = (typeof thousandsSep === 'undefined') ? '.' : thousandsSep
         var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
         var s = ''

         var toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec)
          return '' + (Math.round(n * k) / k)
            .toFixed(prec)
         }

         // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
         s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
         if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
         }
         if ((s[1] || '').length < prec) {
          s[1] = s[1] || ''
          s[1] += new Array(prec - s[1].length + 1).join('0')
         }

         return s.join(dec)
      }
    },
    mounted(){
      this.fetchPengajuanInstalasi();
      this.getRolesPermission();      
    }
  }
</script>
<style type="text/css">
  .table-pengajuan-survey .active{
    background: #49a4e7 !important;
    color: white;
  }
</style>