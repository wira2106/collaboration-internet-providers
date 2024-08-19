<template>
  <div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h5 class="card-title text-white">{{ trans('pengajuans.step.konfirmasiISP') }}</h5>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                    
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  import axios from 'axios';  
  import ShortcutHelper from '../../../../../../Core/Assets/js/mixins/ShortcutHelper';
  import TranslationHelper from '../../../../../../Core/Assets/js/mixins/TranslationHelper';
  import UserRolesPermission from '../../../../../../Core/Assets/js/mixins/UserRolesPermission';
  import Toast from '../../../../../../Core/Assets/js/mixins/Toast';

  export default {
    mixins: [UserRolesPermission,Toast],
    props: ['pelanggan_id'],
    data() {
        return {
           
        }
    },
    methods: {
      fetchData(){
        console.log("step konfirmasi ISP");
      },
      
    },
    mounted(){
      this.getRolesPermission();
      this.fetchData();
    }
  }
</script>