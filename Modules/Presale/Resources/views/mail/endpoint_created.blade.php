@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> {{ trans('presale::endpoint.mail.endpoint created.successfull created endpoint', ['biaya' => $data['biaya']]) }}</p>
           
            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('presale::endpoint.mail.endpoint created.detail endpoint') }}
            </p>
        </div>
    </div>
@endsection