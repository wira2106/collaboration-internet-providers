<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">  {{ trans('companies.title.companies') }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/backend">{{ trans('core.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item">{{ trans('companies.title.companies') }} </li>
                </ol>
            </div>          
        </div>
        <div class="container">
            <div class="card">
                
                <div class="card-body">
                    <div class="sc-table">
                        <div class="tool-bar row" style="padding-bottom: 20px;">
                            <div class="actions col-8">
                                <el-button type="primary" @click.prevent="goToCreate()"><i class="el-icon-edit"></i>
                                    {{ trans('companies.button.create company') }}
                                </el-button>
                            </div>
                            <div class="search col-4">
                                <el-input prefix-icon="el-icon-search" @keyup.native="performSearch" v-model="searchQuery">
                                </el-input>
                            </div>
                        </div>

                        <el-table
                                :data="data"
                                stripe
                                style="width: 100%"
                                ref="pageTable"
                                v-loading.body="tableIsLoading"
                                @sort-change="handleSortChange">
                            <el-table-column prop="logo_perusahaan" :label="trans('companies.table.logo')" sortable="custom">
                                <template slot-scope="scope">
                                    <a @click.prevent="goToEdit(scope)" href="#">
                                        <SmallImage :url="scope.row.logo">
                                        </SmallImage>
                                    </a>
                                </template>
                            </el-table-column>
                            <el-table-column prop="name" :label="trans('companies.table.name')" sortable="custom">
                                <template slot-scope="scope">
                                    <a @click.prevent="goToEdit(scope)" href="#">
                                        {{ scope.row.name }}
                                    </a>
                                </template>
                            </el-table-column>
                            <el-table-column prop="type" :label="trans('companies.table.type')" sortable="custom">
                                <template slot-scope="scope">
                                    <a @click.prevent="goToEdit(scope)" href="#">
                                        {{ capitalizeLetter(scope.row.type) }}
                                    </a>
                                </template>
                            </el-table-column>
                            <el-table-column prop="rating" :label="trans('companies.table.rating')" sortable="custom">
                                <template slot-scope="scope">
                                    <a @click.prevent="goToEdit(scope)" href="#">
                                        <i :class="scope.row.rating > 0 ? 'el-icon-star-on' : 'el-icon-star-off'" style="font-size: 18px; color:#ff9900"></i>
                                        {{scope.row.rating}}
                                    </a>
                                </template>
                            </el-table-column>
                            <el-table-column prop="created_at" :label="trans('core.table.created at')"
                                                sortable="custom">
                            </el-table-column>
                            <el-table-column prop="actions" :label="trans('core.table.actions')">
                                <template slot-scope="scope">
                                    <el-button-group>
                                        <el-button
                                                size="mini"
                                                @click.prevent="goToEdit(scope)"
                                                v-if="scope.row.urls.edit_url">
                                            <i class="fa fa-edit"></i>
                                        </el-button>
                                        <delete-button :scope="scope" :rows="data"></delete-button>
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
        </div>
        <button v-shortkey="['c']" @shortkey="pushRoute({name: 'admin.user.users.create'})" v-show="false"></button>
    </div>
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';

    export default {
        mixins: [ShortcutHelper],
        data() {
            return {
                data: [],
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
                tableIsLoading: false,
            };
        },
        methods: {
            queryServer(customProperties) {
                const properties = {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery,
                };

                axios.get(route('admin.company.company.pagination', _.merge(properties, customProperties)))
                    .then((response) => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                        this.order_meta.order_by = properties.order_by;
                        this.order_meta.order = properties.order;
                    });
            },
            fetchData() {
                this.tableIsLoading = true;
                this.queryServer();
            },
            handleSizeChange(event) {
                console.log(`per page :${event}`);
                this.tableIsLoading = true;
                this.queryServer({ per_page: event });
            },
            handleCurrentChange(event) {
                console.log(`current page :${event}`);
                this.tableIsLoading = true;
                this.queryServer({ page: event });
            },
            handleSortChange(event) {
                console.log('sorting', event);
                this.tableIsLoading = true;
                this.queryServer({ order_by: event.prop, order: event.order });
            },
            performSearch: _.debounce(function (query) {
                console.log(`searching:${query.target.value}`);
                this.tableIsLoading = true;
                this.queryServer({ search: query.target.value });
            }, 300),
            goToEdit(scope) {
                this.$router.push({ name: 'admin.company.company.edit', params: { company: scope.row.company_id } });        
            },
            goToCreate() {
                this.$router.push({ name: 'admin.company.company.create'});
            },
            capitalizeLetter(string) {
                return string.toUpperCase();
            }
        },
        mounted() {
            this.fetchData();
        },
    };
</script>
