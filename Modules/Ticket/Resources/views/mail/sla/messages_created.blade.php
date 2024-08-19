@extends('mail::Admin.mail.master')
@section('konten')
<div>
    <div style="margin-top:7vw; margin-left:0.2rem">
        <h5>Dear, <br> {{$data['company']['name']}},</h5>
    </div>
    <div style="font-size: 14px">
        <p style="padding-top:15px; margin-left:0.2rem"> {{trans('ticket::ticketsSla.mail.text.terdapat balasan')}} :
        </p>
        <table>
            <tr valign="top" height="15">
                <td>{{trans('ticket::tickets.mail.text.name')}}</td>
                <td>:</td>
                <td>{{$data['pelanggan']['nama_pelanggan']}}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{trans('ticket::tickets.mail.text.address')}}</td>
                <td>:</td>
                <td>{{$data['pelanggan']['address']}}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{trans('ticket::tickets.mail.text.packet')}}</td>
                <td>:</td>
                <td>{{$data['pelanggan']['paket_berlangganan']}}</td>
            </tr>
            <tr valign="top" height="15">
                <td>{{trans('ticket::tickets.mail.text.message')}}</td>
                <td>:</td>
                <td>{{$data['messages']['message']}}</td>
            </tr>
        </table>
        <p style="padding-top:10px; margin-left:0.2rem">
            {{trans('ticket::tickets.mail.text.click button')}}
        </p>
    </div>
</div>
@endsection