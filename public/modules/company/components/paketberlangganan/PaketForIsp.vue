<template>
    <div>
        <div class="row">
        <div class="col-md-12">
            <div class="btn-group pull-left" style="margin: 0 15px 15px 0;">
                    <router-link :to="{name: 'admin.company.paketforisp.create'}">
                        <el-button type="primary" size="small">
                            <i class="fa fa-edit"></i> {{ trans('paketberlangganans.button.create paketberlangganan') }}
                        </el-button>
                    </router-link>
            </div>
            <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <el-input prefix-icon="el-icon-search" size="small" @keyup.native="performSearch" v-model="searchQuery"></el-input>
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <el-table
                :data="paket"
                stripe
                style="width: 100%"
                v-loading.body="tableIsLoading"
                @sort-change="handleSortChange">
                <el-table-column label="No" width="75" sortable="custom">
                    <template slot-scope="scope" > 
                        {{(meta.per_page * (meta.current_page - 1)) + scope.$index+1}}
                    </template>
                </el-table-column>
                <el-table-column prop="name"
                :label="trans('paketberlangganans.table.nama company')" sortable="custom">
                    <template slot-scope="scope">
                        <a href="#" @click.privent="goToEdit(scope)">
                            {{scope.row.name}}
                        </a>
                    </template>
                </el-table-column>
                <el-table-column prop="nama_paket"
                :label="trans('paketberlangganans.table.nama paket')" sortable="custom">
                    <template slot-scope="scope">
                        <a href="#" @click.privent="goToEdit(scope)">
                            {{scope.row.nama_paket}}
                        </a>
                    </template>
                </el-table-column>
                <el-table-column prop="biaya_otc"
                :label="trans('paketberlangganans.table.biaya otc')" sortable="custom">
                    <template slot-scope="scope">
                            Rp. {{scope.row.biaya_otc}}
                    </template>
                </el-table-column>
                <el-table-column prop="harga_paket"
                :label="trans('paketberlangganans.table.harga paket')" sortable="custom">
                    <template slot-scope="scope">
                            Rp. {{scope.row.harga_paket}}
                    </template>
                </el-table-column>
                <el-table-column prop="sla"
                :label="trans('paketberlangganans.table.sla')" sortable="custom">
                    <template slot-scope="scope">
                            {{scope.row.sla}} %
                    </template>
                </el-table-column>

                <el-table-column prop="actions"
                :label="trans('paketberlangganans.table.action')" sortable="custom">
                        <template slot-scope="scope">
                        <el-button-group>
                            <edit-button
                                    :to="{name: 'admin.company.paketberlangganan.edit', params: {paketberlangganan: scope.row.paket_id}}"></edit-button>
                            <delete-button :scope="scope" :rows="paket"></delete-button>
                        </el-button-group>
                        </template>
                </el-table-column>
            </el-table>
            <div class="pagination-wrap" style="text-align: center; padding-top: 20px;">
                <el-pagination
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :current-page.sync="meta.current_page"
                        :page-sizes="[10, 20, 50, 100]"
                        :page-size="parseInt(meta.per_page)"
                        layout="total, sizes, prev, pager, next, jumper"
                        :total="meta.total">
                </el-pagination>
            </div>
            </div>
        </div>

    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import _ from 'lodash';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';

    export default {
        data(){
            return {
                paket:[],
                
                meta: {
                    current_page: 1,
                    per_page: 10,
                },
                order_meta: {
                    order_by: '',
                    order: '',
                },
                links: {},
                searchQuery: '',
                tableIsLoading:false,
                loading:false,
            }
        },
        methods:{
            queryServer(customProperties){
                const properties = {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery,
                };
                axios.get(route('api.company.paketforisp.index',_.merge(properties, customProperties)))
                .then((response)=>{                    
                    this.tableIsLoading = false;
                    this.paket = response.data.data;
                    this.meta = response.data.meta;
                    this.link = response.data.link;
                    this.order_meta.order_by = properties.order_by;
                    this.order_meta.order = properties.order;
                });
                                
            },
            fetchData(){
                this.tableIsLoading = true;
                this.queryServer();
            },
            handleSizeChange(event) {                
                this.tableIsLoading = true;
                this.queryServer({ per_page: event });
            },
            handleCurrentChange(event) {                
                this.tableIsLoading = true;
                this.queryServer({ page: event });
            },
            handleSortChange(event) {                
                this.tableIsLoading = true;
                this.queryServer({ order_by: event.prop, order: event.order });
            },
            performSearch: _.debounce(function (query) {                
                this.tableIsLoading = true;
                this.queryServer({ search: query.target.value });
            }, 300),
            goToEdit(scope) {
                this.$router.push({name:'admin.company.paketberlangganan.edit', params: { paketberlangganan: scope.row.paket_id }});          
            }
        },
        mounted(){
            this.fetchData();
        }
    }
</script>