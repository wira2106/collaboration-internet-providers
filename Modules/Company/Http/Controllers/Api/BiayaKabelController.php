<?php

namespace Modules\Company\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\BiayaKabel;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\RangeBiayaKabel;
use Modules\Company\Events\BiayaKabelIsUpdating;
use Modules\Company\Events\RangeBiayaKabelIsCreating;
use Modules\Company\Events\RangeBiayaKabelIsUpdating;
use Modules\Company\Http\Requests\CreateBiayaKabelRequest;
use Modules\Company\Repositories\BiayaKabelRepository;
use Modules\Company\Transformers\BiayaKabelTransformer;
use Modules\Company\Transformers\RangeBiayaKabelTransformer;
use Modules\Company\Transformers\CompanyForSelectTransformer;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Core\Traits\ConvertToCurrency;

class BiayaKabelController extends AdminBaseController
{
    use ConvertToCurrency;
    private $biaya_kabel;
    
    public function __construct(BiayaKabelRepository $biaya_kabel)
    {
        $this->biaya_kabel = $biaya_kabel;
    }

    public function index(BiayaKabel $biaya_kabel, Request $request) {
        $all_osp = (new Company())->getAllCompany('osp');
        $company_id = $request->company_id;
        if(!$company_id) {
            $company_id = Auth::user()->hasAllAccessCompanies() ? (new Company())->getOneOSP()->company_id : Auth::user()->userCompanies->company_id;
            $request->merge([
                'company_id' => $company_id
            ]);
        } 
        
        return (
                new BiayaKabelTransformer($biaya_kabel->getBiayaKabel($request))
                )
                ->additional([
                    "companies"=> count($all_osp) > 0 ? CompanyForSelectTransformer::collection($all_osp) : [],
                    "company_id" => (int)$company_id
                ]);
    }

    public function indexRangeBiayaKabel(RangeBiayaKabel $biaya_kabel, Request $request) {
        return RangeBiayaKabelTransformer::collection($biaya_kabel->serverPaginationFilteringFor($request));
    }

    public function getBiayaKabel(Request $request) {
        $biaya_kabel = [];
        if($request->company_id) {
            $company_id = Auth::user()->hasAllAccessCompanies() ? $request->company_id : Auth::user()->userCompanies->company_id;

            $company = Company::find($company_id);
        } else {
            $company = Auth::user()->company();
        }

        if($company) {
            $biaya_kabel = $company->biayakabel();
        }


        return response()->json([
            'errors' => false,
            'data' => $biaya_kabel
        ]);
    }

    public function findNew(RangeBiayaKabel $range_biaya_kabel) {
        return (new RangeBiayaKabelTransformer($range_biaya_kabel));
    }

    public function find(RangeBiayaKabel $range_biaya_kabel) {
        return (new RangeBiayaKabelTransformer($range_biaya_kabel));
    }

    public function create(RangeBiayaKabel $biaya_kabel, CreateBiayaKabelRequest $request) {
        event($event = new RangeBiayaKabelIsCreating($biaya_kabel, $request->all()));
        if(!$event->getAttribute('company_id')) {
            $event->setAttributes([
                "company_id" => Auth::user()->userCompanies->company_id
            ]);
        }

        $event->check_data();

        $biaya_kabel->fill($event->getAttributes());
        $biaya_kabel->save();

        Auth::user()
        ->createLog(
            trans('company::range_biaya_kabel.logs.create.tipe'), 
            trans('company::range_biaya_kabel.logs.create.description', [
                'harga' => $this->rupiah($event->getAttribute('biaya')),
                'panjang' => $event->getAttribute('panjang_kabel')
            ]),
            $biaya_kabel->company_id()
        );

        return response()->json([
            'errors' => false,
            'message' => trans('company::biaya_kabel.messages.biaya_kabel created')
        ]);
    }

    public function delete(RangeBiayaKabel $range_biaya_kabel) {
        $range_biaya_kabel->delete();

        Auth::user()
        ->createLog(
            trans('company::biaya_kabel.logs.destroy.tipe'), 
            trans('company::biaya_kabel.logs.destroy.description', [
                'panjang' => $range_biaya_kabel->panjang_kabel
            ]),
            $range_biaya_kabel->company_id()
        );

        return response()->json([
            'errors' => false,
            'message' => trans('company::biaya_kabel.messages.biaya_kabel destroy')
        ]);
    }

    public function updateRangeBiayaKabel(RangeBiayaKabel $range_biaya_kabel, Request $request) {
        
        event($event = new RangeBiayaKabelIsUpdating($range_biaya_kabel, $request->all()));

        
        $event->check_data();
        $range_biaya_kabel->fill($event->getAttributes());
        
        $original = $range_biaya_kabel->getOriginal();
        $log_description = trans('company::range_biaya_kabel.logs.edit.description', [
            'panjang_before' =>  $original['panjang_kabel'],
            'harga_before' => $this->rupiah($original['biaya']),
            'panjang' => $range_biaya_kabel->panjang_kabel,
            'harga' => $this->rupiah($range_biaya_kabel->biaya)
        ]);

        $range_biaya_kabel->save();

        $log = Auth::user()
        ->createLog(
            trans('company::range_biaya_kabel.logs.edit.tipe'),
            $log_description,
            $range_biaya_kabel->company_id()
        );
        
        return response()->json([
            'errors' => false,
            'message' => trans('company::biaya_kabel.messages.biaya_kabel updated')
        ]);
    }

    public function update(BiayaKabel $biaya_kabel, Request $request) {
        event($event = new BiayaKabelIsUpdating($biaya_kabel, $request->all()));

        $biaya_kabel->fill($event->getAttributes());
        $original = $biaya_kabel->getOriginal();
        $biaya_kabel->save();

        Auth::user()
        ->createLog(
            trans('company::biaya_kabel.logs.edit.tipe'),
            trans('company::biaya_kabel.logs.edit.description',[
                'harga_before' => $this->rupiah($original['harga_per_meter']),
                'harga' => $this->rupiah($biaya_kabel->harga_per_meter)
            ]),
            $biaya_kabel->company_id
        );
        return response()->json([
            'errors' => false,
            'message' => trans('company::biaya_kabel.messages.biaya_kabel updated')
        ]);
    }
}
