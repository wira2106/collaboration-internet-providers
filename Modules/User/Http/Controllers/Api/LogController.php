<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Company;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Entities\Log;
use Modules\User\Transformers\LogTransformer;
use Modules\User\Transformers\TipeLogTransformer;

class LogController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $log = new Log();        
        return LogTransformer::collection($log->historyLog($request));
    }

    public function loadTipe(Request $req)
    {
        $tipe_log = Log::select('tipe')->groupBy('tipe')->orderBy('tipe','asc')->get();

        return Response()->json(TipeLogTransformer::collection($tipe_log));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
