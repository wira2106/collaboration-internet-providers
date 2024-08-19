<?php

namespace Modules\Wilayah\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Wilayah\Entities\Wilayah;
use Modules\Wilayah\Repositories\WilayahRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Contracts\Authentication;
use Auth;

class PengajuanController extends AdminBaseController
{
    public function index()
    {
        return view('wilayah::admin.pengajuans.index');
    }
    public function mail()
    {
        return view('wilayah::notif.isp');
    }
    public function create()
    {
        return view('wilayah::admin.pengajuans.create');
    }
    public function detail()
    {
        return view('wilayah::admin.pengajuans.detail');
    }
}
