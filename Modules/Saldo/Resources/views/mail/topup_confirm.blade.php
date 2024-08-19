@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> {{ $email_body }}</p>
           
            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('saldo::saldos.mail.topup is confirmation.detail saldo') }}
            </p>
        </div>
    </div>
@endsection