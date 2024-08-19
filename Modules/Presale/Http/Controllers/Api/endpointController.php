<?php

namespace Modules\Presale\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Presale\Emails\BayarEndpointMail;
use Modules\Presale\Entities\Endpoint;
use Modules\Presale\Events\BayarEndpoint;
use Modules\Presale\Events\EndpointIsCreated;
use Modules\Presale\Events\EndpointIsCreating;
use Modules\Presale\Events\EndpointIsDestroy;
use Modules\Presale\Events\EndpointIsUpdated;
use Modules\Presale\Repositories\endpointRepository;
use Modules\Presale\Transformers\EndpointLocationTransformer;
use Modules\Presale\Transformers\ListEndpointTransformer;
use Modules\Wilayah\Entities\Wilayah;

class endpointController extends AdminBaseController
{
    /**
     * @var endpointRepository
     */
    private $endpoint;

    public function __construct(endpointRepository $endpoint)
    {
        parent::__construct();
        $this->endpoint = $endpoint;
    }


    public function validationName(Request $request)
    {
        $company = Company::find($request->company_id);

        return response()->json([
            'valid' => $company->validationEndpointName($request)
        ]);
    }

    public function updateOrCreate(Endpoint $endpoint, Request $request)
    {
        $endpoint->hasAccess(Auth::user(), $request);
        $isUpdate = $endpoint->end_point_id != null;
        event($event = new EndpointIsCreating($endpoint, $request->all()));

        $endpoint->fill($event->getAttributes());

        if ($isUpdate) {
            $endpoint->updated_by = Auth::user()->id;
            event($event = new EndpointIsUpdated($endpoint, $request->all()));
        } else {
            $endpoint->created_by = Auth::user()->id;
            event($event = new EndpointIsCreated($endpoint, $request->all()));
        }
        $endpoint->save();

        return response()->json([
            'errors' => false,
            'message' => $isUpdate ? trans('core::core.messages.resource updated', ['name' => trans('presale::endpoint.title.sidebar')]) : trans('core::core.messages.resource created', ['name' => trans('presale::endpoint.title.sidebar')]),
            'data' => new EndpointLocationTransformer($endpoint)
        ]);
    }

    public function getEndpointLocation(Request $request)
    {
        $wilayah = Wilayah::find($request->wilayah_id);
        // $wilayah->hasAccess(Auth::user());
        $endpoint = $wilayah->endpoint();
        return EndpointLocationTransformer::collection($endpoint);
    }

    public function getEndpointDetail(Endpoint $endpoint, Request $request)
    {
        return response()->json($endpoint);
    }

    public function getEndpointDetailHtml(Endpoint $endpoint)
    {
        return $endpoint->infowindow();
    }


    public function delete(Endpoint $endpoint)
    {
        $endpoint->deletePresale();
        event(new EndpointIsDestroy($endpoint));
        $endpoint->delete();

        return response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource deleted', ['name' => trans('presale::endpoint.title.sidebar')])
        ]);
    }

    public function getRoutes(Endpoint $endpoint)
    {
        return $endpoint->routes();
    }

    public function index(Endpoint $endpoint, Request $request)
    {
        $data = $endpoint->serverPaginationFilteringFor($request);
        return ListEndpointTransformer::collection($data);
    }

    public function bayar(Wilayah $wilayah, Request $request)
    {
        DB::beginTransaction();

        event($event = new BayarEndpoint($wilayah, $request->endpoints));
        $biaya = $event->potong_saldo();
        $emails = (new Company)->admin_email($wilayah->company_id);

        DB::commit();

        Mail::to($emails)->send(new BayarEndpointMail($wilayah, $biaya));

        return response()->json([
            'errors' => false,
            'message' => trans('presale::endpoint.message.sukses bayar endpoint')
        ]);
    }
}
