@extends('mail::Admin.mail.master')
@section('konten')
<div>
    <div style="margin-top:7vw">
        <h5>Dear, <br> PT. Jinom Network Indonesia,</h5>
    </div>
    <div style="font-size: 14px">
        <p style="padding-top:15px;"> {{ trans('mail::mails.mail.pengajuan.start') }} <b>Abiansemal, Badung, Bali</b> {{ trans('mail::mails.mail.pengajuan.end') }}</p>
        <table>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.wilayah') }}</td>
                <td>:</td>
                <td><b>Abiansemal, Badung, Bali</b>{{-- $mail['wilayah'] --}}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.pemilik') }}</td>
                <td>:</td>
                <td>I Made Adi Sasmita {{-- $mail['osp'] --}}</td>
            </tr>
            <tr>
                <td><br></td>
            </tr>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.diajukan') }}</td>
                <td width="">:</td>
                <td>PT. Jinom Network Indonesia {{-- $mail['isp'] --}}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.alasan pengajuan') }}</td>
                <td>:</td>
                <td>Untuk melancarkan kegiatan PJJ{{-- $mail['alasan'] --}}</td>
            </tr>
            <tr>
                <td><br></td>
            </tr>
        </table>
        <p style="padding-top:10px; margin-left:0.2rem">
            {{ trans('mail::mails.mail.tag.tindakan konfirmasi') }}
        </p>
    </div>
</div>
@endsection