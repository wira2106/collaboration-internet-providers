<template>
  <div>
    <i class="fas fa-circle text-danger" v-if="row.cancel == 1"></i>
    <i
      class="fas fa-circle text-purple"
      v-else-if="row.status_perubahan_harga == 'request'"
    />
    <i
      class="fas fa-circle text-warning"
      v-else-if="row.pengajuan_jadwal_survey_pending > 0"
    />
    <i
      class="fas fa-circle text-info"
      v-else-if="row.status_survey == 'active' && row.cancel != 1"
    ></i>
    <i
      class="fas fa-circle text-success"
      v-else-if="row.status_survey == 'finish'"
    />
  </div>
</template>

<script>
export default {
  props: ["row"],
};
</script>

<style></style>
