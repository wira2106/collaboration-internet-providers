<template>
  <div class="container">
    <div
      class="row"
      v-for="(dokumentasi_show, index) in dokumentasi"
      :key="index"
    >
      <!-- start image pelanggan instalasi -->
      <div class="col-md-4">
        <div class="image-preview attachments-image">
          <el-image
            :src="dokumentasi_show.foto_dokumentasi_url"
            alt=""
            fit="contain"
            style="overflow:hidden;width:100%;height:150px"
            :preview-src-list="foto_dokumentasi"
          />
        </div>
      </div>
      <div class="col-md-8">
        <p style="width: 250px;" class="font-custom">
          Keterangan :
        </p>
        {{ dokumentasi_show.keterangan }}
      </div>
      <!-- end image pelanggan instalasi -->
    </div>
    <p class="text-center w-100" v-if="dokumentasi.length == 0">
      {{ trans("installations.messages.tidak ada dokumentasi") }}
    </p>
  </div>
</template>

<script>
export default {
  props: ["dokumentasi"],
  computed: {
    foto_dokumentasi: function() {
      return this.dokumentasi.map((dokumentasi) => {
        return dokumentasi.foto_dokumentasi_url;
      });
    },
  },
  watch: {
    dokumentasi: function() {
      setTimeout(() => {
        $(".fancybox").fancybox();
      }, 100);
    },
  },
};
</script>

<style></style>
