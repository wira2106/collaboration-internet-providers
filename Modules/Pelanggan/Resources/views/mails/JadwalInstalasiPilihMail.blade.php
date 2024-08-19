@extends('mail::admin.mail.master')
@section('konten')
    <div style="margin-top:7vw; margin-left:0.2rem">
        <h5>Dear, <br> {{ $data['company_name'] }},</h5>
    </div>
    <div style="font-size: 14px">
        <p style="padding-top:15px; margin-left:0.2rem">{{ trans('pelanggan::installations.mail.select.pengajuan jadwal.body', [
                'send_name' => $data['name'],
            ]) }} 
        </p>
        <table>
            <tr valign="top" height="15">
                <td>{{trans('pelanggan::pelanggans.table.nama pelanggan')}}</td>
                <td>:</td>
                <td><b>{{ $data['pelanggan']['nama_pelanggan'] }}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{trans('pelanggan::pelanggans.table.paket')}}</td>
                <td>:</td>
                <td>{{ $data['pelanggan']['paketBerlangganan']['nama_paket']."( Rp. ".number_format($data['pelanggan']['biaya_mrc'],0,',','.')." )" }}</td>
            </tr>       
            <tr valign="top" height="15">
                <td>{{trans('pelanggan::pelanggans.alamat')}}</td>
                <td>:</td>
                <td>{{ $data['pelanggan']['address'] }}</td>
            </tr>    
            <tr valign="top" height="15">
                <td>{{trans('pelanggan::pelanggans.tanggal')}}</td>
                <td>:</td>
                <td>{{ $data['tanggal'] }}</td>
            </tr>   
            <tr valign="top" height="15">
                <td>{{trans('company::slot_instalasi.title.slot_instalasi')}}</td>
                <td>:</td>
                <td>{{ $data['slot'] }}</td>
            </tr>   
        </table>
        <p style="padding-top:10px; margin-left:0.2rem">
        </p>
    </div>
@endsection

