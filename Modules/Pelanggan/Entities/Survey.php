<?php

namespace Modules\Pelanggan\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Saldo\Entities\Saldo;
use DB;

class Survey extends Model
{
    use Translatable;

    protected $table = 'survey';
    public $translatedAttributes = [];
    protected $fillable = [
        'pelanggan_id',
        'tgl_survey',
        'tinggi_bangunan',
        'satuan_tinggi',
        'foto_signal_kabel',
        'foto_jalur_kabel',
        'keterangan',
        'status',
    ];

    public function getSurvey($pelanggan_id)
    {
        $survey = DB::table('survey')
            ->join('pelanggan', 'survey.pelanggan_id', '=', 'pelanggan.pelanggan_id')
            ->leftjoin('survey_staff', 'survey.id', '=', 'survey_staff.survey_id')
            // ->leftjoin('barang_tambahan', 'survey.id', '=', 'barang_tambahan.survey_id')
            ->leftjoin('perangkat_tambahan', 'survey.id', '=', 'perangkat_tambahan.survey_id')
            ->where('survey.pelanggan_id', '=', $pelanggan_id)
            ->select([
                'survey.*',
                'survey.id as id_survey',
                'survey_staff.*',
                // 'barang_tambahan.barang_id',
                // 'barang_tambahan.harga_per_pcs',
                // 'barang_tambahan.qty as qty_barang',
                // 'barang_tambahan.harga',
                'perangkat_tambahan.perangkat_id',
                'perangkat_tambahan.qty as qty_perangkat',
                'perangkat_tambahan.jenis_perangkat',
                'pelanggan.status as status_pelanggan'
            ])
            ->first();
        return $survey;
    }

    public function getTeknisiSurvey($survey_id)
    {
        $teknisiSurvey = DB::table('survey_staff')
            ->join('teknisi', 'survey_staff.user_id', '=', 'teknisi.user_id')
            ->join('users', 'teknisi.user_id', '=', 'users.id')
            ->where('survey_staff.survey_id', '=', $survey_id)
            ->select(['users.full_name as nama_teknisi', 'teknisi.user_id', 'survey_staff.survey_id'])
            ->get();
        return $teknisiSurvey;
    }


    public function cancelSurvey($pelanggan_id, $osp_id, $isp_id, $prosesRefund)
    {
        $update_saldo = new Saldo;
        $pelanggan = DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id);

        // Penarikan dari saldo Oppenaccess
        $update_saldo
            ->potong_saldo_dibekukan(1, $prosesRefund['totalTagihan'], trans('pelanggan::pelanggans.messages.refund cancel survey openaccess', ['nama_pelanggan' => $pelanggan->first()->nama_pelanggan]));

        $prosesRefund['totalTagihan'] -= $prosesRefund['refundOpenaccess'];

        $update_saldo
            ->potong_saldo(1, $prosesRefund['totalTagihan'], trans('pelanggan::pelanggans.messages.refund cancel survey openaccess', ['nama_pelanggan' => $pelanggan->first()->nama_pelanggan]));


        $prosesRefund['totalTagihan'] -= $prosesRefund['refundOSP'];

        $saldo_biaya_pelanggan = DB::table('saldo_biaya_pelanggan')->where('pelanggan_id', $pelanggan_id);
        $saldo_biaya_pelanggan->update([
            'biaya_admin' => $prosesRefund['refundOpenaccess'],
            'persentase_biaya_admin' => $prosesRefund['persentaseOpenaccess'],
            'biaya_osp' => $prosesRefund['refundOSP'],
            'persentase_biaya_osp' =>  $prosesRefund['persentaseOSP'],
            'pengembalian_isp' => $prosesRefund['totalTagihan'] + $prosesRefund['sisa'],
            'settlement' => 1
        ]);

        //saldo ke osp
        $update_saldo->tambah_saldo($osp_id, $prosesRefund['refundOSP'], trans('pelanggan::pelanggans.messages.fee cancel survey osp', ['nama_pelanggan' => $pelanggan->first()->nama_pelanggan]));

        //saldo ke isp
        $update_saldo->tambah_saldo($isp_id, $prosesRefund['totalTagihan'] + $prosesRefund['sisa'], trans('pelanggan::pelanggans.messages.fee cancel survey isp', ['nama_pelanggan' => $pelanggan->first()->nama_pelanggan]));

        DB::table('survey')
            ->where('pelanggan_id', '=', $pelanggan_id)
            ->update(['status' => 'cancel']);

        return true;
    }

    public function getRangeJadwalPengajuanSurvey($pelanggan_id)
    {
        $pelanggan = new Pelanggan();
        $jadwalPengajuan = $pelanggan->loadPengajuan($pelanggan_id, 'survey');
        $jadwalSurvey = null;
        $surveyNow = false;
        $surveyFinish = false;
        $status_survey = [
            'surveyNow' => $surveyNow,
            'surveyFinish' => $surveyFinish
        ];
        foreach ($jadwalPengajuan as $value) {
            if ($value->status == "accept") {
                $jadwalSurvey  = $value;
            }
        }
        if ($jadwalSurvey != null) {
            foreach ($jadwalSurvey->list as $value) {
                if ($value->status == "active") {
                    $tgl_survey  = $value->tgl_survey;
                }
            }
            $hari_survey = date('Y-m-d', strtotime($tgl_survey));
            $tgl_saat_ini = date('Y-m-d');
            if ($tgl_saat_ini >= $hari_survey) {
                $surveyNow = true;
                $surveyFinish = true;
                $status_survey = [
                    'surveyNow' => $surveyNow,
                    'surveyFinish' => $surveyFinish
                ];
            }
        }



        return $status_survey;
    }

    public function updateTeknisiSurvey($dataTeknisi, $survey_id)
    {

        $check = DB::table('survey_staff')
            ->where('survey_staff.survey_id', '=', $survey_id);
        if ($check->count() !== 0) {
            $check->delete();
        }
        $insert = [];
        foreach ($dataTeknisi as $value) {
            if (gettype($value) == 'integer') {
                $insert[] = [
                    'survey_id' => $survey_id,
                    'user_id' => $value,
                ];
            } else {
                $insert[] = [
                    'survey_id' => $survey_id,
                    'user_id' => $value['user_id'],
                ];
            }
        }
        DB::table('survey_staff')->insert($insert);
    }
}
