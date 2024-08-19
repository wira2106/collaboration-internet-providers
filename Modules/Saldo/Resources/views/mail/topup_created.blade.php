@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> {{ trans('saldo::saldos.mail.topup saldo created.successfull created topup', ['total' => $data['total'], 'company_name' => $data['company_name']]) }}</p>
           
            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('saldo::saldos.form.rek pengirim') }}
            </p>
            <table>
                <tr valign="top" height="15">
                    <td>{{ trans('saldo::saldos.form.nama') }}</td>
                    <td>:</td>
                    <td><b>{{ $data['data']['atas_nama'] }}</td>
                </tr>
                <tr valign="top" height="15">
                    <td>{{ trans('saldo::saldos.form.no') }}</td>
                    <td>:</td>
                    <td>{{ $data['data']['rekening_pengirim'] }}</td>
                </tr>       
                <tr valign="top" height="15">
                    <td>{{ trans('saldo::saldos.form.bank') }}</td>
                    <td>:</td>
                    <td>{{ $bank['namaBank'] }}</td>
                </tr>       
            </table>

            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('saldo::saldos.form.rek') }}
            </p>
            <table>
                <tr valign="top" height="15">
                    <td>{{ trans('saldo::saldos.form.nama') }}</td>
                    <td>:</td>
                    <td><b>{{ $penerima['atas_nama'] }}</td>
                </tr>
                <tr valign="top" height="15">
                    <td>{{ trans('saldo::saldos.form.no') }}</td>
                    <td>:</td>
                    <td>{{ $penerima['rekening'] }}</td>
                </tr>       
                <tr valign="top" height="15">
                    <td>{{ trans('saldo::saldos.form.bank') }}</td>
                    <td>:</td>
                    <td>{{ $bank_penerima }}</td>
                </tr>       
            </table>

            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('saldo::saldos.mail.topup saldo created.detail saldo') }}
            </p>

        </div>
    </div>
@endsection