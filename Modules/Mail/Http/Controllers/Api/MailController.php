<?php

namespace Modules\Mail\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Modules\Mail\Entities\Mail;
use Modules\Mail\Http\Requests\CreateMailRequest;
use Modules\Mail\Http\Requests\UpdateMailRequest;
use Modules\Mail\Repositories\MailRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Support\Facades\Mail;
use Modules\Mail\Emails\NotifikasiWilayah;
use DB;
use Illuminate\Support\Facades\Auth;

class MailController
{
    public static function sendEmailOSP($data)
    {
        $sendEmail = [];
        $email = DB::table('users')
            ->select('users.email')
            ->join('user_companies', 'users.id', '=', 'user_companies.user_id')
            ->where('company_id', '=', $data['company_id'])
            ->get();
        foreach ($email as  $value) {
            array_push($sendEmail, $value->email);
        }
        $mail = [
            'isp' => $data['isp'],
            'osp' => $data['osp'],
            'status' => $data['status'],
            'alasan' => $data['alasan'],
            'alasan_batal' => $data['alasan_batal'],
            'tgl_konfirmasi' => date('Y-M-d H:i:s'),
            'tgl_email' => date('M d, Y'),
            'wilayah' => $data['wilayah'],
            'success' => $data['success'],
            "title" => trans('wilayah::pengajuans.title.pengajuans wilayah'),
            "url" => route('admin.wilayah.pengajuan.index'),
            'type_company' => Auth::user()->company()->type
        ];
        Mail::to($sendEmail)->send(new NotifikasiWilayah($mail));
        return json_encode($mail);
    }
    public static function sendEmailISP($data)
    {
        $email = '';
        $mail = [
            'isp' => $data['isp'],
            'osp' => $data['osp'],
            'status' => $data['status'],
            'alasan' => $data['alasan'],
            'alasan_batal' => $data['alasan_batal'],
            'tgl_konfirmasi' => date('Y-M-d H:i:s'),
            'tgl_email' => date('M d, Y'),
            'wilayah' => $data['wilayah'],
            'success' => $data['success'],
            "title" => trans('wilayah::pengajuans.title.pengajuans wilayah'),
            "url" => route('admin.wilayah.pengajuan.index'),
        ];


        Mail::to($email)->send(new NotifikasiWilayah($mail));
        return json_encode($mail);
    }
}
