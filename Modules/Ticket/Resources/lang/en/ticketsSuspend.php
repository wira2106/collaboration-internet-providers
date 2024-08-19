<?php

return [
    'list resource' => 'List tickets suspend',
    'create resource' => 'Create tickets suspend',
    'edit resource' => 'Edit tickets suspend',
    'destroy resource' => 'Destroy tickets suspend',
    'title' => [
       
    ],
    'span status'=>[
        
    ],
    "label" => [
       
    ],
    'button' => [
       'lihat'=>"Lihat"
    ],
    'table' => [
       
    ],
    'form' => [
       
    ],
    'alert'=>[
        'close ticket'=>'Tutup ticket support suspend ?',
        'menunggu_approve'=>"Menunggu approve tanggal dari OSP",
        'approve'=>'Silahkan approve tanggal jika menyetujuinya',
        'pelanggan sudah terdaftar'=>"Pelanggan yang kamu pilih sudah terdaftar di ticket support suspend"
    ],
    'messages' => [
       
        'tanggal updated' => 'Waktu suspend pelanggan berhasil di update',
        'tanggal approve' => 'Waktu suspend pelanggan berhasil di approve',
     
        'suspend tidak muncul' => [
            'isp' => 'Suspend pelanggan tidak akan muncul karena ISP belum melakukan approve pada koneksi mati pelanggan',
            'osp' => 'Suspend pelanggan tidak akan muncul karena OSP belum melakukan approve pada koneksi mati pelanggan'
        ]
    ],
    'validation' => [
    ],
    'placeholder' => [
       
    ],
    'logs' => [
        'create ticket' => [
            'tipe' => 'Tambah Ticket Support',
            'description' => 'Penambahan Ticket Support gangguan pelanggan a/n :nama_pelanggan'
        ]
    ],
    'mail' => [
        'subject' => [
            'create ticket' => 'Ticket Support baru'
        ],
        'text' => [
            'approve'=> 'Tanggal ticket support suspend yang Anda ajukan disetujui, sebagai berikut : ',
            'new ticket' => 'Terdapat suspend ticket support pelanggan baru',
            'update ticket'=>'Terdapat pembahruan suspend ticket support pelanggan',
            'new messages' => 'Terdapat balasan dari suspend tiket support pelanggan',
            'terdapat balasan' => 'Terdapat balasan dari suspend tiket support pelanggan',
            'ticket closed' => "Perubahan status CLOSED pada suspend ticket support pelanggan",
        ]
    ]

];