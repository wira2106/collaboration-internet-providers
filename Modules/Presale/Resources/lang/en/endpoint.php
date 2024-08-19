<?php
$icon_konfirmasi = url('/modules/presale/img/odp-marker-disable.ico');
return [
    "list resource" => "List Endpoint",
    "create resource" => "Create Endpoint",
    "edit resource" => "Edit Endpoint",
    "delete resource" => "Delete Endpoint",
    'change region' => 'Ganti wilayah',
    "title" => [
        "sidebar" => "Endpoint",
        'pembayaran endpoint' => 'Pembayaran Endpoint' 
    ],
    'form' => [
        'name' => 'Name',
        'provinces_id' => 'Provinces',
        'regencies_id' => 'Regencies',
        'districts_id' => 'District',
        'villages_id' => 'villages',
        'address' => 'Address',
        'type' => 'Type'
    ],
    'validation' => [
        'required' => ':field is required',
        'valid' => 'Please correct :field address',
        'not match' => 'Two inputs don\'t match!',
        'max' => "Maximum limit(s) of :field",
        'name' => 'name already used'
    ],
    'tooltip' => [
        'endpoint disable' => 'Tidak dapat open wilayah karena terdapat endpoint yang belum lunas',
        'open wilayah' => 'Digunakan untuk merubah status wilayah menjadi open (dapat dilihat oleh ISP).'
    ],
    'message' => [
        'wilayah changed' => 'success change wilayah',
        'payment failed' => 'Pembayaran tidak berhasil dikarenakan kekurangan saldo',
        'sukses bayar endpoint' => 'Pembayaran endpoint berhasil'
    ],
    'logs' => [
        'create' => [
            'tipe' => 'Tambah Endpoint',
            'description' => 'Menambah Data Endpoint :data'
        ],
        'edit' => [
            'tipe' => 'Ubah Endpoint',
            'description' => 'Melakukan Perubahan Data Endpoint :data',
        ],
        'destroy' => [
            'tipe' => 'Hapus Endpoint',
            'description' => 'Menghapus Data Endpoint :data'
        ]
    ],
    'mail' => [
        'endpoint created' => [
            'subject' => 'Endpoint berhasil dibuat',
            'successfull created endpoint' => 'Endpoint berhasil dibuat dan saldo berhasil dipotong sebesar :biaya',
            'detail endpoint' => 'Anda bisa menggunakan link berikut : ',
            'title' => 'Endpoint'
        ],
        'bayar endpoint' => [
            'subject' => 'Pembayaran Endpoint',
            'sukses bayar endpoint' => 'Sukses membayar biaya penambahan endpoint sebesar :biaya pada wilayah :wilayah_name',
            'title' => 'Endpoint'
        ]
    ],
    'pilih semua' => 'Pilih semua',
    'tour' => [
        'tambah endpoint' => [
            'title' => 'Tambah Endpoint',
            'step' => [
                'step-1' => [
                    'content' => 'Klik tombol + untuk menampilkan tombol tambah endpoint'
                ],
                'step-2' => [
                    'content' => 'Klik tombol tambah endpoint'
                ],
                'step-3' => [
                    'content' => 'Tentukan posisi endpoint yang akan ditambah. Jika sudah, klik tombol tambah (+). Ingin batal klik tombol (x)'
                ],
                'step-4' => [
                    'content' => 'Isi data nama, type dan alamat endpoint kamu. Lalu klik tombol Save'
                ],
            ]
        ],
        'hapus endpoint' => [
            'title' => 'Hapus Endpoint',
            'step' => [
                'step-1' => [
                    'content' => 'Klik marker endpoint yang ingin di hapus'
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" class="btn-action-marker btn btn-danger btn-sm "><i class="fa fa-trash"></i>  </button>'
                ],
            ]
        ],
        'edit data endpoint' => [
            'title' => 'Ubah data endpoint',
            'step' => [
                'step-1' => [
                    'content' => 'Klik marker endpoint yang ingin di edit'
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" class="btn-action-marker btn btn-warning btn-sm "><i class="fa fa-edit"></i>  </button>'
                ],
                'step-3' => [
                    'content' => 'Ubah data sesuai yang diinginkan, setelah selesai klik button save.'
                ],
            ]
            
        ],
        'edit location endpoint' => [
            'title' => 'Edit Lokasi Endpoint',
            'step' => [
                'step-1' => [
                    'content' => 'Klik marker endpoint yang ingin di ubah lokasinya'
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" data-type="1" class="btn-action-marker btn btn-primary btn-sm"><i class="fa fa-arrows-alt"></i></button>'
                ],
                'step-3' => [
                    'content' => 'Geser map dan sesuaikan lokasi endpoint yang baru. Jika sudah klik tombol <button type="button" data-type="1" class="el-button el-button--primary is-circle"><i class="fa fa-save"></i></button>'
                ],
            ]
        ],
        'hide marker endpoint' => [
            'title' => 'Sembunyikan Marker Endpoint',
            'step' => [
                'step-1' => [
                    'content' => 'Klik tombol <button type="button" class="btn btn-secondary"><i class="fa fa-cog"> </i></button>'
                ],
                'step-2' => [
                    'content' => 'Klik slider endpoint menjadi tidak aktif dan marker endpoint akan hilang.'
                ],
            ]
        ],
        'konfirmasi endpoint' => [
            'title' => 'Konfirmasi Biaya Pembuatan Endpoint',
            'step' => [
                'step-1' => [
                    'content' => "Klik marker dengan icon endpoint yang abu-abu <img src='$icon_konfirmasi' style='width: 35px;'>"
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" class="btn btn-info btn-action-marker"><i class="fa fa-check"> </i></button>'
                ],
                'step-3' => [
                    'content' => 'Pilih endpoint yang ingin di konfirmasi, jika sudah tekan tombol dipilih untuk melakukan proses konfirmasi endpoint.'
                ],
            ]
        ],
        
    ],
];
