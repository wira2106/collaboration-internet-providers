<template>
  <div>
      <slot />
  </div>
</template>

<script>
export default {
    mounted() {
        $('a[data-action="collapse"]').on("click", function(i) {
            i.preventDefault(), $(this).closest(".card").find('[data-action="collapse"] i').toggleClass("ti-minus ti-plus"), 
            $(this).closest(".card").children(".card-body").collapse("toggle");
        }), $('a[data-action="expand"]').on("click", function(i) {
            i.preventDefault(), $(this).closest(".card").find('[data-action="expand"] i').toggleClass("mdi-arrow-expand mdi-arrow-compress"), 
            $(this).closest(".card").toggleClass("card-fullscreen");
        }), $('a[data-action="close"]').on("click", function() {
            $(this).closest(".card").removeClass().slideUp("fast");
        });
    }
}
</script>

<style>

</style>