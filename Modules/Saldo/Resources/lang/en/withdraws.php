<?php

return [
    'logs' => [
        'create' => [
            'tipe' => 'Withdraw',
            'description' => 'Melakukan Request Tarik Saldo'
        ],
        'edit' => [
            'tipe' => 'Konfirmasi Tarik Saldo',
            'description' => 'Melakukan Konfirmasi Tarik Saldo atas nama :user sebesar :data'
        ],
        'reject' => [
            'tipe' => 'Tolak Tarik Saldo',
            'description' => 'Melakukan Penolakan Tarik Saldo Saldo atas nama :user sebesar :data'
        ]
    ],
    'messages' => [
        'tarik saldo' => 'Yakin ingin tarik saldo?',
        'cancel withdraw' => 'Tolak tarik Saldo?',
        'confirm withdraw' => 'Konfirmasi tarik Saldo?',
    ],
    'button' => [
        'topup' => 'Topup',
        'withdraw' => 'Withdraw'
    ],
    'placeholder' => [
        'cari withdraw' => 'Cari withdraw...'
    ],

    'insert'=>[
        'deskripsi'=> 'Request penarikan Saldo Sebesar Rp. :nominal oleh :fullname',
        'deskripsi mutasi debit'=>'Penolakan tarik Saldo Sebesar Rp. :nominal oleh Admin karena :keterangan'
    ]

];