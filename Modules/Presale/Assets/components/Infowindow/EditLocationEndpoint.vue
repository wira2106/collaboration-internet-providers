<template>
  <div>
      <p style="margin-bottom:0px"><b>latitude</b> : {{location.latitude}}</p>
      <p><b>longitude</b> : {{location.longitude}}</p>
      <p style="margin-bottom:0px" class="d-flex justify-content-center">
        <el-button-group>
          <el-button
          type="primary"
          icon="fa fa-save"
          circle
          @click="updateLocation"
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
  </div>
</template>

<script>
import TranslationHelper from '../../../../Core/Assets/js/mixins/TranslationHelper'
import Toast from '../../../../Core/Assets/js/mixins/Toast'
import _ from 'lodash';
import axios from 'axios';

export default {
  mixins: [TranslationHelper,Toast ],
  props: {
    btnSaveLoading:false,
    location: {
      default: {
        latitude: 0,
        longitude: 0,
        address:''
      }
    },
    end_point_id: {default:null, required:true},
    handleClose: {
      default:() => {console.log('close function not passed : handleClose')},
      required:true
    },
    handleClosePopover: {
      default:() => {console.log('close function not passed : handleClosePopover')},
      required:true
    },

  },
  methods: {
    updateLocation() {
        this.$emit('handleSubmit', true)

        let properties = {
            lat_end_point: this.location.latitude,
            lon_end_point: this.location.longitude
        }
      axios.post(route('api.presale.endpoint.update', {endpoint: this.end_point_id}), properties)
      .then(response => {
          this.Toast({
            icon:'success',
            title: response.data.message
          })
          this.$emit('updated', this.location)
      })
      .catch(err => {
          this.Toast({
            icon:'error',
            title: this.trans("core.error 500 title")
          })
      })
    },
    
  }
}
</script>

<style>

</style>