<template>
    <bs-modal @onClose="onClose" id="modal-bayar-invoice" >
        <span slot="header">
            {{trans('invoices.title.pembayaran invoice', {invoice: invoice.invoice_no})}}
        </span>

        <table  v-loading="loading" style="width:100%;">
            <tr>
                <td style="font-weight: 700;">{{trans('invoices.table.total tagihan')}}</td>
                <td>: {{ rupiah(invoice.total_tagihan) }}</td>
            </tr>
            <tr>
                <td style="font-weight: 700;">{{ trans('invoices.table.sisa saldo') }}</td>
                <td>: {{ rupiah(sisa_saldo)}} </td>
            </tr>
        </table>

        <p class="text-center mt-4" v-if="sisa_saldo < 0">{{ trans('invoices.saldo tidak mencukupi') }}</p>
        <hr>
        <el-checkbox v-if="sisa_saldo >= 0" v-model="checkbox"></el-checkbox> 
        <span style="font-size:14px;cursor:pointer;" @click="checkbox = !checkbox">{{trans('saldos.messages.agree',{pembayaran : 'Pembayaran Invoice'})}}</span>
        <div slot="footer" v-if="checkbox">
            <el-button
            size="small"
            type="primary"
            v-if="sisa_saldo >= 0"
            @click="goToBayar"
            >
                {{trans('invoices.button.bayar')}}
            </el-button>
            <el-button
            v-if="sisa_saldo < 0"
            size="small"
            @click="goToTopup"
            type="primary"
            >
                {{ trans('saldos.title.topup') }}
            </el-button>
            <el-button
            size="small"
            type="secondary"
            @click="onClose"
            >
                {{trans('core.button.close')}}
            </el-button>
        </div>
    </bs-modal>

</template>

<script>
import CurrencyHelper from '../../../../Core/Assets/js/mixins/CurrencyHelper'
import Toast from '../../../../Core/Assets/js/mixins/Toast'
import TranslationHelper from '../../../../Core/Assets/js/mixins/TranslationHelper'
import BsModalVue from '../../../../Presale/Assets/components/modal/BsModal.vue'

export default {
    mixins: [ TranslationHelper, CurrencyHelper, Toast ],
    props:['invoice_id'],
    components: {
        'bs-modal' : BsModalVue
    },
    data() {
        return {
            checkbox: false,
            loading: false,
            invoice: {
                "invoice_id": null,
                "invoice_no": "",
                "periode": "",
                "status": "",
                "created_at": "",
                "settlement_at": "",
                "from": {
                    "company_id": null,
                    "name": "",
                    "type": "",
                    "provinces_id": null,
                    "regencies_id": null,
                    "districts_id": null,
                    "villages_id": null,
                    "address": "",
                    "pop_address": "",
                    "post_code": "",
                    "display_name": "",
                    "logo_perusahaan": null,
                    "company_img": "",
                    "pop_lat": null,
                    "pop_lon": null,
                    "company_lat": null,
                    "company_lon": null,
                    "total_endpoint": null,
                    "rating": null
                },
                "to": {
                    "company_id": null,
                    "name": "",
                    "type": "",
                    "provinces_id": null,
                    "regencies_id": null,
                    "districts_id": null,
                    "villages_id": null,
                    "address": "",
                    "pop_address": "",
                    "post_code": "",
                    "display_name": "",
                    "logo_perusahaan": null,
                    "company_img": "",
                    "pop_lat": null,
                    "pop_lon": null,
                    "company_lat": null,
                    "company_lon": null,
                    "total_endpoint": null,
                    "rating": null
                },
                "item": [
                ],
                "total_tagihan": 0
            },
            saldo: {
                saldo: 0
            }
        }
    },
    methods: {
        fetchInvoice() {
            if(this.invoice_id != null){
                this.loading = true;
                axios.get(route('api.invoice.invoices.show', {invoice: this.invoice_id}))
                .then(response => {
                    this.loading = false;
                    this.invoice = response.data.data;
                    this.saldo = response.data.saldo;
                })
            }
        },
        onClose() {
            $('#modal-bayar-invoice').modal('hide');
        },
        goToTopup() {
            this.onClose();
            this.$router.push({
                name: 'admin.saldo.topup.index',
            });
        },
        goToBayar() {
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
                    axios.get(route('api.invoice.invoices.bayar', {invoice: this.invoice_id}))
                    .then(response => {
                        this.Toast({
                            icon:'success',
                            title: response.data.messages
                        })
                        this.onClose();
                        this.$emit('handleSuccess', true)
                    })
                    .catch(error => {
                        if(error.response.status === 417) {
                            return this.Toast({
                                icon:'info',
                                title: error.response.data.message
                            })
                        }

                        return this.Toast({
                            icon:'error',
                            title: trans('core.error 500 title')
                        })
                    })
                }
            })
            
        }
    },
    watch: {
        invoice_id: function() {
            this.fetchInvoice();
        }
    },
    computed: {
        sisa_saldo: function() {
            return this.saldo.saldo - this.invoice.total_tagihan
        }
    },
    mounted(){
        this.fetchInvoice();
    }
}
</script>

<style>

</style>