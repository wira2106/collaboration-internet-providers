<?php

namespace Modules\Company\Http\Controllers\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Company\Entities\Company;
use Modules\Company\Http\Requests\CreateCompanyRequest;
use Modules\Company\Http\Requests\UpdateCompanyRequest;
use Modules\Company\Repositories\CompanyRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Support\Facades\DB;
use Modules\Company\Events\CompanyIsUpdating;
use Modules\Company\Transformers\CompanyTransformer;
use Modules\Company\Transformers\FullCompanyTransformer;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\Utils\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\DayOff;
use Modules\Company\Events\WorkDayIsUpdated;
use Modules\Company\Transformers\DayOffTransformer;
use Modules\Company\Transformers\WorkingDayTransformer;

class CompanyController extends AdminBaseController
{
    /**
     * @var CompanyRepository
     */
    private $company;

    public function __construct(CompanyRepository $company)
    {
        parent::__construct();

        $this->company = $company;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$companies = $this->company->all();

        return view('company::admin.companies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('company::admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCompanyRequest $request
     * @return Response
     */
    public function store(CreateCompanyRequest $request)
    {
        $company = new Company;
        event($event = new CompanyIsUpdating($company, $request->all()));
        
        if($event->getAttribute("logo_perusahaan")) {
            $filename = ImageController::uploadFoto($event->getAttribute("logo_perusahaan"));

            $event->setAttributes([
                "logo_perusahaan" => $filename
            ]);
        } else {
            $event->removeAttribute("logo_perusahaan");
        }
        $company->fill($event->getAttributes());
        $company->save();


        return redirect()->route('admin.company.company.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('company::companies.title.companies')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Company $company
     * @return Response
     */
    public function edit(Company $company)
    {
        // dd($company);
        // dd($company);
        return view('company::admin.companies.edit', compact('company'));
    }

    public function profile()
    {
        return view('company::admin.companies.profile');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Company $company
     * @param  UpdateCompanyRequest $request
     * @return Response
     */
    public function update(Company $company,  Request $request)
    {
        event($event = new CompanyIsUpdating($company, $request->all()));
        if ($event->getAttribute("logo_perusahaan")) {
            $filename = ImageController::uploadFoto($event->getAttribute("logo_perusahaan"));

            $event->setAttributes([
                "logo_perusahaan" => $filename
            ]);
        } else {
            $event->removeAttribute("logo_perusahaan");
        }
        $company->fill($event->getAttributes());
        $company->save();

        return redirect()->route('admin.company.company.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('company::companies.title.companies')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Company $company
     * @return Response
     */
    public function destroy(Company $company)
    {
        $this->company->destroy($company);

        return redirect()->route('admin.company.company.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('company::companies.title.companies')]));
    }

    public function pagination(Request $request)
    {
        return CompanyTransformer::collection($this->serverPaginationFilteringFor($request));
    }

    public function serverPaginationFilteringFor(Request $request)
    {
        $company = new Company;
        $company = DB::table($company->getTableName())->whereNull("deleted_at")->whereNotNull("type");
        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $company->where('name', 'LIKE', "%{$term}%")
                ->orWhere('address', 'LIKE', "%{$term}%")
                ->orWhere('company_id', $term);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $company->orderBy($request->get('order_by'), $order);
        } else {
            $company->orderBy('created_at', 'desc');
        }

        return $company->paginate($request->get('per_page', 10));
    }

    public function find(Company $company)
    {
        return new FullCompanyTransformer($company);
    }

    public function findNew(Company $company)
    {
        return (new FullCompanyTransformer($company))->additional(['data' => ['is_new' => true]]);
    }

    public function jsonListCompany(Request $req)
    {
        $jmlSeluruh = $this->serverPaginationFilteringFor($req, 1)->count();
        $data = $this->serverPaginationFilteringFor($req);
        $kirim[] = $jmlSeluruh;
        $kirim[] = CompanyTransformer::collection($data);
        return json_encode($kirim);
    }

    public function updateApi(Company $company, UpdateCompanyRequest $request )
    {
        event($event = new CompanyIsUpdating($company, $request->all()));
        
        if($event->getAttribute("logo_perusahaan")) {
            $filename = ImageController::uploadFoto($event->getAttribute("logo_perusahaan"));
            // dd($filename);
            $event->setAttributes([
                "logo_perusahaan" => $filename
            ]);
        } else {
            $event->removeAttribute("logo_perusahaan");
        }

        $company->fill($event->getAttributes());
        $company->save();


        return response()->json([
            'errors' => false,
            'message' => $company ? trans('company::companies.messages.company created') : trans('company::companies.messages.company updated'),
        ]);
    }

    public function destroyApi($company)
    {
        $company->deleted_at = date("Y-m-d H:i:s");
        $company->save();

        return response()->json([
            'errors' => false,
            'message' => trans('company::companies.messages.company deleted'),
        ]);
    }

    public function dayoff(Company $company, Request $request) {
        $date = $request->date;
        $description = $request->description;
        DayOff::create([
            'company_id' => $company->company_id,
            'tgl_libur' => $date,
            'deskripsi_libur' => $description,
            'created_at' => date("Y-m-d H:i:s"),
            'created_by' => Auth::user()->id
        ]);
        return response()->json([
            'error' => false,
            'message' => trans('core::core.messages.resource updated', ['name' => trans('company::companies.title.dayoff')])
        ]);
    }

    public function getDayOff(Company $company) {
        return (DayOffTransformer::collection($company->dayoff));
    }

    public function dayon(Company $company, Request $request) {
       DayOff::where('company_id', $company->company_id)
            ->where('tgl_libur', $request->date)
            ->delete();
        return response()->json([
            'error' => false,
            'message' => trans('core::core.messages.resource updated', ['name' => trans('company::companies.title.dayoff')])
        ]);
    }

    public function workday(Company $company, Request $request) {
        event($event = new WorkDayIsUpdated($company, $request->all()));
        
        return response()->json([
            'error' => false,
            'message' => trans('core::core.messages.resource updated', ['name' => trans('company::companies.title.workingday')])
        ]);
    }

    public function getWorkDay(Company $company) {
        return (WorkingDayTransformer::collection($company->workingday));
    }

    public function biaya_kabel(Company $company) {
        return view('company::admin.biayakabel.index');
    }

    public function slot_instalasi() {
        return view('company::admin.slot_instalasi.index');
    }

    public function loginResetPassword(){
        return view('company::users.login');
    }
}
