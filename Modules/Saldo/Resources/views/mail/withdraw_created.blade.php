@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> {{ trans('saldo::saldos.mail.withdraw created.successfull created withdraw', ['total' => $total, 'company_name' => $company->name]) }}</p>
           
            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('saldo::saldos.form.rek') }}
            </p>
            <table>
                <tr valign="top" height="15">
                    <td>{{ trans('saldo::saldos.form.nama') }}</td>
                    <td>:</td>
                    <td><b>{{ $bank['atas_nama'] }}</td>
                </tr>
                <tr valign="top" height="15">
                    <td>{{ trans('saldo::saldos.form.no') }}</td>
                    <td>:</td>
                    <td>{{ $bank['rekening'] }}</td>
                </tr>       
                <tr valign="top" height="15">
                    <td>{{ trans('saldo::saldos.form.bank') }}</td>
                    <td>:</td>
                    <td>{{ $bank['namaBank'] }}</td>
                </tr>       
            </table>

            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('saldo::saldos.mail.withdraw created.detail saldo') }}
            </p>
        </div>
    </div>
@endsection