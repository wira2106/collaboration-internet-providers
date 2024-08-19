@extends('mail::admin.mail.master')
@section('konten')
     <div style="font-size: 14px">
        <div style="text-align: center">
            <span style="display: inline-block;">
                <p style="padding-top:15px;text-align: left" >
                   Dear, {{ $company_name }}. 
                </p>
                <p style="text-align: left">
                    {{ trans('pelanggan::installations.mail.pengajuan jadwal instalasi.body', [
                    'pelanggan_name' => $pelanggan['nama_pelanggan']
                    ]) }}
                </p>
            </span>

        </div>
        <div style="display: flex;flex-direction:column;justify-content:center;align-items:center">

            <table border="1" style="border-collapse: collapse">
                <tr valign="top" height="15" >
                    <td style="padding: 5px">{{trans('pelanggan::pengajuanjadwal.instalasi.table header.no')}}</td>
                    <td width="200" style="padding: 5px">{{trans('pelanggan::pengajuanjadwal.instalasi.table header.tanggal')}}</td>
                    <td width="200" style="padding: 5px">{{trans('pelanggan::pengajuanjadwal.instalasi.table header.slot')}}</td>
                </tr>
                
                @foreach($jadwals as $key => $jadwal)
                    <tr valign="top" height="15" >
                        <td style="padding: 5px">{{ $key+1 }}</td>
                        <td style="padding: 5px">{{ date('d M Y', strtotime(date($jadwal['tgl_instalasi']))) }}</td>
                        <td style="padding: 5px">{{ $jadwal['slot_name'] }}</td>
                    </tr>
                @endforeach
                     
            </table>

            <p style="padding-top:15px; margin-left:0.2rem;text-align:center" >
            {{ trans('pelanggan::installations.mail.pengajuan jadwal instalasi.pilih salah satu') }}
        </p>
        </div>
        
    </div>
@endsection