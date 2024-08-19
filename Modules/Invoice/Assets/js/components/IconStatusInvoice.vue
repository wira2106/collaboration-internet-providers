<template>
    <span>
        <i
            :class="[className]"
            :style="`${status === 'settlement' ? 'color: #67c23a' : ''}`"
        ></i>
    </span>
</template>

<script>
export default {
    props: ['status'],
    computed: {
        className: function() {
            let className =  'fas fa-circle text-danger'
            if( this.status === 'pending') className = 'fas fa-circle text-warning'
            
            if( this.status === 'settlement' ) className = 'fas fa-circle'

            return className;
        }
    }
}
</script>

<style>

</style>