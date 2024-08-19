<?php

namespace Modules\Pelanggan\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\PaketForIsp;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Presale\Entities\Presale;
use Modules\Company\Entities\Company;
use Modules\Utils\Http\Controllers\ImageController;
use Modules\Pelanggan\Transformers\DetailSalesOrderTransformer;
use Illuminate\Support\Facades\Mail;
use Modules\Pelanggan\Emails\TambahSalesOrderMail;
use Modules\Saldo\Entities\Saldo;
use Modules\Wilayah\Entities\Wilayah;

class SalesOrderController extends AdminBaseController
{
  public function company_name()
  {
    $data = Auth::user()->company();
    return $data['name'];
  }
  public function detailSalesOrder(Request $req)
  {
    $paket = new PaketForIsp;
    $presales     = $req->presales;
    $pelanggan     = $req->pelanggan;

    if ($presales != 0) {
      $presale = Presale::find($presales)->withDataEndpoint();
      $company = Auth::User()->company();

      $paketWilayah = $paket->getPaketBerlanggananForISP($presale->wilayah_id);
      $jumlahPelanggan = Pelanggan::where('isp_id', $company->company_id)->whereNull('suspend_id')->count();
      $osp = DB::table('active_presales')->where('presale_id', $presales)->first()->osp_id;

      $data = new DetailSalesOrderTransformer($presale);
    } else if ($pelanggan != 0) {
      $pel = Pelanggan::find($pelanggan)->salesOrder();
      $company = Company::find($pel->isp_id);

      $paketWilayah = $paket->getPaketBerlanggananForISP($pel->wilayah_id, $pel->isp_id);
      $jumlahPelanggan = Pelanggan::where('isp_id', $company->company_id)->whereNull('suspend_id')->count();
      $osp = DB::table('active_presales')->where('presale_id', $pel->presale_id)->first()->osp_id;

      $data = new DetailSalesOrderTransformer($pel);
    }

    $send = [
      'data' => $data,
      'jumlahPelanggan' => $jumlahPelanggan,
      'paket' => $paketWilayah,
      'osp' => $osp
    ];

    return Response()->json($send);
  }

  public function submitSalesOrder(Request $req)
  {
    if ($req->get('pelanggan_id') == null) {
      $pelanggan = $this->tambahSalesOrder($req);
      $pelanggan_id = $pelanggan->pelanggan_id;

      Auth::user()->createLog(
        trans('pelanggan::logpelanggan.crud.create.title.so'),
        trans('pelanggan::logpelanggan.crud.create.desc.so', ['company' => $this->company_name()])
      );
      $data = Pelanggan::find($pelanggan_id);
      $company = Company::find($data->isp_id);
      $saldo = $company->saldo()->saldo;
      $total_biaya = $data->biaya_otc + $data->biaya_mrc;

      if ($saldo - $total_biaya < 0) {
        return response()->json([
          'errors' => true,
          'message' => trans('saldo::saldos.messages.enough saldo')
        ]);
      }

      DB::table('saldo_biaya_pelanggan')->insert([
        'pelanggan_id' => $pelanggan_id,
        'total_biaya' => $total_biaya,
        'saldo_mengendap' => $total_biaya,
        'otc' => $data->biaya_otc,
        'mrc' => $data->biaya_mrc
      ]);

      Saldo::potong_saldo($data->isp_id, $total_biaya, 'Pembayaran biaya MRC dan OTC pertama pelanggan a/n ' . $data->nama_pelanggan);
      Saldo::tambah_saldo_dibekukan(1, $total_biaya, 'Pembayaran biaya MRC dan OTC pertama pelanggan a/n ' . $data->nama_pelanggan . " dari ISP " . $company->name);

      $response = [
        'errors' => false,
        'message' => trans('pelanggan::salesorders.messages.data created'),
        'pelanggan' => $pelanggan->pelanggan_id
      ];
    } else {
      $pelanggan = Pelanggan::updateDataPelanggan($req);
      Auth::user()->createLog(
        trans('pelanggan::logpelanggan.crud.update.title.so'),
        trans('pelanggan::logpelanggan.crud.update.desc.so', ['company' => $this->company_name()])
      );
      $response = [
        'errors' => false,
        'message' => trans('pelanggan::salesorders.messages.data updated'),
        'pelanggan' => $pelanggan
      ];
    }

    return response()->json($response);
  }

  public function tambahSalesOrder($req)
  {

    $pelangganFunction    = new Pelanggan;
    $user         = Auth::User();
    $company_user = $user->company();
    $presale = Presale::find($req->presale_id);
    $presale->withDataEndpoint();
    $wilayah = Wilayah::find($presale->wilayah_id);
    $estimasi_instalasi = Pelanggan::getEstimasiInstalasi($presale->type_ep);


    // insert ke pelanggan
    $insert = [
      'presale_id' => $req->get('presale_id'),
      'kode_pelanggan' => $pelangganFunction->KodePelanggan(),
      'isp_id' => $company_user->company_id,
      'osp_id' => $req->get('osp_id'),
      'wilayah_id' => $presale->wilayah_id,
      'paket_id' => $req->get('paket_id'),
      'biaya_mrc' => (int)$req->get('biaya_mrc'),
      'biaya_otc' => (int)$req->get('biaya_otc'),
      'nama_pelanggan' => $req->get('nama_pelanggan'),
      'telepon' => $req->get('telepon'),
      'email' => $req->get('email'),
      'jenis_identitas' => $req->get('jenis_identitas'),
      'nomor_identitas' => $req->get('nomor_identitas'),
      'foto_identitas' => ImageController::uploadFoto($req->file('foto_identitas')),
      'status' => 'so',
      'suspend_id' => null,
      'status_kepemilikan' => $req->get('status_kepemilikan'),
      'foto_jalur_ep' => ImageController::uploadFoto($req->file('foto_jalur_ep')),
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $user->id,
      'wilayah_id' => $req->wilayah_id,
      'estimasi_instalasi' => $estimasi_instalasi,
      'tipe_ep' => $presale->type_ep
    ];
    $pelanggan = Pelanggan::create($insert);

    // tambah ke pengajuan jika ada
    $insert = [
      'pelanggan_id' => $pelanggan->pelanggan_id,
      'type' => 'survey',
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $user->id,
      'status' => 'pending',
      'keterangan' => $req->get('keterangan')
    ];

    if ($req->get('rekomendasiExist') == 'true') {
      if ($req->get('tgl_rekomendasi') != null && $req->get('tgl_rekomendasi') != '') {
        $insert['tgl_rekomendasi'] = $req->get('tgl_rekomendasi') . " " . $req->get('jam_rekomendasi') . ":00";
      }
    }
    DB::table('pengajuan_jadwal')->insert($insert);



    // send response


    $data = [
      'isp_name' => $company_user->name,
      'wilayah_name' => $wilayah->name,
      'url' => route('admin.pelanggan.form.step', [
        'pelanggan' => $pelanggan->pelanggan_id
      ])
    ];

    $emails = array_merge(
      $company_user->admin_email(),
      (new Company)->admin_email($req->get('osp_id'))
    );
    $pelanggan->withPacket();
    $pelanggan->withDataPresales();
    Mail::to($emails)->send(new TambahSalesOrderMail($data, $pelanggan));
    return $pelanggan;
  }
}
