<template>
  <router-link :class="[active, classname]" :to="to" :aria-expanded="aria_expanded">
    <slot />
  </router-link>
</template>

<script>
export default {
  props: ["classname", "routename", "item", "to", "has_child", "child_url"],
  computed: {
    active: function() {
      if (this.routename == this.$route.name) {
        return "active";
      }
    },
    aria_expanded: function() {
      if(!this.has_child) return false;

      return JSON.parse(this.child_url).find(item => item == this.$route.name) ? true : false;
    }
  },
};
</script>
