<template>
  <bs-modal id="modal-form-odp">
    <div slot="header">{{trans('endpoint.edit resource')}}</div>
    <el-form 
    :model="form_edit_odp" 
    ref="form_edit_odp"
    v-loading="formIsLoading"
    
    >
        <el-form-item :label="trans('endpoint.form.name')"
                    prop="nama_end_point"
                    :rules="rules.nama_end_point"
                    @keyup.native="checkEndpointName">
        <el-input v-model="form_edit_odp.nama_end_point" size="small"></el-input>
        </el-form-item>
        <el-form-item :label="trans('endpoint.form.type')"
                    prop="tipe"
                    :rules="rules.tipe">
            <el-select v-model="form_edit_odp.tipe" placeholder="Select" size="small">
            <el-option
                v-for="item in optionType"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
            </el-select>
        </el-form-item>
        
        <div class="row">
            <div class="col-12 controls">
                <el-form-item :label="trans('endpoint.form.address')"
                        prop="address"
                        :rules="rules.address">
            <el-input v-model="form_edit_odp.address" size="small"></el-input>
            </el-form-item>

            </div>
        </div>
    </el-form>

    <div slot="footer">
        <el-button
        size="small"
        data-dismiss="modal"
        >
            {{trans('core.button.close')}}
        </el-button>
        <el-button type="primary" size="small" @click="saveEndpoint" :loading="buttinSaveIsLoading" >
            {{trans('core.save')}}
        </el-button>

    </div>
    </bs-modal>
</template>

<script>
import axios from 'axios'
import Toast from '../../../Core/Assets/js/mixins/Toast'

export default {
    mixins: [Toast],
    props: {
        company_id: {
            default: null,
            required:true
        },
        end_point_id: {
            default: null,
            required:true
        },
        location: {
            default: () => {
                return {
                    latitude: 0,
                    longitude: 0,
                    address:''
                }
            }
        },
        wilayah_id: {
            default: null,
        },
        handleClose: {
            default:() => {console.log('close function not passed : handleClose')}
        },
        handleClosePopover: {
            default:() => {console.log('close function not passed : handleClosePopover')}
        },
    },
    data() {
        let validationName = (rules, value, cb) => {
            if(value == '') return cb(new Error(this.trans('endpoint.validation.required', {field:'name'})))
            if(!this.isValidName) return cb(new Error(this.trans('endpoint.validation.name'))) 
            cb();
        }
        return {
            formIsLoading: true,
            isValidName: false,
            buttinSaveIsLoading: false,
            form_edit_odp: {
                nama_end_point:'',
                tipe: '',
                address: ''
            },
            optionType: [
            {
                value: 'ODP',
                label: 'ODP'
            },
            {
                value: 'JB',
                label: 'Joint Box'
            },
            {
                value: 'Fixing Slack',
                label: 'Fixing Slack',
            },
            ],
            rules: {
                nama_end_point:[
                    {
                    required:true,
                    validator: validationName, 
                    trigger:'blur'
                    }
                ],
                tipe:[
                    {
                    required:true, 
                    message: this.trans('endpoint.validation.required', {field:'type'}), 
                    trigger:'change'
                    }
                ],
                address: [
                    {
                    required:true,
                    message: this.trans('endpoint.validation.required', {field:'address'}), trigger:'blur'
                    }
                ]
            },
        }
    },
    methods: {
        checkEndpointName: _.debounce(function() {
            let properties = {
            company_id: this.company_id,
            name: this.form_edit_odp.nama_end_point,
            end_point_id: this.end_point_id
            }
    
            if(!this.form_edit_odp.nama_end_point) return this.isValidName = false;
    
            axios.post(route('api.presale.endpoint.check-name'), properties)
            .then(response => {
                this.isValidName = response.data.valid;
                this.$refs['form_edit_odp'].validateField('nama_end_point');
            })
        }, 500),
        
        fetchEndpoint() {
            this.formIsLoading = true;
            axios.post(route('api.presale.endpoint.detail', {endpoint: this.end_point_id}))
            .then(response => {
                this.form_edit_odp = response.data;
                this.formIsLoading = false;
                this.checkEndpointName()
            })
            .catch(err => {
                console.log(err)
            })
        },
      saveEndpoint() {
          var vm = this;
        this.$refs['form_edit_odp'].validate(valid => {
            if(valid) {
                this.buttinSaveIsLoading = true;
                let properties = {}
                if(!this.end_point_id) {
                    properties = {
                        lat_end_point: this.location.latitude,
                        lon_end_point: this.location.longitude,
                        wilayah_id: this.wilayah_id
                    }
                }
            
                
                axios.post(this.getRoute(), _.merge(this.form_edit_odp, properties))
                .then(response => {
                    this.buttinSaveIsLoading = false;
                    this.Toast('', response.data.message, 'success')
                    $("#modal-form-odp").modal('hide');
                    vm.$emit('handleSuccess', true)
                    setTimeout(() => {
                        vm.$emit('handleClose', true)
                    }, 500)
                })
                .catch(err => {
                    this.Toast('', 'something wrong', 'error')
                })

            }
        })
        },
      getRoute() {
        if(this.end_point_id) {
            return route('api.presale.endpoint.update', { endpoint: this.end_point_id });
        }

        return route('api.presale.endpoint.store');
      }
    },
    watch: {
        end_point_id: function() {
            this.isValidName = false;
            this.fetchEndpoint()
        }
    }
}
</script>

<style>

</style>