<template>
  <div>
    <Card
      class="card card-outline-info mt-4 hasil-instalasi"
      v-loading="loading"
      v-if="
        form.jadwal_instalasi.selisih <= 0 &&
          !info &&
          user_roles.name == 'Admin OSP'
      "
    >
      <div class="card-header">
        <div class="card-actions">
          <a data-action="collapse"><i class="ti-minus text-white"></i></a>
        </div>
        <h5 class="text-white">
          {{ trans("pelangganinstalasi.title.hasil") }}
        </h5>
      </div>
      <div class="card-body collapse show">
        <!-- card informatif for osp -->
        <HasilInstalasiInfo v-if="!edit_form_instalasi" />

        <!-- form input for osp -->
        <div class="container">
          <el-form
            v-if="edit_form_instalasi && user_roles.name == 'Admin OSP'"
            size="normal"
            :model="form"
            ref="form"
            style="color: #212529"
            :disabled="status_instalasi"
          >
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
              <!-- PERANGKAT -->
              <div class="row mt-5 mb-4">
                  <div class="col-12">
                    <p>{{ trans("pelangganinstalasi.form.perangkat") }}</p>
                  </div>
                  <div class="col-12">
                    <div :key="perangkat_key">
                      <div
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
                          <div class="col-md-1 d-flex justify-content-end">
                            <el-form-item>
                              <!-- <el-tooltip
                                :content="trans('core.tooltip.remove perangkat')"
                                placement="top"
                              > -->
                                <el-button
                                  @click.prevent="
                                    removePerangkat(index, perangkat.id)
                                  "
                                  type="danger"
                                  size="small"
                                  icon="el-icon-delete"
                                ></el-button>
                              <!-- </el-tooltip> -->
                            </el-form-item>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="pull-right">
                      <!-- <el-tooltip
                          :content="trans('core.tooltip.tambah perangkat')"
                          placement="top"
                        > -->
                        <el-button
                          size="mini"
                          type="primary"
                          @click.prevent="addPerangkat()"
                        >
                          {{ trans("pelangganinstalasi.tambah") }}
                        </el-button>
                      <!-- </el-tooltip> -->
                    </div>
                  </div>
              </div>
              <!-- ALAT -->
              <div class="row mt-5 mb-4">
                <div class="col-12">
                  <p>{{ trans("pelangganinstalasi.form.alat") }}</p>
                </div>
                <div class="col-12">
                  <div :key="alat_key">
                    <div
                      class="row"
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
                            :placeholder="trans('installations.placeholder.jumlah')"
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
                      <div class="col-md-1 d-flex justify-content-end">
                        <el-form-item>
                          <!-- <el-tooltip
                            :content="trans('core.tooltip.remove alat')"
                            placement="top"
                          > -->
                            <el-button
                              @click.prevent="removeAlat(index, alat.id)"
                              type="danger"
                              size="small"
                              icon="el-icon-delete"
                            ></el-button>
                          <!-- </el-tooltip> -->
                        </el-form-item>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="col-12">
                  <div class="pull-right">
                    <!-- <el-tooltip
                      :content="trans('core.tooltip.tambah alat')"
                      placement="top"
                    > -->
                      <el-button
                        size="mini"
                        type="primary"
                        @click.prevent="addAlat()"
                      >
                        {{ trans("pelangganinstalasi.tambah") }}
                      </el-button>
                    <!-- </el-tooltip> -->
                  </div>
                </div>
              </div>
              <!-- DOKUMENTASI -->
              <div class="row mt-5 mb-4">
                <div class="col-12">
                  <p>{{ trans("pelangganinstalasi.form.dokumentasi") }}</p>
                </div>
                <div class="col-12">
                  <div :key="dokumentasi_key">
                    <div
                      v-for="(dokumentasi_show,
                      index) in form.dokumentasi_instalasi"
                      :key="index"
                    >
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
                              message: trans('pelangganinstalasi.form.validasi'),
                              trigger: 'blur',
                            }"
                          >
                            <upload-avatar
                              :onSuccess="
                                (res, file) => handleAvatarSuccess(res, file, index)
                              "
                              :beforeUpload="beforeAvatarUpload"
                              :src="imageUrl"
                              :image="dokumentasi_show.foto_dokumentasi_url"
                            >
                            </upload-avatar>
                          </el-form-item>
                        </div>
                        <div class="col-md-8">
                          <el-form-item
                            :prop="'dokumentasi_instalasi.' + index + '.keterangan'"
                            :rules="{
                              required: true,
                              message: trans('pelangganinstalasi.form.validasi'),
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
                            >
                            </el-input>
                          </el-form-item>
                        </div>
                        <div class="col-md-1 d-flex justify-content-end">
                          <el-form-item>
                            <el-tooltip
                              :content="trans('core.tooltip.remove dokumentasi')"
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
                          </el-form-item>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="pull-right">
                    <!-- <el-tooltip
                      :content="trans('core.tooltip.tambah dokumentasi')"
                      placement="top"
                    > -->
                      <el-button
                        size="mini"
                        type="primary"
                        @click.prevent="addDokumentasi()"
                      >
                        {{ trans("pelangganinstalasi.tambah") }}
                      </el-button>
                    <!-- </el-tooltip> -->
                  </div>
                </div>
              </div>
              <div class="row mt-5" v-if="status_step == 'instalasi'">
                <div class="col-md-12">
                  <el-tooltip
                    :content="
                      trans('core.tooltip.tandai', { tandai: 'instalasi' })
                    "
                    placement="top"
                  >
                    <el-checkbox size="mini" v-model="form.status_instalasi">
                      {{ trans("installations.form.checkbox") }}
                    </el-checkbox>
                  </el-tooltip>
                </div>
              </div>
            </template>
          </el-form>
        </div>
      </div>
      <div
        class="card-footer d-flex justify-content-center"
        v-if="user_roles.name == 'Admin OSP' && !status_instalasi"
      >
        <el-button
          type="danger"
          @click="edit_form_instalasi = false"
          v-if="edit_form_instalasi && show_button"
        >
          {{ trans("pelangganinstalasi.button.batal edit hasil instalasi") }}
        </el-button>
        <el-button
          @click="submitForm('form')"
          type="primary"
          v-if="edit_form_instalasi"
        >
          {{ trans("core.button.save") }}
        </el-button>
        <el-button
          type="primary"
          @click="(edit_form_instalasi = true), (show_button = true)"
          v-if="!edit_form_instalasi && !form.status_instalasi"
        >
          {{ trans("pelangganinstalasi.button.ubah hasil instalasi") }}
        </el-button>
      </div>

      <!-- modal detail pengajuan instalasi oleh osp -->
      <template>
        <div>
          <el-dialog
            :title="trans('pelangganinstalasi.title.detail')"
            :visible.sync="modalDetailPengajuan"
            width="55vmax"
            class="zindex-custom"
          >
            <template>
              <div v-loading="loading">
                <div v-if="form.status_instalasi">
                  <el-alert
                    :title="trans('pelangganinstalasi.info.instalasi selesai')"
                    type="success"
                    effect="dark"
                    style="font-weight:600"
                  >
                  </el-alert>
                </div>
                <table class="table" style="width: 100%;">
                  <tr v-if="jadwal_instalasi.tanggal_instalasi">
                    <td style="width:180px;font-weight: 500;padding:10px 0px">
                      {{ trans("pelangganinstalasi.form.tgl instalasi") }}
                    </td>
                    <td style="width: 10px;padding: 10px 0px;">:</td>
                    <td>
                      {{ jadwal_instalasi.tanggal_instalasi }}
                    </td>
                  </tr>
                  <tr v-if="jadwal_instalasi.slot_instalasi">
                    <td style="width:180px;font-weight: 500;padding:10px 0px">
                      {{ trans("pelangganinstalasi.form.slot") }}
                    </td>
                    <td style="width: 10px;padding: 10px 0px;">:</td>
                    <td>
                      {{ jadwal_instalasi.slot_instalasi }}
                    </td>
                  </tr>
                  <tr v-if="jadwal_instalasi.teknisi.length > 0">
                    <td style="width:180px;font-weight: 500;padding:10px 0px">
                      {{ trans("pelangganinstalasi.form.teknisi") }}
                    </td>
                    <td style="width: 10px;padding: 10px 0px;">:</td>
                    <td>
                      <div>
                        <span
                          class="span_noc"
                          v-for="(item, index) in jadwal_instalasi.teknisi"
                          :key="index"
                        >
                          {{ item.nama_teknisi }}
                        </span>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td
                      style="width: 100px;font-weight: 500;padding:10px 0px"
                      class="cellpadding-custom"
                    >
                      {{ trans("pelangganinstalasi.form.keterangan") }}
                    </td>
                    <td
                      style="width: 10px;padding:10px 0px"
                      class="cellpadding-custom"
                    >
                      :
                    </td>
                    <td colspan="2" class="cellpadding-custom">
                      {{
                        form.keterangan_instalasi
                          ? form.keterangan_instalasi
                          : trans("pelangganinstalasi.info.tidak_ada")
                      }}
                    </td>
                  </tr>
                </table>

                <el-tabs v-model="tabActiveName">
                  <el-tab-pane label="Perangkat" name="perangkat">
                    <PreviewPerangkat
                      :perangkats="perangkat_instalasi_digunakan"
                    />
                  </el-tab-pane>
                  <el-tab-pane label="Alat" name="alat">
                    <PreviewAlat :alats="alat_instalasi_digunakan" />
                  </el-tab-pane>
                  <el-tab-pane label="Dokumentasi" name="dokumentasi">
                    <PreviewFotoDokumentasi
                      :dokumentasi="form.dokumentasi_instalasi"
                    />
                  </el-tab-pane>
                </el-tabs>
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
    </Card>

    <Card
      class="card card-outline-info mt-4 hasil-instalasi"
      v-loading="loading"
      v-if="user_roles.name == 'Admin ISP' && !edit_form_instalasi"
    >
      <div class="card-header">
        <div class="card-actions">
          <a data-action="collapse"><i class="ti-minus text-white"></i></a>
        </div>
        <h5 class="text-white">
          {{ trans("pelangganinstalasi.title.hasil") }}
        </h5>
      </div>
      <div class="card-body collapse show">
        <!-- card informatif for osp -->
        <HasilInstalasiInfo />
      </div>
    </Card>
  </div>
</template>

<script>
import Form from "form-backend-validation";
import SelectPerangkatVue from "../../../../../../Core/Assets/js/components/SelectPerangkat.vue";
import SelectAlatVue from "../../../../../../Core/Assets/js/components/SelectAlat.vue";
import TranslationHelper from "../../../../../../Core/Assets/js/mixins/TranslationHelper";
import HasilInstalasiInfo from "./HasilInstalasiInfo";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";
import PreviewPerangkat from "./PreviewPerangkat.vue";
import PreviewAlat from "./PreviewAlat.vue";
import PreviewFotoDokumentasi from "./PreviewFotoDokumentasi.vue";
import { mapGetters } from "vuex";

export default {
  props: ["disableButton", "info"],
  mixins: [TranslationHelper],
  components: {
    SelectPerangkat: SelectPerangkatVue,
    SelectAlat: SelectAlatVue,
    HasilInstalasiInfo,
    Toast,
    PreviewPerangkat,
    PreviewAlat,
    PreviewFotoDokumentasi,
  },
  data() {
    return {
      tabActiveName: "perangkat",
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
      status_instalasi: false,

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
        Alat: [],
        Perangkat: [],
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
    getNamePerangkat: function(id) {
      var a = this.form_option.Perangkat.find((s) => {
        if (s.perangkat_id == id) {
          return s.nama_perangkat;
        }
      });
      if (a) {
        return a.nama_perangkat;
      }
      return null;
    },
    getNameAlat: function(id) {
      var a = this.form_option.Alat.find((s) => {
        if (s.alat_id == id) {
          return s.nama_alat;
        }
      });
      if (a) {
        return a.nama_alat;
      }
      return null;
    },
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
          this.modalDetailPengajuan = false;
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
          this.status_instalasi = status_instalasi;
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
  computed: {
    ...mapGetters(["jadwal_instalasi"]),
    perangkat_instalasi_digunakan: function() {
      return this.form.perangkat_instalasi_digunakan.map((perangkat) => {
        perangkat.nama_perangkat = this.getNamePerangkat(
          perangkat.perangkat_id
        );
        return perangkat;
      });
    },
    alat_instalasi_digunakan: function() {
      return this.form.alat_instalasi_digunakan.map((perangkat) => {
        perangkat.nama_alat = this.getNameAlat(perangkat.alat_id);
        return perangkat;
      });
    },
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
.font-custom {
  font-weight: 500;
  color: #212529;
}
</style>
