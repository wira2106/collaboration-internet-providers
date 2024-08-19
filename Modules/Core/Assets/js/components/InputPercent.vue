<template>
  <el-input type="number" size="small" v-model="displayValue" :readonly="readonly">
        <template slot="append">%</template>
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
    methods:{
        isNumeric(value) {
            return /^-?\d+$/.test(value);
        },
        percenValidator(value){
          if(!this.isNumeric(value)){
              return 0;
            }else if(value < 0){
              return 0;
            }else if(value > 100){
              return 100;
            }
            return parseInt(value);
        }
    },
    computed: {
      displayValue: {
        get: function() {
            return this.value;
        },
        set: function(modifiedValue) {          
          let newValue = this.percenValidator(modifiedValue);          
          this.$emit('input', newValue)
        }
      },

    }
}
</script>