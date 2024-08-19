<template>
  <bs-modal id="modal-list-konfirmasi" @onClose="onClose" size="large">
    <div slot="header">
      {{ trans("presales.konfirmasi.title") }}
    </div>

    <saldo style="color:black" class="mb-3" />
    <vue-good-table
        :columns="columns"
        :rows="data_konfirmasi"
        :pagination-options="{
          enabled: true,
          mode: 'records',
          perPage: 4,
          rowsPerPageLabel: 'Data per halaman',
        }"
      >
      <template slot="table-row" slot-scope="props">
        <p v-if="props.column.field == 'action'" style="text-align:center">
          <el-button
            type="danger"
            size="small"
            @click="deleteItem(props)"
            icon="fa fa-trash"
          >
          </el-button>
        </p>
        
      </template>

      <div slot="table-actions-bottom" class="p-2 ">
        <p class="text-right" style="margin:0px;">
          {{ trans('saldos.total') }} : {{ rupiah(total_biaya) }}
        </p>
        <p class="text-right" style="margin:0px;">
          {{ trans('saldos.sisa saldo') }} : {{ rupiah(saldo - total_biaya) }}
        </p>
      </div>
    </vue-good-table>


    <div class="mt-4">
      <div class="col-12">
        <el-tooltip class="item" :effect="tooltip.effect" :content="checkbox_open_wilayah" :placement="tooltip.placement">
          <span>
            <el-checkbox v-model="checked_open_wilayah" v-if="canSubmit"  :disabled="endpoint_disable"
              >{{
                trans("wilayahs.form.open wilayah")
              }}
            </el-checkbox>

          </span>
        </el-tooltip>
      </div>

      <div class="col-12">
          <el-checkbox v-model="checked" v-if="canSubmit"
            >{{
              trans("core.konfirmasi pengurangan saldo", {
                field: rupiah(total_biaya),
              })
            }}
          </el-checkbox>
          

      </div>
      
      <div
        class="d-flex flex-column justify-content-center align-items-center bg-warning p-2"
        v-if="!canSubmit"
      >
        <p style="color:white">
          {{
            trans("core.saldo kurang", {
              field: rupiah(kekurangan_saldo),
            })
          }}
        </p>
        <p>
          <el-button type="primary" @click="goToTopup" size="small">
            {{ trans("saldos.title.topup") }}
          </el-button>
        </p>
      </div>
    </div>


    <div slot="footer">
      <el-button
        @click="onSubmit()"
        type="primary"
        icon="el-icon-upload"
        :loading="buttonIsLoading"
        size="small"
        v-if="data_konfirmasi.length > 0 && checked"
      >
        {{ trans("core.save") }}
      </el-button>

      <el-button @click="onClose" type="secondary" size="small">
        {{ trans("core.button.close") }}
      </el-button>
    </div>
  </bs-modal>
</template>

<script>
import CurrencyHelper from "../../../../Core/Assets/js/mixins/CurrencyHelper";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import Constants from "../../../../Core/Assets/js/mixins/Constants";

import "vue-good-table/dist/vue-good-table.css";
import { VueGoodTable } from "vue-good-table";
import { mapGetters } from 'vuex';

export default {
  components: {
    VueGoodTable
  },
  props: ["list_konfirmasi", "data_biaya_kabel", "configuration", "endpoint_disable"],
  mixins: [CurrencyHelper, TranslationHelper, Toast, Constants],
  data() {
    return {
      checked_open_wilayah: false,
      buttonIsLoading: false,
      kabel_terpanjang: 0,
      total_biaya: 0,
      checked: false,
      columns: [
        {
          label: "Site ID",
          field: "site_id",
        },
        {
          label: this.trans('biaya_kabel.form.panjang kabel'),
          field: "panjang_kabel",
          type: "number",
        },
        {
          label: this.trans('core.table.actions'),
          field: "action",
        }
      ]
    };
  },
  mounted() {},
  methods: {
    onClose() {
      $("#modal-list-konfirmasi").modal("hide");
    },
    onSubmit() {
      Swal.fire({
        title: this.trans('core.messages.confirmation-form'),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.ya"),
        cancelButtonText: this.trans("core.tidak"),
      }).then((result) => {

        if(result.value) {
          let data = this.data_konfirmasi.map((item) => {
            return item.presale_id;
          });

          let properties = {
            data: data,
            open_wilayah: this.checked_open_wilayah
          };

          this.buttonIsLoading = true;
          axios
            .post(route("api.presale.presales.konfirmasi"), properties)
            .then((response) => {
              this.$emit("handleSuccess", response.data.data);
              this.Toast({
                icon: "success",
                title: response.data.message,
              });
              this.checked = false;
              this.onClose();
              this.buttonIsLoading = false;
              this.$store.dispatch('getSaldo');
            })
            .catch((error) => {
              if (error.response.status) {
                return this.Toast({
                  icon: "info",
                  title: error.response.data.message,
                });
              }

              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title"),
              });
            });
        }
        
      });
    },
    goToTopup() {
      $("#modal-list-konfirmasi").modal("hide");
      setTimeout(() => {
        this.$router.push({
          name: "admin.saldo.topup.index",
        });
      }, 100);
    },
    deleteItem(scope) {
      let icon = {
        url: window.location.origin + "/modules/presale/img/pengajuan.ico",
        scaledSize: new google.maps.Size(50, 50),
      };

      this.list_konfirmasi[scope.index].setIcon(icon);
      this.list_konfirmasi.splice(scope.index, 1);
    },
  },
  computed: {
    ...mapGetters(['saldo']),
    data_konfirmasi: function() {
      this.kabel_terpanjang = 0;
      this.total_biaya = 0;
      return this.list_konfirmasi.map((item) => {
        if (item.panjang_kabel > this.kabel_terpanjang)
          this.kabel_terpanjang = item.panjang_kabel;
        let kabel_permeter = this.configuration.kabel_per_meter
          ? this.configuration.kabel_per_meter
          : 0;
        this.total_biaya = kabel_permeter * this.kabel_terpanjang;

        return {
          site_id: item.site_id,
          panjang_kabel: item.panjang_kabel,
          presale_id: item.presale_id,
        };
      });
    },
    canSubmit() {
      return this.total_biaya < this.saldo;
    },
    btnSubmitDisabled() {
      return !this.checked;
    },
    kekurangan_saldo() {
      return this.total_biaya - this.saldo;
    },
    checkbox_open_wilayah() {
      if(this.endpoint_disable) {
        return this.trans('endpoint.tooltip.endpoint disable');
      }

      return this.trans('endpoint.tooltip.open wilayah');
    }
  },
};
</script>

<style>
.vgt-wrap__footer {
  padding: 10px !important;
}

.footer__row-count__label {
  margin-bottom: 0px !important;
}

.vgt-wrap__footer .footer__row-count__select,
.vgt-wrap__footer .footer__navigation__page-btn span,
.footer__navigation__page-info,
.vgt-wrap__footer .footer__row-count__label {
  font-size: 0.9rem !important;
}

</style>
