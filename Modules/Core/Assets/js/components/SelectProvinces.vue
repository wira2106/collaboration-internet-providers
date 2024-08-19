<template>
  <el-select
    v-on:change="handleChange"
    v-bind:value="value"
    placeholder="Select"
    filterable
    size="small"
    :loading="loading"
    ref="provinces_id"
  >
    <el-option
      v-for="item in options"
      :key="item.value"
      :label="item.label"
      :value="item.value"
      :data-id="item.value"
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
  props: ["value"],
  data() {
    return {
      options: [],
      loading: true,
    };
  },
  methods: {
    handleChange(e) {
      this.$emit("change", e);
    },
    getProvinces() {
      this.loading = true;
      axios.get(route("api.provinces")).then((response) => {
        this.options = response.data.map((item) => {
          return {
            value: parseInt(item.id),
            label: item.name,
          };
        });

        this.loading = false;
      });
    },
  },
  mounted() {
    this.getProvinces();
    this.$root.$on("provinces_id_required", () => {
      this.$refs.provinces_id.focus();
    });
  },
};
</script>
