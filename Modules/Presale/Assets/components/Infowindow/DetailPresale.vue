<template>
  <div :id="'infoWindowRumah' + presale.presale_id" v-if="kondisi">
    <table class="table-informasi">
      <tr>
        <td colspan="3" style="padding-bottom:5px;">
          <button
            type="button"
            class="btn btn-default btn-sm"
            style="width:100%"
          >
            Status - {{ presale.status_name }}
          </button>
        </td>
      </tr>

      <tr>
        <td valign="top">
          <b>Site ID</b>
        </td>
        <td valign="top">:</td>
        <td valign="top">
          <b>{{ presale.site_id }}</b>
          <a
            class="text-primary"
            href="#!"
            :onclick="`copyToClipboard('${presale.site_id}')`"
            >copy</a
          >
        </td>
      </tr>
      <tr>
        <td valign="top">
          <b>Tipe</b>
        </td>
        <td valign="top">:</td>
        <td valign="top">
          {{ presale.class_name }} <b>{{ presale.nama_gang }}</b>
        </td>
      </tr>

      <tr>
        <td valign="top">
          <b>Alamat</b>
        </td>
        <td valign="top">:</td>
        <td valign="top">
          {{ presale.address }}
        </td>
      </tr>
      <tr>
        <td valign="top">
          <b>Jarak ke endpoint</b>
        </td>
        <td valign="top">:</td>
        <td valign="top">{{ presale.panjang_kabel }} M</td>
      </tr>
      {{
        presale.keterangan
      }}
    </table>

    <div class="btn-edit-marker-odp d-flex justify-content-center">
      `
      <button
        type="button"
        :onclick="`editLokasiMarker(this)`"
        :data-id="presale.presale_id"
        data-type="2"
        class="btn btn-primary btn-sm btn-action-marker"
        style="margin-left:-3.3px;"
      >
        <i class="fa fa-arrows-alt"></i>
      </button>
      <button
        type="button"
        :onclick="`editDataMarker('${presale.presale_id}', 2)`"
        class="btn-action-marker btn btn-warning btn-sm"
      >
        <i class="fa fa-edit"></i>
      </button>
      <button
        type="button"
        :onclick="`hapusDataMarker('${presale.presale_id}', 2)`"
        class="btn-action-marker btn btn-danger btn-sm"
      >
        <i class="fa fa-trash"></i>
      </button>

      <button
        type="button"
        :onclick="`showRoutes('${presale.presale_id}')`"
        class="btn btn-info btn-sm mx-1"
      >
        <i class="fa fa-level-up-alt mr-1"></i>
        {{ trans("presales.jalur kabel") }}
      </button>
      <button
        type="primary"
        icon="fa fa-shopping-cart"
        :onclick="`showFormPelanggan('${presale.presale_id}')`"
        class="btn btn-primary btn-sm mx-1"
        style="margin-left:-3.3px;"
      >
        <i class="fa fa-shopping-cart mr-1"> </i>
        {{ trans("presales.order") }}
      </button>

      <button
        type="button"
        :onclick="`showRoutes('${presale.presale_id}')`"
        class="btn btn-primary btn-sm btn-action-marker"
      >
        <i class="fa fa-level-up-alt"></i>
      </button>

      <button
        type="button"
        :onclick="`addListConfirmation('${presale.presale_id}')`"
        class="btn btn-info btn-sm btn-action-marker"
        style="margin-left:-3.3px;"
      >
        <i class="fa fa-check"></i>
      </button>
    </div>
  </div>
</template>

<script>
import TranslationHelper from "../../../../../public/modules/core/js/mixins/TranslationHelper";
export default {
  props: ["presale"],
  mixins: [TranslationHelper],
  data() {
    return {
      kondisi: false,
    };
  },
  mounted() {
    console.log(this.presale);
    setTimeout(() => {
      this.kondisi = true;
    }, 5000);
  },
};
</script>

<style></style>
