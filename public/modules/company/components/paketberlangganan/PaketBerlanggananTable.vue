<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">  {{ trans('paketberlangganans.title.paketberlangganans') }} </h3> 
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/backend"> 
                            {{ trans('core.breadcrumb.home') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item"> {{ trans('paketberlangganans.title.paketberlangganans') }} </li>
                </ol>
            </div>            
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline-info">
                        <div class="card-header text-white">
                            {{ trans('paketberlangganans.title.paketberlangganans') }}
                            <router-link :to="{name: 'admin.company.paketberlangganan.create'}">
                            <el-button plain size="small" icon="el-icon-plus" class="pull-right">{{trans('paketberlangganans.button.create paketberlangganan')}}</el-button>
                            </router-link>
                        </div>
                        <ListPaket>
                            
                        </ListPaket>
                        <!-- /.box -->
                    </div>
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
    import ListPaket from './ListPaket';
    import PaketForIsp from './PaketForIsp';

    export default{
        mixins: [ShortcutHelper],
        components :{
            'ListPaket': ListPaket,
            'PaketForIsp': PaketForIsp
        },
        data(){
            return {
                activeName: 'first',
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
                tableIsLoading:false
            }
        }
    }
</script>
