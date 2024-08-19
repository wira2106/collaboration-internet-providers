<?php

return [
    'list resource' => 'List Activations',
    'create resource' => 'Create Activations',
    'edit resource' => 'Edit Activations',
    'destroy resource' => 'Destroy Activations',
    'title' => [
        'activation' => 'Aktivasi',
        'form' => 'Form Aktivasi',
        'approve' => "Approve",
        'unapprove' => "Tidak approve",
        'alasan unapprove' => 'Alasan tidak approve',
        'aktivasi unapprove' => 'Aktivasi tidak di approve',
        'menunggu approve' => 'Menunggu approve dari ISP',
        'belum' => 'Belum Di tentukan',
        'detail aktivasi' => 'Detail Aktivasi',
        'rating' => 'Rating :field',
        'estimasi pengembalian' => 'Komisi & Pengembalian instalasi pelanggan',
    ],
    'table' => [
        'kelebihan pembayaran' => 'Kelebihan pembayaran',
        'pengembalian prorata' => 'Pengembalian Prorata MRC',
        'komisi ke osp' => 'Komisi ke OSP',
        'admin fee' => 'Komisi Openaccess',
    ],
    'form' => [
        'tgl aktivasi' => 'Tanggal Aktivasi',
        'status' => [
            'title' => 'Status',
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'cancel' => 'Cancel'
        ],
        'noc' => 'NOC bertugas',
        'keterangan' => 'keterangan',
        'enter noc' => 'Masukkan nama NOC',
        'checkbox' => 'Aktivasi sudah selesai',
        'persetujuan aktivasi' => 'Saya menyetujui penyelesaian proses aktivasi pelanggan'
    ],
    'placeholder' => [
        'keterangan' => 'masukkan keterangan...'
    ],
    'messages' => [
        'empty noc' => 'Harap masukkan NOC yang bertugas',
        'empty keterangan' => 'Harap masukkan keterangan',
        'create activation' => "Sukses menambah data aktivasi",
        'update activation' => "Sukses update data aktivasi",
        "alasan unapprove" => "Masukkan alasan",
        'informasi pengembalian mrc' => 'Biaya MRC akan disetorkan ke OSP pada saat tanggal settlement berlangsung'
    ],
    'modal' => [
        'approve' => [
            'title' => 'Approve Aktivasi'
        ]
    ],
    'log' => [
        'update aktivasi' => [
            'title' => 'Update Aktivasi Pelanggan',
            'desc' => 'Update data aktivasi pelanggan a/n :nama_pelanggan',
            'unapprove' => 'Update data aktivasi menjadi tidak Approve pelanggan a/n :nama_pelanggan'
        ]
    ],
    'mail' => [
        'title' => [
            'aktivasi pelanggan' => 'Aktivasi Pelanggan'
        ],
        'item' => [
            'nama' => 'Nama Pelanggan',
            'paket' => 'Paket Berlangganan',
            'alasan' => 'Alasan tidak approve'
        ],
        'text' => [
            'status selesai' => 'Status selesai aktivasi pada pelanggan',
            'harap approve' => 'Harap untuk melakukan proses Approve aktivasi melalui tombol di bawah berikut',
            'approve berhasil' => 'Proses approve aktivasi telah dilakukan pada pelanggan',
            'approve ditolak' => 'Proses approve ditolak pada pelanggan',
            'aktivasi kembali' => 'Harap untuk melakukan proses Aktivasi kembali melalui tombol di bawah berikut',
            'data pelanggan' => 'Data pelanggan dapat dilihat pada link berikut'
        ]
    ],
    'tooltip' => [
        'informasi pengembalian' => 'Pengembalian akan dikembalikan saat akhir bulan'
    ],
    'button' => [
        'simpan aktivasi' => 'Simpan Aktivasi',
        'ubah aktivasi' => 'Edit Aktivasi',
        'batal edit aktivasi' => 'Batal Edit Aktivasi'
    ]
];
