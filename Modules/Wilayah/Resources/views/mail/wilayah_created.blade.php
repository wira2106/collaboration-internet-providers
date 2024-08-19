@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="margin-top:7vw; margin-left:0.2rem">
            <h5>Dear, <br> {{ $data['company']['name'] }},</h5>
        </div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> {{ trans('wilayah::wilayahs.mail.wilayah created.successfull created wilayah', ['wilayah_name' => $data['data']['name']]) }}</p>
            <table>
                <tr valign="top" height="15">
                    <td>{{ trans('wilayah::wilayahs.form.name') }}</td>
                    <td>:</td>
                    <td><b>{{ $data['data']['name'] }}</td>
                </tr>
                <tr valign="top" height="15">
                    <td>{{ trans('wilayah::wilayahs.form.postal_code') }}</td>
                    <td>:</td>
                    <td>{{ $data['data']['post_code'] }}</td>
                </tr>                    
            </table>
            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('wilayah::wilayahs.mail.wilayah created.detail wilayah') }}
            </p>
        </div>
    </div>
@endsection