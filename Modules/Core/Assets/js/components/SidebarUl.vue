<template>
  <ul :class="[classname, class_in]">
      <slot />
  </ul>
</template>

<script>
export default {
    props:['classname', 'child_url'],
    computed: {
        class_in: function () {
            return JSON.parse(this.child_url).find(item => item == this.$route.name)  ? 'in' : '';
        }
    }
}
</script>

<style>

</style>