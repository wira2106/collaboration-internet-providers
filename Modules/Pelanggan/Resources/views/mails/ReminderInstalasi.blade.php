@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="margin-top:7vw; margin-left:0.2rem">
            <h5>Dear, <br> {{ $company_name }},</h5>
        </div>
         <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem">{{$body}}</p>
            <table>
                <tr valign="top" height="15">
                    <td>{{trans('pelanggan::pelanggans.table.nama pelanggan')}}</td>
                    <td>:</td>
                    <td><b>{{ $nama_pelanggan }}</td>
                </tr>
                <tr valign="top" height="15">
                    <td>{{trans('pelanggan::pelanggans.tanggal')}}</td>
                    <td>:</td>
                    <td><b>{{ $tgl_instalasi }}</td>
                </tr>
                <tr valign="top" height="15">
                    <td>{{trans('pelanggan::pelanggans.alamat')}}</td>
                    <td>:</td>
                    <td>{{ $address }}</td>
                </tr>       
            </table>
        </div>
    </div>
@endsection
