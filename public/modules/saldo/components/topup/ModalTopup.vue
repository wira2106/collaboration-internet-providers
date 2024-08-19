<template>
    <div>
        <div class="modal-header" style="margin-top:-65px;">
            <div class="pull-left">
               <h3><b>Konfirmasi TopUp</b></h3> 
            </div>
        </div>
        <div class="modal-body" v-loading="modalLoading">
            <div class="row">
                <div class="col-md-12">
                    <el-form 
                        :model="topupForm" 
                        :rules="rules" 
                        ref="topupForm" 
                        label-width="200px" 
                        label-position="top">
                        <el-form-item :label="trans('saldos.form.rek')" prop="bank_account_id" >
                            <el-select v-model="topupForm.bank_account_id" style="display:block;" size="small">
                              <el-option
                                v-for="rek in radio"
                                :label="rek.namaBank"
                                :value="rek.bank_account_id"
                                :key="rek.bank_id">
                                {{rek.namaBank}} ( {{rek.rekening}} )
                              </el-option>
                          </el-select>
                        </el-form-item>

                        <el-form-item :label="trans('saldos.form.tgl transfer')" prop="tgl_transfer">
                            <el-date-picker
                                type="date"
                                :picker-options="pickerOptions"
                                v-model="topupForm.tgl_transfer" 
                                size="small"
                                style="width: 100%;"
                                ></el-date-picker>
                        </el-form-item>

                        <el-form-item :label="trans('saldos.form.nominal')" prop="amount">
                          <!-- <el-input type="number" v-model="topupForm.amount" size="small"></el-input> -->
                          <InputCurrency v-model="topupForm.amount">
                          </InputCurrency>
                        </el-form-item>

                        <el-form-item v-if="rekening" :label="trans('saldos.form.rek pengirim')" prop="rekening_pengirim">
                            <el-select
                                size="small"
                                v-model="topupForm.rekening_pengirim"
                                filterable
                                @change="rekeningPengirim(topupForm.rekening_pengirim)">
                                        <el-option
                                        v-for="item in option"
                                        :key="item.bank_id"
                                        :label="item.rekening"
                                        :value="{rek: item.rekening,atas_nama:item.atas_nama, bank_id:item.bank_id}">
                                        {{item.namaBank}} - {{item.rekening}}
                                        </el-option>
                                        <el-option value="lain"> Masukan Rekening lain...</el-option>
                            </el-select>
                        </el-form-item>
                        
                        <el-form-item v-if="rekeningLainnya" :label="trans('saldos.form.rek pengirim')" prop="bank_id">
                            <el-input type="number" v-model="topupForm.rekening_pengirim" class="input-with-select">
                                <el-select v-model="topupForm.bank_id"   slot="prepend" style="width:105px;">
                                    <el-option
                                        v-for="ops in bank"
                                        :key="ops.bank_id"
                                        :label="ops.namaBank"
                                        :value="ops.bank_id">
                                        {{ops.namaBank}}
                                    </el-option>
                                </el-select>
                            </el-input>
                        </el-form-item>

                        <el-form-item :label="trans('saldos.form.nama')" prop="atas_nama">
                            <el-input v-model="topupForm.atas_nama" size="small"></el-input>
                        </el-form-item>

                        <el-form-item :label="trans('saldos.form.bukti')" prop="bukti_transfer">
                            <upload-avatar
                                :onSuccess="handleAvatarSuccess"
                                :beforeUpload="beforeAvatarUpload"
                                :image="topupForm.bukti_transfer"
                            >
                            </upload-avatar>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <el-button size="mini" :loading="modalLoading" data-dismiss="modal">{{trans('saldos.button.cancel')}}</el-button>
            <el-button size="mini" :loading="modalLoading" icon="el-icon-upload"  type="primary" @click="onSubmit()">{{trans('saldos.button.simpan')}}</el-button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import _ from 'lodash';
    import Toast from '../../../../Core/Assets/js/mixins/Toast';
    import InputCurrencyVue from '../../../../Core/Assets/js/components/InputCurrency.vue';
    // import Vue from 'vue';
    export default{
        mixins: [Toast],
        components: {
          'InputCurrency' : InputCurrencyVue
        },
        data(){
            return{
                pickerOptions: {
                    disabledDate(time) {
                        return time.getTime() > Date.now();
                    },
                    shortcuts: [{
                        text: 'Today',
                        onClick(picker) {
                        picker.$emit('pick', new Date());
                        }
                    }, {
                        text: 'Yesterday',
                        onClick(picker) {
                        const date = new Date();
                        date.setTime(date.getTime() - 3600 * 1000 * 24);
                        picker.$emit('pick', date);
                        }
                    }, {
                        text: 'A week ago',
                        onClick(picker) {
                        const date = new Date();
                        date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                        picker.$emit('pick', date);
                        }
                    }]
                },
                topupForm:{
                    bank_id:'',
                    bank_account_id:'',
                    rekening_pengirim:[{
                        rek:'',
                        atas_nama:'',
                        bank_id:''
                    }],
                    amount:'',
                    atas_nama:'',
                    bukti_transfer:'',
                    file_bukti_transfer:'',
                    tgl_transfer:'',
                    status:'',
                },
                rules:{
                    bank_account_id: [
                        { required: true, message: this.trans('saldos.validation.required',{field:this.trans('saldos.form.rek')}), trigger: 'change' }
                    ],
                    rekening_pengirim: [
                        { required: true, message: this.trans('saldos.validation.required',{field:this.trans('saldos.form.rek pengirim')}), trigger: 'change' }
                    ],
                    bank_id: [
                        { required: true, message: this.trans('saldos.validation.required',{field:this.trans('saldos.form.rek')}), trigger: 'change' }
                    ],
                    amount: [
                        { required: true, message: this.trans('saldos.validation.required',{field:this.trans('saldos.form.nominal')}), trigger: 'change' },
                        {validator: this.validAmount,trigger:'change'}
                    ],
                    tgl_transfer: [
                        { required: true, message: this.trans('saldos.validation.required',{field:this.trans('saldos.form.tgl transfer')}), trigger: 'change' }
                    ],
                    atas_nama: [
                        { required: true, message: this.trans('saldos.validation.required',{field:this.trans('saldos.form.nama')}), trigger: 'change' }
                    ],
                    bukti_transfer: [
                        { required: true, message: this.trans('saldos.validation.required',{field:this.trans('saldos.form.bukti')}), trigger: 'change' }
                    ],

                },
                radio:'',
                option:'',
                bank:'',
                atas_nama:'',
                modalLoading:false,
                rekening:true,
                rekeningLainnya:false,
                dialogImageUrl: '',
                dialogVisible: false,
                disabled: false

            }
        },
        methods:{
            setNull(){
                if(this.$refs['topupForm']){
                    this.rekening=true;
                    this.rekeningLainnya=false;
                    this.$refs['topupForm'].resetFields();
                    this.topupForm.bukti_transfer='';
                }
            },
            onSubmit(){
                this.form = new Form(this.topupForm);
                this.$refs['topupForm'].validate((valid)=>{
                    if (valid) {
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
                            this.form.post(route('api.saldo.topup.store')).then((response)=>{
                                    $('.topup').modal();
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

                    } else {
                        return false;
                    }
                });
            },
            getData(){
                axios.get(route('api.saldo.topup.rekening')).then((response)=>{
                    console.log(response.data);
                    this.radio = response.data.rekeningOpenaccess.data;
                    this.option = response.data.rekeningPengirim.data;
                    this.bank = response.data.bank.data;
                });
            },
            rekeningPengirim(rek){
                this.modalLoading = true;
                if (rek == "lain") {
                    this.rekeningLainnya = true;
                    this.rekening = false;
                }else{
                    this.topupForm.atas_nama = rek.atas_nama;
                }
                this.modalLoading = false;
            },
            fetchData(){
                this.getData();
            },
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },
            handleDownload(file) {
                console.log(file);
            },
            handleAvatarSuccess(res, file) {
                this.topupForm.bukti_transfer = URL.createObjectURL(file.raw);
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg' || file.type === "image/png";
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG) {
                  this.$message.error('Avatar picture must be JPG or PNG format!');
                }
                if (!isLt2M) {
                  this.$message.error('Avatar picture size can not exceed 2MB!');
                }
                this.topupForm.file_bukti_transfer = file
                return isJPG && isLt2M;
            },
            validAmount(rule, value, callback){
                if(value <= 0) return callback(new Error(this.trans('configurations.validation.invalid number')))
                callback();
            },            

        },
        mounted(){
            $('.topup').on("show.bs.modal", this.setNull);
            this.fetchData();
        }
        
    }
</script>