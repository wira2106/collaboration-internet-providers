<template>
    <div class="col-12">
        <div class="tool-bar row">
            <div class="actions col-8" style="font-size:14px;font-weight:400">
                Total rating : 
                <el-rate 
                    :value="total_rating / 10"
                    disabled
                    text-color="#ff9900"
                    :max="1"
                    show-score
                    :score-template="total_rating ? total_rating.toString() : '0'"
                    style="display: inline-block;"
                    >
                </el-rate>
            </div>

            <div class="search col-4">
                <el-input prefix-icon="el-icon-search" @keyup.native="performSearch" size="small" v-model="searchQuery">
                </el-input>
            </div>
            
        </div> 
        <el-table
            :data="rating"
            stripe
            style="width: 100%"
            @sort-change="handleSortChange">
            <el-table-column
                prop="full_name"
                :label="trans('companies.table.rating.rater_name')"
                width="180"
                sortable="custom">
            </el-table-column>
            <el-table-column
                prop="name"
                :label="trans('companies.table.rating.company_name')"
                width="180"
                sortable="custom">
            </el-table-column>
            <el-table-column
                prop="rating"
                width="180"
                :label="trans('companies.table.rating.rating')"
                sortable="custom">
                <template slot-scope="scope">
                    <el-rate 
                        :value="scope.row.rating / 10"
                        disabled
                        text-color="#ff9900"
                        :max="1"
                        show-score
                        :score-template="scope.row.rating.toString()"
                        >
                    </el-rate>
                </template>
            </el-table-column>
            <el-table-column
                prop="description"
                :label="trans('companies.table.rating.description')"
                sortable="custom">
            </el-table-column>
            <el-table-column
                prop="created_at"
                :label="trans('companies.table.rating.created_at')"
                sortable="custom">
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
</template>

<script>
import _ from 'lodash';

export default {
    props: ['company_id'],
    data() {
        return {
            rating: [],
            total_rating: 0,
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
        }
    },
    methods: {
        queryServer(customProperties) {
            const properties = {
                page: this.meta.current_page,
                per_page: this.meta.per_page,
                order_by: this.order_meta.order_by,
                order: this.order_meta.order,
                search: this.searchQuery,
                company:this.company_id
            };
            console.log(this.tableIsLoading)
            axios.post(route('api.company.company.rating', _.merge(properties, customProperties)))
            .then(response => {
                this.rating = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
                this.total_rating = response.data.total_rating;
            })
        },
        fetchRatingCompany(customProperties) {
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
    },
    mounted() {
        this.fetchRatingCompany()
    }
}
</script>

<style>

</style>