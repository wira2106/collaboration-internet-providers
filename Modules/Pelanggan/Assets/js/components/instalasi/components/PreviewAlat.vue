<template>
  <div>
    <table class="table" v-if="alats.length > 0">
      <thead>
        <td style="font-weight: 500;">
          {{ trans("pelangganinstalasi.table alat.nama") }}
        </td>
        <td style="font-weight: 500;">
          {{ trans("pelangganinstalasi.table alat.jumlah") }}
        </td>
        <td style="font-weight: 500;">
          {{ trans("pelangganinstalasi.table alat.status") }}
        </td>
      </thead>
      <tr v-for="(a, index) in alats" :key="index">
        <td>{{ a.nama_alat }}</td>
        <td>{{ a.qty }}</td>
        <td>{{ a.status }}</td>
      </tr>
    </table>

    <p class="text-center w-100" v-else>
      {{ trans("installations.messages.tidak ada alat") }}
    </p>
  </div>
</template>

<script>
export default {
  props: ["alats"],
};
</script>

<style></style>
