<template>
  <div>
    <!-- Modal Header -->
    <div class="modal-header">
      <h4 class="modal-title">
        {{ trans("surveys.modal.title pengajuan") }}
      </h4>
      <button type="button" class="close" data-dismiss="modal">
        &times;
      </button>
    </div>

    <!-- Modal body -->
    <div class="modal-body" style="font-size: 14px;">
      <div class="row">
        <div class="col-md-12">
          <b>{{ trans("surveys.modal.tgl") }} : </b>
        </div>
        <div class="col-md-12">
          {{ data_teknisi.tgl_pengajuan }}
          {{ data_teknisi.jam_pengajuan }}
        </div>

        <div class="col-md-12">
          <hr />
          <b>{{ trans("pengajuanjadwal.keterangan") }} : </b>
        </div>
        <div class="col-md-12">
          {{
            data_teknisi.keterangan !== "" && data_teknisi.keterangan !== null
              ? data_teknisi.keterangan
              : "-"
          }}
        </div>
        <hr />
        <div class="col-md-12">
          <hr />
          <h6>{{ trans("surveys.modal.pilih") }} :</h6>
        </div>
        <div class="col-md-12 mb-3">
          <el-input
            :placeholder="trans('surveys.modal.cari')"
            size="mini"
            v-model="search"
          ></el-input>
        </div>
        <div class="col-md-12">
          <div style="height: 180px; overflow: auto;">
            <span v-for="(item, i) in teknisiResult" :key="i">
              <p class="mb-0">
                <el-checkbox v-model="item.status" :label="item.nama_teknisi">
                </el-checkbox>
              </p>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal footer -->
    <div class="modal-footer">
      <button
        type="button"
        class="el-button el-button--danger"
        data-dismiss="modal"
      >
        {{ trans("pengajuanjadwal.button.close") }}
      </button>
      <el-button
        type="primary"
        @click="submit(pengajuan_id, data_teknisi.list_teknisi)"
      >
        {{ trans("pengajuanjadwal.button.modal simpan") }}
      </el-button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["pelanggan_id", "data_teknisi", "pengajuan_id"],
  data() {
    return {
      pengajuan: {
        pengajuan_id: "",
        dataTeknisi: null,
      },
      search: "",
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "",
      },
      loadingTable: false,
    };
  },
  computed: {
    teknisiResult() {
      if (this.search) {
        return this.data_teknisi.list_teknisi.filter((item) => {
          return this.search
            .toLowerCase()
            .split(" ")
            .every((v) => item.nama_teknisi.toLowerCase().includes(v));
        });
      } else {
        return this.data_teknisi.list_teknisi;
      }
    },
  },
  methods: {
    submit(id, data) {
      if (!data.some((item) => item.status == true)) {
        Swal.fire(this.trans("pengajuanjadwal.teknisi kosong"), "", "warning");
        return false;
      }

      data.forEach(async (element) => {
        if (element.status) {
          this.pengajuan.pengajuan_id = id;
          this.pengajuan.dataTeknisi = data;
          this.$emit("pilihTanggalRekomendasi", this.pengajuan);
          return true;
        }
      });
    },
  },
};
</script>
<style>
@media screen and (min-width: 676px) {
  .modal-dialog {
    max-width: 600px; /* New width for default modal */
  }
}
</style>
