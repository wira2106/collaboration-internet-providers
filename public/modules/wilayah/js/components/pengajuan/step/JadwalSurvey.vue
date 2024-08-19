<template>
  <div>

  </div>
</template>
<script type="text/javascript">
  import axios from 'axios';  
  import ShortcutHelper from '../../../../../Core/Assets/js/mixins/ShortcutHelper';
  import TranslationHelper from '../../../../../Core/Assets/js/mixins/TranslationHelper';
  import UserRolesPermission from '../../../../../Core/Assets/js/mixins/UserRolesPermission';
  import Toast from '../../../../../Core/Assets/js/mixins/Toast';

  export default {
    mixins: [UserRolesPermission,Toast],
    props: ['pelanggan_id'],
    data() {
        return {
           
        }
    },
    methods: {
      fetchData(){
        this.loadingPengajuan = true;
        this.btnbatalSchedule = false;
        if (this.pelanggan_id != 0) {
          axios.get(route('api.pelanggan.form.jadwal.survey',{pelanggan_id:this.pelanggan_id}))
          .then((response) => {
            let data = response.data.data;
            this.history = data;
            this.form = data[data.length-1];
            this.loadingPengajuan = false;
            this.checkDisplayButtonRechedule();
          });
        }
      },
      
    },
    mounted(){
      this.getRolesPermission();
      this.fetchData();
    }
  }
</script>