<?php

namespace Modules\Configuration\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Configuration\Entities\Configuration;
use Modules\Configuration\Http\Requests\CreateConfigurationRequest;
use Modules\Configuration\Http\Requests\UpdateConfigurationRequest;
use Modules\Configuration\Repositories\ConfigurationRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use DB;

class ConfigurationController extends AdminBaseController
{
    /**
     * @var ConfigurationRepository
     */
    private $configuration;

    public function __construct(ConfigurationRepository $configuration)
    {
        parent::__construct();
        $this->configuration = $configuration;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('configuration::admin.configurations.index');
    }

    public function data()
    {
        return Configuration::find(1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('configuration::admin.configurations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateConfigurationRequest $request
     * @return Response
     */
    public function store(CreateConfigurationRequest $request)
    {
        $this->configuration->create($request->all());

        return redirect()->route('admin.configuration.configuration.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('configuration::configurations.title.configurations')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Configuration $configuration
     * @return Response
     */
    public function edit(Configuration $configuration)
    {
        // return Configuration::find(1);
        return view('configuration::admin.configurations.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Configuration $configuration
     * @param  UpdateConfigurationRequest $request
     * @return Response
     */
    public function update($configuration, UpdateConfigurationRequest $request)
    {
        $selectedConfiguration = Configuration::find($configuration);

        $selectedConfigurationName = array("admin_fee","persentase_otc_mrc","persentase_refund_osp","persentase_refund_openaccess","sla_odp","sla_join_box","sla_fixing_stack","biaya_ep","kabel_per_meter");

        for($n = 0 ; $n < count($selectedConfigurationName) ; $n++){
            if($request->fieldEdit == $n){
                $dataFieldArray = $selectedConfigurationName[$n];

                $selectedConfiguration->$dataFieldArray = $request->$dataFieldArray;
                $selectedConfiguration->save();
            }
        }
        return response()->json([
            'error' =>false,
            'message'=>trans('core::core.messages.resource updated', ['name' => trans('configuration::configurations.title.configurations')])
        ]);

        // $configuration = Configuration::updateOrCreate(['id'=>$configuration],$request->all());
        // return response()->json([
        //     'error' =>false,
        //     'message'=>trans('core::core.messages.resource updated', ['name' => trans('configuration::configurations.title.configurations')])
        // ]);
    //     return redirect()->route('admin.configuration.configuration.index')
    //         ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('configuration::configurations.title.configurations')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Configuration $configuration
     * @return Response
     */
    public function destroy(Configuration $configuration)
    {
        $this->configuration->destroy($configuration);

        return redirect()->route('admin.configuration.configuration.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('configuration::configurations.title.configurations')]));
    }
}
