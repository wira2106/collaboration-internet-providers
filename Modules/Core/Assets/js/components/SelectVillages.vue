<template>
  <el-select
    v-on:change="handleChange"
    v-bind:value="value"
    placeholder="Select"
    filterable
    size="small"
    :loading="loading"
    ref="villages_id"
  >
    <el-option
      v-for="item in options"
      :key="item.value"
      :label="item.label"
      :value="item.value"
    >
    </el-option>
  </el-select>
</template>

<script>
export default {
  model: {
    prop: "value",
    event: "change",
  },
  props: ["value", "districts_id"],
  data() {
    return {
      options: [],
      loading: true,
    };
  },
  methods: {
    handleChange(e) {
      console.log(e);
      this.$emit("change", e);
    },
    getVillages() {
      this.loading = true;
      axios
        .get(route("api.villages", { districts_id: this.districts_id }))
        .then((response) => {
          if (this.districts_id != "") {
            this.options = response.data.map((item) => {
              return {
                value: parseInt(item.id),
                label: item.name,
              };
            });
          } else {
            this.options = [];
          }

          !this.findVillages() && this.$emit("change", "");
          this.loading = false;
        });
    },
    findVillages() {
      return this.options.find((item) => item.value === this.value);
    },
  },
  watch: {
    districts_id: async function(val) {
      this.getVillages();
    },
  },
  mounted() {
    this.$root.$on("villages_id_required", () => {
      this.$refs.villages_id.focus();
    });
  },
};
</script>
