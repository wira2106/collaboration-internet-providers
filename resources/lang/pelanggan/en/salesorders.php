<?php

return [
    'list resource' => 'List Sales Order',
    'create resource' => 'Create salesorders',
    'edit resource' => 'Edit salesorders',
    'destroy resource' => 'Destroy salesorders',
    'title' => [
        'salesorders' => 'Sales Order',
        'create salesorder' => 'Create a salesorder',
        'edit salesorder' => 'Edit a salesorder',
    ],
    'button' => [
        'create salesorder' => 'Create a salesorder',
        'simpan order' => 'Simpan Order',
        'update order' => 'Update Sales Order',
        'save' => 'Simpan Sales Order',
        'batal edit' => 'Batal Edit Sales Order',
        'edit sales order' => 'Edit Sales Order',
        'top up' => 'Top Up'
    ],
    'table' => [],
    'form' => [
        'site_id' => 'Site ID',
        'nama_pelanggan' => 'Nama Pelanggan',
        'telepon' => 'Telepon',
        'email' => 'Email',
        'jenis_id' => 'Jenis Identitas',
        'nomor_id' => 'Nomor Identitas',
        'foto_id' => 'Foto Identitas',
        'status_pemilik' => 'Status Kepemilikan',
        'alamat' => 'Alamat',
        'paket_berlangganan' => 'Paket Berlangganan',
        'biaya_mrc' => 'Biaya MRC',
        'biaya_otc' => 'Biaya OTC',
        'foto_rumah' => 'Foto Rumah',
        'jalur endpoint' => 'Jalur ke Endpoint'
    ],
    'placeholder' => [
        'nama_pelanggan' => 'Masukkan nama..',
        'telepon' => 'Masukkan telepon..',
        'email' => 'Masukkan email..',
        'nomor_id' => 'Nomor identitas..',
        'alamat' => 'Alamat..',
        'biaya_mrc' => 'Biaya MRC..',
        'biaya_otc' => 'Biaya OTC..'
    ],
    'messages' => [
        'data created' => 'Sales Order berhasil di tambahkan',
        'data updated' => 'Sales Order berhasil di update',
    ],
    'validation' => [],
    'mail' => [
        'insert' => [
            'subject' => 'Penambahan Sales Order',
            'body' => 'Terdapat penambahan sales order oleh ISP :isp_name di wilayah :wilayah_name :',
            'title' => 'Sales Order'
        ],
        'tambah pengajuan jadwal survey' => [
            'subject' => 'Pengajuan Jadwal Survey',
            'title' => 'Sales Order',
            'body' => 'Terdapat penambahan pengajuan jadwal survey pada pelanggan :pelanggan_name dengan pilihan jadwal sebagai berikut :',
            'pilih salah satu' => 'Mohon pilih salah satu jadwal diatas atau ajukan reschedule jika tidak ada jadwal yang sesuai.'
        ],
        'pengajuan jadwal survey disetujui' => [
            'subject' => 'Jadwal Survey Disetujui',
            'title' => 'Survey',
            'body' => 'Pengajuan jadwal survey untuk pelanggan :nama_pelanggan pada tanggal :tgl disetujui'

        ]
    ]

];
