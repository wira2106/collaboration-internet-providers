<template>
  <div class="card mt-4" v-loading="loading">
    <div
      class="card-body"
      v-if="
        form.teknisi_instalasi.length > 0 &&
          form.status_instalasi !== 'finish' &&
          user_roles.name == 'Admin ISP'
      "
    >
      <table class="table">
        <tr class="py-1">
          <th style="width: 250px; font-size: 14px;">
            <h4>{{ trans("pelangganinstalasi.form.teknisi") }}</h4>
          </th>
          <td style="width: 10px;">:</td>
          <td>
            <div v-for="(data, index) in form_show.DataTeknisi" :key="index">
              <span style="font-size: 14px;font-weight: 700;display: block;">
                {{ data.user_name }}
              </span>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div
      class="card-body"
      v-if="
        form.status_instalasi !== 'finish' && user_roles.name == 'Admin OSP'
      "
    >
      <el-form :model="form" ref="form" :rules="validasiForm">
        <h4>{{ trans("pelangganinstalasi.form.teknisi") }}</h4>
        <el-form-item prop="teknisi_instalasi">
          <el-select
            v-if="select_teknisi"
            v-model="form.teknisi_instalasi"
            size="small"
            @change="changeTeknisi"
            filterable
            multiple
            :disabled="
              user_roles.name == 'Admin ISP' ||
                form.status_instalasi == 'finish'
            "
          >
            <el-option
              v-for="(item, index) in form_show.DataTeknisi"
              :label="item.user_name"
              :value="item.user_name"
              :key="index"
            ></el-option>
            <el-option label="+ tambah Teknisi" value="new"></el-option>
          </el-select>
          <input v-else type="text" class="form-control form-control-sm" />
        </el-form-item>
      </el-form>
      <hr />
    </div>
    <div>
      <div class="card-body" v-if="form.jadwal_instalasi.selisih <= 0">
        <h4>{{ trans("pelangganinstalasi.title.hasil") }}</h4>
        <hr />
        <div
          v-if="
            user_roles.name == 'Admin OSP' && form.jadwal_instalasi.selisih <= 0
          "
        >
          <div style="color: #212529">
            <el-form
              size="normal"
              :model="form"
              ref="form"
              :rules="validasiForm"
              :disabled="status_step != 'instalasi'"
            >
              <div class="row">
                <div class="col-md-6">
                  <p>{{ trans("pelangganinstalasi.form.tgl instalasi") }}</p>
                  <el-form-item>
                    <el-date-picker
                      :disabled="true"
                      style="width: 100%"
                      type="date"
                      size="small"
                      v-model="form.jadwal_instalasi.tanggal"
                    >
                    </el-date-picker>
                  </el-form-item>
                </div>
                <div class="col-md-6">
                  <p>{{ trans("pelangganinstalasi.form.slot") }}</p>
                  <el-form-item>
                    <el-input
                      size="small"
                      :disabled="true"
                      v-model="form.jadwal_instalasi.slot"
                    >
                    </el-input>
                  </el-form-item>
                </div>
              </div>
              <div class="row">
                <!-- <div class="col-md-6">
                                <p>{{ trans("pelangganinstalasi.form.teknisi") }}</p>
                                <el-form-item prop="teknisi_instalasi">
                                    <el-select v-if="select_teknisi" v-model="form.teknisi_instalasi" size="small" @change="changeTeknisi" filterable multiple>
                                        <el-option v-for="(item, index) in form_show.DataTeknisi" :label="item.user_name" :value="item.user_name" :key="index"></el-option>
                                        <el-option label="+ tambah Teknisi" value="new"></el-option>
                                    </el-select>
                                    <input v-else type="text" class="form-control form-control-sm" />
                                </el-form-item>
                            </div> -->
                <div class="col-md-12">
                  <p>{{ trans("pelangganinstalasi.form.status") }}</p>
                  <el-form-item prop="status">
                    <el-select
                      placeholder="Status"
                      size="small"
                      v-model="form.status_instalasi"
                    >
                      <el-option
                        v-for="StatusOpsi in StatusOpsi"
                        :label="StatusOpsi.label"
                        :value="StatusOpsi.value"
                        :key="StatusOpsi.value"
                      ></el-option>
                    </el-select>
                  </el-form-item>
                </div>
              </div>
              <p>{{ trans("pelangganinstalasi.form.keterangan") }}</p>
              <el-form-item prop="keterangan_instalasi">
                <el-input type="textarea" v-model="form.keterangan_instalasi">
                </el-input>
              </el-form-item>
              <template v-if="form.status_instalasi == 'finish'">
                <p>{{ trans("pelangganinstalasi.form.perangkat") }}</p>
                <hr />
                <div :key="perangkat_key">
                  <div
                    class="mt-3"
                    v-for="(perangkat,
                    index) in form.perangkat_instalasi_digunakan"
                    :key="index"
                  >
                    <div class="row ">
                      <div class="col-md-5">
                        <el-form-item
                          :prop="
                            'perangkat_instalasi_digunakan.' +
                              index +
                              '.nama_perangkat'
                          "
                          :rules="{
                            required: true,
                            message: 'Data tidak boleh kosong',
                            trigger: 'blur',
                          }"
                        >
                          <el-select
                            v-model="perangkat.nama_perangkat"
                            placeholder="nama perangkat"
                            size="small"
                          >
                            <el-option
                              v-for="item in form_show.Perangkat"
                              :label="item.nama_perangkat"
                              :value="item.perangkat_id"
                              :key="item.perangkat_id"
                            ></el-option>
                          </el-select>
                        </el-form-item>
                      </div>
                      <div class="col-md-2">
                        <el-form-item
                          :prop="
                            'perangkat_instalasi_digunakan.' + index + '.qty'
                          "
                          :rules="{
                            required: true,
                            message: 'Data tidak boleh kosong',
                            trigger: 'blur',
                          }"
                        >
                          <el-input
                            size="small"
                            type="number"
                            placeholder="jumlah"
                            :min="0"
                            v-model="perangkat.qty"
                          ></el-input>
                        </el-form-item>
                      </div>
                      <div class="col-md-2">
                        <el-form-item
                          :prop="
                            'perangkat_instalasi_digunakan.' +
                              index +
                              '.jenis_perangkat'
                          "
                          :rules="{
                            required: true,
                            message: 'Data tidak boleh kosong',
                            trigger: 'blur',
                          }"
                        >
                          <el-select
                            placeholder="jenis"
                            v-model="perangkat.jenis_perangkat"
                            size="small"
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
                          :rules="{
                            required: true,
                            message: 'Data tidak boleh kosong',
                            trigger: 'blur',
                          }"
                        >
                          <el-select
                            placeholder="status"
                            size="small"
                            v-model="perangkat.status"
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
                        <el-button
                          @click.prevent="
                            removePerangkat(index, perangkat.perangkat_id)
                          "
                          size="small"
                          class="col-md-12"
                          icon="el-icon-delete"
                        ></el-button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-12">
                    <div class="float-right">
                      <el-button
                        size="mini"
                        type="primary"
                        @click.prevent="addPerangkat()"
                      >
                        {{ trans("pelangganinstalasi.tambah") }}
                      </el-button>
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
                        :prop="
                          'alat_instalasi_digunakan.' + index + '.nama_alat'
                        "
                        :rules="{
                          required: true,
                          message: 'Data tidak boleh kosong',
                          trigger: 'blur',
                        }"
                      >
                        <el-select
                          placeholder="nama alat"
                          size="small"
                          v-model="alat.nama_alat"
                        >
                          <el-option
                            v-for="alat in form_show.Alat"
                            :label="alat.nama_alat"
                            :value="alat.alat_id"
                            :key="alat.alat_id"
                          ></el-option>
                        </el-select>
                      </el-form-item>
                    </div>
                    <div class="col-md-4">
                      <el-form-item
                        :prop="'alat_instalasi_digunakan.' + index + '.qty'"
                        :rules="{
                          required: true,
                          message: 'Data tidak boleh kosong',
                          trigger: 'blur',
                        }"
                      >
                        <el-input
                          size="small"
                          type="number"
                          placeholder="jumlah"
                          :min="0"
                          v-model="alat.qty"
                        >
                        </el-input>
                      </el-form-item>
                    </div>
                    <div class="col-md-2">
                      <el-form-item
                        :prop="'alat_instalasi_digunakan.' + index + '.status'"
                        :rules="{
                          required: true,
                          message: 'Data tidak boleh kosong',
                          trigger: 'blur',
                        }"
                      >
                        <el-select
                          placeholder="status"
                          size="small"
                          v-model="alat.status"
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
                      <el-button
                        @click.prevent="removeAlat(index, alat.alat_id)"
                        size="small"
                        class="col-md-12"
                        icon="el-icon-delete"
                      ></el-button>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-12">
                    <div class="float-right">
                      <el-button
                        size="mini"
                        type="primary"
                        @click.prevent="addAlat()"
                      >
                        {{ trans("pelangganinstalasi.tambah") }}
                      </el-button>
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
                        <div class="col-md-2">
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
                        <div class="col-md-10">
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
                                placeholder="masukkan keterangan"
                                v-model="dokumentasi_show.keterangan"
                                :rows="5"
                              >
                              </el-input>
                            </el-form-item>
                            <div class="float-right">
                              <el-button
                                size="mini"
                                icon="el-icon-delete"
                                @click.prevent="
                                  removeDokumentasi(
                                    index,
                                    dokumentasi_show.dokumentasi_id
                                  )
                                "
                              >
                              </el-button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-5">
                  <div class="col-md-12">
                    <div class="float-right">
                      <el-button
                        size="mini"
                        type="primary"
                        @click.prevent="addDokumentasi()"
                      >
                        {{ trans("pelangganinstalasi.tambah") }}
                      </el-button>
                    </div>
                  </div>
                </div>
              </template>
            </el-form>
          </div>
        </div>
        <div v-else-if="form.jadwal_instalasi.selisih <= 0">
          <div class="row">
            <div class="col-md-12">
              <table class="table" style="width: 100%;">
                <tr class="py-1">
                  <th style="width: 250px;">
                    {{ trans("pelangganinstalasi.form.teknisi") }}
                  </th>
                  <td style="width: 10px;">:</td>
                  <td colspan="2">
                    <span
                      class="span_noc"
                      v-for="(item, index) in form.teknisi_instalasi"
                      :key="index"
                    >
                      {{ item }}
                    </span>
                  </td>
                </tr>
                <tr class="py-5">
                  <th style="width: 250px;">
                    {{ trans("pelangganinstalasi.form.tgl instalasi") }}
                  </th>
                  <td style="width: 10px;">:</td>
                  <td colspan="2">
                    {{ form.jadwal_instalasi.tanggal }}
                  </td>
                </tr>
                <tr class="py-5">
                  <th style="width: 250px;">
                    {{ trans("pelangganinstalasi.form.slot") }}
                  </th>
                  <td style="width: 10px;">:</td>
                  <td colspan="2">
                    {{ form.jadwal_instalasi.slot }}
                  </td>
                </tr>
                <tr class="py-5">
                  <th style="width: 250px;">
                    {{ trans("pelangganinstalasi.form.status") }}
                  </th>
                  <td style="width: 10px;">:</td>
                  <td colspan="2">
                    {{ form.status_instalasi }}
                  </td>
                </tr>
                <tr class="py-5">
                  <th style="width: 250px;">
                    {{ trans("pelangganinstalasi.form.keterangan") }}
                  </th>
                  <td style="width: 10px;">:</td>
                  <td colspan="2">
                    {{ form.keterangan_instalasi }}
                  </td>
                </tr>
                <tr
                  class="py-1"
                  v-if="form.dokumentasi_instalasi.length > 0"
                  v-for="(dokumentasi_show,
                  index) in form.dokumentasi_instalasi"
                  :key="index"
                >
                  <div v-if="index == 0">
                    <th style="width: 250px;">
                      {{ trans("pelangganinstalasi.form.dokumentasi") }}
                    </th>
                    <td style="width: 10px;">:</td>
                  </div>
                  <div v-else>
                    <td colspan="2"></td>
                  </div>
                  <td>
                    <upload-avatar
                      :src="imageUrl"
                      disabled
                      :image="dokumentasi_show.foto_dokumentasi_url"
                    >
                    </upload-avatar>
                  </td>
                  <td>
                    ket :
                    <p>{{ dokumentasi_show.keterangan }}</p>
                  </td>
                </tr>
              </table>

              <br />
              <div v-if="form.perangkat_instalasi_digunakan.length > 0">
                <p>{{ trans("pelangganinstalasi.form.perangkat") }}</p>
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
              <br />
              <div v-if="form.alat_instalasi_digunakan.length > 0">
                <p>{{ trans("pelangganinstalasi.form.alat") }}</p>
                <table class="table">
                  <thead>
                    <td>{{ trans("pelangganinstalasi.table alat.nama") }}</td>
                    <td>{{ trans("pelangganinstalasi.table alat.jumlah") }}</td>
                    <td>{{ trans("pelangganinstalasi.table alat.status") }}</td>
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
          </div>
        </div>
        <br />
      </div>
    </div>
    <div class="card-footer" v-if="user_roles.name == 'Admin OSP'">
      <el-button
        @click="submitForm('form')"
        type="primary"
        size="small"
        :disabled="status_step != 'instalasi'"
        >Simpan</el-button
      >
    </div>
  </div>
</template>

<script>
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Form from "form-backend-validation";
export default {
  mixins: [PermissionsHelper],
  props: ["disableButton"],
  data() {
    return {
      formData: new Form(),
      status_step: "",
      acceptedPerubahan: false,
      loadingPengajuan: true,
      user_role: "",
      loading: true,
      select_teknisi: true,
      teknisi: "",
      dokumentasi_key: 1,
      alat_key: 2,
      perangkat_key: 3,
      imageUrl: "",
      keterangan: "",
      disable: false,

      //data user yang terdata di database
      form: {
        instalasi_id: "",
        jadwal_instalasi: {},
        teknisi_instalasi: [],
        status_instalasi: "",
        keterangan_instalasi: "",
        perangkat_instalasi_digunakan: [],
        alat_instalasi_digunakan: [],
        dokumentasi_instalasi: [],
      },

      //opsi pilihan for user
      form_show: {
        DataTeknisi: [],
        Alat: [],
        Perangkat: [],
        Dokumentasi: [],
      },

      //validasi form
      validasiForm: {
        teknisi_instalasi: [
          {
            required: true,
            message: "Data tidak boleh kosong",
            trigger: "blur",
          },
        ],
        keterangan_instalasi: [
          {
            required: true,
            message: "Data tidak boleh kosong",
            trigger: "blur",
          },
        ],
        imageUrl: [
          {
            required: true,
            message: "Data tidak boleh kosong",
            trigger: "blur",
          },
        ],
        keterangan: [
          {
            required: true,
            message: "Data tidak boleh kosong",
            trigger: "blur",
          },
        ],
        alat: [
          {
            required: true,
            message: "Data tidak boleh ada yang kosong",
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
        this.loading = true;
        if (valid) {
          Swal.fire({
            title: "Are you sure to submit this ?",
            type: "info",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            showCloseButton: true,
            showLoaderOnConfirm: true,
          }).then((result) => {
            if (result.value) {
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
        } else {
          console.log("error submit!!");
          this.loading = false;
          return false;
        }
      });
    },
    addPerangkat(perangkats = "") {
      this.form.perangkat_instalasi_digunakan.push({
        nama_perangkat: perangkats,
        is_new: true,
        status: null,
      });
    },
    removePerangkat(index, id) {
      console.log(id);
      const params = {
        perangkat_id: id,
      };
      if (typeof id !== "undefined") {
        Swal.fire({
          title: "Are you sure to remove this ?",
          type: "warning",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          showCloseButton: true,
          showLoaderOnConfirm: true,
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
    addAlat(alats = "") {
      this.form.alat_instalasi_digunakan.push({
        nama_alat: alats,
        is_new: true,
        status: null,
      });
    },
    removeAlat(index, id) {
      const params = {
        alat_id: id,
      };
      if (typeof id !== "undefined") {
        Swal.fire({
          title: "Are you sure to remove this ?",
          type: "warning",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          showCloseButton: true,
          showLoaderOnConfirm: true,
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
          title: "Are you sure to remove this ?",
          type: "warning",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          showCloseButton: true,
          showLoaderOnConfirm: true,
        }).then((result) => {
          if (result.value) {
            axios
              .post(route("api.pelanggan.form.data.instalasi.delete"), params)
              .then((res) => {
                this.form.dokumentasi_instalasi.splice(index, 1);
                this.form_show.Dokumentasi.splice(index, 1);
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
        this.form_show.Dokumentasi.splice(index, 1);
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
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error("Avatar picture must be JPG/PNG format!");
      }
      if (!isLt2M) {
        this.$message.error("Avatar picture size can not exceed 2MB!");
      }
      return isJPG && isLt2M;
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
          var jumlah_data_teknisi = response.data.data_teknisi.length;
          var teknisi_push_to_array = [];
          for (var i = 0; i < jumlah_data_teknisi; i++) {
            var teknisi = response.data.data_teknisi[i].user_name;
            teknisi_push_to_array.push(teknisi);
          }
          if (response.data.status_instalasi) {
            this.$emit("status_instalasi", response.data.status_instalasi);
          }
          if (response.data.cancel == "1") {
            this.disable = true;
          }

          var res = response.data;
          this.form = {
            instalasi_id: res.instalasi_id,
            status_instalasi: res.status_instalasi,
            keterangan_instalasi: res.keterangan_instalasi,
            jadwal_instalasi:
              res.jadwal_instalasi[res.jadwal_instalasi.length - 1],
            perangkat_instalasi_digunakan: res.data_perangkat,
            alat_instalasi_digunakan: res.data_alat,
            dokumentasi_instalasi: res.dokumentasi,
            teknisi_instalasi: teknisi_push_to_array,
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
          this.form_show = {
            DataTeknisi: res.data.data_teknisi,
            Alat: res.data.data_alat,
            Perangkat: res.data.data_perangkat,
            Dokumentasi: res.data.dokumentasi,
          };
        });
    },
    changeTeknisi(value) {
      if (value.includes("new")) {
        this.select_teknisi = false;
        Swal.fire({
          title: "Masukkan nama NOC",
          input: "text",
          inputAttributes: {
            autocapitalize: "off",
          },
          showCancelButton: true,
          confirmButtonText: "Simpan",
          allowOutsideClick: false,
        }).then((result) => {
          if (result.value) {
            this.select_teknisi = true;
            if (this.form_show.DataTeknisi.indexOf(result.value) == -1) {
              var format_insert_teknisi = {
                user_name: result.value,
              };
              this.form_show.DataTeknisi.push(format_insert_teknisi);
            }
            if (this.form.teknisi_instalasi.indexOf(result.value) == -1) {
              this.form.teknisi_instalasi.splice(
                this.form.teknisi_instalasi.length - 1,
                1,
                result.value
              );
            } else {
              this.form.teknisi_instalasi.splice(
                this.form.teknisi_instalasi.length - 1,
                1
              );
            }
          } else {
            this.select_teknisi = true;
            this.form.teknisi_instalasi.splice(
              this.form.teknisi_instalasi.length - 1,
              1
            );
          }
        });
      }
    },
    // validasiDokumentasi(formName) {
    //     if (this.keterangan !== "" && this.imageUrl !== '') {
    //         this.Dokumentasi.push({
    //             foto_dokumentasi: this.imageUrl,
    //             is_new: true,
    //             keterangan: this.keterangan,
    //         });
    //         this.imageUrl = "";
    //         this.keterangan = "";
    //     } else {
    //         this.$refs[formName].validate((valid) => {
    //             //
    //         })
    //     }
    // },
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
</style>
