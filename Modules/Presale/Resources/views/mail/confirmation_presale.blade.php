@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> {{ trans('presale::presales.mail.confirmation presale.successfull confirmation presale', ['biaya' => $data['biaya']]) }}</p>
           
            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('presale::presales.mail.confirmation presale.detail presale') }}
            </p>
        </div>
    </div>
@endsection