<template>
  <div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h5 class="card-title text-white">{{ trans('pengajuans.step.pengajuan') }}</h5>
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
      fetchData(intoPaket=0){
        console.log("step pengajuan");
         this.loading = true;
              this.required_pass = false;
              let routeUri = route('api.wilayah.pengajuan.detail', { id: this.id });
              axios.post(routeUri)
                  .then((response) => {
                      if(response.data != null){                              
                        let data = response.data.data;                              
                        this.formDetail = data;
                        this.statusNow  = data.status;
                      }
                    this.loading = false;
                    
                    // menuju ke tab paket
                    if (intoPaket == 1) {
                      Swal.fire(this.trans('pengajuans.messages.empty paket_isp'),"","warning");
                      this.activeTab = "paket_isp";
                    }
                  })
                  .catch(error => {
                    if (error.response.status === 403) {

                      this.$notify.error({
                          title: this.trans('core.unauthorized'),
                          message: this.trans('core.unauthorized-access'),
                      });
                      window.location = route('dashboard.index');
                  }
                      this.loading = false;
                  });
      },
      
    },
    mounted(){
      this.getRolesPermission();
      this.fetchData();
    }
  }
</script>