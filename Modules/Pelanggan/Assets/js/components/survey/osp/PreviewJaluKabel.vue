<template>
  <div>
    <div v-if="jalurKabels.length != 0">
      <div
        v-for="(jalur, index) in jalurKabels"
        :key="index"
        class="d-inline p-2"
      >
        <a
          class="fancybox"
          v-bind:href="jalur.url"
          data-width="2048"
          data-height="1365"
          data-fancybox-type="image"
          v-bind:id="'jalur.name'"
        >
          <img
            :src="jalur.url"
            alt="bukti transfer"
            style="height:150px; width:150px;"
          />
        </a>
      </div>
    </div>
    <p v-else class="text-center">
      {{ trans("pengajuanjadwal.survey.messages.jalur kosong") }}
    </p>
  </div>
</template>
<script>
export default {
  props: ["jalurKabels"],
};
</script>
