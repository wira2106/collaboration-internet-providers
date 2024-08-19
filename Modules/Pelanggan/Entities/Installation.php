<?php

namespace Modules\Pelanggan\Entities;

use Illuminate\Support\Facades\DB;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Installation extends Model
{
    use Translatable;

    protected $table = 'instalasi';
    public $translatedAttributes = [];
    protected $fillable = [];
    protected $primaryKey = "pelanggan_id";

    //show data perangkat sesuai instalasi id
    public function showDataPerangkat($instalasi_id)
    {
        $dataPerangkat = DB::table('instalasi')
            ->select('perangkat_instalasi.*', 'perangkat.nama_perangkat')
            ->join('perangkat_instalasi', 'perangkat_instalasi.instalasi_id', '=', 'instalasi.instalasi_id')
            ->join('perangkat', 'perangkat.perangkat_id', '=', 'perangkat_instalasi.perangkat_id')
            ->where('instalasi.instalasi_id', $instalasi_id)->get();

        return $dataPerangkat;
    }

    //show alat sesuai instalasi id
    public function showDataAlat($instalasi_id)
    {
        $dataAlat = DB::table('instalasi')
            ->select('alat_instalasi.*', 'alat.nama_alat')
            ->join('alat_instalasi', 'alat_instalasi.instalasi_id', '=', 'instalasi.instalasi_id')
            ->join('alat', 'alat.alat_id', '=', 'alat_instalasi.alat_id')
            ->where('instalasi.instalasi_id', $instalasi_id)->get();

        return $dataAlat;
    }

    //show data dokumentasi sesuai instalasi id
    public function showDokumentasi($instalasi_id)
    {
        $dataDokumentasi = DB::table('instalasi')
            ->join('dokumentasi_instalasi', 'dokumentasi_instalasi.instalasi_id', '=', 'instalasi.instalasi_id')
            ->where('instalasi.instalasi_id', $instalasi_id)->get();
        return $dataDokumentasi;
    }

    //show data teknisi sesuai pelanggan id
    public function showTeknisi($instalasi_id, $pelanggan_id)
    {
        $teknisi = DB::table('teknisi')->select('u.full_name as nama_teknisi', 'teknisi.user_id')
            ->join('users as u', 'u.id', '=', 'teknisi.user_id')
            ->join('instalation_staff as is', 'is.user_id', '=', 'u.id')
            ->where('instalasi_id', $instalasi_id)
            ->get();

        return $teknisi;

        // $id_osp = DB::table('pelanggan')->where('pelanggan_id', $pelanggan_id)->first();
        // $dataTeknisi = DB::table('instalasi')->join('pelanggan', 'pelanggan.pelanggan_id', '=', 'instalasi.pelanggan_id')
        //     ->join('instalation_staff', 'instalation_staff.instalasi_id', '=', 'instalasi.instalasi_id')
        //     ->where('osp_id', $id_osp->osp_id)
        //     ->where('instalasi.instalasi_id', $instalasi_id)
        //     ->groupBy('nama_teknisi')
        //     ->get();
        // return $dataTeknisi;

    }

    //show jadwal instalasi sesuai pelanggan id
    public function jadwalInstalasi($pelanggan_id)
    {
        $jadwalInstalasi = DB::table('pengajuan_jadwal')->where('pelanggan_id', $pelanggan_id)
            ->join('tanggal_pengajuan_instalasi', 'tanggal_pengajuan_instalasi.pengajuan_id', '=', 'pengajuan_jadwal.pengajuan_id')
            ->join('slot_instalasi', 'slot_instalasi.slot_id', '=', 'tanggal_pengajuan_instalasi.slot_id')
            ->where('tanggal_pengajuan_instalasi.status', 'active')->get();

        return $jadwalInstalasi;
    }
}
