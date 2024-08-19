<?php

namespace Modules\Analytic\Events\Pelanggan;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Sentinel\User;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\Company;
use Modules\Analytic\Entities\Analytic;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\Rating;
use Modules\Core\Traits\Rating as TraitsRating;

class ApproveAktivasiPelanggan
{
    use SerializesModels, TraitsRating;

    private $pelanggan_id;
    private $pelanggan;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($pelanggan_id)
    {
        $this->pelanggan_id = $pelanggan_id;
        $this->approveAktivasiPelanggan();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function approveAktivasiPelanggan()
    {
        $pelanggan_id = $this->pelanggan_id;
        $this->pelanggan = Pelanggan::find($pelanggan_id);
        $company_id = $this->pelanggan->isp_id;
        // start time
        $created_at = $this->getTanggalAktivasi($pelanggan_id);
        $start_time = Company::getJamKerjaCompanySaatProses($company_id, $created_at);
        if (strtotime($created_at) > strtotime($start_time)) {
            $start_time = $created_at;
        }
        // end time
        $end_time = date('Y-m-d H:i:s');
        // total waktu
        $total_waktu = strtotime($end_time) - strtotime($start_time);


        Analytic::create([
            'company_id' => $company_id,
            'pelanggan_id' => $pelanggan_id,
            'activity_tipe_id' => 9,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'total_waktu' => $total_waktu,
            'created_by' => Auth::User()->id,
        ]);

        $this->createRating();
    }

    public function getTanggalAktivasi($pelanggan_id)
    {
        $aktivasi = DB::table('activity_timer')->where('pelanggan_id', $pelanggan_id)
            ->where('activity_tipe_id', 8)
            ->orderBy('activity_id', 'desc')
            ->first();

        if ($aktivasi == null) {
            $cek = DB::table('activity_timer')->where('pelanggan_id', $pelanggan_id)
                ->where('activity_tipe_id', 9)
                ->orderBy('activity_id', 'desc')
                ->first();

            $send = $cek->end_time;
        } else {
            $send = $aktivasi->end_time;
        }

        return $send;
    }

    private function createRating()
    {
        $timers = DB::table('activity_timer')
            ->select([
                DB::raw('SUM(activity_timer.total_waktu) as total_waktu'),
                'activity_tipe.*',
                'activity_timer.pelanggan_id',
                'companies.type',
                'activity_timer.company_id'
            ])
            ->where('activity_timer.pelanggan_id', $this->pelanggan->pelanggan_id)
            ->groupBy('activity_timer.activity_tipe_id')
            ->groupBy('activity_timer.company_id')
            ->join('activity_tipe', 'activity_tipe.activity_tipe_id', 'activity_timer.activity_tipe_id')
            ->join('companies', 'companies.company_id', 'activity_timer.company_id')
            ->get();

        foreach ($timers as $key => $timer) {
            $dayOnSecond = 86400;
            $penerima_rating_id = $this->pelanggan->isp_id;
            if ($timer->type == 'osp') $penerima_rating_id = $this->pelanggan->osp_id;

            // Kurang 1 digunakan untuk mendapatkan hari yang lewat dari estimasi
            $totalDay = ceil((int)$timer->total_waktu / $dayOnSecond);
            $totalDay = $totalDay < 0 ? 0 : $totalDay - 1;
            $point_rating = $this->total_rating - $totalDay;

            // Aktifitas approve pelanggan aktif
            if ($timer->activity_tipe_id == 9) {
                // Kurang 1 digunakan untuk mendapatkan hari yang lewat dari estimasi
                $totalDay = ceil($timer->total_waktu / $this->pelanggan->estimasi_instalasi);
                $totalDay = $totalDay < 0 ? 0 : $totalDay - 1;
                $point_rating = $this->total_rating - $totalDay;
            }

            $trans = $this->getDescriptionRating($timer->activity_tipe_id, $this->pelanggan->nama_pelanggan);
            Rating::create([
                'tipe_rating' => 'openaccess',
                'pemberi_rating_id' => 1,
                'penerima_rating_id' => $penerima_rating_id,
                'rating' => $point_rating,
                'description' => $trans
            ]);
        }

        $this->generateRating($this->pelanggan->osp_id);
        $this->generateRating($this->pelanggan->isp_id);
    }

    private function getDescriptionRating($activity_tipe_id, $nama_pelanggan)
    {
        $trans = '';
        switch ($activity_tipe_id) {
            case 1:
                $trans = trans('analytic::analytics.rating.pemberian jadwal survey', [
                    'nama_pelanggan' => $nama_pelanggan
                ]);

                break;

            case 2:
                $trans = trans('analytic::analytics.rating.penentuan jadwal survey', [
                    'nama_pelanggan' => $nama_pelanggan
                ]);

                break;

            case 3:
                $trans = trans('analytic::analytics.rating.survey pelanggan', [
                    'nama_pelanggan' => $nama_pelanggan
                ]);

                break;

            case 4:
                $trans = trans('analytic::analytics.rating.accept perubahan pelanggan', [
                    'nama_pelanggan' => $nama_pelanggan
                ]);

                break;

            case 5:
                $trans = trans('analytic::analytics.rating.pemberian jadwal instalasi', [
                    'nama_pelanggan' => $nama_pelanggan
                ]);

                break;

            case 6:
                $trans = trans('analytic::analytics.rating.penentuan jadwal instalasi', [
                    'nama_pelanggan' => $nama_pelanggan
                ]);

                break;

            case 7:
                $trans = trans('analytic::analytics.rating.instalasi pelanggan', [
                    'nama_pelanggan' => $nama_pelanggan
                ]);



                break;

            case 8:
                $trans = trans('analytic::analytics.rating.aktivasi pelanggan', [
                    'nama_pelanggan' => $nama_pelanggan
                ]);

                break;

            case 9:
                $trans = trans('analytic::analytics.rating.approve aktivasi pelanggan', [
                    'nama_pelanggan' => $nama_pelanggan
                ]);

                break;

            default:
                # code...
                break;
        }


        return $trans;
    }
}
