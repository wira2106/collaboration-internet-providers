<template>
  <bs-modal :id="id" @onClose="onClose()" size="large" v-loading="loading">
    <div slot="header">
      {{ trans('core.cart') }}
    </div>
    <div>
      <saldo style="color:black" class="mb-3" />
      <vue-good-table
        :columns="columns"
        :rows="value"
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
          <span v-else-if="props.column.field == 'harga'">
            {{ rupiah(props.formattedRow[props.column.field]) }}
          </span>
          <span v-else-if="props.column.field == 'total'">
            {{
              rupiah(props.formattedRow["harga"] * props.formattedRow["qty"])
            }}
          </span>
          <span v-else>
            {{ props.formattedRow[props.column.field] }}
          </span>
        </template>

        <div slot="table-actions-bottom" class="p-2 ">
          <div class="text-right" style="margin:0px;">
            {{ trans('saldos.total') }} : {{ rupiah(total_harga) }}
          </div>
          <div class="text-right" style="margin:0px;">
            {{ trans('saldos.sisa saldo') }} : {{ rupiah(saldo - total_harga) }}
          </div>
        </div>
      </vue-good-table>
    </div>

    <div class="mt-4">
      <el-checkbox v-model="checked" v-if="canSubmit"
        >{{
          trans("core.konfirmasi pengurangan saldo", {
            field: rupiah(total_harga),
          })
        }}
      </el-checkbox>

      <div
        class="d-flex flex-column justify-content-center align-items-center bg-warning p-2"
        v-if="!canSubmit"
      >
        <p style="color:white;text-align:center">
          {{
            trans("core.saldo kurang", {
              field: rupiah(kekurangan_saldo),
            })
          }}
        </p>
        <p style="text-align:center">
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
        size="small"
        v-if="canSubmit && (!btnSubmitDisabled)"
        
      >
        {{ trans("core.submit") }}
      </el-button>
      <el-button @click="onClose()" type="secondary" size="small">
        {{ trans("core.button.close") }}
      </el-button>
    </div>
  </bs-modal>
</template>

<script>
import TranslationHelper from "../mixins/TranslationHelper";
import "vue-good-table/dist/vue-good-table.css";
import { VueGoodTable } from "vue-good-table";
import SaldoVue from "../../../../Saldo/Assets/components/Saldo/Saldo.vue";
import CurrencyHelper from "../mixins/CurrencyHelper";
import { mapGetters } from "vuex";

export default {
  components: {
    VueGoodTable,
    SaldoVue,
  },
  props: {
    id: {
      default: 'modal-cart'
    },
    loading: {
      default: false
    },
    value: {
      required: true,
      default: () => {
        return [];
      },
    },
    action: {
      default: true,
    },
  },
  mixins: [TranslationHelper, CurrencyHelper],
  data() {
    let columns = [
      {
        label: "Nama",
        field: "name",
      },
      {
        label: "Qty",
        field: "qty",
        type: "number",
      },
      {
        label: "Harga",
        field: "harga",
      },
      {
        label: "Total",
        field: "total",
        type: "string",
      },
    ];

    if (this.action) {
      columns.push({
        label: "Action",
        field: "action",
      });
    }

    return {
      checked: false,
      columns: columns,
    };
  },
  methods: {
    editItem(item) {
      console.log(item);
    },
    deleteItem(item) {
      let carts = this.value;
      carts.splice(item.index, 1);
      this.$emit("input", carts);
    },
    goToTopup() {
      $("#modal-cart").modal("hide");
      setTimeout(() => {
        this.$router.push({
          name: "admin.saldo.topup.index",
        });
      }, 100);
    },
    onSubmit() {
      this.onClose();
      this.$emit("submit");
    },
    onClose() {
      $(`#${this.id}`).modal("hide");
    },
  },
  computed: {
    ...mapGetters(["saldo"]),
    total_harga() {
      let harga = 0;
      this.value.map((item) => {
        harga += item.harga * item.qty;
      });
      return harga;
    },
    canSubmit() {
      return this.total_harga <= this.saldo;
    },
    kekurangan_saldo() {
      return this.total_harga - this.saldo;
    },
    btnSubmitDisabled() {
      return !this.checked;
    },
  },
};
</script>
