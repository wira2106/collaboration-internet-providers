<template>
    <div class="pilih-odp-fix" style="">
        <div class="row">
            <div class="col-12">
            <center><b class="nama-odp-yang-dipilih">{{end_point_name}}</b></center>
            </div>
            <div class="col-6" style="padding: 0px;">
            <button type="button" class="btn btn-primary pilih" @click="pilih_odp_yang_sudah_fix()" style="width: 100%;">{{ trans('core.choose') }}</button>
            </div>
            <div class="col-6" style="padding: 0px;">
            <button type="button" class="btn btn-danger" @click="kembali_pilih_odp_yang_sudah_fix()" style="width: 100%;">{{ trans('core.back') }}</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['end_point_name'],
    methods: {
        kembali_pilih_odp_yang_sudah_fix() {
            this.$emit('handleCancel');
        },
        pilih_odp_yang_sudah_fix() {
            this.$emit('handleChoose');
        }
    }
}
</script>

<style>
    .pilih-odp-fix {
        position: absolute;
        bottom: 10px;
        width: calc(100% - 20px);
        left: 10px;
        background-color: white;
        border-radius: 15px;
        box-shadow: grey 5px 5px 8px 5px;
        border: 1px solid #929492;
        overflow-y: auto;
        z-index: 11;
    }

</style>