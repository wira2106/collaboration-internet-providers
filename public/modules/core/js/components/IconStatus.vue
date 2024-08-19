<template>
    <div>
        <i class="el-icon-success" :style="styling()" v-if="status.status == 'success'"></i>
        <i class="el-icon-time " :style="styling()" v-if="status.status == 'pending'"></i>
        <i class="el-icon-error" :style="styling()" v-if="status.status == 'cancel'"></i>
    </div>
</template>
<script>
  export default {
    props: ["status"],
    data() {
      return {
        fits: ['cover'],
      }
    },
    methods:{
        styling(){
            return {
                'color':this.status.color,
                'font-size':this.status.size
            }
        }
    }
  }
</script>