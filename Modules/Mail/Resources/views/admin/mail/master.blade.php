<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>


<body style="font-family: sans-serif; margin:0 0 4rem 0">
    <div style="height: 2rem; padding:1% 1rem 0 1rem; color:grey; font-size:10px;">
        <div style="float: right;">
            <div style="text-align: right;">
                <b> {{date('d F Y')}}</b>
                <br>
                <b>{{date('g:i a')}}</b>
            </div>
        </div>
        <div style="float: left;">
            <p></p>
            <div style="text-align:left">
                <b>{{isset($title) ? $title : ''}}</b>
            </div>
        </div>
    </div>
    <div style="
            height: 10rem;
            background: #007BFF;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
        ">
        <center>
            <img src="{{url('/openaccess.png')}}" alt="open access" style="
                height: 3.5rem;
                width: auto;
            ">
        </center>
    </div>

    <div style="
                margin:auto 13vw;
                position: relative;">
        @yield('konten')

        <a href="{{isset($url) ? $url : '#'}}" style="
                    border: none;
                    color: white;
                    padding: 15px 0;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16vin;
                    margin: 4px 2px;
                    width:100%;
                    cursor: pointer;
                    background: #007BFF;
                ">
            {{ trans('mail::mails.mail.selengkapnya') }}
        </a>
        <p style="font-size:13px; margin-left:0.2rem; margin-top:1.5rem">
            {{ trans('mail::mails.mail.klik link') }}
            <br>
            <br>
            @if(isset($url))
            <a href="{{ $url }}">
                <i>
                    {{ $url }}
                </i>
            </a>
            @endif
        </p>
    </div>

</body>

</html>