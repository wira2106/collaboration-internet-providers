<?php

namespace Modules\Analytic\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Analytic\Entities\Analytic;
use Modules\Analytic\Http\Requests\CreateAnalyticRequest;
use Modules\Analytic\Http\Requests\UpdateAnalyticRequest;
use Modules\Analytic\Repositories\AnalyticRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Company\Entities\Company;
use Modules\Analytic\Transformers\ListAnalyticInstalasiPelanggan;
use Auth;

class AnalyticController extends AdminBaseController
{
    
    public function indexPelanggan(Request $request)
    {
        $company  = new Company;
        $analytic = new Analytic;
        $company_id = $request->get("company_id");

        if (!Auth::User()->hasRoleName('Super Admin')) {
            $comp = Auth::User()->company();
            $company_id = $comp->company_id;
        }
        $data = $analytic->listActivityInstalasiPelanggan($company_id, $request);
        $allDataCompany = [
            "selected" => $company_id
        ];

        return ListAnalyticInstalasiPelanggan::collection($data)->additional($allDataCompany);
    }

    public function detailPelanggan($pelanggan_id)
    {
        $pelanggan = Pelanggan::find($pelanggan_id);
        $pelanggan->nama_osp = Company::find($pelanggan->osp_id)->name;
        $pelanggan->nama_isp = Company::find($pelanggan->isp_id)->name;
        $pelanggan->tanggal_buat = date("d M Y H:i", strtotime($pelanggan->created_at));
 
        $activitas_instalasi = (new Analytic)->getActivityPelanggan($pelanggan_id);
        $activity = [];
        $total_osp = 0;
        $total_isp = 0;
        foreach ($activitas_instalasi as $key => $value) {
            $activity[] = $value;
            if($value->type == 'osp'){
                $total_osp += $value->total_waktu;
            }else{
                $total_isp += $value->total_waktu;
            }
        }
       

        $activity[] = (object)[
            'nama_activity' => 'Total Estimasi',
            'type' => 'isp',
            'total_waktu' => $total_isp
        ];

        $activity[] = (object)[
            'nama_activity' => 'Total Estimasi',
            'type' => 'osp',
            'total_waktu' => $total_osp
        ];


        $activity[] = (object)[
            'nama_activity' => 'Jangka Instalasi '.$pelanggan->tipe_ep,
            'type' => 'estimasi',
            'total_waktu' => $pelanggan->estimasi_instalasi
        ];

        $send = [
            "pelanggan" => $pelanggan,
            "activity" => $activity
        ];

       return Response()->json($send);
    }
}
