<?php

namespace Modules\Pelanggan\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Pelanggan\Entities\Survey;
use Modules\Pelanggan\Http\Requests\CreateSurveyRequest;
use Modules\Pelanggan\Http\Requests\UpdateSurveyRequest;
use Modules\Pelanggan\Repositories\SurveyRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class SurveyController extends AdminBaseController
{
    /**
     * @var SurveyRepository
     */
    private $survey;

    public function __construct(SurveyRepository $survey)
    {
        parent::__construct();

        $this->survey = $survey;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$surveys = $this->survey->all();

        return view('pelanggan::admin.surveys.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pelanggan::admin.surveys.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateSurveyRequest $request
     * @return Response
     */
    public function store(CreateSurveyRequest $request)
    {
        $this->survey->create($request->all());

        return redirect()->route('admin.pelanggan.survey.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('pelanggan::surveys.title.surveys')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Survey $survey
     * @return Response
     */
    public function edit(Survey $survey)
    {
        return view('pelanggan::admin.surveys.edit', compact('survey'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Survey $survey
     * @param  UpdateSurveyRequest $request
     * @return Response
     */
    public function update(Survey $survey, UpdateSurveyRequest $request)
    {
        $this->survey->update($survey, $request->all());

        return redirect()->route('admin.pelanggan.survey.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('pelanggan::surveys.title.surveys')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Survey $survey
     * @return Response
     */
    public function destroy(Survey $survey)
    {
        $this->survey->destroy($survey);

        return redirect()->route('admin.pelanggan.survey.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('pelanggan::surveys.title.surveys')]));
    }
}
