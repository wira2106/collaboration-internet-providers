<template>
  <div>
      <p style="margin-bottom:0px"><b>latitude</b> : {{location.latitude}}</p>
      <p><b>longitude</b> : {{location.longitude}}</p>
      <p style="margin-bottom:0px" class="d-flex justify-content-center">
        <el-button-group>
          <el-button
          type="primary"
          icon="el-icon-plus"
          circle
          @click="openFormEndpoint"
          >
          </el-button>
          <el-button
          type="danger"
          icon="el-icon-close"
          circle
          @click="handleClose"
          >
          </el-button>
        </el-button-group>
      </p>

      <div class="modal fade" id="addEndpoint" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addEndpointLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="addEndpointLabel">{{trans('endpoint.create resource')}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <el-form :model="region" ref="region">
                    <el-form-item :label="trans('endpoint.form.name')"
                                prop="nama_end_point"
                                :rules="rules.nama_end_point"
                                @keyup.native="checkEndpointName">
                    <el-input v-model="region.nama_end_point" size="small"></el-input>
                    </el-form-item>
                    <el-form-item :label="trans('endpoint.form.type')"
                                prop="tipe"
                                :rules="rules.tipe">
                      <el-select v-model="region.tipe" placeholder="Select" size="small">
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
                        <el-input v-model="region.address" size="small"></el-input>
                        </el-form-item>

                      </div>
                    </div>

                </el-form>
              </div>
              <div class="modal-footer">
                <el-button
                      size="small"
                      icon="el-icon-upload"
                      type="primary"
                      :loading="btnSaveLoading"
                      @click="saveEndpoint('addEndpoint')"
                      
                  >Save</el-button>
                  <el-button
                  size="small"
                  data-dismiss="modal"
                  >
                      Close
                  </el-button>
                  
              </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import _ from 'lodash';
import TranslationHelper from '../../../../../../Core/Assets/js/mixins/TranslationHelper';
import Toast from '../../../../../../Core/Assets/js/mixins/Toast';

export default {
  mixins: [TranslationHelper,Toast ],
  props: {
    endpoint: {
        default: []
    },
    endpoint_index: {
        default:null
    },
    location: {
      default: {
        latitude: 0,
        longitude: 0,
        address:''
      }
    },
    handleClose: {
      default:() => {console.log('close function not passed : handleClose')},
      required:true
    },
    handleClosePopover: {
      default:() => {console.log('close function not passed : handleClosePopover')},
      required:true
    },

  },
  data() {
    let validationName = (rules, value, cb) => {
      if(value == '') return cb(new Error(this.trans('endpoint.validation.required', {field:'name'})))
      if(!this.isValidName) return cb(new Error(this.trans('endpoint.validation.name'))) 
      cb();
    }
    return {
      isValidName: false,
      btnSaveLoading:false,
      region: {
        nama_end_point:'',
        tipe: '',
        address: this.location.address
      },
      rules: {
        nama_end_point:[
            {
              required:true,
              validator: validationName, 
              trigger:'change'
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
      ]
    }
  },
  methods: {
    openFormEndpoint() {
      // console.log('executed')
      $("#addEndpoint").modal('show')
      this.$emit('addButtonClicked',true)
      // this.handleClosePopover(false);
    },
    saveEndpoint() {
      this.$refs['region'].validate(valid => {
        if(valid) {
          Swal.fire({
              title: this.trans('core.messages.confirmation-form'),
              text: "",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: this.trans('core.button.confirm'),
              cancelButtonText: this.trans('core.button.cancel')
          }).then((result) => {
            if(result.value) {
              this.btnSaveLoading = true;
              let properties = {
                ...this.region,
                position: {
                    lat: this.location.latitude,
                    lng: this.location.longitude
                },
                visibility:true
              }
              
                this.$emit('handleSuccess', properties);
                $("#addEndpoint").modal('hide')

            }
          })
        }
      })
      
    },
    checkEndpointName: _.debounce(function() {
        console.log(this.findEndpointByName(this.region.nama_end_point), this.endpoint)
        if(this.findEndpointByName(this.region.nama_end_point)) {
            this.isValidName = false;
        } else {
            this.isValidName = true;

        }
        this.$refs['region'].validateField('nama_end_point');
    }, 500),
    findEndpointByName(name) {
        return this.endpoint.find((item, index) => {
            return item.nama_end_point === name && index != this.endpoint_index
        })
    },
  },
  watch: {
    location:function(value) {
      this.region.address = value.address
    },
    endpoint_index: function(value) {
        this.checkEndpointName()
    }
  },
  mounted() {
    let vm = this;
  }
}
</script>

<style>

</style>