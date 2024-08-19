<template>
  <div>
    <!-- CARD INFORMASI JADWAL SURVEY YANG DIPILIH -->
    <div class="card card-outline-info" v-if="form.status == 'accept'">
      <div class="card-header" style="border-bottom: 0px">
        <div class="card-actions">
          <a
            v-show="history.length > 1"
            href="#!"
            class="text-white"
            @click="seeHistoryPengajuanSurvey"
            ><u>{{ trans("pengajuanjadwal.see history") }}</u></a
          >
          <a data-action="collapse"><i class="ti-minus text-white"></i></a>
        </div>
        <h5 class="text-white">{{trans('pengajuanjadwal.survey.tanggal dipilih')}}</h5>
      </div>
      <div class="card-body collapse show">
        <div class="row">
          <div class="col-md-12">
            <div class="d-flex justify-content-center">
              <h4 class="text-primary">{{ tanggal_survey }}</h4>
            </div>
          </div>
          <!-- tombol ajukan reschedule -->
          <div
            class="col-md-12 pt-2 tombol-ajukan-rechedule"
            v-if="user_company.type == 'osp' && buttonRechedule"
          >
            <div class="row">
              <div class="col-md-12">
                <div class="text-center">
                  <button
                    type="button"
                    class="btn btn-primary"
                    @click="recheduleJadwal()"
                  >
                    {{trans('pengajuanjadwal.button.add reschedule')}}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- card footer and button save -->
    </div>
  </div>
</template>

<script type="text/javascript">
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [Toast, PermissionsHelper],
  props: ["response", "pelanggan_id"],
  data() {
    return {
      loadingPengajuan: false,
      tanggal_survey: null,
      form: {
        rekomendasiExist: false,
        jam_rekomendasi: "08:00",
        tgl_rekomendasi: "",
        keterangan: "",
        status: "",
        list: [],
      },
      history: [],
      buttonRechedule: true,
    };
  },
  methods: {
    fetchPengajuanSurvey() {
      let data = this.response.data.data;
      this.buttonRechedule = this.response.buttonRechedule;
      this.history = data;
      if (data.length != 0) {
        this.form = data[data.length - 1];
        let vm = this;
        this.form.list.forEach((val) => {
          if (val.status == "active") {
            vm.tanggal_survey = val.full_name;
            return false;
          }
        });
      }
    },
    seeHistoryPengajuanSurvey() {
      $(".history-pengajuan-survey").modal();
    },
    recheduleJadwal() {
      this.$emit("rechedule");
    },
  },
  mounted() {
    this.fetchPengajuanSurvey();
  },
};
</script>
