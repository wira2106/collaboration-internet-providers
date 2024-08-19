<?php

namespace Modules\Company\Events;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Modules\Company\Emails\CompanyCreatedMail;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\UserCompanies;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Saldo\Entities\Saldo;
use Modules\User\Contracts\Authentication;

class CompanyIsCreated extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $user;
    private $code;
    private $auth;
    private $company;
    private $data;
    private $admin;
    private $password_generate;
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct(Company $company, array $data)
    {
        $this->company = $company;
        $this->data = $data;
        parent::__construct($data);
        $this->log =  Auth::user();
        $this->log->createLog(
            trans('company::companies.logs.create.tipe'), 
            trans('company::companies.logs.create.description', ['company_name' => $data['name']])
        );
        $this->createAdminUser();
        $this->createSaldoRecord();
        $this->send_email();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    public function createAdminUser()
    {
        Model::unguard();
        $this->admin = $this->data['admin'];
        $this->password_generate = Hash::make(str_random(8));
        $user = Sentinel::create(
            [
                'email' => $this->admin['email'],
                'password' => $this->password_generate,
                'full_name' => $this->admin['fullname'],
                'phone' => $this->admin['phone']
            ]
        );

        $this->code = app(Authentication::class)->createReminderCode($user);
        $this->user = $user;

        // Activate the admin directly
        $activation = Activation::create($user);
        Activation::complete($user, $activation->code);

        // Find the group using the group id
        if ($this->company->type == 'isp') {
            $adminGroup = Sentinel::findRoleBySlug('admin isp');
        } else if ($this->company->type == 'osp') {
            $adminGroup = Sentinel::findRoleBySlug('admin osp');
        } else {
            $adminGroup = Sentinel::findRoleBySlug('admin');
        }

        // Assign the group to the user
        $adminGroup->users()->attach($user);

        UserCompanies::create([
            'user_id' => $user->id,
            'company_id' => $this->company['company_id']
        ]);

        app(\Modules\User\Repositories\UserTokenRepository::class)->generateFor($user->id);
    }

    public function createSaldoRecord() {
        Saldo::create([
            'company_id' => $this->company['company_id'],
            'saldo' => 0
        ]);
    }

    public function send_email() {
        $admin = $this->admin;
        $data = [
            "title" => trans('company::companies.mail.company created.title'),
            "data" => $this->data,
            "url" => route('resetpassword.complete',[
                'id'=>$this->user['id'],
                'code'=>$this->code,
            ]),
        ];
        Mail::to($admin['email'])->send(new CompanyCreatedMail($data));
    }
}
