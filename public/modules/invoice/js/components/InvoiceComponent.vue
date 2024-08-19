<template>
  <div class="card card-body printableArea" v-loading="loading">
    <h3><b>{{ trans('invoices.title.invoices') }}</b> <span class="pull-right">{{ invoice.invoice_no }}</span></h3>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="pull-left">
                <address>
                    <h3 style="width:250px"> &nbsp;<b class="text-danger">{{ invoice.nama_tagihan }}</b></h3>
                    <p class="text-muted m-l-5" style="width:250px">
                        {{ invoice.from.address }}
                    </p>
                </address>
            </div>
            <div class="pull-right text-right">
                <address>
                    <h3>To,</h3>
                    <h4 class="font-bold">{{ invoice.to.name }}</h4>
                    <p class="text-muted m-l-30" style="width:250px">
                        {{ invoice.to.address }}
                    </p>
                    <p class="m-t-30"><b>{{ trans('invoices.tanggal invoice')}} :</b> <i class="fa fa-calendar"></i> {{
                        invoice.created_at
                    }}</p>
                    <p><b>{{ trans('invoices.expired') }} :</b> <i class="fa fa-calendar"></i> {{ invoice.jatuh_tempo }}</p>
                </address>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive m-t-40" style="clear: both;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{ trans('invoices.deskripsi') }}</th>
                            <th class="text-right">{{ trans('invoices.quantity') }}</th>
                            <th class="text-right">{{ trans('invoices.harga') }} </th>
                            <th class="text-right">{{ trans('invoices.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, i) in item_invoice" :key="i">
                            <td class="text-center">{{ i+1 }}</td>
                            <td>  {{ trans('invoices.tagihan pelanggan', { name: item.name}) }}</td>
                            <td class="text-right">1</td>
                            <td class="text-right"> {{ rupiah(item.amount) }} </td>
                            <td class="text-right"> {{ rupiah(item.amount) }} </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="pull-right m-t-30 text-right">
                <p>{{ trans('invoices.ppn', { ppn:invoice.ppn, amount: rupiah(total_pajak) }) }} </p>
                <hr>
                <h3><b>{{ trans('invoices.total') }} :</b> {{ rupiah(total + total_pajak) }}</h3>
            </div>
            <div class="clearfix"></div>
            <hr v-if="this.$route.name != 'admin.invoice.invoices.print'">
            <div class="text-right" v-if="this.$route.name != 'admin.invoice.invoices.print'">
                <button class="btn btn-danger" type="submit" @click="goToBayar" v-if="invoice.status == null"> {{ trans('invoices.button.bayar') }} </button>
                <button id="print" class="btn btn-default btn-outline" type="button" @click="goToPrint(invoice.invoice_id)"> <span><i class="fa fa-print"></i> {{ trans('invoices.print') }}</span> </button>
            </div>
        </div>
    </div>
    <modal-bayar-invoice :invoice_id="invoice.invoice_id" @handleSuccess="handleSuccessBayar">

    </modal-bayar-invoice>
</div>
</template>

<script>
import CurrencyHelper from '../../../../Core/Assets/js/mixins/CurrencyHelper';
import ModalBayarInvoiceVue from './ModalBayarInvoice.vue';

export default {
    mixins: [CurrencyHelper],
    components: {
        'modal-bayar-invoice': ModalBayarInvoiceVue
    },
    props:['invoice', 'loading'],
    data(){
        return {
            total: 0,
        }
    },
    methods: {
        goToBayar() {
            $('#modal-bayar-invoice').modal('show');
        },
        handleSuccessBayar() {
            $('#modal-bayar-invoice').modal('hide');
            this.$emit('handleSuccessBayar', true)
        },
        goToPrint(invoice_id) {
            window.location.href = route('admin.invoice.invoices.print', {invoice:invoice_id})
        },
    },
    computed: {
        item_invoice: function() {
            this.total = 0;
            let invoice = this.invoice.item.map(item => {
                this.total += item.amount;
                return item;
            })
            return invoice;
        },
        total_pajak: function() {

            return this.total * (this.invoice.ppn / 100);
        }
    },
}
</script>

<style>

</style>