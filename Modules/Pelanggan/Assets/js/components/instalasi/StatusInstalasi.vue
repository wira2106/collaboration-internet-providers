<template>
  <div>
    <i
      class="fas fa-circle text-success"
      v-if="row.pengajuan_jadwal_instalasi_pending > 0"
    >
    </i>
    <i
      class="fas fa-circle text-info"
      v-else-if="row.status_perubahan_harga == 'request'"
    >
    </i>

    <i
      class="fas fa-circle text-info"
      v-else-if="row.status_instalasi == 'active'"
    >
    </i>

    <i class="fas fa-circle text-danger" v-if="row.cancel == 1"> </i>
  </div>
</template>

<script>
export default {
  props: ["row"],
};
</script>

<style></style>
