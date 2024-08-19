<?php

return [
    "topup resource" => "Top up",
    "withdraw resource" => "Withdraw",
    "mutasi resource" => "Mutasi",
    'dibekukan' => 'Dibekukan : ',
    'sisa saldo' => 'Sisa saldo',
    'total' => 'Total',
    "title" => [
        'topup' => "Top up",
        'withdraw' => "Withdraw",
        'saldo' => "Saldo",
        'mutasi' => "Mutasi",
        'total saldo' => 'Total Saldo',
        'total tagihan' => 'MRC bulan depan'
    ],
    "table" => [
        'tanggal' => 'Tanggal Transaksi',
        'metode' => 'Metode',
        'jmlh' => 'Nominal',
        'jmlh withdraw' => 'Nominal',
        'status' => 'Status',
        'detail' => 'Detail',
        'action' => 'Action',

        'oleh' => 'Oleh',
        'jumlah' => 'Jumlah',
        'bukti' => 'Bukti Transaksi',
        'ket' => 'Keterangan',
        'tgl success' => 'Tanggal Success',
        'tgl transfer' => 'Tanggal Transfer',
    ],
    "modal" => [
        'modal topup' => 'Detail Topup',
        'modal withdraw' => 'Konfirmasi Withdraw',

        'topup saldo' => 'Topup Saldo',
        'tarik saldo' => 'Tarik Saldo',

        'status' => 'Status',
        'tgl transfer' => 'Tanggal Transfer',
        'dari'  => 'Dari :',
        'penerima' => ' Penerima : ',
        'bukti' => 'Bukti Transfer',

        'jmlh penarikan' => 'Jumlah Penarikan',
        'rek penerima' => 'Rekening Penerima',

        'success' => 'Transaksi Telah Berhasil Dilakunan'
    ],
    "button" => [
        'create topup' => 'Create Top up',
        'create withdraw' => 'Create Withdraw',
        'simpan' => 'Save',
        'cancel' => 'Close',
        'terima' => 'Terima',
        'tolak' => 'Tolak',
        'konfirmasi' => 'Konfirmasi',
        'batal' => 'Batal',
        'detail' => 'Detail'
    ],
    "form" => [
        'rek' => 'Rek. Penerima',
        'nominal' => 'Nominal',
        'nama' => 'Atas Nama',
        'rek pengirim' => 'Rek. Pengirim',
        'bukti' => 'Bukti Transfer',
        'tgl transfer' => 'Tanggal Transfer',
        'alasan' => 'Alasan',
        'tarik' => 'Tarik Semua Saldo',
        'tambah rekening' => 'Rekening baru',
        'no' => 'Nomor',
        'bank' => 'Bank'
    ],
    "messages" => [
        'success' => 'Data Berhasil Disimpan',
        'cancel' => 'Data Batal Disimpan',
        'error' => 'Terdapat Kesalahan',
        'enough saldo' => 'Saldo tidak mencukupi.',
        'confirmation-form' => 'Top up Saldo?',
        'file upload format' => "Avatar picture must be JPG or PNG format!",
        'file upload size' => 'Avatar picture size can not exceed 2MB!',
        'agree' => 'Saya menyetujui pengurangan saldo untuk melakukan :pembayaran',
    ],

    "validation" => [
        'required' => ':field Data Tidak Boleh Kosong',
        'withdraw saldo' => 'jumlah penarikan melebihi saldo',
    ],
    'log' => [
        'create topup' => 'Melakukan Request Topup Saldo ',
        'update topup' => 'Melakukan Konfirmasi Topup Saldo atasnama :user',
        'batal topup' => 'Melakukan Pembatalan Topup Saldo atasnama :user',
        'create withdraw' => 'Melakukan Request Tarik Saldo ',
        'update withdraw' => 'Melakukan Konfirmasi Tarik Saldo atas nama :user',
        'batal withdraw' => 'Melakukan Pembatalan Tarik Saldo Saldo atas nama :user',

        'create rek' => 'Menambah Rekening',
        'update rek' => 'Melakukan Perubahan Rekening',
        'delete rek' => 'Menghapus Rekening',

    ],
    'mail' => [
        'topup saldo created' => [
            'subject' => 'Topup saldo',
            'successfull created topup' => 'Halo admin openaccess, Perusahaan :company_name telah melakukan topup sebesar :total. Mohon melakukan verifikasi pembayaran',
            'detail saldo' => 'Klik link berikut untuk melihat detail : ',
            'title' => 'Topup saldo'
        ],
        'topup is confirmation' => [
            'confirmed subject' => 'Topup saldo sukses dilakukan',
            'rejected subject' => 'Topup saldo gagal dilakukan',
            'confirmed body' => 'Halo admin :company_name, Topup sebesar :total telah berhasil.',
            'rejected body' => 'Halo admin :company_name, Topup sebesar :total gagal dikarenakan alasan : :alasan.',
            'detail saldo' => 'Silahkan cek saldo dengan klik link dibawah : ',
            'title' => 'Topup saldo'
        ],
        'withdraw created' => [
            'subject' => 'Tarik saldo',
            'successfull created withdraw' => 'Halo admin openaccess, Perusahaan :company_name telah melakukan withdraw sebesar :total. Mohon melakukan verifikasi pembayaran',
            'detail saldo' => 'Klik link berikut untuk melihat detail : ',
            'title' => 'Withdraw saldo'
        ],
        'withdraw is confirmation' => [
            'confirmed subject' => 'Tarik saldo sukses dilakukan',
            'rejected subject' => 'Tarik saldo gagal dilakukan',
            'confirmed body' => 'Halo admin :company_name, Tarik saldo sebesar :total telah berhasil.',
            'rejected body' => 'Halo admin :company_name, Tarik saldo sebesar :total gagal dikarenakan alasan : :alasan.',
            'detail saldo' => 'Silahkan cek saldo dengan klik link dibawah : ',
            'title' => 'Tarik saldo'
        ],

    ]

];
