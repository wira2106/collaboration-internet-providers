import axios from 'axios';
import Form from 'form-backend-validation';
import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';
import TranslationHelper from '../../../../Core/Assets/js/mixins/TranslationHelper';
import PermissionsHelper from '../../../../Core/Assets/js/mixins/PermissionsHelper';
import Toast from '../../../../Core/Assets/js/mixins/Toast';
import CardDetailPelangganInstalasi from '../components/instalasi/components/CardDetailPelangganInstalasi';

export default {
  components: {
    'Card-detail': CardDetailPelangganInstalasi,
  },
  props: ['jumlahPelanggan', 'stepSekarang'],
  mixins: [
    ShortcutHelper,
    TranslationHelper,
    Toast,
    PermissionsHelper
  ],
  data() {
    return {
      loading: false,
      pelanggan_id: null,
      status_checkbox:false,

      form: {
        pelanggan_id: null,
        tgl_aktivasi: null,
        status: 'proses',
        keterangan: null,
        noc: [],
        status_checkbox:false,
      },
      list_noc: [],
      select_noc: true,
      rules: {
        required_field: [
          { required: true, trigger: ['blur', 'change'], message: this.trans('core.form.required') }
        ]
      },
      pickerOptions: {
        disabledDate(time) {
          let date = new Date();
          date.setDate(date.getDate() - 1);
          return time.getTime() < date.getTime();
        }
      },
      loading: false,
      disable_form: false,
      status_aktivasi_now: null,
    }
  },
  methods: {
    changeTeknisi(value) {
      if (value.includes('new')) {
        this.select_noc = false;
        Swal.fire({
          title: this.trans('activations.form.enter noc'),
          input: 'text',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Simpan',
          allowOutsideClick: false
        }).then((result) => {
          if (result.value) {
            this.select_noc = true;
            if (this.list_noc.indexOf(result.value) == -1) {
              this.list_noc.push(result.value);
            }
            if (this.form.noc.indexOf(result.value) == -1) {
              this.form.noc.splice((this.form.noc.length - 1), 1, result.value);
            } else {
              this.form.noc.splice((this.form.noc.length - 1), 1);
            }

          } else {
            this.select_noc = true;
            this.form.noc.splice((this.form.noc.length - 1), 1);
          }
        })

      }
    },
    checkSaldoSudahCukup() {
      return new Promise((resolve) => {
        this.loadingPengajuan = true;
        axios.get(route('api.pelanggan.saldo.check', { pelanggan_id: this.pelanggan_id }))
          .then((response) => {
            return resolve(response.data);
          });
      });
    },
    fetchDataAktivasi() {
      axios.get(route('api.pelanggan.form.detail.aktivasi', { pelanggan_id: this.pelanggan_id }))
        .then(response => {
          let data = response.data.data;
          let noc = response.data.noc;
          let pelanggan = response.data.pelanggan;

          this.form = data;
          this.status_aktivasi_now = data.status;

          if (data.status == 'selesai' || data.status == 'approve') {
            this.status_checkbox = true;
          } else {
            this.status_checkbox = false;
          } 

          this.list_noc = noc;

          if (pelanggan.cancel == 1 || pelanggan.status == 'aktif') {
            this.disable_form = true;
          } else {
            this.disable_form = false;
          }
        });
    },
    fetchNOC() {
      axios.get(route('api.company.slotinstalasi.get-range-time'), { company_id: osp_id })
        .then(response => {
          this.selectableRange = response.data.data;
        })
        .catch(err => {
          setTimeout(() => {
            this.fetchDateRangeSlot(osp_id)
          }, 2000);
        })
    },
    setPresalesAndPelangganId() {
      let pelanggan = this.$route.query.pelanggan;
      this.pelanggan_id = (pelanggan !== undefined && pelanggan != "" ? pelanggan : 0);
    },
    onSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (this.form.noc.length == 0) {
            Swal.fire(this.trans('activations.messages.empty noc'), '', 'warning');
            return false;
          }

          if (this.form.status == 'cancel' && (this.form.keterangan == null || this.form.keterangan == '')) {
            Swal.fire(this.trans('activations.messages.empty keterangan'), '', 'warning');
            this.$refs.keterangan.focus();
            return false;
          }

          Swal.fire({
            title: this.trans('core.messages.confirmation-form'),
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: this.trans('core.button.confirm'),
            cancelButtonText: this.trans('core.button.cancel')
          }).then((result) => {
            if (result.value) {
              this.submitForm();
            }
          });
        }
      });
    },
    submitForm(status = null) {
      this.loading = true;
      if (status != null) {
        this.form.status = 'approve';
      }
      this.form.status_checkbox = this.status_checkbox;
      axios.post(route('api.pelanggan.form.submit.activation'), this.form)
        .then((response) => {
          let data = response.data;
          this.Toast({
            icon: "success",
            title: data.message
          });
          // return false;
          if (data.redirect_detail) {
            window.location = route('admin.pelanggan.form.detail', { pelanggan_id: this.pelanggan_id });
          } else {
            window.location = route('admin.pelanggan.form.step') + "?pelanggan=" + this.pelanggan_id;
          }
        }).catch((error) => {
          this.loading = false;
          this.Toast({
            icon: "error",
            title: "error"
          });
        });
    },
    approveInstalasi() {
      Swal.fire({
        title: this.trans('core.messages.confirmation-form'),
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.trans('core.button.confirm'),
        cancelButtonText: this.trans('core.button.cancel')
      }).then(async (result) => {
        if (result.value) {
          this.loading = true;
          let check = await this.checkSaldoSudahCukup();
          if (check.cukup) {
            this.submitForm('approve');
          } else {
            this.bayarBiayaPelanggan(null, check.data);
          }
        }
      });
    },
    bayarBiayaPelanggan(form, data) {
      let total = parseInt(data.mrc) + parseInt(data.otc);
      let saldo = parseInt(data.saldo);
      let sisa_saldo = saldo - total;
      let linkSaldo = window.origin + '/backend/saldo/topups';
      let html = `
        <table style="width:100%">
          <tr><td>Saldo</td><td>:</td><td>Rp. ${this.number_format(data.saldo)}</td></tr>
          <tr><td colspan='3'><hr></td></tr>
          <tr><td>Biaya OTC</td><td>:</td><td>Rp. ${this.number_format(data.otc)}</td></tr>
          <tr><td>Biaya MRC</td><td>:</td><td>Rp. ${this.number_format(data.mrc)}</td></tr>`;

      if (data.terbayar != 0) {
        html += `<tr><th>Terbayar</th><td>:</td><td>- Rp. ${this.number_format(data.terbayar)}</td></tr>`;
        total = total - data.terbayar;
        sisa_saldo = sisa_saldo + data.terbayar;
      }

      html += `<tr><th>Total Biaya</th><td>:</td><td>Rp. ${this.number_format(total)}</td></tr>
          <tr><td colspan='3'><hr></td></tr>
          <tr><th>Sisa Saldo</th><td>:</td><td>Rp. ${this.number_format(sisa_saldo)}</td></tr>
        </table>
        <br>
        `;
      if (sisa_saldo < 0) {
        html += `<a href="${linkSaldo}">
            <button class="btn btn-sm btn-primary">Topup Sekarang</button>
          </a> `;
      }
      Swal.fire({
        title: '',
        icon: 'info',
        html: html,
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          if (sisa_saldo < 0) {
            Swal.fire('Saldo tidak mencukupi', '', 'error');
            this.loading = false;
          } else {
            axios.post(route('api.pelanggan.saldo.submit', { pelanggan_id: this.pelanggan_id }))
              .then((response) => {
                if (response.data.errors) {
                  Swal.fire(response.data.message, '', 'error');
                  this.loading = false;
                } else {
                  this.submitForm('approve');
                }
              }).catch((er) => {
                console.log(er);
                this.Toast({
                  icon: "error",
                  title: "error"
                });
                this.loading = false;
              });
          }
        } else {
          this.loading = false;
        }
      });
    },
    number_format(number, decimals, decPoint, thousandsSep) {
      number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
      var n = !isFinite(+number) ? 0 : +number
      var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
      var sep = (typeof thousandsSep === 'undefined') ? '.' : thousandsSep
      var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
      var s = ''

      var toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec)
        return '' + (Math.round(n * k) / k)
          .toFixed(prec)
      }

      // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
      }
      if ((s[1] || '').length < prec) {
        s[1] = s[1] || ''
        s[1] += new Array(prec - s[1].length + 1).join('0')
      }

      return s.join(dec)
    },
    tolakApproveInstalasi() {
      Swal.fire({
        title: this.trans('activations.messages.alasan unapprove'),
        input: 'text',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        allowOutsideClick: false
      }).then((result) => {
        if(typeof result.value !== 'undefined'){
          if(result.value == '' || result.value == null){
            this.tolakApproveInstalasi();
          }else{
            this.loading = true;
            let send = {
              pelanggan_id: this.pelanggan_id,
              alasan: result.value
            };
            axios.post(route('api.pelanggan.form.submit.activation.unapprove'), send)
              .then((response) => {
                this.loading = false;
                let data = response.data;
                this.Toast({
                  icon: "success",
                  title: data.message
                });
                window.location = route('admin.pelanggan.form.step') + "?pelanggan=" + this.pelanggan_id;
              }).catch((error) => {
                this.loading = false;
                this.Toast({
                  icon: "error",
                  title: "error"
                });
              });
          }
        }
      
      });
    }
  },
  mounted() {
    this.setPresalesAndPelangganId();
    this.fetchDataAktivasi();
  }
}