<template>
  <div>
    <div v-if="signals.length != 0">
      <div v-for="(signal, index) in signals" :key="index" class="d-inline p-2">
        <a
          class="fancybox"
          v-bind:href="signal.url"
          data-width="2048"
          data-height="1365"
          data-fancybox-type="image"
          v-bind:id="'signal.name'"
        >
          <img :src="signal.url" alt="" style="height:150px; width:150px;" />
        </a>
      </div>
    </div>
    <p v-else class="text-center">
      {{ trans("pengajuanjadwal.survey.messages.signal kosong") }}
    </p>
  </div>
</template>
<script>
export default {
  props: ["signals"],
};
</script>
