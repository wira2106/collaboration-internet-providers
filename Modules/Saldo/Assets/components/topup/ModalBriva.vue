<template>
  <div>
    <div class="modal-header" style="margin-top:-65px;">
      <div class="pull-left">
        <h3>
          <b>{{
            user_roles.name == "Super Admin"
              ? trans("topups.title.konfirmasi topup")
              : trans("topups.title.detail topup")
          }}</b>
        </h3>
      </div>
    </div>
    <div class="modal-body" v-loading="modalLoading">
      <div class="row">
        <div class="col-md-12" v-if="konfirm">
          <div class="row">
            <div class="col-md-12">
              <ul class="list-group">
                <li class="list-group-item text-center" style="border: 0px;">
                  <IconStatus
                    :status="{ status: data.status, color: color, size: size }"
                  ></IconStatus>
                  <!-- <span>{{trans('saldos.table.status')}}</span> -->
                  <h4>{{ data.status }}</h4>
                  <span v-if="data.keterangan != null && data.keterangan != ''">
                    {{ data.keterangan }}
                  </span>
                  <span v-if="data.status == 'success'">
                    {{ trans("saldos.modal.success") }}
                  </span>
                </li>
                <li class="list-group-item p-0" style="border: 0px;">
                  <table class="table">
                    <tr>
                      <td style="font-size: 14px;">
                        {{trans('topups.title.amount')}}
                      </td>
                      <td>
                        <div style="padding-top:0px;">
                          <span
                            style="font-size: 14px;font-weight: 700;display: block;"
                          >
                            <b>Rp.{{ data.amount }}</b>
                          </span>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size: 14px;">
                         {{trans('topups.title.brivaNo')}}
                      </td>
                      <td>
                        <div style="padding-top:0px;">
                          <span
                            style="font-size: 14px;display: inline-block;"
                          >
                            {{ data.brivaNo+data.custCode }}
                          </span>
                          <a href="#!" @click="copyBrivaNo(data.brivaNo+data.custCode)"><i class="fa fa-copy text-primary"></i></a>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size: 14px;">
                         {{trans('topups.title.created_at')}}
                      </td>
                      <td>
                        <div style="padding-top:0px;">
                          <span
                            style="font-size: 14px;display: block;"
                          >
                            {{ data.created_at }}
                          </span>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size: 14px;">
                         {{trans('topups.title.expired_at')}}
                      </td>
                      <td>
                        <div style="padding-top:0px;">
                          <span
                            style="font-size: 14px;display: block;"
                          >
                            {{ data.expired_at }}
                          </span>
                        </div>
                      </td>
                    </tr>
                  </table>
                </li>
              </ul>
            </div>
            <div class="col-md-12" v-if="data.status != 'success'">
                <div
                    class="row text-center"
                >
                    <div class="col-md-12">
                    <el-button
                        type="danger"
                        style="width: 350px;"
                        @click="deleteBriva(data)"
                        >Hapus Transaksi</el-button
                    >
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import _ from "lodash";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import PermissionsHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";
import ClipboardHelper from '../../../../Core/Assets/js/mixins/ClipboardHelper';

export default {
  mixins: [Toast, PermissionsHelper, ClipboardHelper],
  props: ["topup_id"],
  data() {
    return {
      data: {},
      modalLoading: false,
      color: "",
      size: "95px",
      keterangan: "",
      konfirm: true,
      batal: false,
      konfirmasi: {
        topup_id: "",
        company_id: "",
        status: "",
        saldo: "",
        pengirim: "",
        keterangan: "",
      },
      rules: {
        ket: [
          {
            required: true,
            message: this.trans("saldos.validation.required", {
              field: this.trans("saldos.form.alasan"),
            }),
            trigger: "change",
          },
        ],
      },
    };
  },
  watch: {
    topup_id: function() {
      this.fetchData();
    },
  },
  methods: {
    copyBrivaNo(text){
        $('.modal-topup-saldo').append(`<input type='text' id='copy_text_command_field' value="${text}">`);
        var copyText = document.getElementById("copy_text_command_field");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        this.Toast({
            title:this.trans('core.copied'),
            icon: 'success'
        })
        $("#copy_text_command_field").remove();
    },
    fetchData() {
      this.data = this.topup_id[0];
      if (this.data.status == "success") this.color = "#67C23A";
      if (this.data.status == "pending") this.color = "#909399";
      if (this.data.status == "cancel") this.color = "#e94e11";
      this.def();
    },
    deleteBriva(data){
        Swal.fire({
            title: this.trans("topups.messages.delete topup"),
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: this.trans("core.button.confirm"),
            cancelButtonText: this.trans("core.button.cancel"),
          }).then((result) => {
            if (result.value) {
                this.modalLoading = true;
                axios.post(route('api.saldo.topup.briva.delete'), {id : data.topup_id})
                .then((response) => {
                    let data = response.data;
                    if(data.errors){
                        this.Toast({
                            icon: "error",
                            title: data.message,
                        });
                    }else{
                        this.Toast({
                            icon: "success",
                            title: data.message,
                        });
                    }
                    this.modalLoading = false;
                    this.$emit("modalResponse", "Response modal");
                }).catch((err) => {
                    this.modalLoading = false;
                    this.Toast({
                        icon: "error",
                        title: this.trans("core.error 500 title"),
                    });
                });
            }
          });
    },
    def() {
      this.modalLoading = true;
      this.batal = false;
      this.konfirm = true;
      $(".fancybox").fancybox();
      this.modalLoading = false;
    },
  },
  created(){
      window.copyToClipboard = this.copyToClipboard
  },
  mounted() {
    // $('.topup').on("hidden.bs.modal", this.def);
    this.fetchData();
    $(".fancybox").fancybox();
  },
};
</script>
