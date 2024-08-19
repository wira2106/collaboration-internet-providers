<template>
  <el-select
    v-on:change="handleChange"
    v-bind:value="value"
    placeholder="Select"
    filterable
    size="small"
    :loading="loading"
    ref="regencies_id"
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
  props: ["value", "provinces_id"],
  data() {
    return {
      oldProvincesId: this.provinces_id,
      options: [],
      loading: true,
    };
  },
  methods: {
    handleChange(e) {
      this.$emit("change", e);
    },
    getRegencies() {
      this.loading = true;
      axios
        .get(route("api.regencies", { provinces_id: this.provinces_id }))
        .then((response) => {
          if (this.provinces_id != "") {
            this.options = response.data.map((item) => {
              return {
                value: parseInt(item.id),
                label: item.name,
              };
            });
          } else {
            this.options = [];
          }

          !this.findRegencies() && this.$emit("change", "");
          this.loading = false;
        });
    },
    findRegencies() {
      return this.options.find((item) => item.value === this.value);
    },
  },
  watch: {
    provinces_id: function(val) {
      this.getRegencies();
    },
  },
  mounted() {
    this.$root.$on("regencies_id_required", () => {
      this.$refs.regencies_id.focus();
    });
  },
};
</script>
