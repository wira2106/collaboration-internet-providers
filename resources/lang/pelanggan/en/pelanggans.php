<?php

return [
    'list resource' => 'List pelanggans',
    'create resource' => 'Create pelanggans',
    'edit resource' => 'Edit pelanggans',
    'destroy resource' => 'Destroy pelanggans',
    'title' => [
        'pelanggans' => 'Pelanggan',
        'create pelanggan' => 'Create a pelanggan',
        'edit pelanggan' => 'Edit a pelanggan',
        'alasan suspend' => 'Alasan Suspend',
        'alasan cancel' => 'Alasan Cancel',
        'suspend pelanggan' => 'Suspend Pelanggan',
        'lihat riwayat' => 'Lihat riwayat Instalasi Pelanggan'
    ],
    'button' => [
        'create pelanggan' => 'Create a pelanggan',
        'create order' => 'Create Order'
    ],
    'tab' => [
        'list pelanggan' => 'List Pelanggan',
        'cancel' => 'Cancel',
        'sales order' => 'Sales Order',
        'survey' => 'Survey',
        'instalasi' => 'Instalasi',
        'aktivasi' => 'Aktivasi',

        'detail' => 'Detail Pelanggan',
        'sla' => 'History SLA Pelanggan',
    ],
    'table' => [
        'no' => 'No',
        'pelanggan' => 'Pelanggan',
        'tlp mail' => 'Tlp/Email',
        'paket' => 'Paket',
        'penggunaan' => 'Penggunaan',
        'actions' => 'Actions',
        'site id' => 'Site ID',

        'nama pelanggan' => 'Nama Pelanggan',
        'nama paket' => 'Nama Paket',
        'sla' => 'SLA Paket',
        'biaya' => 'Harga Paket',
        'persen toleransi' => '% Toleransi',
        'persen total' => '% Koneksi Mati',
        'total koneksi mati' => 'Koneksi Mati',
        'total pengurangan tagihan' => '(-)Tagihan',
        'bulan' => 'Bulan',
        'keterangan' => 'Keterangan'

    ],
    'tooltip' => [
        'penggunaan' => 'Lihat history koneksi mati',
        'edit' => 'Edit',
        'suspend' => 'Suspend',
        'delete' => 'Hapus Pelanggan',
        'alasan' => 'Tampilkan alasan cancel'
    ],
    'tanggal' => 'Tanggal',
    'tanggal request' => 'Tanggal request pelanggan',
    'alamat' => 'Alamat',
    'form' => [
        'tgl suspend' => 'Tanggal Suspend',
        'alasan suspend' => "Masukkan alasan suspend"
    ],
    'modal' => [
        'modal sla' => 'List Koneksi Mati Pelanggan',

        //table history sla

        'start' => 'Awal Gangguan',
        'end' => 'Selesai Gangguan',
        'paket' => 'Nama Paket',
        'sla' => 'SLA Paket',
        'wajar' => 'Pemakaian Wajar',
        'koneksi mati' => 'Koneksi Mati',
        'total' => 'Total Koneksi Mati',
    ],
    'messages' => [
        'success suspend' => 'Berhasil suspend pelanggan',
        'active suspend' => 'Pelanggan berhasil di aktifkan',
        'aktifkan pelanggan' => "Aktifkan Pelanggan?",
        'suspend pelanggan' => "Suspend Pelanggan?",
        'data updated' => 'Data pelanggan berhasil di update',
        'data perubahan harga' => 'Data Perubahan Harga Berhasil Disimpan',
        'rejected' => 'Rejected successfully',
        'accepted' => 'Accepted successfully',
        'cukup' => 'Saldo Mencukupi',
        '404' => 'Pelanggan tidak ditemukan',
        'cancel pelanggan' => 'Pelanggan :nama_pelanggan berhasil di cancel',
        'fee cancel survey osp' => 'Fee Pembatalan Order Pelanggan :nama_pelanggan',
        'fee cancel survey isp' => 'Pengembalian Saldo Pembatalan Order Pelanggan :nama_pelanggan',
        'fee cancel survey openaccess' => 'Fee Pembatalan Order Pelanggan :nama_pelanggan',
        'refund cancel survey openaccess' => 'Pengembalian Dana Pembatalan Order Pelanggan :nama_pelanggan',
        'saldo biaya pelanggan not found' => 'Oops! Something went wrong #AKTV02',
        'pengembalian biaya aktivasi pelanggan' => 'Pengembalian dana aktivasi pelanggan :nama_pelanggan',
        'biaya instalasi' => 'Biaya instalasi untuk pelanggan :nama_pelanggan',
        'pengembalian kelebihan tagihan' => 'Pengembalian kelebihan tagihan pelanggan :nama_pelanggan',
        'aktivasi sukses' => 'Pembagian komisi pelanggan atas nama :nama_pelanggan',
        'prorata' => 'Prorata pelanggan a/n :nama_pelanggan',
        'success delete pelanggan' => 'Pelanggan :nama_pelanggan berhasil dihapus'
    ],
    'ubah paket' => 'Ubah Paket?',
    'validation' => [],
    'status' => [
        'so' => 'Sales Order',
        'survey' => 'Survey',
        'instalasi' => 'Instalasi',
        'aktivasi' => 'Aktivasi',
        'aktif' => 'Aktif',
        'new' => 'Baru',
        'active' => 'Aktif',
        'cancel' => 'Cancel',
        'penentuan jadwal survey' => 'Penentuan Jadwal Survey',
        'request perubahan harga' => 'Request Perubahan harga',
        'penentuan jadwal instalasi' => 'Penentuan Jadwal Instalasi',
        'menunggu approve' => 'Menunggu Approve'
    ],
    'placeholder' => [
        'cari pelanggan' => 'Cari pelanggan..'
    ],
    'saldo mengendap' => [
        'total biaya' => 'Total Biaya',
        'saldo mengendap' => 'Saldo Mengendap',
        'kekurangan biaya' => 'kekurangan biaya',
        'kelebihan biaya' => 'kelebihan biaya'
    ],
    'input nama teknisi' => 'Masukkan nama teknisi',
    'mails' => [
        'reminder pilih tanggal survey' => [
            'subject' => 'Reminder request tanggal survey',
            'body' => 'Terdapat request tanggal survey dari pelanggan :',
            'title' => 'Pelanggan'
        ],
        'reminder pilih tanggal instalasi' => [
            'subject' => 'Reminder request tanggal instalasi',
            'body' => 'Terdapat request tanggal instalasi dari pelanggan :',
            'title' => 'Pelanggan'
        ],
        'reminder survey' => [
            'subject' => 'Reminder survey',
            'body' => 'Diingatkan kepada tim untuk melakukan survey pemasangan internet pelanggan pada:',
            'title' => 'Pelanggan'
        ],
        'reminder instalasi' => [
            'subject' => 'Reminder instalasi',
            'body' => 'Diingatkan kepada tim untuk melakukan instalasi pemasangan internet pelanggan pada :',
            'title' => 'Pelanggan'
        ]
    ],
];
