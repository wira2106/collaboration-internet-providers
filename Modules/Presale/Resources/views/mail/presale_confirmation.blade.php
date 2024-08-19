@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> {{ trans('presale::presales.mail.presale confirmation email.successfull created presale', ['wilayah_name' => $data['wilayah']['name']]) }}</p>
           
            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('presale::presales.mail.presale confirmation email.detail presale') }}
            </p>
        </div>
    </div>
@endsection