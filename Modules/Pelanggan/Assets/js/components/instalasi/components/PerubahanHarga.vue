<template>
  <div>
    <div class="card-outline-danger mb-2" v-loading="loading" v-if="Perubahan">
      <!-- v-if="Perubahan == true" -->
      <div class="card-header text-white">
        <div class="row">
          <div class="col-md-4">
            <h5 class="text-white pt-2">
              {{ trans("pelangganinstalasi.perubahan") }}
            </h5>
          </div>
          <div class="col-md-4"></div>
          <div class="col-md-2 mt-1">
            <el-form v-if="statusStep !== 'cancel'">
              <el-button
                :hidden="acceptedPerubahan == true"
                type="danger"
                plain
                size="small"
                class="col-md-12"
                data-toggle="modal"
                data-target="#form_slott"
              >
                {{ trans("pelangganinstalasi.cek") }}
              </el-button>
            </el-form>
          </div>
          <div class="col-md-2 mt-1">
            <el-form>
              <el-button
                plain
                size="small"
                type="danger"
                class="col-md-12"
                data-toggle="modal"
                data-target="#form_slot"
              >
                {{ trans("pelangganinstalasi.histori") }}
              </el-button>
            </el-form>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="form_slot"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      aria-labelledby="form_slotLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="form_slotLabel">
              {{ trans("pelangganinstalasi.histori") }}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table
              v-for="(Histori, index) in HistoriPerubahanHarga"
              :key="index"
              class="table table-striped"
              style="font-size: 12px; border: solid #212529 2px"
            >
              <tr>
                <td width="25%">{{ trans("pelangganinstalasi.tanggal") }}</td>
                <td width="5%">:</td>
                <td width="30%">{{ Histori.created_at }}</td>
              </tr>
              <tr>
                <td width="25%">
                  {{ trans("pelangganinstalasi.paket") }}
                </td>
                <td width="5%">:</td>
                <td width="30%">
                  {{ Histori.nama_paket }}
                </td>
              </tr>
              <tr>
                <td width="25%">
                  {{ trans("pelangganinstalasi.harga otc") }}
                </td>
                <td width="5%">:</td>
                <td width="30%">
                  {{ Histori.harga_otc }}
                </td>
              </tr>
              <tr>
                <td width="25%">
                  {{ trans("pelangganinstalasi.harga mrc") }}
                </td>
                <td width="5%">:</td>
                <td width="30%">
                  {{ Histori.harga_mrc }}
                </td>
              </tr>
              <tr>
                <td width="25%">
                  {{ trans("pelangganinstalasi.keterangan") }}
                </td>
                <td width="5%">:</td>
                <td width="30%">{{ Histori.keterangan }}</td>
              </tr>
              <tr>
                <td width="25%">Status</td>
                <td width="5%">:</td>
                <td v-show="Histori.status == 'request'" width="30%">
                  Diajukan
                </td>
                <td v-show="Histori.status != 'request'" width="30%">
                  {{ Histori.status }}
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <el-button size="small" data-dismiss="modal">{{
              trans("pelangganinstalasi.close")
            }}</el-button>
          </div>
        </div>
      </div>
    </div>
    <div
      class="modal fade"
      id="form_slott"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      aria-labelledby="form_slotLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content" v-loading="modalLoading">
          <div class="modal-header">
            <h5 class="modal-title" id="form_slotLabel">
              {{ trans("pelangganinstalasi.modal.perubahan") }}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div v-if="ajukanUlang">
              <el-form
                class="w-100"
                :disabled="ajukanUlang == false"
                ref="DataPengajuan"
                :rules="rules"
                :model="DataPengajuan"
              >
                <el-form-item :label="trans('pelangganinstalasi.harga otc')">
                  <input-currency
                    type="number"
                    v-model="DataPengajuan.harga_otc"
                    size="small"
                    prop="harga_otc"
                  >
                  </input-currency>
                </el-form-item>
                <el-form-item :label="trans('pelangganinstalasi.harga mrc')">
                  <input-currency
                    type="number"
                    v-model="DataPengajuan.harga_mrc"
                    size="small"
                  >
                  </input-currency>
                </el-form-item>

                <el-form-item>
                  <el-checkbox
                    v-model="ubah_paket"
                    size="medium"
                    :label="trans('pelanggans.ubah paket')"
                  ></el-checkbox>
                </el-form-item>
                <el-form-item
                  v-if="ubah_paket"
                  :label="trans('salesorders.form.paket_berlangganan')"
                >
                  <el-select
                    v-model="DataPengajuan.paket_id"
                    size="small"
                    ref="type"
                  >
                    <el-option
                      v-for="item in list_paket"
                      :key="item.paket_id"
                      :label="item.nama_paket"
                      :value="item.paket_id"
                    >
                    </el-option>
                  </el-select>
                </el-form-item>
                <el-form-item
                  prop="keterangan"
                  :label="trans('pelangganinstalasi.keterangan')"
                >
                  <el-input
                    v-model="DataPengajuan.keterangan"
                    type="textarea"
                    :autosize="{ minRows: 2, maxRows: 4 }"
                  ></el-input>
                </el-form-item>
              </el-form>
            </div>
            <div v-else>
              <table class="table">
                <tr>
                  <td style="font-size: 14px">
                    {{ trans("pelangganinstalasi.harga otc") }}
                  </td>
                  <td>
                    <div style="padding-top: 0px">
                      <span
                        style="
                          font-size: 14px;
                          font-weight: 300;
                          display: block;
                        "
                      >
                        {{ harga_otc_with_format }}
                      </span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td style="font-size: 14px">
                    {{ trans("pelangganinstalasi.harga mrc") }}
                  </td>
                  <td>
                    <div style="padding-top: 0px">
                      <span
                        style="
                          font-size: 14px;
                          font-weight: 300;
                          display: block;
                        "
                      >
                        {{ harga_mrc_with_format }}
                      </span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td style="font-size: 14px">
                    {{ trans("pelangganinstalasi.paket") }}
                  </td>
                  <td>
                    <div style="padding-top: 0px">
                      <span
                        style="
                          font-size: 14px;
                          font-weight: 300;
                          display: block;
                        "
                      >
                        {{ nama_paket }}
                      </span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td style="font-size: 14px">
                    {{ trans("pelangganinstalasi.keterangan") }}
                  </td>
                  <td>
                    <div style="padding-top: 0px">
                      <span
                        style="
                          font-size: 14px;
                          font-weight: 300;
                          display: block;
                        "
                      >
                        {{ DataPengajuan.keterangan }}
                      </span>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <div
            class="modal-footer"
            v-if="user_profile.id != idPengaju || ajukanUlang == true"
          >
            <el-button
              size="small"
              :hidden="ajukanUlang == true"
              v-if="user_profile.id != idPengaju"
              data-dismiss="modal"
              @click="SetTolak"
              type="danger"
            >
              {{ trans("pelangganinstalasi.tolak") }}
            </el-button>

            <el-button
              size="small"
              :hidden="ajukanUlang == true"
              v-if="user_profile.id != idPengaju"
              data-dismiss="modal"
              type="primary"
              @click="SetTerima"
            >
              {{ trans("pelangganinstalasi.terima") }}
            </el-button>

            <el-button
              size="small"
              v-show="ajukanUlang == true"
              :hidden="tombolAjukan == false"
              data-dismiss="modal"
              type="danger"
            >
              {{ trans("pelangganinstalasi.batal") }}
            </el-button>
            <el-button
              size="small"
              type="primary"
              v-show="ajukanUlang == true"
              :hidden="tombolAjukan == false"
              @click="SetSimpan('DataPengajuan')"
            >
              {{ trans("pelangganinstalasi.ajukan_perubahan") }}
            </el-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import InputCurrencyVue from "../../../../../../Core/Assets/js/components/InputCurrency.vue";

import Toast from "../../../../../../Core/Assets/js/mixins/Toast";
export default {
  mixins: [Toast],
  props: ["statusStep"],
  components: {
    "input-currency": InputCurrencyVue,
  },
  data() {
    return {
      loading: true,
      ubah_paket: false,
      HistoriPerubahanHarga: [],

      DataPengajuan: {
        harga_otc: 0,
        harga_mrc: 0,
        keterangan: "",
        paket_id: null,
      },
      harga_otc_with_format: 0,
      harga_mrc_with_format: 0,
      nama_paket: null,
      Perubahan: false,
      idPengaju: null,
      modalLoading: false,
      acceptedPerubahan: false,
      ajukanUlang: true,
      tombolAjukan: true,
      list_paket: [],

      rules: {
        keterangan: [
          {
            required: true,
            message: "Data tidak boleh kosong",
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {
    loadListPaket() {
      axios
        .post(route("api.pelanggan.form.detail.so"), {
          pelanggan: this.$route.query.pelanggan,
        })
        .then((response) => {
          this.list_paket = response.data.paket;
          this.DataPengajuan.paket_id = response.data.data.paket_id;
        });
    },

    loadDatabasePerubahanHarga() {
      const params = this.$route.query.pelanggan;
      axios
        .post(
          route("api.pelanggan.form.perubahan.harga", {
            pelanggan: params,
          })
        )
        .then((res) => {
          this.loading = true;
          var jumlahHistori = res.data.data.length;
          if (jumlahHistori > 0) {
            this.Perubahan = true;
            this.ajukanUlang = false;
            for (var n = 0; n < jumlahHistori; n++) {
              this.HistoriPerubahanHarga.push(res.data.data[n]);
            }
            var dataLast = res.data.data[jumlahHistori - 1];

            this.harga_mrc_with_format = dataLast.harga_mrc;
            this.harga_otc_with_format = dataLast.harga_otc;
            this.nama_paket = dataLast.nama_paket;
            this.idPengaju = dataLast.created_by;

            if (dataLast.status == "request") {
              this.DataPengajuan.harga_otc = parseFloat(
                dataLast.harga_otc_nonFormat
              );
              this.DataPengajuan.harga_mrc = parseFloat(
                dataLast.harga_mrc_nonFormat
              );
              this.DataPengajuan.keterangan = dataLast.keterangan;
              this.tombolAjukan = false;
            } else {
              if (dataLast.status == "accepted") {
                this.ajukan = false;
                this.acceptedPerubahan = true;
              } else {
                this.ajukanUlang = true;
                this.tombolAjukan = true;
              }
            }
            this.loading = false;
          } else {
            this.ajukanUlang = true;
          }
          this.loading = false;
        });
    },
    SetTolak() {
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
          var lastData = this.HistoriPerubahanHarga.length;
          var lastDataPut = this.HistoriPerubahanHarga[lastData - 1];
          var perubahan_harga_id = lastDataPut.perubahan_harga_id;
          axios
            .post(
              route("api.pelanggan.form.tolak.perubahan", {
                perubahan_harga_id,
              })
            )
            .then((response) => {
              this.ajukanUlang = true;
              this.ajukanUlang = true;
              this.tombolAjukan = true;
              this.DataPengajuan = {
                harga_otc: 0,
                harga_mrc: 0,
                keterangan: "",
              };

              console.log(response);
              this.Toast({
                icon: "success",
                title: response.data.message,
              });
            });
        } else {
          console.log("Canclled Successfully");
        }
      });
    },
    async SetTerima() {
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
          var lastData = this.HistoriPerubahanHarga.length;
          var lastDataPut = this.HistoriPerubahanHarga[lastData - 1];
          var perubahan_harga_id = lastDataPut.perubahan_harga_id;
          axios
            .post(
              route("api.pelanggan.form.terima.perubahan", {
                perubahan_harga_id,
              })
            )
            .then(async (respond) => {
              this.acceptedPerubahan = true;
              this.loadDatabasePerubahanHarga();
              this.ajukan = false;
              this.Toast({
                icon: "success",
                title: respond.data.message,
              });
              this.$router.go();
            });
        } else {
          console.log("Canclled Successfully");
        }
      });
    },
    checkSaldoSudahCukup() {
      return new Promise((resolve) => {
        axios
          .get(
            route("api.pelanggan.saldo.check", {
              pelanggan_id: this.$route.query.pelanggan,
            })
          )
          .then((response) => {
            return resolve(response.data);
          });
      });
    },
    SetSimpan(DataPengajuan) {
      // this.ajukanUlang=false;
      this.$emit("ajukanFalse", "false");
      this.$refs[DataPengajuan].validate((valid) => {
        if (valid) {
          const propeties = {
            harga_mrc: this.DataPengajuan.harga_mrc,
            harga_otc: this.DataPengajuan.harga_otc,
            keterangan: this.DataPengajuan.keterangan,
            paket_id: this.DataPengajuan.paket_id,
            id: this.$route.query.pelanggan,
          };
          Swal.fire({
            title: this.trans("core.messages.confirmation-form"),
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: this.trans("core.button.confirm"),
            cancelButtonText: this.trans("core.button.cancel"),
            loading: true,
          }).then((result) => {
            if (result.value) {
              this.modalLoading = true;
              axios
                .post(route("api.pelanggan.form.ajukan.perubahan"), propeties)
                .then((respond) => {
                  this.ajukanUlang = false;
                  this.loadDatabasePerubahanHarga();
                  this.modalLoading = false;
                  this.Toast({
                    icon: "success",
                    title: respond.data.message,
                  });
                });
            } else {
              console.log("Canceled Successfully");
            }
          });
        }
      });
    },
    number_format(number, decimals, decPoint, thousandsSep) {
      number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
      var n = !isFinite(+number) ? 0 : +number;
      var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
      var sep = typeof thousandsSep === "undefined" ? "." : thousandsSep;
      var dec = typeof decPoint === "undefined" ? "." : decPoint;
      var s = "";

      var toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return "" + (Math.round(n * k) / k).toFixed(prec);
      };

      // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
      }

      return s.join(dec);
    },
  },
  mounted() {
    this.loadDatabasePerubahanHarga();
    this.loadListPaket();
  },
};
</script>
