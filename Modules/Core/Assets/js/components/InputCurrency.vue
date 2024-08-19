<template>
  <el-input type="text" size="small" v-model="displayValue" :readonly="readonly">
        <template slot="prepend">Rp. </template>
    </el-input>
</template>

<script>
export default {
    props: {
            value: [String, Number],
            readonly: {
                type: Boolean,
                default: false
            }
        },
    computed: {
        displayValue: {
            get: function() {
                if(this.value === null) return 0;
                return this.value.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
            },
            set: function(modifiedValue) {
                // Recalculate value after ignoring "$" and "," in user input
                let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""))
                // Ensure that it is not NaN
                if (isNaN(newValue)) {
                    newValue = 0
                }
                // Note: we cannot set this.value as it is a "prop". It needs to be passed to parent component
                // $emit the event so that parent component gets it
                this.$emit('input', newValue)
            }
        },

    }
}
</script>

<style>

</style>