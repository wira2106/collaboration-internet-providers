<template>
  <div class="modal" tabindex="-1">
    <div :class="['modal-dialog',sizeModal]">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <slot name="header"></slot>
                </h5>
                <button type="button" @click="onCloseClicked()" class="close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <slot></slot>
            </div>
            <div class="modal-footer" v-if="$slots.footer">
                <slot name="footer"></slot>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'bs-modal',
    props:['id'],
    methods: {
        onCloseClicked() {
            this.$emit('onClose', true)
        }
    },
    props: {
        size: {
            default: ''
        }
    },
    computed: {
        sizeModal: function() {
            let className = '';
            if(this.size === 'large') className = 'modal-lg';
            if(this.size === 'small') className = 'modal-sm';

            return className;
        }
    },
    mounted() {
        
    }
}
</script>

<style>

</style>