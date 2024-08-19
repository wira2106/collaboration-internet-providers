<template>
  <li :class="[active,classname]">
    <slot />
  </li>
</template>

<script>
export default {
  props: ["classname", "routename", "item", "child_url", "has_child"],
  mounted() {
  },
  computed: {
    active: function() {
      let check = JSON.parse(this.child_url).find(item => item == this.$route.name) || this.routename == this.$route.name ? 'active' : ''
      

      return check;
    },
  },
};
</script>
