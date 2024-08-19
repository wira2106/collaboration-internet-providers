<?php

namespace Modules\Wilayah\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Wilayah\Emails\WilayahIsOpenMail;
use Modules\Wilayah\Entities\Wilayah;

class WilayahIsOpen
{
    use SerializesModels;
    private $wilayah;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Wilayah $wilayah)
    {
        $this->wilayah = $wilayah;
        $this->send_email();
    }

    private function send_email() {
        $admins = (new Company())->admin_isp();
        foreach ($admins as $key => $admin) {
            Mail::to($admin->email)->send(new WilayahIsOpenMail($this->wilayah, $admin));
        }
    }

    
}
