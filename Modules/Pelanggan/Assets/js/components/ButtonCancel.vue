<template>
  <div class="row">
    <div class="col-md-12">
      <el-button
        :disabled="disable == true"
        v-show="disable != true || status_step != '3'"
        @click="Cancel_step"
        type="danger"
        plain
        size="small"
        class="col-md-12"
      >
        {{ trans("pelangganinstalasi.cancel") }}
      </el-button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["buttonDisabled"],
  data() {
    return {
      form: {
        pelanggan_id: this.$route.query.pelanggan,
        alasan_cancel: "",
      },
      disable: false,
      status_step: "",
    };
  },
  methods: {
    Cancel_step() {
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
          Swal.fire({
            title: "Masukkan alasan cancel",
            input: "textarea",
            confirmButtonText: "Send",
            showCloseButton: true,
            showLoaderOnConfirm: true,
          }).then((result) => {
            if (result.value) {
              this.form.alasan_cancel = result.value;
              axios
                .post(route("api.pelanggan.status.step.cancel"), this.form)
                .then((res) => {
                  this.$emit("changeStatusStep", "cancel");
                  var response = res.data;
                  console.log(response);
                  if (response.error == true) {
                    var type = "error";
                  } else {
                    var type = "success";
                  }
                  Swal.fire({
                    toast: true,
                    icon: type,
                    position: "top-end",
                    timer: 3000,
                    type: type,
                    showConfirmButton: false,
                    title: response.message,
                  });
                  this.$router.go();
                });
            } else {
              console.log("Cancel Successfully");
            }
          });
        } else {
          console.log("Cancel Successfully");
        }
      });
    },
  },
  watch: {
    buttonDisabled(value) {
      if (value == "1") {
        this.disable = true;
      } else {
        this.disable = false;
      }
      // this.disable = true;
    },
  },
  mounted() {
    let presales = this.$route.query.presales;
    presales = presales !== undefined && presales != "" ? presales : 0;
    let pelanggan = this.$route.query.pelanggan;
    pelanggan = pelanggan !== undefined && pelanggan != "" ? pelanggan : 0;

    axios
      .post(route("api.pelanggan.form.status"), {
        presales: presales,
        pelanggan: pelanggan,
      })
      .then((res) => {
        this.step_status = res.data.status;
      });
  },
};
</script>
