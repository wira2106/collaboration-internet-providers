<template>
    <el-button type="danger" @click="deleteRow" size="mini"><i class="fa fa-trash"></i></el-button>
</template>

<script>
    import Toast from '../mixins/Toast';
    export default {
        mixins: [Toast],
        props: {
            rows: { default: null },
            scope: { default: null },
        },
        data() {
            return {
                deleteMessage: '',
                deleteTitle: '',
            };
        },
        methods: {
            deleteRow(event) {
                Swal.fire({
                  title: this.deleteTitle,
                  text: this.deleteMessage,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: this.trans('core.button.confirm'),
                  cancelButtonText: this.trans('core.button.cancel'),
                  onClose: () => {this.$emit('onDeleteSuccess', true)}
                }).then((result) => {
                  if (result.value) {
                    this.$emit('onDelete', true)
                    axios.delete(this.scope.row.urls.delete_url)
                        .then((response) => {
                            if (response.data.errors === false) {

                                this.$emit('onDeleteSuccess', true)
                                this.rows.splice(this.scope.$index, 1);
                                this.Toast({
                                    icon: "success",
                                    title: response.data.message
                                })
                                
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                            this.$emit('onDeleteSuccess', true)
                            this.Toast({
                                icon: "error",
                                title: this.trans("core.error 500 title")
                            })
                        });
                  }
                })
            },
        },
        mounted() {
            this.deleteMessage = this.trans('core.modal.confirmation-message');
            this.deleteTitle = this.trans('core.modal.title');
        },
    };
</script>
