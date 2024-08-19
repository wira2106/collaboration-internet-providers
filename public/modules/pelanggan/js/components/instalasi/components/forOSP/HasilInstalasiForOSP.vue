<template>
  <div class="card card-outline-info mt-4" v-loading="loading">
    <div class="card-header">
      <div class="card-actions">
        <a data-action="collapse"><i class="ti-minus text-white"></i></a>
      </div>
      <h5 class="text-white">
        {{ trans("pelangganinstalasi.title.hasil") }}
      </h5>
    </div>
    <div>
      <div class="card-body" v-if="form.jadwal_instalasi.selisih <= 0">
        <div class="mb-4" :hidden="status_step != 'instalasi'">
          <div class="float-right mb-3" v-if="!edit_form_instalasi">
            <el-button
              type="primary"
              size="small"
              @click="(edit_form_instalasi = true), (show_button = true)"
            >
              Ubah data
            </el-button>
          </div>
          <div
            class="float-right mb-3"
            v-if="edit_form_instalasi && show_button"
          >
            <el-button
              type="primary"
              size="small"
              @click="edit_form_instalasi = false"
            >
              Batal ubah data
            </el-button>
          </div>
        </div>

        <!-- card informatif for osp -->
        <HasilInstalasiInforOSP v-if="!edit_form_instalasi" />

        <!-- form input for osp -->
        <el-form
          v-if="edit_form_instalasi"
          size="normal"
          :model="form"
          ref="form"
          :disabled="status_step != 'instalasi'"
        >
          <div v-if="form.jadwal_instalasi.selisih <= 0">
            <div style="color: #212529">
              <p>{{ trans("pelangganinstalasi.form.keterangan") }}</p>
              <el-form-item prop="keterangan_instalasi">
                <el-input
                  type="textarea"
                  ref="keterangan"
                  v-model="form.keterangan_instalasi"
                >
                </el-input>
              </el-form-item>
              <template>
                <p>{{ trans("pelangganinstalasi.form.perangkat") }}</p>
                <hr />
                <div :key="perangkat_key">
                  <div
                    class="mt-3"
                    v-for="(perangkat,
                    index) in form.perangkat_instalasi_digunakan"
                    :key="index"
                  >
                    <div class="row">
                      <div class="col-md-5">
                        <el-form-item
                          :prop="
                            'perangkat_instalasi_digunakan.' +
                              index +
                              '.perangkat_id'
                          "
                          :rules="[
                            {
                              required: true,
                              trigger: 'change',
                              message: trans('core.form.required'),
                            },
                          ]"
                        >
                          <SelectPerangkat
                            v-if="form_option.Perangkat != null"
                            v-model="perangkat.perangkat_id"
                            :list_perangkat="form_option.Perangkat"
                            :reference="'nama_perangkat' + index"
                            :update_list="
                              (result) => {
                                this.form_option.Perangkat = result;
                              }
                            "
                          ></SelectPerangkat>
                        </el-form-item>
                      </div>
                      <div class="col-md-2">
                        <el-form-item
                          :prop="
                            'perangkat_instalasi_digunakan.' + index + '.qty'
                          "
                          :rules="[
                            {
                              required: true,
                              trigger: 'change',
                              message: trans('core.form.required'),
                            },
                          ]"
                        >
                          <el-input
                            size="small"
                            type="number"
                            :placeholder="
                              trans('installations.placeholder.jumlah')
                            "
                            :min="0"
                            v-model="perangkat.qty"
                            ref="qty_perangkat"
                          >
                          </el-input>
                        </el-form-item>
                      </div>
                      <div class="col-md-2">
                        <el-form-item
                          :prop="
                            'perangkat_instalasi_digunakan.' +
                              index +
                              '.jenis_perangkat'
                          "
                          :rules="[
                            {
                              required: true,
                              trigger: 'change',
                              message: trans('core.form.required'),
                            },
                          ]"
                        >
                          <el-select
                            placeholder="jenis"
                            v-model="perangkat.jenis_perangkat"
                            size="small"
                            ref="jenis_perangkat"
                          >
                            <el-option
                              v-for="item in JenisPerangkatOpsi"
                              :label="item.label"
                              :value="item.value"
                              :key="item.value"
                            ></el-option>
                          </el-select>
                        </el-form-item>
                      </div>
                      <div class="col-md-2">
                        <el-form-item
                          :prop="
                            'perangkat_instalasi_digunakan.' + index + '.status'
                          "
                          :rules="[
                            {
                              required: true,
                              trigger: 'change',
                              message: trans('core.form.required'),
                            },
                          ]"
                        >
                          <el-select
                            placeholder="status"
                            size="small"
                            v-model="perangkat.status"
                            ref="status"
                          >
                            <el-option
                              v-for="statusPerangkatOpsi in statusBPOpsi"
                              :label="statusPerangkatOpsi.label"
                              :value="statusPerangkatOpsi.value"
                              :key="statusPerangkatOpsi.value"
                            ></el-option>
                          </el-select>
                        </el-form-item>
                      </div>
                      <div class="col-md-1">
                        <el-form-item>
                          <el-tooltip
                            :content="trans('core.tooltip.remove perangkat')"
                            placement="top"
                          >
                            <el-button
                              @click.prevent="
                                removePerangkat(index, perangkat.id)
                              "
                              type="danger"
                              size="small"
                              icon="el-icon-delete"
                            ></el-button>
                          </el-tooltip>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-12">
                    <div class="float-right">
                      <el-tooltip
                        :content="trans('core.tooltip.tambah perangkat')"
                        placement="top"
                      >
                        <el-button
                          size="mini"
                          type="primary"
                          @click.prevent="addPerangkat()"
                        >
                          {{ trans("pelangganinstalasi.tambah") }}
                        </el-button>
                      </el-tooltip>
                    </div>
                  </div>
                </div>
                <br />
                <p>{{ trans("pelangganinstalasi.form.alat") }}</p>
                <hr />
                <div :key="alat_key">
                  <div
                    class="row mt-3"
                    v-for="(alat, index) in form.alat_instalasi_digunakan"
                    :key="index"
                  >
                    <div class="col-md-5">
                      <el-form-item
                        :prop="'alat_instalasi_digunakan.' + index + '.alat_id'"
                        :rules="[
                          {
                            required: true,
                            trigger: 'change',
                            message: trans('core.form.required'),
                          },
                        ]"
                      >
                        <SelectAlat
                          v-if="form_option.Alat != null"
                          v-model="alat.alat_id"
                          :list_alat="form_option.Alat"
                          :reference="'nama_alat' + index"
                          :update_list="
                            (result) => {
                              this.form_option.Alat = result;
                            }
                          "
                        ></SelectAlat>
                      </el-form-item>
                    </div>
                    <div class="col-md-4">
                      <el-form-item
                        :prop="'alat_instalasi_digunakan.' + index + '.qty'"
                        :rules="[
                          {
                            required: true,
                            trigger: 'change',
                            message: trans('core.form.required'),
                          },
                        ]"
                      >
                        <el-input
                          size="small"
                          type="number"
                          :placeholder="
                            trans('installations.placeholder.jumlah')
                          "
                          :min="0"
                          v-model="alat.qty"
                          ref="qty_alat"
                        >
                        </el-input>
                      </el-form-item>
                    </div>
                    <div class="col-md-2">
                      <el-form-item
                        :prop="'alat_instalasi_digunakan.' + index + '.status'"
                        :rules="[
                          {
                            required: true,
                            trigger: 'change',
                            message: trans('core.form.required'),
                          },
                        ]"
                      >
                        <el-select
                          placeholder="status"
                          size="small"
                          v-model="alat.status"
                          ref="statusalat"
                        >
                          <el-option
                            v-for="statusAlatOpsi in statusBPOpsi"
                            :label="statusAlatOpsi.label"
                            :value="statusAlatOpsi.value"
                            :key="statusAlatOpsi.value"
                          ></el-option>
                        </el-select>
                      </el-form-item>
                    </div>
                    <div class="col-md-1">
                      <el-form-item>
                        <el-tooltip
                          :content="trans('core.tooltip.remove alat')"
                          placement="top"
                        >
                          <el-button
                            @click.prevent="removeAlat(index, alat.id)"
                            type="danger"
                            size="small"
                            icon="el-icon-delete"
                          ></el-button>
                        </el-tooltip>
                      </el-form-item>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-12">
                    <div class="float-right">
                      <el-tooltip
                        :content="trans('core.tooltip.tambah alat')"
                        placement="top"
                      >
                        <el-button
                          size="mini"
                          type="primary"
                          @click.prevent="addAlat()"
                        >
                          {{ trans("pelangganinstalasi.tambah") }}
                        </el-button>
                      </el-tooltip>
                    </div>
                  </div>
                </div>
                <br />
                <p>{{ trans("pelangganinstalasi.form.dokumentasi") }}</p>
                <div :key="dokumentasi_key">
                  <div
                    v-for="(dokumentasi_show,
                    index) in form.dokumentasi_instalasi"
                    :key="index"
                  >
                    <div class="mb-5">
                      <div class="row">
                        <div class="col-md-3">
                          <el-form-item
                            :prop="
                              'dokumentasi_instalasi.' +
                                index +
                                '.foto_dokumentasi_url'
                            "
                            :rules="{
                              required: true,
                              message: trans(
                                'pelangganinstalasi.form.validasi'
                              ),
                              trigger: 'blur',
                            }"
                          >
                            <upload-avatar
                              :onSuccess="
                                (res, file) =>
                                  handleAvatarSuccess(res, file, index)
                              "
                              :beforeUpload="beforeAvatarUpload"
                              :src="imageUrl"
                              :image="dokumentasi_show.foto_dokumentasi_url"
                            >
                            </upload-avatar>
                          </el-form-item>
                        </div>
                        <div class="col-md-9">
                          <div class="ml-2">
                            <el-form-item
                              :prop="
                                'dokumentasi_instalasi.' + index + '.keterangan'
                              "
                              :rules="{
                                required: true,
                                message: trans(
                                  'pelangganinstalasi.form.validasi'
                                ),
                                trigger: 'blur',
                              }"
                            >
                              <el-input
                                type="textarea"
                                :placeholder="
                                  trans('installations.placeholder.keterangan')
                                "
                                v-model="dokumentasi_show.keterangan"
                                :ref="'keterangan_dokumentasi' + index"
                                :rows="7"
                              >
                              </el-input>
                            </el-form-item>
                            <el-form-item>
                              <div class="float-right">
                                <el-tooltip
                                  :content="
                                    trans('core.tooltip.remove dokumentasi')
                                  "
                                  placement="top"
                                >
                                  <el-button
                                    size="small"
                                    icon="el-icon-delete"
                                    type="danger"
                                    @click.prevent="
                                      removeDokumentasi(
                                        index,
                                        dokumentasi_show.dokumentasi_id
                                      )
                                    "
                                  >
                                  </el-button>
                                </el-tooltip>
                              </div>
                            </el-form-item>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-5">
                  <div class="col-md-12">
                    <div class="float-right">
                      <el-tooltip
                        :content="trans('core.tooltip.tambah dokumentasi')"
                        placement="top"
                      >
                        <el-button
                          size="mini"
                          type="primary"
                          @click.prevent="addDokumentasi()"
                        >
                          {{ trans("pelangganinstalasi.tambah") }}
                        </el-button>
                      </el-tooltip>
                    </div>
                  </div>
                </div>
                <div class="row mt-5">
                  <div class="col-md-12">
                    <hr />
                    <el-tooltip
                      :content="
                        trans('core.tooltip.tandai', { tandai: 'instalasi' })
                      "
                      placement="top"
                    >
                      <el-checkbox
                        size="mini"
                        v-model="form.status_instalasi"
                        >{{ trans("installations.form.checkbox") }}</el-checkbox
                      >
                    </el-tooltip>
                  </div>
                </div>
              </template>
            </div>
          </div>
          <br />
          <div class="card-footer">
            <el-button
              @click="submitForm('form')"
              type="primary"
              :disabled="status_step != 'instalasi'"
              >Simpan
            </el-button>
            <el-button
              @click="edit_form_instalasi = false"
              type="info"
              :disabled="status_step != 'instalasi'"
              v-if="edit_form_instalasi && show_button"
              >Batal
            </el-button>
          </div>
        </el-form>
      </div>
    </div>

    <!-- modal detail pengajuan instalasi oleh osp -->
    <template>
      <div>
        <el-dialog
          :title="pelangganinstalasi.title.detail"
          :visible.sync="modalDetailPengajuan"
          width="80%"
          class="zindex-custom"
        >
          <template>
            <div v-loading="loading">
              <table class="table-custom" style="width: 100%;">
                <tr class="py-5">
                  <td
                    style="width: 250px;font-weight: 500;"
                    class="cellpadding-custom"
                  >
                    {{ trans("pelangganinstalasi.form.keterangan") }}
                  </td>
                  <td style="width: 10px;" class="cellpadding-custom">
                    :
                  </td>
                  <td colspan="2" class="cellpadding-custom">
                    {{ form.keterangan_instalasi }}
                  </td>
                </tr>
                <template v-if="form.dokumentasi_instalasi.length > 0">
                  <tr>
                    <td
                      style="width: 250px;font-weight: 500;"
                      class="cellpadding-custom"
                    >
                      {{ trans("pelangganinstalasi.form.dokumentasi") }}
                    </td>

                    <td class="cellpadding-custom">:</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="3">
                      <div
                        class="row"
                        v-for="(dokumentasi_show,
                        index) in form.dokumentasi_instalasi"
                        :key="index"
                      >
                        <!-- start image pelanggan instalasi -->
                        <div class="col-md-4">
                          <a
                            :href="dokumentasi_show.foto_dokumentasi_url"
                            class="fancybox"
                          >
                            <div
                              class="image-preview attachments-image"
                              :style="{
                                'background-image':
                                  'url(' +
                                  dokumentasi_show.foto_dokumentasi_url +
                                  ')',
                              }"
                            ></div>
                          </a>
                        </div>
                        <div class="col-md-5">
                          <p style="width: 250px;font-weight: 500;">
                            Keterangan :
                          </p>
                          {{ dokumentasi_show.keterangan }}
                          <br />
                          <br />
                        </div>
                        <!-- end image pelanggan instalasi -->
                      </div>
                    </td>
                  </tr>
                </template>
              </table>

              <div v-if="form.perangkat_instalasi_digunakan.length > 0">
                <br />
                <p class="p-custom">
                  {{ trans("pelangganinstalasi.form.perangkat") }}
                </p>
                <table class="table">
                  <thead>
                    <td>
                      {{ trans("pelangganinstalasi.table perangkat.nama") }}
                    </td>
                    <td>
                      {{ trans("pelangganinstalasi.table perangkat.jumlah") }}
                    </td>
                    <td>
                      {{ trans("pelangganinstalasi.table perangkat.jenis") }}
                    </td>
                    <td>
                      {{ trans("pelangganinstalasi.table perangkat.status") }}
                    </td>
                  </thead>
                  <tr
                    v-for="(p, index) in form.perangkat_instalasi_digunakan"
                    :key="index"
                  >
                    <td>{{ p.nama_perangkat }}</td>
                    <td>{{ p.qty }}</td>
                    <td>{{ p.jenis_perangkat }}</td>
                    <td>{{ p.status }}</td>
                  </tr>
                </table>
              </div>
              <div v-if="form.alat_instalasi_digunakan.length > 0">
                <br />
                <p class="p-custom">
                  {{ trans("pelangganinstalasi.form.alat") }}
                </p>
                <table class="table">
                  <thead>
                    <td>
                      {{ trans("pelangganinstalasi.table alat.nama") }}
                    </td>
                    <td>
                      {{ trans("pelangganinstalasi.table alat.jumlah") }}
                    </td>
                    <td>
                      {{ trans("pelangganinstalasi.table alat.status") }}
                    </td>
                  </thead>
                  <tr
                    v-for="(a, index) in form.alat_instalasi_digunakan"
                    :key="index"
                  >
                    <td>{{ a.nama_alat }}</td>
                    <td>{{ a.qty }}</td>
                    <td>{{ a.status }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </template>
          <span slot="footer" class="dialog-footer">
            <el-button type="danger" @click="modalDetailPengajuan = false">{{
              trans("core.button.cancel")
            }}</el-button>
            <el-button type="primary" @click="submitFormSend()">{{
              trans("core.button.save")
            }}</el-button>
          </span>
        </el-dialog>
      </div>
    </template>
  </div>
</template>

<script>
import Form from "form-backend-validation";
import SelectPerangkatVue from "../../../../../../../Core/Assets/js/components/SelectPerangkat.vue";
import SelectAlatVue from "../../../../../../../Core/Assets/js/components/SelectAlat.vue";
import TranslationHelper from "../../../../../../../Core/Assets/js/mixins/TranslationHelper";
import HasilInstalasiInforOSP from "./HasilInstalasiInforOSP";

export default {
  props: ["disableButton"],
  mixins: [TranslationHelper],
  components: {
    SelectPerangkat: SelectPerangkatVue,
    SelectAlat: SelectAlatVue,
    HasilInstalasiInforOSP,
  },
  data() {
    return {
      formData: new Form(),
      status_step: "",
      acceptedPerubahan: false,
      loadingPengajuan: true,
      loading: true,
      dokumentasi_key: 1,
      alat_key: 2,
      perangkat_key: 3,
      imageUrl: "",
      keterangan: "",
      disable: false,
      edit_form_instalasi: false,
      modalDetailPengajuan: false,
      show_button: false,

      //data user yang terdata di database
      form: {
        instalasi_id: "",
        jadwal_instalasi: {},
        status_instalasi: "",
        keterangan_instalasi: "",
        perangkat_instalasi_digunakan: [],
        alat_instalasi_digunakan: [],
        dokumentasi_instalasi: [],
      },

      //opsi pilihan for user
      form_option: {
        DataTeknisi: [],
        Alat: null,
        Perangkat: null,
        Dokumentasi: [],
      },

      //validasi form
      validasiForm: {
        imageUrl: [
          {
            required: true,
            message: this.trans("core.form.required"),
            trigger: "blur",
          },
        ],
      },

      //jenis perangkat opsi
      JenisPerangkatOpsi: [
        {
          label: "ONT",
          value: "ONT",
        },
        {
          label: "Akses Point",
          value: "akses point",
        },
      ],

      //status opsi for perangkat dan alat
      statusBPOpsi: [
        {
          label: "Dibeli",
          value: "dibeli",
        },
        {
          label: "Dipinjamkan",
          value: "dipinjamkan",
        },
      ],

      //status instalasi for user
      Status: [],
      StatusOpsi: [
        {
          label: "Proses",
          value: "active",
        },
        {
          label: "Finish",
          value: "finish",
        },
      ],
    };
  },
  methods: {
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        let fields = this.$refs[formName].fields;
        for (let i = 0; i < fields.length; i++) {
          if (fields[i].validateState == "error") {
            $(fields[i].$el)
              .find("input")
              .focus();
            return false;
          }
        }
        if (valid) {
          this.modalDetailPengajuan = true;
        } else {
          this.submitForm(formName);
        }
      });
    },
    submitFormSend() {
      Swal.fire({
        title: this.trans("pelangganinstalasi.info.simpan data instalasi"),
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.ya"),
        cancelButtonText: this.trans("core.tidak"),
      }).then((result) => {
        if (result.value) {
          this.loading = true;
          this.formData = new Form(this.form);
          this.formData
            .post(route("api.pelanggan.form.data.instalasi.save"))
            .then((res) => {
              if (res.next == false) {
                Swal.fire({
                  toast: true,
                  icon: "success",
                  position: "top-end",
                  timer: 3000,
                  type: "success",
                  showConfirmButton: false,
                  title: this.trans("pelangganinstalasi.alert.sukses"),
                });
                this.loadDataPelangganInstalasi();
                this.edit_form_instalasi = false;
                this.loading = false;
              } else {
                if (res.error == false) {
                  Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    timer: 3000,
                    type: "success",
                    showConfirmButton: false,
                    title: this.trans("pelangganinstalasi.alert.sukses"),
                  });
                  window.location =
                    route("admin.pelanggan.form.step") +
                    "?pelanggan=" +
                    this.$route.query.pelanggan;
                } else {
                  this.loading = false;
                  this.loadDataPelangganInstalasi();
                  this.loadDataPerangkatAlatTeknisiDokumentasi();
                }
              }
            })
            .catch((error) => {
              this.loading = false;
              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title"),
              });
            });
        } else {
          this.loading = false;
        }
      });
    },
    addPerangkat(perangkats = null) {
      this.form.perangkat_instalasi_digunakan.push({
        id: null,
        perangkat_id: perangkats,
        is_new: true,
        status: null,
      });
    },
    removePerangkat(index, id) {
      if (id != null) {
        const params = {
          perangkat_id: id,
        };

        Swal.fire({
          title: this.trans("core.messages.confirmation-form"),
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: this.trans("core.button.confirm"),
          cancelButtonText: this.trans("core.button.cancel"),
        }).then((result) => {
          if (result.value) {
            axios
              .post(route("api.pelanggan.form.data.instalasi.delete"), params)
              .then((res) => {
                this.form.perangkat_instalasi_digunakan.splice(index, 1);
                Swal.fire({
                  toast: true,
                  icon: "success",
                  position: "top-end",
                  timer: 3000,
                  type: "success",
                  showConfirmButton: false,
                  title: this.trans("pelangganinstalasi.alert.sukses"),
                });
              });
          } else {
            console.log("Cancel Successfully");
          }
        });
      } else {
        this.form.perangkat_instalasi_digunakan.splice(index, 1);
      }
    },
    addAlat(alats = null) {
      this.form.alat_instalasi_digunakan.push({
        id: null,
        alat_id: alats,
        is_new: true,
        status: null,
      });
    },
    removeAlat(index, id) {
      if (id != null) {
        const params = {
          alat_id: id,
        };
        Swal.fire({
          title: this.trans("core.messages.confirmation-form"),
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: this.trans("core.button.confirm"),
          cancelButtonText: this.trans("core.button.cancel"),
        }).then((result) => {
          if (result.value) {
            axios
              .post(route("api.pelanggan.form.data.instalasi.delete"), params)
              .then((res) => {
                this.form.alat_instalasi_digunakan.splice(index, 1);
                Swal.fire({
                  toast: true,
                  icon: "success",
                  position: "top-end",
                  timer: 3000,
                  type: "success",
                  showConfirmButton: false,
                  title: this.trans("pelangganinstalasi.alert.sukses"),
                });
              });
          } else {
            console.log("Cancel Successfully");
          }
        });
      } else {
        this.form.alat_instalasi_digunakan.splice(index, 1);
      }
    },
    addDokumentasi() {
      this.form.dokumentasi_instalasi.push({
        dokumentasi_id: null,
        fotoUpload: true,
        foto_dokumentasi: null,
        foto_dokumentasi_url: "",
        keterangan: "",
      });
    },
    removeDokumentasi(index, id) {
      const params = {
        dokumentasi_id: id,
      };
      if (id !== null) {
        Swal.fire({
          title: this.trans("core.messages.confirmation-form"),
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: this.trans("core.button.confirm"),
          cancelButtonText: this.trans("core.button.cancel"),
        }).then((result) => {
          if (result.value) {
            axios
              .post(route("api.pelanggan.form.data.instalasi.delete"), params)
              .then((res) => {
                this.form.dokumentasi_instalasi.splice(index, 1);
                this.form_option.Dokumentasi.splice(index, 1);
                Swal.fire({
                  toast: true,
                  icon: "success",
                  position: "top-end",
                  timer: 3000,
                  type: "success",
                  showConfirmButton: false,
                  title: this.trans("pelangganinstalasi.alert.sukses"),
                });
              });
          } else {
            console.log("Cancel Successfully");
          }
        });
      } else {
        this.form_option.Dokumentasi.splice(index, 1);
        this.form.dokumentasi_instalasi.splice(index, 1);
      }
    },
    handleAvatarSuccess(res, file, index) {
      this.imageUrl = URL.createObjectURL(file.raw);
      this.form.dokumentasi_instalasi[
        index
      ].foto_dokumentasi_url = this.imageUrl;
      this.form.dokumentasi_instalasi[index].fotoUpload = file;
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === "image/jpeg" || file.type === "image/png";
      // const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error("Avatar picture must be JPG/PNG format!");
      }
      // if (!isLt2M) {
      //   this.$message.error("Avatar picture size can not exceed 2MB!");
      // }
      return isJPG;
    },
    loadDataPelangganInstalasi() {
      const params = this.$route.query.pelanggan;
      axios
        .get(
          route("api.pelanggan.form.data.instalasi", {
            pelanggan: params,
          })
        )
        .then((response) => {
          this.status_step = response.data.status;
          var res = response.data;
          var status_instalasi;

          if (
            res.data_alat.length == 0 &&
            res.data_perangkat.length == 0 &&
            res.dokumentasi.length == 0 &&
            res.keterangan_instalasi == null
          ) {
            this.edit_form_instalasi = true;
            this.show_button = false;
          }

          if (res.status_instalasi) {
            this.$emit("status_instalasi", res.status_instalasi);
          }

          if (res.cancel == "1") {
            this.disable = true;
          }

          if (res.status_instalasi === "finish") {
            status_instalasi = true;
          } else {
            status_instalasi = false;
          }
          this.form = {
            instalasi_id: res.instalasi_id,
            status_instalasi: status_instalasi,
            keterangan_instalasi: res.keterangan_instalasi,
            jadwal_instalasi:
              res.jadwal_instalasi[res.jadwal_instalasi.length - 1],
            perangkat_instalasi_digunakan: res.data_perangkat,
            alat_instalasi_digunakan: res.data_alat,
            dokumentasi_instalasi: res.dokumentasi,
            // teknisi_instalasi: teknisi_push_to_array,
          };
          this.loading = false;
        });
    },
    loadDataPerangkatAlatTeknisiDokumentasi() {
      var pelanggan_id = this.$route.query.pelanggan;
      axios
        .get(
          route("api.pelanggan.form.data.instalasi.data.instalasi", {
            pelanggan: pelanggan_id,
          })
        )
        .then((res) => {
          this.form_option = {
            // DataTeknisi: res.data.data_teknisi,
            Alat: res.data.data_alat,
            Perangkat: res.data.data_perangkat,
            Dokumentasi: res.data.dokumentasi,
          };
        });
    },
  },
  watch: {
    disableButton() {
      this.disable = this.disableButton;
    },
  },
  mounted() {
    this.loadDataPelangganInstalasi();
    this.loadDataPerangkatAlatTeknisiDokumentasi();
  },
};
</script>

<style>
.span_noc {
  border: 1px solid #b0b0ff;
  border-radius: 5px;
  padding: 0px 5px;
  margin-right: 2px;
}
.el-tooltip__popper {
  z-index: 4000 !important;
}
.swal2-container {
  z-index: 100000 !important;
}
</style>
