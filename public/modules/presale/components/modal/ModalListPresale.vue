<template>
  <bs-modal
  id="modal-list-presale"
  size="large"
  @onClose="onClose()"
  >
  <div slot="header">
      {{ trans('presales.list resource') }}
  </div>

  <div class="row">
    <div class="col-12">
        <div class="form-group">
            <label>{{ trans('presales.title.search') }}</label>
            <el-input
            size="small"
            v-model="presale.search"
            type="text"
            :placeholder="trans('presales.form.search')"
            @keyup.native="performSearch"
            >

            </el-input>                     

        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <label>{{ trans('presales.form.status') }}</label>
            <el-select
            v-model="presale.status_id"
            size="small"
            @change="queryServer"
            filterable
            >
                <el-option
                v-for="(option, index) in option_status"
                :key="index"
                :label="option.label"
                :value="option.value"
                >
                </el-option>
            </el-select>

        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
        <label>{{ trans('presales.form.class') }}</label>
        <el-select
        v-model="presale.class_id"
        size="small"
        @change="queryServer"
        filterable
        >
            <el-option
            v-for="(option, index) in option_class"
            :key="index"
            :label="option.label"
            :value="option.value"
            
            >

            </el-option>
        </el-select>
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                <label>{{ trans('endpoint.title.sidebar') }}</label>
                <el-select
                size="small"
                v-model="presale.end_point_id"
                @change="queryServer"
                filterable
                >
                    <el-option
                    v-for="(option, index) in list_endpoints"
                    :key="index"
                    :label="option.nama_end_point"
                    :value="option.end_point_id"
                    >
                    </el-option>
                </el-select>
                </div>
            </div>
        </div>
    </div>

    

    <div class="col-6" v-if="list_presales.length > 0">
        <div class="form-group">
            <button class="btn btn-sm btn-primary" @click="showSearched" type="button">{{ trans('presales.form.show') }}  
            </button>
        </div>
    </div>


    </div>

    <div class="col-12">
        <br>
        <b>{{trans('presales.form.total')}} : {{meta.total}}</b>
        <div class="content-menu-rumah-right" style="max-height: 450px;overflow-y: auto;">
            <table style='width:100%;margin-bottom:0px;' class='table table-hover table-list-rumah' v-if="list_presales.length > 0">
                <tr 
                v-for="(list, index) in list_presales"
                :key="index"
                @click="intoMarkerRumah(list.presale_id)"
                >
                    <td valign='center' style='width:12px;padding:.75rem 0 0 .30rem;padding-top:25px;' align='center'>
                        <img :src='list.icon' style='height:30px;'>
                    </td>     
                    <td style='padding:.75rem .75rem .75rem .20rem'>&nbsp; 
                        <span class='span-class' style='font-size:12px;'>
                            {{ list.class_name }}
                        </span>
                        <span class='span-class' style='font-size:8px;margin-top:4px;float:right'>
                            {{ list.created_at }}
                        </span>
                        <b style='font-size:11px;display:block;' v-html="list.gang_nomor">&nbsp; 
                            
                        </b>
                        <i class='span-alamat' style='font-size:9px;display:block;line-height:1;padding-left:5px;'>
                            {{ list.address }}
                        </i>
                    </td>
                </tr>
            </table>

            <p style="text-align:center" v-if="list_presales.length == 0 && loading == false">
                {{trans('presales.form.no data')}}
            </p>
            <center>
                <i id="loading-text-list-rumah" v-if="loading">{{trans('core.loading')}}
                </i>
            </center>

        </div>
    </div>
  </bs-modal>
</template>

<script>
import BsModalVue from './BsModal.vue'
import _ from 'lodash';
import axios from 'axios';

export default {
    props: {
        wilayah_id: {default: null}
    },
    components: {
        'bs-modal' : BsModalVue
    },
    data() {
        return {
            option_status: [],
            option_class: [],
            list_endpoints: [],
            list_presales: [],
            totalSearched: 0,
            presale: {
                search : '',
                class_id : '',
                end_point_id : '',
                site_id : '',
                status_id: ''
            },
            meta: {
                total: 0,
            },
            links: {},
            loading:false,
            requests_list:null,
            requests_total: null,
        }
    },
    methods: {
        onClose() {
            $("#modal-list-presale").modal('hide');
        },
        fetchListPresale(url = route('api.presale.presales.list')) {
            if(this.requests_list) this.requests_list.cancel()

            const cancelSource = axios.CancelToken.source();
            this.loading = true;
            let properties = {
                params: {
                    per_page: 20,
                    wilayah_id: this.wilayah_id,
                    ...this.presale
                },
                cancelToken: cancelSource.token
            }

            this.requests_list = {
                cancel: cancelSource.cancel
            }

            axios.get(url, properties)
            .then(response => {
                if(response) {

                    this.list_presales = [
                        ...this.list_presales,
                        ...response.data.data
                    ]
                    this.links = response.data.links;
                    this.totalSearched = response.data.meta.total;
                }
                this.loading = false
            })
        },
        queryServer() {
            this.list_presales = [];
            this.fetchListPresale(route('api.presale.presales.list'));
        },
        fetchTotalPresale(url = route('api.presale.presales.list'), params) {
            if(this.requests_total) this.requests_total.cancel();
            const cancelSource = axios.CancelToken.source();
            this.loading = true;
            let properties = {
                params: {
                    per_page: 20,
                    wilayah_id: this.wilayah_id
                },
                cancelToken: cancelSource.token
            }

            this.requests_total = {
                cancel: cancelSource.cancel
            }
            axios.get(url, properties)
            .then(response => {
                if(response) {
                    this.meta = response.data.meta;
                    this.totalSearched = this.meta.total;
                }
                
                this.loading = false;
            })
        },
        fetchGetClassPresale() {
            axios.get(route('api.presale.class.index'))
            .then(response => {
                this.option_class = [
                    {
                        'label': 'All',
                        value: ''
                    },
                    ...response.data
                ]
            })
            .catch(err => {
                console.log(err)
                setTimeout(() => {
                    this.fetchGetClassPresale()
                }, 2000);
            })
        },
        fetchGetStatusPresale() {
            axios.get(route('api.presale.status.index'))
            .then(response => {
                this.option_status = [
                    {
                        'label': 'All',
                        value: ''
                    },
                    ...response.data
                ]
            })
            .catch(err => {
                console.log(err)
                setTimeout(() => {
                    this.fetchGetStatusPresale()
                }, 2000);
            })
        },
        fetchListEndpoint(url = route('api.presale.endpoint.index')) {
            this.loading = true
            let properties = {
                params: {
                    wilayah_id: this.wilayah_id,
                }
            }
            axios.get(url, properties)
            .then(response => {
                this.list_endpoints = [
                    {
                        nama_end_point: 'All',
                        end_point_id: ''
                    },
                    ...response.data.data
                ]
                this.loading = false

            })
        },
        intoMarkerRumah(id) {
            $("#modal-list-presale").modal('hide')
            this.$emit('gotoMarker', id)
        },
        performSearch: _.debounce(function (query) {
            this.list_presales = []
            this.fetchListPresale(route('api.presale.presales.list'));
        }, 300),
        showSearched() {
            Swal.fire({
                title: 'Loading Presales...',
                html: '',
                allowOutsideClick: false,
                onOpen: () => {
                swal.showLoading()
                }
            });
            
            let properties = {
                params: {
                    per_page: this.totalSearched,
                    wilayah_id: this.wilayah_id,
                    ...this.presale
                }
            }

            axios.get(route('api.presale.presales.list'), properties)
            .then(response => {
                this.$emit('handleShowSearched', response.data.data);
            })
            .catch(err => {
                console.log(err)
            })
        }
        
    },
    mounted() {
        this.fetchGetClassPresale();
        this.fetchGetStatusPresale();

        $('.content-menu-rumah-right').scroll(() => {
            if($('.content-menu-rumah-right').scrollTop()+10 >= $('.table-list-rumah').height() - $('.content-menu-rumah-right').height()) {
                if(this.links.next && this.loading == false) {
                    this.fetchListPresale(this.links.next);      
                }
            }
        }); 

        $("#modal-list-presale").on('show.bs.modal', () => {
            this.queryServer();
            this.fetchTotalPresale();
        })
    },
    watch: {
        wilayah_id: function() {
            this.list_presales = [];
            this.list_endpoints = [];
            this.fetchListPresale();
            this.fetchListEndpoint();
            this.fetchTotalPresale();
        }
    }
}
</script>

<style>

</style>