<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="
     margin: 0;
    padding: 0;
     background: #FFFFF;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    overflow: hidden;
    transition: 1s ease-in;
    ">
    <div style="
        width: 600px;
        height: 500px;
        display: flex;
        flex-direction: column;
        font-family: sans-serif;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.9);
        white-space: nowrap;
    ">
        <div style="
            height: 21%;
            background: #007BFF;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            padding-left: 15px;
            padding-right: 15px;
            
        ">
            <img src="/openaccess.png" style="padding-left:25%; width:221px; height:48px;">
        </div>

        <div style="
            height: 75%;
            background: #FFFFF;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
             font-size: 0.8em;
        ">
            <div style="padding:18px;">
                <p style="padding-left:65%; padding-bottom:px; padding-top:2px;">{{$mail['tgl_email']}}</p>

                <h3>Dear {{$mail['osp']}},</h3>
                <p style="padding-top:15px;">Terdapat pengajuan baru pada wilayah Sukawati dengan detail sebagai berikut :</p>
                <p>
                <table style="padding-top:10px;">
                    <tr height="25">
                        <td>Wilayah</td>
                        <td>:</td>
                        <td>{{$mail['wilayah']}}</td>
                    </tr>
                    <tr height="25">
                        <td>Pemilik</td>
                        <td>:</td>
                        <td> {{$mail['osp']}}</td>
                    </tr>
                    <tr height="15"></tr>
                    <tr height="25">
                        <td>Diajukan oleh</td>
                        <td width="">:</td>
                        <td> {{$mail['isp']}}</td>
                    </tr>
                    <tr height="25">
                        <td>Alasan Pengajuan</td>
                        <td>:</td>
                        <td>{{$mail['alasan']}}</td>
                    </tr>
                </table>
                </p>

                <p style="padding-top:10px;">Harap untuk melakukan tindakan konfirmasi pengajuan pada halaman berikut</p>
                <p style="padding-top:25px; padding-left:30%;">
                    <button style="
                        border: none;
                        color: white;
                        padding: 15px 32px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;
                        margin: 4px 2px;
                        cursor: pointer;
                        background-color: #008CBA;
                    ">Lihat Selengkapnya</button>
                </p>
            </div>
            <a href=" #"><i style="font-size: 0.8em; font-size:24px"></i></a>
        </div>

    </div>
</body>

</html>