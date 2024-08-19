<?php

return [
    'list resource' => 'List surveys',
    'create resource' => 'Create surveys',
    'edit resource' => 'Edit surveys',
    'destroy resource' => 'Destroy surveys',
    'title' => [
        'surveys' => 'Survey',
        'hasil' => 'Hasil Survey',
        'survey' => 'Data Survey',
        'create survey' => 'Create a survey',
        'edit survey' => 'Edit a survey',
        'add teknisi' => 'Tambah Teknisi'
    ],
    'button' => [
        'create survey' => 'Create a survey',
        'save' => 'Simpan hasil Survey',
        'batal'=>'Batal edit hasil survey',
        'cancel' => 'Cancel',
        'tambah barang' => 'Tambah Barang',
        'tambah perangkat' => 'Tambah Perangkat',
    ],
    'tooltip' => [
        'hasil' => 'Simpan Data Survey',
        'edit' => 'Edit Data Hasil Survey',
        'batal edit' => 'Batal Edit Data Hasil Survey',
        'tambah pengajuan survey' => 'Menambah Jadwal Pengajuan Survey',
        'tambah pengajuan instalasi' => 'Menambah Jadwal Pengajuan Instalasi',
        'simpan pengajuan survey' => 'Menyimpan Jadwal Pengajuan Survey',
        'simpan pengajuan instalasi' => 'Menyimpan Jadwal Pengajuan Instalasi',
        'tambah pengajuan survey' => 'Tambah Jadwal Pengajuan Survey',
        'hapus pengajuan survey' => 'Hapus Pilih Jadwal Pengajuan Survey',
        'tambah pengajuan instalasi' => 'Tambah Jadwal Pengajuan Instalasi',
        'hapus pengajuan instalasi' => 'Hapus Pilih Jadwal Pengajuan Instalasi',
        'pilih pengajuan survey' => 'Pilih Jadwal Pengajuan Survey',
        'batal pengajuan survey' => 'Batal Pilih Jadwal Pengajuan Survey',
        'pilih pengajuan instalasi' => 'Pilih Jadwal Pengajuan Instalasi',
        'batal pengajuan instalasi' => 'Batal Pilih Jadwal Pengajuan Instalasi',
        'ajukan survey' => 'Membuat Jadwal Reschedule Survey',
        'ajukan instalasi' => 'Membuat Jadwal Reschedule Instalasi',
        'batal ajukan survey' => 'Membatalkan Jadwal Reschedule Survey',
        'batal ajukan instalasi' => 'Membatalkan Jadwal Reschedule Survey',
        'tambah input perangkat' => 'Menambah Perangkat Tambahan',
        'hapus input perangkat' => 'Menghapus Perangkat Tambahan',
        'upload signal' => 'Mengunggah Foto Signal Akses Point',
        'upload jalur' => 'Mengunggah Foto Jalur Kabel',
        'simpan teknisi' => 'Simpan Data Teknisi',
        'batal teknisi' => 'Batal Simpan Data Teknisi',
        'history' => 'History Pengajuan Jadwal Survey',
        'edit jadwal' => 'Edit Jadwal',
        'batal edit jadwal' => 'Batal Edit Jadwal',
    ],
    'table' => [],
    'placeholder' => [
        'teknisi' => 'Petugas Teknisi',
        'satuan' => 'meter/lantai',
        'status' => 'Status Survey',
        'enter keterangan' => 'Masukkan keterangan...',
        'pilih slot instalasi'=>'Pilih slot instalasi'
    ],
    'form' => [
        'teknisi' => 'Teknisi yang bertugas',
        'tinggi' => 'Tinggi Bangunan',
        'signal' => 'Signal Akses Point',
        'kabel' => 'Jalur Kabel',
        'status' => 'Status',
        'barang' => 'Barang Tambahan',
        'perangkat' => 'Perangkat Yang Akan digunakan',
        'keterangan' => "Keterangan",
        'namaBarang' => 'Nama Barang',
        'namaPerangkat' => 'Nama Perangkat',
        'qty' => 'Qty',
        'harga per pcs' => 'Harga / Pcs',
        'harga' => 'Harga',
        'jenis_perangkat' => 'Jenis Perangkat',
        'tgl' => 'Tanggal',
        'tgl survey' => 'Tanggal Survey',
    ],
    'messages' => [
        'survey selesai'=>'Survey sudah selesai',
        'update teknisi' => 'Simpan Perubahan Data Teknisi?',
        'simpan' => 'Simpan hasil survey',
        'simpan teknisi' => "Data Teknisi Berhasil di Simpan",
        'teknisi kosong' => "Data Teknisi tidak boleh kosong",
        'format' => "File Harus Berupa JPG / PNG",
        'ukuran' => "Ukuran File Tidak Boleh lebih dari !MB",
        'pengajuan pengubahan jadwal sukses' => "Pengajuan Pengubahan Jadwal Sukses"
    ],
    'validation' => [
        'required' => "Data :field Tidak Boleh Kosong"
    ],
    'modal' => [
        'title pengajuan' => 'Jadwal Survey',
        'tgl' => 'Permintaan Jadwal Survey',
        'cari' => 'Cari Teknisi ...',
        'pilih' => 'Pilih Teknisi Yang Bertugas',
    ],
    'mails' => [
        'pengajuan perubahan harga' => [
            'subject' => 'Permintaan Perubahan Harga',
            'title' => 'Survey',
            'body' => 'Permintaan perubahan harga pada pelanggan atas nama :nama_pelanggan dengan harga MRC :mrc dan OTC :otc'
        ],
        'pengajuan perubahan harga diterima' => [
            'subject' => 'Perubahan Harga Diterima',
            'title' => 'Pelanggan',
            'body' => 'Perubahan harga pada pelanggan atas nama :nama_pelanggan diterima dengan harga MRC :mrc dan OTC :otc'
        ],
        'pengajuan perubahan harga ditolak' => [
            'subject' => 'Perubahan Harga ditolak',
            'title' => 'Pelanggan',
            'body' => 'Perubahan harga pada pelanggan atas nama :nama_pelanggan ditolak'
        ]
    ]
];