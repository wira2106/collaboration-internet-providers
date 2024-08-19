@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="margin-top:7vw; margin-left:0.2rem">
            <h5>Dear, <br> {{ $data['data']['admin']['fullname'] }},</h5>
        </div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> {{ trans('company::companies.mail.company created.successfull created company', ['company_name' => $data['data']['name']]) }}</p>
            <table>
                <tr valign="top" height="15">
                    <td>{{ trans('company::companies.table.name') }}</td>
                    <td>:</td>
                    <td><b>{{ $data['data']['name'] }}</td>
                </tr>       
                <tr valign="top" height="15">
                    <td>{{ trans('company::companies.form.address') }}</td>
                    <td>:</td>
                    <td><b>{{ $data['data']['address'] }}</td>
                </tr>       
                {{-- <tr valign="top" height="15">
                    <td>{{ trans('company::companies.form.admin.password') }}</td>
                    <td>:</td>
                    <td>{{ $data['data']['admin']['password'] }}</td>
                </tr>       
                <tr valign="top" height="15">
                    <td>{{ trans('company::companies.form.admin.phone') }}</td>
                    <td>:</td>
                    <td>{{ $data['data']['admin']['phone'] }}</td>
                </tr>        --}}
            </table>
            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('company::companies.mail.company created.detail company') }}
            </p>
        </div>
    </div>
@endsection
