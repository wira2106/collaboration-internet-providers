<template>
    <img :src="url" class="small-image" onerror="this.src = window.location.origin + '/uploadgambar/nologo.png'">
    </img>
</template>
<style>
    .small-image {
        height:50px;
        width:50px
    }
</style>
<script>
  export default {
    props: ["url"],
    data() {
      return {
        fits: ['cover'],
      }
    }
  }
</script>