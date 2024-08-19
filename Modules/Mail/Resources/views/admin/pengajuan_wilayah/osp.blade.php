@extends('mail::admin.pengajuan_wilayah.master')
@section('konten')
<div>
    <div style="margin-top:7vw">
        <h5>Dear, <br> {{$mail['osp']}},</h5>
    </div>
    <div style="font-size: 14px">
        @if($mail['success'])
        <p style="padding-top:15px;"> {{ trans('mail::mails.mail.pengajuan.success',['isp'=> $mail['isp']]) }} <b>{{ $mail['wilayah'] }}</b> {{ trans('mail::mails.mail.pengajuan.end') }}</p>
        @elseif($mail['alasan_batal'] =='')
        <p style="padding-top:15px;"> {{ trans('mail::mails.mail.pengajuan.start') }} <b>{{ $mail['wilayah'] }}</b> {{ trans('mail::mails.mail.pengajuan.end') }}</p>
        @else
        <p style="padding-top:15px;"> {{ trans('mail::mails.mail.pengajuan.batal',['alasan'=> $mail['alasan_batal']]) }} <b>{{ $mail['wilayah'] }}</b> {{ trans('mail::mails.mail.pengajuan.end') }}</p>
        @endif
        <table>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.wilayah') }}</td>
                <td>:</td>
                <td>{{ $mail['wilayah'] }}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.pemilik') }}</td>
                <td>:</td>
                <td>{{$mail['osp']}}</td>
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
            @if($mail['status'] == 'disagree')
            <tr valign="top" height="15">
                <td>{{ trans('mail::mails.mail.status') }}</td>
                <td>:</td>
                <td>{{ $mail['status']}}</td>
            </tr>
            @endif
        </table>
        @if($mail['success'])
        <p style="padding-top:10px; margin-left:0.2rem">
            {{ trans('mail::mails.mail.tag.success') }}
        </p>
        @elseif($mail['alasan_batal'] =='')
        <p style="padding-top:10px; margin-left:0.2rem">
            {{ trans('mail::mails.mail.tag.tindakan konfirmasi') }}
        </p>
        @else
        <p style="padding-top:10px; margin-left:0.2rem">
            {{ trans('mail::mails.mail.tag.tindakan selanjutnya') }}
        </p>
        @endif
    </div>
</div>
@endsection