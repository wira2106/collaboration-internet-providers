<template>
    <div class="btn-group btn-group-pengaturan">
     
        <button type="button" class="btn btn-default btn-list-pengaturan" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-cog"></i>
        </button>
        <div class="dropdown-menu" style="transform: translate3d(-120px, 160px, 0px)!important;min-width:0px!important;">
            <h6 class="dropdown-header" style="text-align:center">{{ trans('presales.title.sidebar') }}</h6>
            <a class="dropdown-item">
                <div class="col-12">
                    <div class="row justify-content-center">
                    <label class="switch">
                        <input type="checkbox" id="CheckBoxRumah" v-model="presale" @change="showHideRumah()">
                    <span class="slider round-2"></span>
                    </label>
                    </div>
                </div>
            </a>

            <h6 class="dropdown-header" style="text-align:center" >{{ trans('endpoint.title.sidebar') }}</h6>
            <a class="dropdown-item" >
                <div class="col-12">
                    <div class="row justify-content-center">
                    <label class="switch">
                        <input type="checkbox" v-model="endpoint" @change="showHideEndpoint()">
                        <span class="slider round-2"></span>
                    </label>
                    </div>
                </div>
            </a>

            <h6 class="dropdown-header" style="text-align:center" v-if="canSendConfirmationEmail">{{ trans('presales.konfirmasi.title') }}</h6>
            <a class="dropdown-item" v-if="canSendConfirmationEmail">
                <div class="col-12">
                    <div class="row justify-content-center">
                        <el-button
                        type="primary"
                        icon="mdi mdi-email"
                        @click="send_confirmation"
                        size="small"
                        >
                            {{ trans('core.button.send') }}

                        </el-button>
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>

<script>
import PermissionsHelper from '../../../../Core/Assets/js/mixins/PermissionsHelper';
import Toast from '../../../../Core/Assets/js/mixins/Toast';

export default {
    mixins:[Toast, PermissionsHelper],
    data() {
        return {
            presale: true,
            endpoint: true
        }
    },
    props: {
        showed: {
            default:() => {
                return [];
            }
        },
        marker: {
            default: () => {
                return {}
            }
        },
        map: {
            default: null
        },
        wilayah_id: {
            default: null
        }
    },
    methods: {
        showHideRumah() {
            if (this.presale) {
                Object.entries(this.marker).forEach(([key, val]) => {
                    this.marker[key].setMap(this.map);

                })
            }else{
                $.each(this.marker, (key, val) => {
                    if(this.showed.indexOf(parseInt(key)) === -1) {
                        this.marker[key].setMap(null);
                    }
                });

            }
        },
        showHideEndpoint() {
            this.$emit('handleSwitchEndpoint', this.endpoint);
        },
        send_confirmation() {
            this.$refs.progress.start()
            axios.post(route('api.presale.presales.email_confirmation', {wilayah : this.wilayah_id}))
            .then(response => {
                 
                this.Toast({
                    icon: 'success',
                    title: response.data.messages
                })
            })
        }
    },
    computed: {
        canSendConfirmationEmail() {
            return this.hasAccess('presale.presales.create');
        }
    }
}
</script>

<style>

</style>