@extends('mail::admin.mail.master')
@section('konten')
    <div>
        <div style="margin-top:7vw; margin-left:0.2rem">
            <h5>Dear, <br> {{ $data['data']['company']['name'] }},</h5>
        </div>
        <div style="font-size: 14px">
            <p style="padding-top:15px; margin-left:0.2rem">{{trans('pelanggan::activations.mail.text.approve ditolak')}} :</p>
            <table>
                <tr valign="top" height="15">
                    <td>{{trans('pelanggan::activations.mail.item.nama')}}</td>
                    <td>:</td>
                    <td><b>{{ $data['data']['pelanggan']['nama_pelanggan'] }}</td>
                </tr>
                <tr valign="top" height="15">
                    <td>{{trans('pelanggan::activations.mail.item.paket')}}</td>
                    <td>:</td>
                    <td>{{ $data['data']['pelanggan']['paketBerlangganan']['nama_paket']."( Rp. ".number_format($data['data']['pelanggan']['biaya_mrc'],0,',','.')." )" }}</td>
                </tr>  
                <tr valign="top" height="15">
                    <td>{{trans('pelanggan::activations.mail.item.alasan')}}</td>
                    <td>:</td>
                    <td>{{ $data['data']['aktivasi']->keterangan_unapprove}}</td>
                </tr>       
            </table>
            <p style="padding-top:10px; margin-left:0.2rem">
            {{trans('pelanggan::activations.mail.text.aktivasi kembali')}}.
            </p>
        </div>
    </div>
@endsection
