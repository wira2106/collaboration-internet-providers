<template>
  <div>
    <el-select
      v-on:change="handleChange"
      v-bind:value="value"
      placeholder="Select"
      filterable
      size="small"
      :loading="loading"
      ref="districts_id"
    >
      <el-option
        v-for="item in options"
        :key="item.value"
        :label="item.label"
        :value="item.value"
      >
      </el-option>
    </el-select>
  </div>
</template>

<script>
export default {
  model: {
    prop: "value",
    event: "change",
  },
  props: ["value", "regencies_id"],
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
    getDistrict() {
      this.loading = true;
      axios
        .get(route("api.districts", { regencies_id: this.regencies_id }))
        .then((response) => {
          if (this.regencies_id != "") {
            this.options = response.data.map((item) => {
              return {
                value: parseInt(item.id),
                label: item.name,
              };
            });
          } else {
            this.options = [];
          }

          !this.findDistrict() && this.$emit("change", "");
          this.loading = false;
        });
    },
    findDistrict() {
      return this.options.find((item) => item.value === this.value);
    },
  },
  watch: {
    regencies_id: async function(val) {
      this.getDistrict();
    },
  },
  mounted() {
    this.$root.$on("districts_id_required", () => {
      this.$refs.districts_id.focus();
    });
  },
};
</script>
