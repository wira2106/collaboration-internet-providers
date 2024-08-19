<?php

namespace Modules\Wilayah\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\User\Entities\Sentinel\User;
use Modules\Wilayah\Entities\Wilayah;
use Modules\Wilayah\Events\Handlers\SendEmailWilayahCreated;

class WilayahIsCreated extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $wilayah;
    private $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct(Wilayah $wilayah, array $data)
    {
        //
        parent::__construct($data);
        $this->wilayah = $wilayah;
        $this->data = $data;
        $this->send_email();
        $this->log(Auth::user());
    }

    public function send_email() {
        $company = Company::find($this->getAttribute('company_id'));
        $emails_owner = $company->admin_email();
        $emails_openaccess = $company->admin_email(1);
        $emails = array_merge_recursive($emails_owner, $emails_openaccess);
        $data = [
            "title" => trans('wilayah::wilayahs.mail.wilayah created.title'),
            "data" => $this->data,
            "url" => route('admin.wilayah.wilayah.index'),
            'company' => $company,
        ];
        
        Mail::to($emails)->send(new SendEmailWilayahCreated($data));
        
    }

    private function log( User $user ) {
        $user->createLog(
            trans('wilayah::wilayahs.logs.create.tipe'),
            trans('wilayah::wilayahs.logs.create.description', [
                'wilayah' => $this->wilayah->name
            ]),
            $this->wilayah->company_id
        );
    }
}
