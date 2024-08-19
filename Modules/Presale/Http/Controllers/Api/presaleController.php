<?php

namespace Modules\Presale\Http\Controllers\Api;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\Company;
use Modules\Presale\Repositories\presaleRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Presale\Emails\EndpointCreatedEmail;
use Modules\Presale\Emails\EndpointCreatedMail;
use Modules\Presale\Entities\Presale;
use Modules\Presale\Entities\PresaleClass;
use Modules\Presale\Entities\StatusPresale;
use Modules\Presale\Events\EmailConfirmationPresale;
use Modules\Presale\Events\PresaleIsConfirmed;
use Modules\Presale\Events\PresaleIsCreated;
use Modules\Presale\Events\PresaleIsCreating;
use Modules\Presale\Events\PresaleIsDestroy;
use Modules\Presale\Events\PresaleIsUpdated;
use Modules\Presale\Http\Requests\CreatepresaleRequest;
use Modules\Presale\Http\Requests\UpdatePresaleLocationRequest;
use Modules\Presale\Transformers\FullPresaleTransformer;
use Modules\Presale\Transformers\ListPresaleTransformer;
use Modules\Presale\Transformers\PresaleLocationTransformer;
use Modules\Presale\Transformers\PresaleLocationPengajuan;
use Modules\Utils\Http\Controllers\ImageController;
use Modules\Wilayah\Entities\Wilayah;
use Modules\Wilayah\Events\WilayahIsOpen;
use Modules\Core\Traits\GzipEncode;
use Modules\Presale\Entities\Endpoint;

class presaleController extends AdminBaseController
{
    use GzipEncode;

    /**
     * @var presaleRepository
     */
    private $presale;
    private $mail;

    public function __construct(presaleRepository $presale, Mailer $mail)
    {
        parent::__construct();
        $this->presale = $presale;
        $this->mail = $mail;
    }

    public function region(Request $request)
    {
        $company = Auth::user()->company();
        if (Auth::user()->hasAllAccessCompanies()) {
            $company = new Company();
            $company = $company::find($request->company_id) ? $company::find($request->company_id) : $company->getOneOSP();
        }

        $wilayah = $company->getWilayah($request);
        $companies = $company->getAllOSP(Auth::user());
        $data = [
            'wilayah' => $wilayah,
            'companies' => $companies,
            'saldo' => $company->saldo(),
            'selected' => [
                'company_id' => $company->company_id,
            ]
        ];
        if (!($request->wilayah)) {
            $data['selected']['wilayah'] = count($wilayah) > 0 ? $wilayah[0] : null;
        }
        return response()->json($data);
    }

    public function index(Presale $presale, Request $request)
    {
        if (Auth::user()->getRoleName() === 'Admin ISP') {
            $paginate = $presale->serverPaginationFilteringForIsp($request);
        } else {
            $paginate = $presale->serverPaginationFilteringFor($request);
        }

        $data = PresaleLocationTransformer::collection($paginate)->getCollection();
        $links = $this->generateLinksPagination($paginate, $request->page);
        $send = (object)[
            'data' => $data,
            'links' => $links
        ];
        // dd($paginate->getLastPage(), $paginate->getCurrentPage());
        return $this->gzipEncode($send);
    }

    function generateLinksPagination($paginate, $page)
    {
        $links = [
            'first' => route('api.presale.presales.index', ['page' => 1]),
            'last' => route('api.presale.presales.index', ['page' => $paginate->lastPage()]),
            'next' => (int)$page != $paginate->lastPage() ? route('api.presale.presales.index', ['page' => (int)$page + 1]) : null,
            'prev' => (int)$page != 1 ? route('api.presale.presales.index', ['page' => (int)$page - 1]) : null,
        ];

        return $links;
    }

    public function biayakabel(Request $request)
    {
        $company = Company::findOrNew($request->company_id);

        return $company->biayakabel();
    }

    public function presaleClass()
    {
        return PresaleClass::select('class_id as value', 'class_name as label')->where('deleted', 0)->get();
    }

    public function presaleStatus()
    {
        return StatusPresale::select('name as label', 'status_id as value')->where('deleted', 0)->get();
    }

    public function updateOrCreate(Presale $presale, CreatepresaleRequest $request)
    {
        $isNew = true;
        event($event = new PresaleIsCreating($presale, $request->all()));

        if ($event->getAttribute("foto_rumah")) {
            $filename = ImageController::uploadFoto($event->getAttribute("foto_rumah"));
            $event->setAttributes([
                "foto_rumah" => $filename
            ]);
        } else {
            $event->removeAttribute("foto_rumah");
        }

        if ($presale->presale_id) {
            $isNew = false;
            $presale->updated_by = Auth::user()->id;
        } else {
            $presale->created_by = Auth::user()->id;
        }

        $presale->fill($event->getAttributes());
        $presale->save();
        $presale->withIcon();

        $active_presale = DB::table('active_presales')->where('presale_id', $presale->presale_id)->first();

        $presale->active_presale_id = $active_presale;

        if ($isNew) {
            event($event = new PresaleIsCreated($presale, $request->all()));
        } else {
            event($event = new PresaleIsUpdated($presale, $request->all()));
        }

        return response()->json([
            'errors' => false,
            'message' => $presale->presale_id == null ? trans('presale::presales.messages.presale created') : trans('presale::presales.messages.presale updated'),
            'data' => new PresaleLocationTransformer($presale)
        ]);
    }

    public function getPresaleHtml(Presale $presale_id)
    {


        return $presale_id->infowindow();
    }

    public function presaleUpdateLocation(Presale $presale, UpdatePresaleLocationRequest $request)
    {
        $presale->fill($request->all());
        $presale->save();
        $presale->updatePathLine($request->path);

        event(new PresaleIsUpdated($presale, $request->all()));

        return response()->json([
            'errors' => false,
            'message' => trans('presale::presales.messages.presale updated'),
        ]);
    }

    public function show(Presale $presale)
    {
        return new FullPresaleTransformer($presale->withDataEndpoint());
    }

    public function delete(Presale $presale)
    {
        $presale->delete();

        event(new PresaleIsDestroy($presale));
        return response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource deleted', ['name' => trans('presale::presales.title.sidebar')])
        ]);
    }

    public function updateStatus(Presale $presale, Request $request)
    {
        $user = Auth::user();
        $presale->fill($request->all());
        $presale->save();
        $presale->withIcon();

        if ($user->hasRoleName('Admin OSP')) {
            $presale->isActivePresale();
        }



        event(new PresaleIsUpdated($presale, $request->all()));

        return response()->json([
            'errors' => false,
            'message' => trans('presale::presales.messages.presale updated'),
            'data' => new PresaleLocationTransformer($presale)
        ]);
    }

    public function konfirmasi(Request $request)
    {
        $model = Presale::findMany($request->data);

        DB::beginTransaction();
        $kabel_terpanjang = 0;
        $wilayah_id = false;
        foreach ($model as $key => $presale) {
            if (!$wilayah_id) $wilayah_id = $presale->wilayah_id;
            if ($presale->panjang_kabel > $kabel_terpanjang) $kabel_terpanjang = $presale->panjang_kabel;
        }

        $presale = (new Presale)->confirm($model, $kabel_terpanjang);

        $message = trans('presale::presales.messages.presale confirmed');

        if ($request->open_wilayah) {
            $check_endpoint = Endpoint::where('wilayah_id', $wilayah_id)->whereNull('settlement_at')->first();
            if ($check_endpoint == null) {
                $wilayah = Wilayah::select([
                    'wilayah.*',
                    'companies.name as company_name',
                    'wilayah.name as wilayah_name',
                ])
                    ->where('wilayah_id', $wilayah_id)
                    ->join('companies', 'companies.company_id', 'wilayah.company_id')
                    ->first();

                $wilayah->open = 1;
                $wilayah->status_presale = 'finish';
                $wilayah->save();

                event(new WilayahIsOpen($wilayah));
            } else {
                $message = trans('wilayah::wilayahs.tidak bisa open wilayah');
            }
        }

        DB::commit();

        $data = (new Presale)->findManyJoinActivePresale($request->data);

        return response()->json([
            'errors' => false,
            'message' => $message,
            'data' => PresaleLocationTransformer::collection($data)
        ]);
    }

    public function getRoutes(Presale $presale)
    {
        return $presale ? $presale->routes() : [];
    }

    public function list(Presale $presale, Request $request)
    {
        if (Auth::user()->getRoleName() === 'Admin ISP') {
            $data = $presale->serverPaginationFilteringForIsp($request);
        } else {
            $data = $presale->serverPaginationFilteringFor($request);
        }

        return ListPresaleTransformer::collection($data);
    }

    public function getPresalesLocation(Request $request)
    {
        $wilayah = Wilayah::find($request->wilayah_id);
        // $wilayah->hasAccess(Auth::user());
        $presales = $wilayah->presales();
        return PresaleLocationPengajuan::collection($presales);
    }

    public function email_confirmation(Wilayah $wilayah)
    {

        $wilayah->status_presale = 'review';
        event($event = new EmailConfirmationPresale($wilayah));
        $wilayah->save();

        return response()->json([
            'errors' => false,
            'messages' => trans('presale::presales.konfirmasi.email terkirim')
        ]);
    }


    public function testing()
    {
        $data = [
            "title" => trans('presale::endpoint.mail.endpoint created.title'),
            "url" => route('admin.presale.presale.index'),
            "biaya" => '23123',
            'data' => 'asd',
            'emails' => 'asd'
        ];

        // dd(new EndpointCreatedMail($data));

        $this->mail->to('rupadana@gmail.com')->send(new EndpointCreatedMail($data));
    }
}
