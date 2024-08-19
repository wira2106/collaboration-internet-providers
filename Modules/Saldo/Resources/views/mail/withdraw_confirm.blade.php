@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> {{ $email_body }}</p>
           

            @if($status != 'cancel') 
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
                        <td>{{ $nama_bank }}</td>
                    </tr>       
                </table>
            @endif

            <p style="padding-top:10px; margin-left:0.2rem">
                {{ trans('saldo::saldos.mail.withdraw is confirmation.detail saldo') }}
            </p>
        </div>
    </div>
@endsection