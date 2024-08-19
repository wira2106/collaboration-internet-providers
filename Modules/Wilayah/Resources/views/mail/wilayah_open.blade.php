@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="margin-top:7vw; margin-left:0.2rem">
            <h5>Hai, <br> {{ $isp_name }},</h5>
        </div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> 
            {{ 
                trans('wilayah::wilayahs.mail.wilayah open.body', [
                    'wilayah_name' => $wilayah_name,
                    'company_name' => $osp_name
                ]) 
            }}
            </p>
            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('wilayah::wilayahs.mail.wilayah created.detail wilayah') }}
            </p>
        </div>
    </div>
@endsection