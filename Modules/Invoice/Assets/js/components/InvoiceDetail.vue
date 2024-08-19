<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">{{ trans("invoices.title.invoices") }}</h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">{{
              trans("core.breadcrumb.home")
            }}</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link
              :to="{
                name: 'admin.invoice.invoices.index',
              }"
            >
              {{ trans("invoices.title.invoices") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">{{ trans("invoices.title.detail") }}</li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <el-tabs v-model="activeTab">
              <el-tab-pane
                :label="trans('invoices.tab.detail invoice')"
                name="detail"
              >
                <invoice
                  v-if="data_invoice.invoice_id != null"
                  :invoice="data_invoice"
                  :loading="loading"
                  @handleSuccessBayar="fetchData"
                >
                </invoice>
              </el-tab-pane>
              <el-tab-pane
                :label="trans('invoices.tab.invoice pelanggan')"
                name="pelanggan"
              >
                <invoicePelanggan
                  v-if="data_invoice.invoice_id != null"
                  :invoice="data_invoice"
                >
                </invoicePelanggan>
              </el-tab-pane>
            </el-tabs>
          </div>
          <div class="card-footer">
            <button
              class="btn btn-danger"
              type="button"
              @click="$router.push({ name: 'admin.invoice.invoices.index' })"
            >
              {{ trans("core.back") }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import InvoiceComponentVue from "./InvoiceComponent.vue";
import InvoicePelangganVue from "./InvoicePelanggan.vue";

export default {
  components: {
    invoice: InvoiceComponentVue,
    invoicePelanggan: InvoicePelangganVue,
  },
  data() {
    return {
      loading: true,
      activeTab: "detail",
      data_invoice: {
        invoice_id: null,
        invoice_no: "",
        periode: "",
        status: "",
        created_at: "",
        settlement_at: "",
        from: {
          company_id: null,
          name: "",
          type: "",
          provinces_id: null,
          regencies_id: null,
          districts_id: null,
          villages_id: null,
          address: "",
          pop_address: "",
          post_code: "",
          display_name: "",
          logo_perusahaan: null,
          company_img: "",
          pop_lat: null,
          pop_lon: null,
          company_lat: null,
          company_lon: null,
          total_endpoint: null,
          rating: null,
        },
        to: {
          company_id: null,
          name: "",
          type: "",
          provinces_id: null,
          regencies_id: null,
          districts_id: null,
          villages_id: null,
          address: "",
          pop_address: "",
          post_code: "",
          display_name: "",
          logo_perusahaan: null,
          company_img: "",
          pop_lat: null,
          pop_lon: null,
          company_lat: null,
          company_lon: null,
          total_endpoint: null,
          rating: null,
        },
        item: [],
      },
    };
  },
  methods: {
    fetchData() {
      axios
        .get(
          route("api.invoice.invoices.show", {
            invoice: this.$route.params.invoice,
          })
        )
        .then((response) => {
          this.loading = false;
          this.data_invoice = response.data.data;
          this.fetchPelangganPending();
        })
        .catch((err) => {
          setTimeout(() => {
            this.fetchData();
          }, 2000);
        });
    },
    fetchPelangganPending(){
       axios
        .get(route("api.invoice.pelanggan.jumlah.pending",{invoice : this.$route.params.invoice}))
        .then((response) => {
          let jumlah = response.data;
          if(jumlah > 0){
            $("#tab-pelanggan").append(`<span class="badge badge-pill badge-danger" style="position:absolute;">${jumlah}</span>`);
          }
        });
    }
  },
  mounted() {
    this.fetchData();
  },
};
</script>

<style></style>
