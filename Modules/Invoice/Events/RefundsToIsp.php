<?php

namespace Modules\Invoice\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Invoice\Emails\RefundMail;
use Modules\Invoice\Entities\RefundItem;

class RefundsToIsp
{
    use SerializesModels;

    public $company;
    public $refund_item;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($company_id, $refund_item)
    {
        $this->company = Company::find($company_id);
        $this->refund_item = $refund_item;

        $this->send_email();
        
    }

    public function send_email()
    {
        $emails = $this->company->admin_email();
        Mail::to($emails)->send(new RefundMail($this->refund_item));
    }
}
