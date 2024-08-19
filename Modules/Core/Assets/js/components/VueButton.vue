
<template>
    <span v-if="isLoading">
        loading
    </span>
</template>


<script>
    export default {
        props: {
            isLoading : {type:Boolean}
        },
        data() {
            console.log(this.isLoading)
        }
    }
</script>