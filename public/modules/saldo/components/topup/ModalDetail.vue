<template>
    <div>
        <div class="modal-header" style="margin-top:-65px;">
            <div class="pull-left">
               <h3><b>Detail TopUp</b></h3> 
            </div>
        </div>
        <div class="modal-body" v-loading="modalLoading">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10" v-if="konfirm">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group">
                                 <li class="list-group-item text-center" style="border: 0px;">
                                     <IconStatus :status="{'status':data.status,'color':color,'size':size}"></IconStatus>
                                    <!-- <span>{{trans('saldos.table.status')}}</span> -->
                                    <h4>{{data.status}}</h4>
                                    <span v-if="data.keterangan != null && data.keterangan != ''">
                                      {{data.keterangan}}
                                    </span>
                                    <span v-if="data.status == 'success'">
                                       {{trans('saldos.modal.success')}}
                                    </span>
                                </li>
                                <li class="list-group-item p-0" style="border: 0px;">
                                    <table class="table">
                                         <tr>
                                            <td style="font-size: 14px;">{{trans('saldos.form.tgl transfer')}}</td>
                                            <td>
                                                <div style="padding-top:0px;">
                                                    <span style="font-size: 14px;font-weight: 700;display: block;">
                                                       {{data.tgl_transfer}}
                                                    </span>
                                                    <span class="search-regex" style="font-size:12px;display:block;">
                                                        <b>Rp.{{data.amount}}</b> 
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 14px;">
                                                {{trans('saldos.modal.dari')}}
                                            </td>
                                            <td>
                                                <div style="padding-top:0px;">
                                                    <span style="font-size: 14px;font-weight: 700;display: block;">
                                                       {{data.atas_nama}}
                                                    </span>
                                                    <span class="search-regex" style="font-size:12px;display:block;">
                                                        <b>{{data.nama_bank_pengirim}} {{data.rekening_pengirim}}</b> 
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 14px;">{{trans('saldos.modal.penerima')}}</td>
                                            <td>
                                                <div style="padding-top:0px;">
                                                    <span style="font-size: 14px;font-weight: 700;display: block;">
                                                      {{data.nama_penerima}}
                                                    </span>
                                                    <span class="search-regex" style="font-size:12px;display:block;">
                                                        <b>{{data.nama_bank_penerima}} {{data.rekening_penerima}}</b> 
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </li>
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <li class="list-group-item" style="border: 0px;">
                                            <h5>{{trans('saldos.form.bukti')}}</h5>
                                            <a class="fancybox" v-bind:href="data.bukti_transfer"  data-width="2048" data-height="1365" data-fancybox-type="image" v-bind:id="'data.topup_id'">
                                                <img :src="data.bukti_transfer" alt="bukti transfer" style="height:150px; width:150px;">
                                            </a>
                                        </li>
                                    </div>
                                </div>
                            </ul>
                        </div>

                    </div>
                    <div class="row text-center" v-if="data.status !== 'success' && data.status !== 'cancel' && topup_id[1]== 'Super Admin'">
                        <div class="col-md-12 pb-1">
                            <el-button type="primary" style="width: 350px;" @click="onSubmit('success')">{{trans('saldos.button.terima')}}</el-button>
                        </div>
                        <div class="col-md-12">
                            <el-button type="danger" style="width: 350px;" @click="reject(true,false)">{{trans('saldos.button.tolak')}}</el-button>
                        </div>
                    </div>
                </div>
                <div class="col-md-10"  v-if="batal">
                    <el-form
                        ref="alasan" 
                       :model="konfirmasi">
                        <el-form-item :label="trans('saldos.form.alasan')"  prop="keterangan" :rules="[{ required: true, message: this.trans('saldos.validation.required',{field:this.trans('saldos.form.alasan')}), trigger: 'change' }]">
                            <el-input
                                type="textarea"
                                :rows="5"
                                v-model="konfirmasi.keterangan">
                            </el-input>
                        </el-form-item>
                    </el-form>
                    <div class="col-md-12 text-center pt-2">
                        <el-button type="primary" style="width: 350px;" 
                            @click="onSubmit('cancel')">{{trans('saldos.button.simpan')}}</el-button>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import _ from 'lodash';
    import Toast from '../../../../Core/Assets/js/mixins/Toast';

    export default{
        mixins: [Toast],
        props:['topup_id'],
        data(){
            return{
                data:{},
                modalLoading:false,
                color:'',
                size:'95px',
                keterangan:'',
                konfirm: true,
                batal: false,
                konfirmasi:{
                    'topup_id':'',
                    'company_id':'',
                    'status':'',
                    'saldo':'',
                    'pengirim':'',
                    'keterangan':'',
                },
                rules:{
                    ket: [{ required: true, message: this.trans('saldos.validation.required',{field:this.trans('saldos.form.alasan')}), trigger: 'change' }],
                }
            }
        },
        watch:{
            topup_id:function(){
                this.fetchData();
            }
        },
        methods:{
            onSubmit(status){
                this.konfirmasi.topup_id =this.data.topup_id,
                this.konfirmasi.company_id =this.data.company_id,
                this.konfirmasi.status =status,
                this.konfirmasi.saldo =this.data.saldo,
                this.konfirmasi.pengirim =this.data.pengirim
                this.form = new Form(this.konfirmasi);
                console.log(status);
                if (status == 'success') {
                    this.confirm();
                } else {
                    this.$refs['alasan'].validate((valid)=>{
                        console.log(valid);
                        if (valid) {
                           this.confirm();
                        } else {
                            return false;
                        }
                    });
                }
            },
            confirm(){
                Swal.fire({
                    title: this.trans('core.messages.confirmation-form'),
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: this.trans('core.button.confirm'),
                    cancelButtonText: this.trans('core.button.cancel')
                }).then((result) => {
                    if (result.value) {
                        this.modalLoading = true;
                        this.form.post(route('api.saldo.topup.update')).then((response)=>{
                            this.Toast({
                                icon: "success",
                                title: response.message
                            })
                            this.$emit('modalResponse','Response modal');
                            this.modalLoading = false;
                        }).catch((error) => {
                            this.modalLoading = false;
                            this.Toast({
                                icon: "error",
                                title: "error"
                            });
                        });
                    }
                });
            },
            fetchData(){
                this.data = this.topup_id[0];
                if(this.data.status == 'success') this.color = "#67C23A";
                if(this.data.status == 'pending') this.color = "#909399";
                if(this.data.status == 'cancel') this.color = "#e94e11";
                this.def();
            },
            reject(batal,konfirm){
                this.modalLoading = true;
                this.batal = batal;
                this.konfirm = konfirm;
                this.modalLoading = false;
            },
            def(){
                this.modalLoading = true;
                this.batal = false;
                this.konfirm = true;
                this.modalLoading = false;
            }
        },
        mounted(){
            // $('.topup').on("hidden.bs.modal", this.def);
            this.fetchData();
             $('.fancybox').fancybox();
        }
        
    }
</script>