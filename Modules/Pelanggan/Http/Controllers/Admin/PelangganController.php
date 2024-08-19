<?php

namespace Modules\Pelanggan\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Pelanggan\Http\Requests\CreatePelangganRequest;
use Modules\Pelanggan\Http\Requests\UpdatePelangganRequest;
use Modules\Pelanggan\Repositories\PelangganRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class PelangganController extends AdminBaseController
{

    public function index()
    {
        return view('pelanggan::admin.pelanggan.index');
    }

    public function steps(Request $req)
    {
    	return view('pelanggan::admin.pelanggan.step');	
    }

    public function detail($pelanggan_id)
    {
        return view('pelanggan::admin.pelanggan.step');
    }
}
