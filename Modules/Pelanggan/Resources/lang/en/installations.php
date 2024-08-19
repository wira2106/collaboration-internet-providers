<?php

return [
    'list resource' => 'List installations',
    'create resource' => 'Create installations',
    'edit resource' => 'Edit installations',
    'destroy resource' => 'Destroy installations',
    'title' => [
        'installations' => 'Installation',
        'create installation' => 'Create a installation',
        'edit installation' => 'Edit a installation',
        'tambah teknisi' => 'Teknisi baru',
        'tambah noc' => 'NOC baru',
        'enter teknisi' => 'Masukkan nama teknisi',
        'pengajuan jadwal'=>'Pengajuan Jadwal Instalasi'
    ],
    'button' => [
        'create installation' => 'Create a installation',
    ],
    'table' => [],
    'form' => [
        'checkbox' => 'Instalasi sudah selesai'
    ],
    'messages' => [
        'tidak ada perangkat' => 'Tidak terdapat perangkat yang di tambahkan',
        'tidak ada alat' => 'Tidak terdapat alat yang di tambahkan',
        'tidak ada dokumentasi' => 'Tidak terdapat dokumentasi yang di tambahkan',
    ],
    'validation' => [],
    'placeholder' => [
        'jumlah' => 'Jumlah',
        'keterangan' => 'Masukkan keterangan'
    ],
    'mail' => [
        'insert' => [
            'pengajuan jadwal' => [
                'subject' => 'Pengajuan Jadwal Instalasi',
                'body' => 'Terdapat pengajuan jadwal baru pada tahap instalasi oleh :send_type : :send_name',
                'title' => 'Instalasi'
            ]
        ],
        'select' => [
            'pengajuan jadwal' => [
                'subject' => 'Pengajuan Jadwal Instalasi',
                'body' => 'Jadwal instalasi pelanggan :pelanggan_name pada tanggal :tanggal jam :jam disetujui oleh :send_name',
                'title' => 'Instalasi'
            ]
        ],
        'pengajuan jadwal instalasi' => [
            'subject' => 'Pengajuan Jadwal Instalasi',
            'title' => 'Survey',
            'body' => 'Terdapat penambahan pengajuan jadwal instalasi pada pelanggan :pelanggan_name dengan pilihan jadwal sebagai berikut :',
            'pilih salah satu' => 'Mohon pilih salah satu jadwal diatas atau ajukan reschedule jika tidak ada jadwal yang sesuai.'
        ]
    ]
];