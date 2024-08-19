<template>
  <bs-modal id="modal-list-endpoint" size="large" @onClose="onCancel()">
      <div slot="header">
          {{trans('endpoint.list resource')}}
      </div>

        <div class="row">
            <div class="col-6">
                <el-input type="text" :placeholder="trans('presales.title.search')" @keyup.native="performSearch" v-model="searchQuery" size="small" ></el-input>
            </div>
            <div class="col-6">
                <el-select
                v-model="tipe"
                size="small"
                @change="queryServer"
                >
                    <el-option
                    v-for="item in option_tipe"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                    >
                        
                    </el-option>
                </el-select>
            </div>
        </div>
        <b class="mt-2 mb-2" style="display: block;">{{ trans('presales.form.total') }} : {{meta.total}}</b>
        <div class="content-menu-odp-right" style="max-height: 450px;overflow-y: auto;">
            <table style='width:100%;margin-bottom:0px;' class='table table-hover table-list-odp' v-if="list_endpoints.length > 0">
                <tr 
                v-for="(endpoint, index) in list_endpoints"
                :key="index"
                @click="intoMarkerODP(endpoint.end_point_id)" 

                >
                <td>
                  {{ endpoint.nama_end_point }}<br>
                  <i class="wilayah-list-odp">
                      {{ endpoint.address }}
                  </i>
                </td>
              </tr>
            </table>

            <p style="text-align:center" v-if="list_endpoints.length == 0">{{ trans('presales.form.no data') }}</p>

            <center><i id="loading-text-list-odp" v-if="loading">{{trans('core.loading')}}</i></center>
        </div>
  </bs-modal>
</template>

<script>
import axios from 'axios';
import BsModalVue from './BsModal.vue'
import _ from 'lodash';

export default {
    components: {
        'bs-modal': BsModalVue
    },
    props: {
        wilayah_id: {default : null}
    },
    data() {
        return {
            list_endpoints: [],
            meta: {
                current_page: 1,
                total: 0
            },
            links: {

            },
            loading: true,
            searchQuery: '',
            option_tipe: [
                {
                    label: 'ODP',
                    value: 'ODP'
                },
                {
                    label: 'JB',
                    value: 'JB'
                },
                {
                    label: 'Fixing Slack',
                    value: 'Fixing Slack'
                },
                {
                    label: 'All',
                    value: ''
                }
            ],
            tipe: "",
            request_list: null,
            request_total: null
        }
    },
    methods: {
        onCancel() {
            $('#modal-list-endpoint').modal('hide');
        },
        fetchListEndpoint(url = route('api.presale.endpoint.index'), params) {

            if(this.request_list) this.request_list.cancel();
            const cancelSource = axios.CancelToken.source();

            this.request_list = {
                cancel: cancelSource.cancel
            }
            this.loading = true
            let properties = {
                params: {
                    wilayah_id: this.wilayah_id,
                    tipe:this.tipe,
                    ...params
                },
                cancelToken: cancelSource.token
            }
            axios.get(url, properties)
            .then(response => {
                if(response) {

                    this.list_endpoints = [
                        ...this.list_endpoints,
                        ...response.data.data
                    ]
                    this.links = response.data.links
                    this.loading = false
                }

            })
        },
        fetchTotalEndpoint(url = route('api.presale.endpoint.index')) {
            if(this.request_total) this.request_total.cancel();
            const cancelSource = axios.CancelToken.source();

            this.request_total = {
                cancel: cancelSource.cancel
            }
            this.loading = true
            let properties = {
                params: {
                    wilayah_id: this.wilayah_id,
                },
                cancelToken: cancelSource.token
            }
            axios.get(url, properties)
            .then(response => {
                if(response) {
                    this.meta = response.data.meta
                    this.loading = false   
                }

            })
        },
        intoMarkerODP(id) {
            this.$emit("gotoMarker", id)
        },
        performSearch: _.debounce(function (query) {
            this.list_endpoints = []
            this.fetchListEndpoint(route('api.presale.endpoint.index'), {
                search : query.target.value
            });
        }, 300),
        queryServer() {
            this.list_endpoints = [];
            this.fetchListEndpoint();
        }

    },
    watch: {
        wilayah_id: function(value) {
            this.list_endpoints = [];
            this.fetchListEndpoint();
            this.fetchTotalEndpoint();
        }
    },
    mounted() {
        $('#modal-list-endpoint').on('show.bs.modal', () => {
            this.queryServer();
        });

        $('.content-menu-odp-right').scroll(() =>  {
            if($('.content-menu-odp-right').scrollTop()+10 >= $('.table-list-odp').height() - $('.content-menu-odp-right').height()) {
                if(this.links.next && this.loading == false) {
                    this.fetchListEndpoint(this.links.next);
                }
            }
        });
    }
}
</script>

<style>

</style>