@extends('mail::admin.mail.master')
@section('konten')

    <div style="font-size: 14px">
        <p style="padding-top:15px; margin-left:0.2rem">
        {{ trans('pelanggan::salesorders.mail.insert.body', [
            'isp_name' => $data['isp_name'],
            'wilayah_name' => $data['wilayah_name']
        ]) }}
        </p>
        <table>
            <tr valign="top" height="15" >
                <td>{{trans('pelanggan::salesorders.form.nama_pelanggan')}}</td>
                <td>:</td>
                <td><b>{{ $nama_pelanggan }}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{trans('pelanggan::salesorders.form.telepon')}}</td>
                <td>:</td>
                <td><b>{{ $telepon }}</td>
            </tr>       
            <tr valign="top" height="15">
                <td>{{trans('pelanggan::salesorders.form.alamat')}}</td>
                <td>:</td>
                <td><b>{{ $address }}</td>
            </tr>           
            <tr valign="top" height="15">
                <td>{{trans('pelanggan::salesorders.form.paket_berlangganan')}}</td>
                <td>:</td>
                <td><b>{{ $nama_paket }}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{trans('pelanggan::salesorders.form.biaya_mrc')}}</td>
                <td>:</td>
                <td><b>{{ $harga_paket }}</td>
            </tr>       
            <tr valign="top" height="15">
                <td>{{trans('pelanggan::salesorders.form.biaya_otc')}}</td>
                <td>:</td>
                <td><b>{{ $biaya_otc }}</td>
            </tr>       
        </table>
        
    </div>
    

@endsection