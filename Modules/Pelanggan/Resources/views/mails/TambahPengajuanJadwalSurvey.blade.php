@extends('mail::admin.mail.master')
@section('konten')

    <div style="font-size: 14px">
        <div style="text-align: center">
            <span style="display: inline-block;">
                <p style="padding-top:15px;text-align: left" >
                   Dear, {{ $company_name }}. 
                </p>
                <p style="text-align: left">
                    {{ trans('pelanggan::salesorders.mail.tambah pengajuan jadwal survey.body', [
                    'pelanggan_name' => $pelanggan['nama_pelanggan']
                    ]) }}
                </p>
            </span>

        </div>
        <div style="display: flex;flex-direction:column;justify-content:center;align-items:center">

            <table border="1" style="border-collapse: collapse">
                <tr valign="top" height="15" >
                    <td style="padding: 5px">{{trans('pelanggan::pengajuanjadwal.table survey.no')}}</td>
                    <td width="200" style="padding: 5px">{{trans('pelanggan::pengajuanjadwal.table survey.tgl')}}</td>
                    <td width="200" style="padding: 5px">{{trans('pelanggan::pengajuanjadwal.table survey.waktu')}}</td>
                </tr>
                
                @foreach($jadwals as $key => $jadwal)
                    <tr valign="top" height="15" >
                        <td style="padding: 5px">{{ $key+1 }}</td>
                        <td style="padding: 5px">{{ date('d M Y', strtotime(date($jadwal['tgl_survey']))) }}</td>
                        <td style="padding: 5px">{{ $jadwal['jam_survey'] }}</td>
                    </tr>
                @endforeach
                     
            </table>

            <p style="padding-top:15px; margin-left:0.2rem;text-align:center" >
            {{ trans('pelanggan::salesorders.mail.tambah pengajuan jadwal survey.pilih salah satu') }}
        </p>
        </div>
        
    </div>
    

@endsection