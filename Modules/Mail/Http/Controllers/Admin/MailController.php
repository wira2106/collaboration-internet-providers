<?php

namespace Modules\Mail\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Modules\Mail\Entities\Mail;
use Modules\Mail\Http\Requests\CreateMailRequest;
use Modules\Mail\Http\Requests\UpdateMailRequest;
use Modules\Mail\Repositories\MailRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Mail\Emails\NotifikasiWilayah;

class MailController extends AdminBaseController
{
    public function test()
    {
        $image = public_path() . '/openaccess.png';
        // dd($image);
        $mail = [
            'isp' => 'PT. Putra Wiranata',
            'osp' => 'PT. Jinom Netrwork Indonesia',
            'status' => 'request',
            'alasan' => 'BIAASALAAAHH...',
            'tgl_konfirmasi' => date('Y-M-d H:i:s'),
            'tgl_email' => date('M d, Y'),
            'wilayah' => 'Gianyar',
            'image' => $image
        ];
        // return view('mail::admin.pengajuan_wilayah.osp', compact('mail'));
        // return view('wilayah::notif.isp', compact('mail'));
        Mail::to(['wiranataputra21@gmail.com', 'gintokidmc@gmail.com'])->send(new NotifikasiWilayah($mail));
        return json_encode($mail);
    }
    public function sendMail()
    {
        $emails = ['wiranataputra21@gmail.com', 'gintokidmc@gmail.com']; // data yang ingin dikirimkan mail
        Mail::send('mail::Admin.mail.mail', ['title' => 'Test'], function ($message) use ($emails) {
            $message->to($emails)->subject('Hello guys. How are you today ?')
                ->from('no-reply@jasainter.net', 'Jasainter.net'); //mail,nama identitas  
        });

        return "Sukses";
        // var_dump( Mail:: failures());
        exit;
    }
}
