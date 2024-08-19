<template>
  <bs-modal id="modal-rating-pelanggan" size="large" @onClose="onClose">
    <span slot="header">
      {{ trans("activations.modal.approve.title") }}
    </span>
    <div class="container" v-loading.body="loading">
      <div class="row">
        <div class="col-12">
          <Card class="card card-outline-info">
            <div class="card-header text-white">
              {{ trans("activations.title.estimasi pengembalian") }}
            </div>
            <div class="card-body no-padding">
              <table class="table">
                <tr>
                  <td width="255px">
                    {{ trans("activations.table.pengembalian prorata") }}
                  </td>
                  <td width="20px">:</td>
                  <td>
                    {{ ratings.pengembalian.pengembalian_prorata | harga }}
                  </td>
                </tr>
                <tr>
                  <td width="220px">
                    {{ trans("activations.table.kelebihan pembayaran") }}
                  </td>
                  <td width="20px">:</td>
                  <td>
                    {{ ratings.pengembalian.kelebihan_pembayaran | harga }}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ trans("activations.table.komisi ke osp") }}
                  </td>
                  <td>:</td>
                  <td>
                    {{ ratings.pengembalian.osp | harga }} (
                    {{ ratings.pengembalian.percentage_osp }}% )
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ trans("activations.table.admin fee") }}
                  </td>
                  <td>:</td>
                  <td>
                    {{ ratings.pengembalian.openaccess | harga }} (
                    {{ ratings.pengembalian.percentage_openaccess }}% )
                  </td>
                </tr>
              </table>
            </div>
          </Card>

          <p
            style="
            font-weight: 600;
            font-size: 14px;"
          >
            <b class="text-danger">*</b>
            {{ trans("activations.messages.informasi pengembalian mrc") }}
          </p>
        </div>

        <div class="col-12" v-for="(rating, i) in ratings.rating" :key="i">
          <CardRatingPelanggan :rating="rating" :title="i" />
        </div>
        <div class="col-12">
          <el-checkbox v-model="checked" style="font-weight: 600;">
            <b class="text-danger">*</b>
            {{ trans("activations.form.persetujuan aktivasi") }}
          </el-checkbox>
        </div>
      </div>
    </div>

    <div slot="footer">
      <el-button type="danger" @click="onClose">
        {{ trans("core.button.close") }}
      </el-button>
      <el-button type="primary" @click="approve" :disabled="!checked">
        {{ trans("activations.title.approve") }}
      </el-button>
    </div>
  </bs-modal>
</template>

<script>
import axios from "axios";
import BsModal from "../../../../../Presale/Assets/components/modal/BsModal.vue";
import CardRatingPelanggan from "./CardRatingPelanggan.vue";

export default {
  components: {
    BsModal,
    CardRatingPelanggan,
  },
  props: ["status", "loading"],
  data() {
    return {
      ratings: {
        rating: [],
        pengembalian: {
          isp: 0,
          osp: 0,
          openaccess: 0,
        },
      },
      checked: false,
    };
  },
  methods: {
    onClose() {
      $("#modal-rating-pelanggan").modal("hide");
    },
    fetchRating() {
      axios
        .get(
          route("api.pelanggan.rating", {
            pelanggan: this.$route.query.pelanggan,
          })
        )
        .then((response) => {
          this.ratings = response.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    approve() {
      this.$emit("approve", true);
    },
  },
  watch: {
    status: function() {
      if (this.status) {
        this.fetchRating();
      }
    },
  },
};
</script>

<style></style>
