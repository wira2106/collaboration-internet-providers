<?php

return [
    'list resource' => 'List tickets',
    'create resource' => 'Create tickets',
    'edit resource' => 'Edit tickets',
    'destroy resource' => 'Destroy tickets',
    'title' => [
        'tickets' => 'Ticket Support',
        'ticket sla'=>'SLA Pelanggan',
        'ticket suspend'=>'Suspend Pelanggan',
        'create ticket' => 'Create a ticket',
        'edit ticket' => 'Edit a ticket',
        'view ticket' => "View Ticket",
        'approve isp by' => 'Approve ISP by',
        'approve osp by' => 'Approve OSP by',
        'new message' => "Terdapat pesan baru"
    ],
    'span status'=>[
        'reply isp'=>'Reply ISP',
        'reply admin'=>'Reply Admin',
        'reply osp'=>'Reply OSP',
        'open'=>'Open',
        'closed'=>'Closed'
    ],
    "label" => [
        "ticket id" => "Ticket ID",
        "status" => "Status",
    ],
    'button' => [
        'create ticket' => 'Buat ticket baru',
        'close ticket' => "Close Ticket",
        'approve tanggal' => "Approve Tanggal"
    ],
    'table' => [
        'nama pelanggan' => "Pelanggan",
        'wilayah' => "Wilayah",
        'mati koneksi' => "Koneksi Mati",
        'status' => "Status",
        'isp' => "ISP"
    ],
    'form' => [
        'pelanggan' => "Pilih Pelanggan",
        'start mati' => 'Jam mulai mati koneksi',
        'end mati' => 'Jam akhir mati koneksi',
        'messages' => 'Messages',
        'upload image' => "Upload Image",
        'select date time'=>"Select date and time",
        'please input'=>'Please input',
        'select' => 'Select',
        
        'alasan'=>'Alasan suspend',
        'tgl suspend'=>'Tanggal suspend',
    ],
    'messages' => [
        'ticket created' => 'Berhasil menambahkan tiket support',
        'ticket closed' => 'Ticket support berhasil di Closed',
        'ticket deleted' => "Ticket support berhasil di hapus",
    ],
    'validation' => [
    ],
    'placeholder' => [
        "enter message" => "Masukkan pesan...",
        'alasan suspend'=>'Masukkan alasan suspend'
    ],
    'logs' => [
        'create ticket' => [
            'tipe' => 'Tambah Ticket Support',
            'description sla' => 'Penambahan Ticket Support gangguan pelanggan a/n :nama_pelanggan',
            'descripsi suspend'=>'Suspend pelanggan Ticket Support a/n :nama_pelanggan'
        ]
    ],
    'mail' => [
        'subject' => [
            'create ticket' => 'Ticket Support baru'
        ],
        'text' => [
            'name' => 'Nama',
            'address' => 'Alamat',
            'packet' => 'Paket Berlangganan',
            'click button' => 'Klik tombol berikut untuk melihat detail ticket support',
            'message' => 'Pesan',
        ]
    ]

];