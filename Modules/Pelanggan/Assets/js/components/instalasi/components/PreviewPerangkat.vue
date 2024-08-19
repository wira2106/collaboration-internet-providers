<template>
  <div>
    <table class="table" v-if="perangkats.length > 0">
      <thead>
        <td style="font-weight: 500;">
          {{ trans("pelangganinstalasi.table perangkat.nama") }}
        </td>
        <td style="font-weight: 500;">
          {{ trans("pelangganinstalasi.table perangkat.jumlah") }}
        </td>
        <td style="font-weight: 500;">
          {{ trans("pelangganinstalasi.table perangkat.jenis") }}
        </td>
        <td style="font-weight: 500;" v-if="status_pelanggans != 'survey'">
          {{ trans("pelangganinstalasi.table perangkat.status") }}
        </td>
      </thead>
      <tr v-for="(p, index) in perangkats" :key="index">
        <td>
          {{ p.nama_perangkat }}
        </td>
        <td>{{ p.qty }}</td>
        <td>{{ p.jenis_perangkat }}</td>
        <td v-if="status_pelanggans == 'survey'">{{ p.status }}</td>
      </tr>
    </table>

    <p class="text-center w-100" v-else>
      {{ trans("installations.messages.tidak ada perangkat") }}
    </p>
  </div>
</template>

<script>
export default {
  props: ["perangkats", "status_pelanggans"],
};
</script>

<style></style>
