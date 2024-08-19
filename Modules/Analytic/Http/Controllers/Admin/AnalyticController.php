<?php

namespace Modules\Analytic\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Analytic\Entities\Analytic;
use Modules\Analytic\Http\Requests\CreateAnalyticRequest;
use Modules\Analytic\Http\Requests\UpdateAnalyticRequest;
use Modules\Analytic\Repositories\AnalyticRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Analytic\Events\Pelanggan\AktivasiPelanggan;
use DB;
use Auth;

class AnalyticController extends AdminBaseController
{
    public function indexPelanggan()
    {
        return view('analytic::admin.analytics.index');
    }
    
    public function detailPelanggan($pelanggan)
    {
        return view('analytic::admin.analytics.detail');
    }
}
