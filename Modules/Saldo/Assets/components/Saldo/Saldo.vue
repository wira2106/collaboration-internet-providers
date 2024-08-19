<template>
  <span
    style="    
    height: 100%;
    display: flex;
    align-items: center;
    color: white;
    margin-right: 20px;"
  >
    {{ trans("saldos.title.saldo") }} : {{ saldo | harga }}
  </span>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters(["saldo"]),
  },
};
</script>

<style></style>
