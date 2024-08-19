<?php

namespace Modules\Saldo\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\BankAccount;
use Modules\Company\Entities\Company;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Saldo\Emails\WithdrawIsConfirmed as EmailsWithdrawIsConfirmed;
use Modules\Saldo\Entities\Saldo;
use Modules\Saldo\Entities\topup;
use Modules\Saldo\Entities\withdraw;

class WithdrawIsConfirmed extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels, ConvertToCurrency;

    private $data;
    private $company;
    private $withdraw;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Company $company,withdraw $withdraw, array $data)
    {
        $this->data = $data;
        $this->company = $company;
        $this->withdraw = $withdraw;

        parent::__construct($data);
        $this->send_email();
    }
    
    public function send_email() {
        $emails = $this->company->admin_email();
        $data_update = $this->data; 
        $bank = BankAccount::where('bank_account_id', $this->withdraw->bank_account_id)
                            ->join('bank', 'bank.bank_id', 'bank_account.bank_id')
                            ->first();
        $data = [
            "title" => trans('saldo::saldos.mail.topup saldo created.title'),
            "url" => route('admin.saldo.withdraw.index'),
            "total" => $this->rupiah($this->withdraw->amount) ,
            "company" => Company::find($this->getAttribute('company_id')),
            'status' => $this->getAttribute('status'),
            'bank' => $bank,
            'bukti_transfer' => $data_update['bukti_transfer'],
        ];
        if($this->getAttribute('status') !== 'cancel') {
            $data['email_body'] = trans('saldo::saldos.mail.withdraw is confirmation.confirmed body', [
                'company_name' => $this->company->name,
                'total' => $this->rupiah($this->withdraw->amount) ,
                
                
            ]);
            $data['subject'] = trans('saldo::saldos.mail.withdraw is confirmation.confirmed subject');
        } else {
            $data['email_body'] = trans('saldo::saldos.mail.withdraw is confirmation.rejected body', [
                'company_name' => $this->company->name,
                'total' => $this->rupiah($this->withdraw->amount),
                'alasan' => $this->getAttribute('keterangan'),
            ]);
            $data['subject'] = trans('saldo::saldos.mail.withdraw is confirmation.rejected subject');
        }

        
        Mail::to($emails)->send(new EmailsWithdrawIsConfirmed($data));
        // Mail::send('saldo::mail.withdraw_confirm', $data, function($message) use ($emails,$subject, $data_update) {
        //     $message->to($emails)->subject($subject)->from('no-reply@openaccess.net.id');

        //     if($data_update['status']  !== 'cancel') {
        //         $message->attach(public_path('uploadgambar/'.$data_update['bukti_transfer']));
        //     }
        // });
    }
    
}
