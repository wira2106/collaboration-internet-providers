@extends('mail::admin.pengajuan_wilayah.master')
@section('konten')
<div>
    <div style="margin-top:7vw; margin-left:0.2rem">
        <h5>Dear, <br> {{$mail['isp']}},</h5>
    </div>
    <div style="font-size: 14px">
        <p style="padding-top:15px; margin-left:0.2rem"> {{ trans('mail::mails.mail.pengajuan.start') }} <b>{{$mail['wilayah']}}</b> {{ trans('mail::mails.mail.pengajuan.end') }} </p>
        <table>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.wilayah') }}</td>
                <td>:</td>
                <td><b>{{$mail['wilayah']}}</b></td>
            </tr>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.pemilik') }}</td>
                <td>:</td>
                <td>{{$mail['osp'] }}</td>
            </tr>
            <tr>
                <td><br></td>
            </tr>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.diajukan') }}</td>
                <td width="">:</td>
                <td>{{$mail['isp']}}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.alasan pengajuan') }}</td>
                <td>:</td>
                <td>{{ $mail['alasan']}}</td>
            </tr>
            <tr>
                <td><br></td>
            </tr>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.status') }}</td>
                <td>:</td>
                <td>{{$mail['status']}}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.konfirmasi') }}</td>
                <td width="">:</td>
                <td>{{$mail['tgl_konfirmasi']}}</td>
            </tr>
            @if($mail['alasan_batal'] !== '')
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.alasan') }}</td>
                <td width="">:</td>
                <td>{{$mail['alasan_batal']}}</td>
            </tr>
            @endif
        </table>+
        <p style="padding-top:10px; margin-left:0.2rem">
            {{ trans('mail::mails.mail.tag.detail konfirmasi') }}
        </p>
    </div>
</div>
@endsection