<template>
  <div>
    <i class="fas fa-circle text-danger" v-if="row.cancel == 1"></i>
    <i
      class="fas fa-circle text-info"
      v-else-if="row.status_perubahan_harga == 'request'"
    >
    </i>

    <i
      class="fas fa-circle text-warning"
      v-else-if="row.status_aktivasi == 'selesai'"
    >
    </i>

    <i class="fas fa-circle text-info" v-else></i>
  </div>
</template>

<script>
export default {
  props: ["row"],
};
</script>

<style></style>
