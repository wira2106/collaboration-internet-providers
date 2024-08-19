<?php

namespace Modules\Wilayah\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Wilayah\Entities\Wilayah;
use Modules\Wilayah\Http\Requests\CreateWilayahRequest;
use Modules\Wilayah\Http\Requests\UpdateWilayahRequest;
use Modules\Wilayah\Repositories\WilayahRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Contracts\Authentication;
use Auth;

class WilayahController extends AdminBaseController
{
    /**
     * @var WilayahRepository
     */
    private $wilayah;

    public function __construct(WilayahRepository $wilayah)
    {
        parent::__construct();

        $this->wilayah = $wilayah;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$wilayahs = $this->wilayah->all();
        return view('wilayah::admin.wilayahs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('wilayah::admin.wilayahs.create');
    }

    public function edit($id)
    {
        return view('wilayah::admin.wilayahs.edit');
    }
}
