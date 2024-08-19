<?php

return [
    'list resource' => 'List tickets SLA',
    'create resource' => 'Create tickets SLA',
    'edit resource' => 'Edit tickets SLA',
    'destroy resource' => 'Destroy tickets SLA',
    'title' => [
       'ticket support' => 'Ticket Support SLA',
       'view ticket'=>'View Ticket SLA',
    ],
    'span status'=>[
        
    ],
    "label" => [
    ],
    'button' => [
    ],
    'form' => [
    ],
    'alert'=>[
        'close ticket'=>'Tutup ticket support SLA ?',
        'approve'=>'Silahkan approve tanggal jika menyetujuinya',
        'menunggu_approve'=>'Menunggu approve tanggal dari :type'
    ],
    'messages' => [
        'tanggal updated' => 'Waktu mati koneksi pelanggan berhasil di update',
        'tanggal approve' => 'Waktu mati koneksi pelanggan berhasil di approve',
       
        'sla tidak muncul' => [
            'isp' => 'SLA pelanggan tidak akan muncul karena ISP belum melakukan approve pada koneksi mati pelanggan',
            'osp' => 'SLA pelanggan tidak akan muncul karena OSP belum melakukan approve pada koneksi mati pelanggan'
        ]
    ],
    'mail' => [
        'subject' => [
            'create ticket' => 'Ticket Support baru'
        ],
        'text' => [
            'approve'=> 'Tanggal ticket support SLA yang Anda ajukan disetujui, sebagai berikut : ',
            'new ticket' => 'Terdapat ticket support gangguan koneksi pelanggan baru',
            'update ticket'=>'Terdapat pembahruan ticket support gangguan koneksi pelanggan',
            'new messages' => 'Terdapat balasan dari tiket support gangguan koneksi pelanggan',
            'terdapat balasan' => 'Terdapat balasan dari tiket support gangguan koneksi pelanggan',
            'ticket closed' => "Perubahan status CLOSED ticket support pada pelanggan",
        ]
    ]

];