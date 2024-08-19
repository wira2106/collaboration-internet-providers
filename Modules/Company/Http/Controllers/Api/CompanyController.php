<?php

namespace Modules\Company\Http\Controllers\Api;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Modules\Company\Entities\Company;
use Modules\Company\Http\Requests\UpdateCompanyRequest;
use Modules\Company\Repositories\CompanyRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Company\Events\CompanyIsUpdating;
use Modules\Company\Transformers\CompanyTransformer;
use Modules\Company\Transformers\FullCompanyTransformer;
use Modules\Utils\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\DayOff;
use Modules\Company\Entities\HariKerja;
use Modules\Company\Entities\SlotInstalasi;
use Modules\Company\Entities\WorkingDay;
use Modules\Company\Events\CompanyIsCreated;
use Modules\Company\Events\CompanyIsUpdated;
use Modules\Company\Events\WorkDayIsDestroy;
use Modules\Company\Events\WorkDayIsUpdated;
use Modules\Company\Transformers\CompanyForSelectTransformer;
use Modules\Company\Transformers\DayOffTransformer;
use Modules\Company\Transformers\FullRatingTransformer;
use Modules\Company\Transformers\SlotInstalasiTransformer;
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
        $this->middleware(function ($request, $next) {
            $this->log =  Auth::user();
            return $next($request);
        });
        $this->company = $company;
    }


    public function pagination(Request $request)
    {
        return CompanyTransformer::collection($this->serverPaginationFilteringFor($request));
    }

    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {
        $company = Company::whereNotNull("type");

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $company->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('address', 'LIKE', "%{$term}%")
                    ->orWhere('company_id', $term);
            });
        }
        if ($request->type) {
            $company->where('type', $request->get('type'));
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $company->orderBy($request->get('order_by'), $order);
        } else {
            $company->orderBy('name', 'asc');
        }

        return $company->paginate($request->get('per_page', 10));
    }


    public function rating(Company $company, Request $request)
    {
        $data = [
            'total_rating' => $company->rating
        ];

        return FullRatingTransformer::collection($company->rating_history($request))->additional($data);
    }

    public function profile()
    {

        return new FullCompanyTransformer(Auth::user()->company());
    }

    public function find(Company $company)
    {
        return new FullCompanyTransformer($company);
    }

    public function findNew(Company $company)
    {
        $data = [
            'is_new' => true,
            'admin' => [
                'fullname' => '',
                'phone' => '',
                'password' => '',
                'check_pass' => '',
                'email' => ''
            ]
        ];
        return (new FullCompanyTransformer($company))->additional(['data' => $data]);
    }


    public function updateApi(Company $company, UpdateCompanyRequest $request)
    {
        DB::beginTransaction();
        event($event = new CompanyIsUpdating($company, $request->all()));
        if ($event->getAttribute("logo_perusahaan")) {
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
        
        if ($event->getAttribute('is_new')) event($event = new CompanyIsCreated($company, $event->getAttributes()));

        if (!$event->getAttribute('is_new')) event($event = new CompanyIsUpdated($company, $event->getAttributes()));

        DB::commit();
        return response()->json([
            'errors' => false,
            'message' => $request->company_id == null ? trans('company::companies.messages.company created') : trans('company::companies.messages.company updated'),
        ]);
    }

    public function destroyApi($company)
    {
        $this->log->createLog(
            trans('company::companies.logs.destroy.tipe'), 
            trans('company::companies.logs.destroy.description', [
                'company_name' => $company->name
            ])
        );
        $company->deleted_at = date("Y-m-d H:i:s");
        $company->save();
        return response()->json([
            'errors' => false,
            'message' => trans('company::companies.messages.company deleted'),
        ]);
    }

    public function dayoff(Company $company, Request $request)
    {
        $dates = $request->date;
        $description = $request->description;
        $insert = [];
        foreach ($dates as $date) {
            $insert[] = [
                'company_id' => $company->company_id,
                'tgl_libur' => $date,
                'deskripsi_libur' => $description,
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => Auth::user()->id
            ];
        }
        DayOff::insert($insert);
        $this->log->createLog(
            trans('company::dayoff.logs.create.tipe'), 
            trans('company::dayoff.logs.create.description')
        );
        return response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource updated', ['name' => trans('company::companies.title.dayoff')])
        ]);
    }

    public function getDayOff(Company $company)
    {
        return (DayOffTransformer::collection($company->dayoff));
    }

    public function dayon(Company $company, Request $request)
    {
        DayOff::where('company_id', $company->company_id)
            ->where('tgl_libur', $request->date)
            ->delete();

        $this->log->createLog(
            trans('company::dayoff.logs.destroy.tipe'), 
            trans('company::dayoff.logs.destroy.description', ['data' => $request->date])
        );
        return response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource updated', ['name' => trans('company::companies.title.dayoff')])
        ]);
    }

    public function workday(Company $company, Request $request)
    {
        event($event = new WorkDayIsUpdated($company, $request->all()));
        
        return response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource updated', ['name' => trans('company::companies.title.workingday')])
        ]);
    }

    public function getWorkDay(Company $company)
    {
        return (WorkingDayTransformer::collection($company->workingday));
    }

    public function biayaKabel()
    {
        return Auth::user()->company()->getRangeBiayaKabel();
    }

    public function updateBiayaKabel(Request $request)
    {
        Auth::user()->company()->updateBiayaKabel($request);
        $this->log->createLog(
            trans('company::biaya_kabel.logs.edit.tipe'), 
            trans('company::biaya_kabel.logs.edit.description')
        );
        return response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource updated', ['name' => trans('company::biaya_kabel.title.biaya-kabel')])
        ]);
    }

    public function slotInstalasi(Request $request) {
        $all_osp = (new Company())->getAllCompany('osp');
        $company_id = $request->company_id;
        if(!$company_id) {
            $company_id = Auth::user()->hasAllAccessCompanies() ? (new Company())->getOneOSP()->company_id : Auth::user()->userCompanies->company_id;
            $request->merge([
                'company_id' => $company_id
            ]);
        }

        return SlotInstalasiTransformer::collection(Company::find($company_id)->getSlotInstalasi($request))
                                        ->additional([
                                            "companies"=> count($all_osp) > 0 ? CompanyForSelectTransformer::collection($all_osp) : [],
                                            "company_id" => (int)$company_id
                                        ]);
    } 

    public function updateOrCreateSlot(Request $request)
    {
        $isUpdate = $request->slot_id !== null;
        $slot = SlotInstalasi::findOrNew($request->slot_id);

        $slot->fill($request->all());

        if ($isUpdate) {
            $this->log->createLog(
                trans('company::slot_instalasi.logs.edit.tipe'), 
                trans('company::slot_instalasi.logs.edit.description', [
                    'data' => $slot->name
                ])
            );

            $slot->updated_by = Auth::user()->id;
        } else {

            $this->log->createLog(
                trans('company::slot_instalasi.logs.create.tipe'), 
                trans('company::slot_instalasi.logs.create.description',[
                    'data' => $slot->name
                ])
            );

            $slot->company_id = $request->company_id;
            $slot->created_by = Auth::user()->id;
        }



        $slot->save();

        return response()->json([
            'errors' => false,
            'message' => $isUpdate ? trans('core::core.messages.resource updated', ['name' => trans('company::slot_instalasi.title.slot_instalasi')]) : trans('core::core.messages.resource created', ['name' => trans('company::slot_instalasi.title.slot_instalasi')])
        ]);
    }

    public function deleteSlot(SlotInstalasi $slotInstalasi)
    {
        $slotInstalasi->delete();
        $this->log->createLog(
            trans('company::slot_instalasi.logs.destroy.tipe'), 
            trans('company::slot_instalasi.logs.destroy.description', [
                'data' => $slotInstalasi->name
            ])
        );
        
        return response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource deleted', ['name' => trans('company::slot_instalasi.title.slot_instalasi')])
        ]);
    }

    public function companies(Company $company, Request $req)
    {
        $data = $company->getCompanyByType($req);
        return response()->json($data);
    }

    public function deleteWorkDay(WorkingDay $id) {
        event(new WorkDayIsDestroy($id));

        $id->delete();

        return response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource deleted', ['name' => trans('company::companies.title.workingday')])
        ]);
    }

    public function getRangeTime(Request $request) {
        $company_id = $request->company_id;
        if(!$company_id) {
            $company_id = Auth::user()->hasAllAccessCompanies() ? (new Company())->getOneOSP()->company_id : Auth::user()->userCompanies->company_id;
            $request->merge([
                'company_id' => $company_id
            ]);
        } 

        $rangeTime = (new HariKerja())->SelectableRange($company_id);

        return response()->json([
            'errors' => false,
            'data' => $rangeTime
        ]);

        
    }
}