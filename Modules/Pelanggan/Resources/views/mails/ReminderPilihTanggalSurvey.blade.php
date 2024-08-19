@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem"> {{ $body }} </p>
            <table padding="padding-bottom:20px">
                <tr valign="top" height="15">
                    <td>{{trans('pelanggan::pelanggans.table.nama pelanggan')}}</td>
                    <td>:</td>
                    <td><b>{{ $nama_pelanggan }}</td>
                </tr>
                <tr valign="top" height="15">
                    <td>{{trans('pelanggan::pelanggans.tanggal request')}}</td>
                    <td>:</td>
                    <td><b>{{ $tanggalRequest }}</td>
                </tr>
                <tr valign="top" height="15">
                    <td>{{trans('pelanggan::pelanggans.table.keterangan')}}</td>
                    <td>:</td>
                    <td>{{ $keterangan }}</td>
                </tr>       
            </table>
        </div>
    </div>
@endsection
