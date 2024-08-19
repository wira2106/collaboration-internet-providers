<?php

namespace Modules\Saldo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Saldo\Entities\withdraw;
use Modules\Saldo\Http\Requests\CreatewithdrawRequest;
use Modules\Saldo\Http\Requests\UpdatewithdrawRequest;
use Modules\Saldo\Repositories\withdrawRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class mutasiController extends AdminBaseController
{
    public function index(Request $req)
    {
        return view('saldo::admin.mutasi.index');
    }
}
