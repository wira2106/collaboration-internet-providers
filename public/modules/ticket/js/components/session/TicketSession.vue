<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("tickets.title.view ticket") }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/backend">
              {{ trans("core.breadcrumb.home") }}
            </a>
          </li>
          <li class="breadcrumb-item">{{ trans("tickets.title.tickets") }}</li>
        </ol>
      </div>
    </div>
    <div class="container" v-loading="loading">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-4">
              <div class="ticket-top">
                <table style="width: 100%">
                  <tr>
                    <td>{{ trans("tickets.label.ticket id") }}</td>
                    <td>:</td>
                    <td>#{{ data.code }}</td>
                  </tr>
                  <tr>
                    <td>{{ trans("tickets.label.status") }}</td>
                    <td>:</td>
                    <td>
                      <b
                        v-if="data.status == 'open'"
                        class="status-ticket open"
                        >{{ trans("tickets.span status.open") }}</b
                      >
                      <b
                        v-if="data.status == 'closed'"
                        class="status-ticket status-closed"
                        >{{ trans("tickets.span status.closed") }}</b
                      >
                      <b
                        v-if="data.status == 'reply_admin'"
                        class="status-ticket text-primary"
                        >{{ trans("tickets.span status.reply admin") }}</b
                      >
                      <b
                        v-if="data.status == 'reply_isp'"
                        class="status-ticket text-primary"
                        >{{ trans("tickets.span status.reply isp") }}</b
                      >
                      <b
                        v-if="data.status == 'reply_osp'"
                        class="status-ticket text-primary"
                        >{{ trans("tickets.span status.reply osp") }}</b
                      >
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="col-md-8" style="padding-top: 25px">
              <center>
                <div
                  class="btn-group w-25"
                  v-if="data.status != 'closed' && user_company.type == 'isp'"
                >
                  <button
                    type="button"
                    @click="closeTicketSupport()"
                    class="btn btn-danger btn-sm"
                  >
                    {{ trans("tickets.button.close ticket") }}
                  </button>
                </div>
              </center>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <hr />
        </div>
        <div class="col-md-12">
          <div class="card" style="margin-bottom: 10px">
            <div class="card-body">
              <h5 @click="fetchMessages()">{{ data.subject }}</h5>
            </div>
          </div>
        </div>

        <!-- card informasi SLA dan approve -->
        <!-- <information_sla v-if="load_data" :data="data" /> -->

        <!-- card accept jam mati SLA pelanggan -->
        <accept_sla
          v-if="load_data"
          :data="data"
          @saveAcceptSLA="fetchData(1)"
        />

        <div class="col-md-12">
          <div class="message-chat" v-loading="loading_chat">
            <div class="chat-body">
              <div class="chat-message-body">
                <div
                  v-for="(val, index) in data.messages"
                  :key="index"
                  :class="'message ' + setClassTicketSupport(val)"
                >
                  <img
                    alt=""
                    class="img-circle medium-image"
                    :src="val.foto_profile"
                  />

                  <div class="message-body">
                    <div class="message-info">
                      <h4>{{ val.nama_user }}</h4>
                      <h5><i class="fas fa-clock"></i> {{ val.created_at }}</h5>
                      <br />
                      <span class="span-company">{{
                        val.isp_id != null ? val.isp_name : val.osp_name
                      }}</span>
                    </div>
                    <hr />
                    <div class="message-text">
                      {{ val.message }}
                    </div>
                    <div
                      class="image-preview-div"
                      style="padding: 5px"
                      v-if="val.attachments.length > 0"
                    >
                      <a
                        :href="value"
                        class="fancybox"
                        :rel="'group' + index"
                        v-for="(value, i) in val.attachments"
                        :key="i"
                      >
                        <div
                          class="image-preview attachments-image"
                          :style="{
                            'background-image': 'url(' + value + ')',
                          }"
                        ></div>
                      </a>
                    </div>
                  </div>
                  <br />
                </div>
              </div>
            </div>
            <div
              class="new-message-arrive"
              v-if="new_message.display"
              @click="toBottomMessages"
            >
              {{ trans("tickets.title.new message") }}
            </div>
            <div
              class="mb-2"
              v-if="data.status != 'closed' || user_company.type == 'isp'"
            >
              <div class="chat-footer">
                <input
                  ref="input_message"
                  type="text"
                  v-model="form.messages"
                  style="width: 100%"
                  class="send-message-text"
                  :placeholder="trans('tickets.placeholder.enter message')"
                  @keyup.enter="onSubmit('form')"
                />
                <label class="upload-file" @click="toggleMessage()">
                  <!-- <input type="file" required=""> -->
                  <i class="fa fa-paperclip"></i>
                </label>
                <button
                  type="button"
                  @click="onSubmit('form')"
                  class="send-message-button btn-info"
                >
                  <i class="fa fa-paper-plane"></i>
                </button>
              </div>
              <div>
                <div
                  class="input-field input-drag-drop-image"
                  v-show="show_form"
                >
                  <div class="drag-drop-image" style="background: white"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <el-button @click="$router.go(-1)">
                {{ trans("core.back") }}
              </el-button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import Form from "form-backend-validation";
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";
import AcceptSLAVue from "../partials/AcceptSLA.vue";

export default {
  mixins: [Toast, PermissionsHelper],
  components: {
    accept_sla: AcceptSLAVue,
  },
  data() {
    return {
      load_data: false,
      data: {},
      form: {
        ticket_id: null,
        messages: null,
        attachments: [],
      },
      loading: false,
      loading_chat: false,
      show_form: false,
      form_data: new Form(),
      request: null,
      new_message: {
        display: false,
      },
      rules: {
        required_field: [
          {
            required: true,
            trigger: ["blur", "change"],
            message: this.trans("core.form.required"),
          },
        ],
      },
    };
  },
  methods: {
    initDragAndDropImage() {
      $(".drag-drop-image").imageUploader({
        imagesInputName: "attachments",
        vmodel: this.form.attachments,
      });
    },
    onSubmit(formName) {
      let message = this.form.messages;
      if (message == null || message == "") {
        this.$refs.input_message.focus();
        return false;
      }

      Swal.fire({
        title: this.trans("core.messages.confirmation-form"),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then((result) => {
        if (result.value) {
          this.loading_chat = true;
          this.form_data = new Form(this.form);
          this.form_data
            .post(
              route("admin.api.ticket.session.message.send", {
                ticket_id: this.$route.params.id,
              })
            )
            .then((response) => {
              this.form.attachments = [];
              this.form.messages = null;
              this.show_form = false;
              $(".input-drag-drop-image").html(
                `<div class="drag-drop-image" style="background: white"></div>`
              );
              this.initDragAndDropImage();
              this.fetchData(1);
              this.fetchMessages(1);
            })
            .catch((error) => {
              this.loading_chat = false;
              this.Toast({
                icon: "error",
                title: "error",
              });
            });
        }
      });
    },
    toggleMessage() {
      this.show_form = !this.show_form;
    },
    fetchData(whitoutchat = 0) {
      this.load_data = false;
      axios
        .get(
          route("admin.api.ticket.session.detail", {
            id: this.$route.params.id,
          })
        )
        .then((response) => {
          this.load_data = true;
          if (whitoutchat == 1) {
            this.data.status = response.data.status;
            this.data.accept_isp_by = response.data.accept_isp_by;
            this.data.accept_osp_by = response.data.accept_osp_by;
            this.data.user_approve_isp = response.data.user_approve_isp;
            this.data.user_approve_osp = response.data.user_approve_osp;
          } else {
            this.data = response.data;
            setTimeout(() => {
              $(".fancybox").fancybox();
              $(".chat-body").scrollTop($(".chat-message-body").height());
            }, 1000);
            let vm = this;
            setInterval(() => {
              vm.fetchMessages(0);
            }, 5000);
          }
        })
        .catch((error) => {
          if (error.response.status === 403) {
            this.$notify.error({
              title: this.trans("core.unauthorized"),
              message: this.trans("core.unauthorized-access"),
            });
            window.location = route("dashboard.index");
          } else {
            this.Toast({
              icon: "error",
              title: "error",
            });
          }
        });
    },
    fetchMessages(scroll = 1) {
      const properties = {
        params: {
          id_terakhir: this.data.messages[this.data.messages.length - 1]
            .message_id,
        },
      };
      if (this.request == null) {
        if (this.$route.params.id === undefined) {
          return false;
        }
        this.request = axios
          .get(
            route("admin.api.ticket.session.message.get", {
              ticket_id: this.$route.params.id,
            }),
            _.merge(properties)
          )
          .then((response) => {
            this.loading_chat = false;
            if (response.data.length > 0) {
              this.data.messages = [...this.data.messages, ...response.data];
              if (response.data[response.data.length - 1].isp_id != null) {
                this.data.status = "reply_isp";
              } else {
                this.data.status = "reply_osp";
              }
              if (scroll == 1) {
                console.log("masuk sini nih");
                this.toBottomMessages();
              } else {
                let scrollTop = $(".chat-body").scrollTop();
                let chatHeight = $(".chat-body").height();
                let chatMessagesHeight = $(".chat-message-body").height();

                if (
                  scrollTop != 0 &&
                  scrollTop + 50 < chatMessagesHeight - chatHeight
                ) {
                  this.new_message.display = true;
                } else {
                  this.toBottomMessages();
                  this.new_message.display = false;
                }
              }

              setTimeout(() => {
                $(".fancybox").fancybox();
              }, 500);
            }
            this.request = null;
          });
      }
    },
    toBottomMessages() {
      let vm = this;
      setTimeout(function() {
        $(".chat-body").scrollTop($(".chat-message-body").height());
        vm.new_message.display = false;
      }, 500);
    },
    closeTicketSupport() {
      let text = "";
      if (this.data.accept_isp_by == null) {
        text = this.trans("tickets.messages.sla tidak muncul.isp");
      } else if (this.data.accept_osp_by == null) {
        text = this.trans("tickets.messages.sla tidak muncul.osp");
      }
      Swal.fire({
        title: this.trans("core.messages.confirmation-form"),
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.button.confirm"),
        cancelButtonText: this.trans("core.button.cancel"),
      }).then((result) => {
        if (result.value) {
          this.loading_chat = true;
          axios
            .post(
              route("admin.api.ticket.session.close", {
                ticket_id: this.$route.params.id,
              })
            )
            .then((response) => {
              this.loading_chat = false;
              this.Toast({
                icon: "success",
                title: response.data.message,
              });
              this.fetchData();
            })
            .catch((er) => {
              this.loading_chat = false;
              this.Toast({
                icon: "error",
                title: "error",
              });
            });
        }
      });
    },
    setClassTicketSupport(val) {
      if (this.user_company.type == null) {
        if (val.isp_id == null && val.osp_id == null) {
          return "my-message";
        } else if (val.isp_id != null) {
          return "success";
        } else if (val.osp_id != null) {
          return "warning";
        }
      } else {
        if (val.isp_id == null && val.osp_id == null) {
          return "warning";
        } else if (
          (this.user_company.type == "isp" && val.isp_id != null) ||
          (this.user_company.type == "osp" && val.osp_id != null)
        ) {
          return "my-message";
        } else {
          return "info";
        }
      }
    },
  },
  mounted() {
    this.form.ticket_id = this.$route.params.id;
    this.initDragAndDropImage();
    this.fetchData();
  },
  created() {
    window.showGambarFancyBox = (gambar) => {
      $.fancybox([gambar], {
        type: "image",
      });
    };
  },
};
</script>
<style>
@import "../../../css/TicketSession.css";
</style>
