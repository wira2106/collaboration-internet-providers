<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">  {{ trans('slot_instalasi.title.slot_instalasi') }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/backend">{{ trans('core.breadcrumb.home') }}</a></li>
                    <li class="breadcrumb-item">{{ trans(`slot_instalasi.${pageTitle}`) }} </li>
                </ol>
            </div>                

        </div>

        <div class="container">
            <div class="card card-outline-info">
                <div class="card-header text-white">
                    {{ trans('slot_instalasi.title.slot_instalasi') }}
                    <el-button plain size="small" icon="el-icon-plus" class="pull-right" data-toggle="modal" data-target="#form_slot" @click="addSlotInstalasi()">{{trans('slot_instalasi.button.create slotinstalasi')}}</el-button>
                </div>
                <div class="card-body">
                    <div class="tool-bar row asusad" style="padding-bottom: 20px;">
                        <div class="col-12" v-if="companies.length > 0">
                            <div class="row">

                                <div class="col-4">
                                    <el-select                                              
                                        v-model="company_id"
                                        placeholder="Select"
                                        filterable
                                        size="small"
                                        @change="queryServer"
                                    >
                                        <el-option
                                        v-for="item in companies"
                                        :key="item.company_id"
                                        :label="item.name"
                                        :value="item.company_id"
                                        >
                                        </el-option>
                                    </el-select>  

                                </div>
                            </div>
                        </div>
                    </div>
                  <el-table
                    :data="slot_instalasi"
                    stripe
                    style="width: 100%"
                    v-loading.body="tableIsLoading"
                    @sort-change="handleSortChange">
                     <el-table-column prop="id" label="No" width="75" >
                        <template slot-scope="scope">
                            <a @click.prevent="goToEdit(scope)" href="#">
                                <div>{{(meta.per_page * (meta.current_page - 1)) + scope.$index+1}}</div>
                            </a>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="name"
                        :label="trans('slot_instalasi.table.name')"
                        width="180"
                        sortable="custom">
                        <template slot-scope="scope">
                            <a @click.prevent="goToEdit(scope)" href="#">
                                {{ scope.row.name }}
                            </a>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="jam_start"
                        :label="trans('slot_instalasi.table.jam_start')"
                        width="180"
                        sortable="custom">
                        <template slot-scope="scope">
                            <a @click.prevent="goToEdit(scope)" href="#">
                                {{ scope.row.jam_start }}
                            </a>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="jam_end"
                        :label="trans('slot_instalasi.table.jam_end')"
                        width="180"
                        sortable="custom">
                        <template slot-scope="scope">
                            <a @click.prevent="goToEdit(scope)" href="#">
                                {{ scope.row.jam_end }}
                            </a>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="updated_at"
                        :label="trans('slot_instalasi.table.updated_at')"
                        sortable="custom">
                        <template slot-scope="scope">
                            <a @click.prevent="goToEdit(scope)" href="#">
                                {{ scope.row.updated_at }}
                            </a>
                        </template>

                    </el-table-column>
                    <el-table-column
                        prop="action"
                        :label="trans('slot_instalasi.table.action')">
                        <template slot-scope="scope">
                            <el-button-group>
                                <el-button icon="el-icon-edit" @click="goToEdit(scope)" size="mini"> 
                                </el-button>
                                <delete-button :scope="scope" :rows="slot_instalasi" v-on:onDelete="tableIsLoading = $event" v-on:onDeleteSuccess="tableIsLoading = !$event"></delete-button>
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
        
        <div class="modal fade" id="form_slot" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="form_slotLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="form_slotLabel">{{modalTitle()}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <el-form :model="form_slot" class="w-100" ref="form_slot">
                        <div class="col-12 ">
                            <el-form-item
                                prop="name"
                                :label="trans('slot_instalasi.form.slot_name')"
                                :rules="rulesAddSlot"
                            >
                                <el-input v-model="form_slot.name" size="small"></el-input>
                            </el-form-item>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <el-time-picker
                                        v-model="form_slot.jam[0]"
                                        :picker-options="{
                                            selectableRange: selectableRangePicker
                                        }"
                                        :placeholder="trans('slot_instalasi.table.jam_start')">
                                    </el-time-picker>
                                </div>
                                <div class="col-6">
                                    <el-time-picker
                                        v-model="form_slot.jam[1]"
                                        :picker-options="{
                                            selectableRange: selectableRangePicker
                                        }"
                                        :placeholder="trans('slot_instalasi.table.jam_end')">
                                    </el-time-picker>
                                </div>
                            </div>
                        </div>
                    </el-form>
                </div>
                <div class="modal-footer">
                    <el-button
                    size="small"
                    data-dismiss="modal"
                    >
                        Close
                    </el-button>
                    <el-button
                        size="small"
                        icon="el-icon-upload"
                        type="primary"
                        :loading="btnSaveLoading"
                        @click="saveSlotInstalasi('form_slot')"
                        
                    >Save</el-button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import TranslationHelper from '../../../../Core/Assets/js/mixins/TranslationHelper';
import Toast from '../../../../Core/Assets/js/mixins/Toast';
export default {
    mixins: [TranslationHelper,Toast],
    props: {
        pageTitle: {default:null}
    },
    data() {
        return {
            company_id: null,
            companies: [],
            defaultFormSlot: {
                slot_id:null,
                name: '',
                jam: [new Date(2016, 9, 10, 8), new Date(2016, 9, 10, 17)]
            },
            form_slot: {
                slot_id:null,
                name: '',
                jam: [new Date(2016, 9, 10, 8), new Date(2016, 9, 10, 17)]
            },
            processForm: '',
            
            btnSaveLoading: false,
            value: '1231',
            slot_instalasi: [],
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
            rulesAddSlot: {
                required:true,
                message: this.trans('slot_instalasi.validation.required', {field: 'Nama slot'})
            },
            selectableRange: [],
        }
    },
    methods: {
        modalTitle(){
            return this.processForm === 'edit' ? this.trans('slot_instalasi.title.edit') : this.trans('slot_instalasi.title.create')
        },

        deleteBiayaKabel(index){
            this.slot_instalasi.data.splice(index, 1)
        },
        queryServer(customProperties) {
            const properties = {
                page: this.meta.current_page,
                per_page: this.meta.per_page,
                order_by: this.order_meta.order_by,
                order: this.order_meta.order,
                search: this.searchQuery,
                company_id:this.company_id
            };
            this.tableIsLoading = true;
            axios.post(route('api.company.slotinstalasi.index', _.merge(properties, customProperties)))
            .then(response => {
                this.slot_instalasi = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
                this.tableIsLoading = false;
                this.company_id = response.data.company_id;
                this.companies = response.data.companies;
            })
        },
        fetchSlotInstalasi() {
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
        addSlotInstalasi() {
            this.fetchDateRangeSlot();
            this.processForm = 'add';
            this.form_slot = this.defaultFormSlot            
        },
        goToEdit(scope) {
            this.processForm = 'edit'; 
            $('#form_slot').modal('show');           
            this.form_slot = {
                slot_id: scope.row.slot_id,
                name: scope.row.name,
                jam: [new Date('2021-01-01 ' + scope.row.jam_start), new Date('2021-01-01 ' + scope.row.jam_end)]
            }
        },
        dayFormatted() {
            this.form_slot.jam_start = this.form_slot.jam[0].toLocaleTimeString('it-IT');
            this.form_slot.jam_end = this.form_slot.jam[1].toLocaleTimeString('it-IT');
        },
        saveSlotInstalasi(formName) {
            
            this.dayFormatted()
            this.$refs[formName].validate(valid => {
                if(valid) {
                    this.btnSaveLoading = true;
                    let properties = {
                        ...this.form_slot,
                        company_id: this.company_id
                    }
                    axios.put(route('api.company.slotinstalasi.update'), properties)
                    .then(response => {
                        $('#form_slot').modal('hide')
                        this.btnSaveLoading = false;
                        this.queryServer()
                        this.Toast({
                          icon: "success",
                            title: response.data.message
                        })
                        this.resetFormSlot()
                    })
                    .catch(err => {
                         this.Toast({
                              icon: "error",
                              title: "error"
                          })
                        this.btnSaveLoading = false;
                    })
                }
            })
        },
        resetFormSlot() {
            this.$refs['form_slot'].resetFields();
        },
        fetchDateRangeSlot() {
            axios.get(route('api.company.slotinstalasi.get-range-time'), {company_id: this.company_id})
            .then(response => {
                this.selectableRange = response.data.data;
                this.form_slot.jam = [new Date(`2019 ${this.selectableRange[0]}`), new Date(`2019 ${this.selectableRange[1]}`)];
            })
            .catch(err => {
                setTimeout(() => {
                    this.fetchDateRangeSlot()
                }, 2000);
            })
        },
    },
    computed: {
        selectableRangePicker: function() {
            return `${this.selectableRange[0]} - ${this.selectableRange[1]}`;
        }
    },
    mounted() {
        this.fetchSlotInstalasi()
    }
}
</script>

<style>

</style>