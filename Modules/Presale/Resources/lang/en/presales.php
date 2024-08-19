<?php
$icon_konfirmasi = url('/modules/presale/img/pengajuan.ico');
return [
    'list resource' => 'List presales',
    'create resource' => 'Create presales',
    'edit resource' => 'Edit presales',
    'destroy resource' => 'Delete presales',
    'confirm resource' => 'Confirm presales',
    'title' => [
        'presales' => 'presale',
        'create presale' => 'Create a presale',
        'edit presale' => 'Edit a presale',
        'sidebar' => 'Presale',
        'choose_odp' => 'Choose odp',
        'search' => "Search",
        'order' => 'Order'
    ],
    'button' => [
        'create presale' => 'Create a presale',
        'change' => 'Pilih',
        'start' => 'Mulai',
        'konfirmasi presale' => 'Konfirmasi Presale'
    ],
    'table' => [
        'region' => 'Region'
    ],
    'form' => [
        'search' => 'Search...',
        'lat' => 'Lat',
        'lon' => 'Long',
        'end_point_name' => 'Endpoint name',
        'panjang_kabel' => 'Cable length',
        'biaya_kabel' => 'Cable cost',
        'biaya_instalasi' => 'Installation Cost',
        'total_biaya' => 'Total cost',
        'provinces' => 'Provinces',
        'regencies' => 'Regencies',
        'districts' => 'Districts',
        'villages' => 'Villages',
        'nama_gang' => 'Gang name',
        'no_rumah' => 'House number',
        'kode_pos' => 'Postal code',
        'address' => 'Address',
        'class' => 'House type',
        'status' => 'Status',
        'keterangan' => 'Description',
        'foto_rumah' => 'House picture',
        'show' => 'Show',
        'total' => 'Total',
        'no data' => 'No data'
    ],
    'messages' => [
        'not-found' => ':data not found',
        "presale updated" => "Presale successfully updated.",
        "presale created" => "Presale successfully created.",
        "presale deleted" => "Presale successfully deleted.",
        'presale confirmed' => 'Presale successfully confirmed',
        'payment failed' => 'Pembayaran tidak berhasil dikarenakan kekurangan saldo',
        'open wilayah' => 'Apakah ingin langsung open wilayah?',
        '404 active presale' => 'Active presale not found',
        'minimum saldo order' => 'Minimum saldo untuk melakukan order di wilayah ini sebesar :rupiah'
    ],
    'log' => [
        'create' => 'Menambah Data Presale',
        'update' => 'Melakukan Perubahan Data Presale',
        'delete' => 'Menghapus Data Presale',
    ],
    'tooltip' => [
        'button' => [
            'list endpoint' => 'List Endpoint',
            'list presales' => 'List Presale',
            'refresh' => 'Refresh Marker Presales & Endpoint',
            'pengaturan' => 'Pengaturan',
            'user location' => 'Pindah ke lokasi saat ini',
            'tambah presale & endpoint' => 'Tambah endpoint / presale',
            'pindah wilayah' => 'List wilayah',
            'search' => 'Cari lokasi',
            'tambah endpoint' => 'Klik untuk tambah endpoint',
            'tambah presale' => 'Klik untuk tambah presale',
            'guide' => 'Menampilkan list panduan'
        ]
    ],
    'selected' => 'Presale sudah ada di list konfirmasi',
    'pilih konfirmasi presale' => 'Pilih konfirmasi presale?',
    'konfirmasi presale' => 'Konfirmasi presale?',
    'konfirmasi' => [
        'title' => 'Konfirmasi Presale',
        'total biaya' => 'Total Biaya',
        'checkbox' => 'Saya menyetujui pengurangan saldo untuk konfirmasi presale sebesar :field',
        'saldo kurang' => 'Kekurangan saldo sebesar :field',
        'email terkirim' => 'Email konfirmasi telah dikirim'
    ],
    'pilih semua' => "Pilih semua",
    'mail' => [
        'presale confirmation email' => [
            'subject' => 'Wilayah :wilayah telah di presale',
            'successfull created presale' => 'Wilayah :wilayah_name telah dilakukan presale oleh pihak openaccess, anda sekarang dapat melakukan konfirmasi presale.',
            'detail presale' => 'Klik link berikut untuk melihat detail : ',
            'title' => 'Presale'
        ],
        'confirmation presale' => [
            'subject' => 'Konfirmasi presale',
            'successfull confirmation presale' => 'Konfirmasi presale berhasil dilakukan dan saldo telah dipotong sebesar :biaya',
            'detail presale' => 'Klik link berikut untuk melihat detail : ',
            'title' => 'Presale'
        ]
    ],
    'logs' => [
        'create' => [
            'tipe' => 'Tambah Presale',
            'description' => 'Penambahan Presales baru :data'
        ],
        'edit' => [
            'tipe' => 'Ubah Presale',
            'description' => 'Perubahan data Presales :data'
        ],
        'destroy' => [
            'tipe' => 'Hapus Presale',
            'description' => 'Penghapusan Presales :data'
        ],
        'confirmed' => [
            'tipe' => 'Konfirmasi Presale',
            'description' => 'Konfirmasi presale pada di wilayah :data'
        ]
    ],
    'tour' => [
        'title' => 'Guide',
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
        'tambah presale' => [
            'title' => 'Tambah Presale',
            'step' => [
                'step-1' => [
                    'content' => 'Klik tombol + untuk menampilkan tombol tambah presale'
                ],
                'step-2' => [
                    'content' => 'Klik tombol tambah presale'
                ],
                'step-3' => [
                    'content' => 'Tentukan posisi presale yang akan ditambah. Jika sudah, klik tombol tambah (+). Ingin batal klik tombol (x)'
                ],
                'step-4' => [
                    'content' => 'Pilih endpoint yang terdekat'
                ],
                'step-5' => [
                    'content' => '<p>Otomatis : Jalur kabel ditentukan sesuai jalan</p> <p>Manual : Tentukan jalur sendiri tanpa mengikuti jalan</p>'
                ],
                'step-6' => [
                    'content' => 'Tentukan jalur kabel dari site ke endpoint. <p>Klik Pilih saat sudah selesai menentukan jalur kabel.</p> <p>Klik kembali untuk kembali ke menu pilih endpoint.</p>'
                ],
                'step-7' => [
                    'content' => 'Isi data-data site yang kamu presale. Lalu klik tombol Save untuk simpan.'
                ],
            ]
        ],
        'edit data presale' => [
            'title' => 'Edit Data Presale',
            'step' => [
                'step-1' => [
                    'content' => 'Klik marker presale yang ingin di edit'
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" class="btn-action-marker btn btn-warning btn-sm "><i class="fa fa-edit"></i>  </button>'
                ],
                'step-3' => [
                    'content' => 'Ubah data sesuai yang diinginkan, setelah selesai klik button save.'
                ],
            ]

        ],
        'edit location presale' => [
            'title' => 'Edit Lokasi Presale',
            'step' => [
                'step-1' => [
                    'content' => 'Klik marker presale yang ingin di ubah lokasinya'
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" class="btn-action-marker btn btn-primary btn-sm "><i class="fa fa-arrows-alt"></i>  </button>'
                ],
                'step-3' => [
                    'content' => 'Geser map dan sesuaikan lokasi endpoint yang baru. Jika sudah klik tombol <button type="button" data-type="1" class="el-button el-button--primary is-circle"><i class="fa fa-check"></i></button>'
                ],
                'step-4' => [
                    'content' => '<p>Otomatis : Jalur kabel ditentukan sesuai jalan</p> <p>Manual : Tentukan jalur sendiri tanpa mengikuti jalan</p>'
                ],
                'step-5' => [
                    'content' => 'Tentukan jalur kabel dari site ke endpoint. <p>Jika sudah klik tombol <button type="button" class="btn-action-marker btn btn-primary btn-sm"><i class="fa fa-save"></i></button> untuk simpan perubahan.</p> <p>Klik tombol <button type="button" class="btn-action-marker btn btn-danger btn-sm"><i class="fa fa-times"> </i></button> untuk batal edit lokasi presale</p> '
                ]
            ]

        ],
        'hapus presale' => [
            'title' => 'Hapus Presale',
            'step' => [
                'step-1' => [
                    'content' => 'Klik marker presale yang ingin di hapus'
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" class="btn-action-marker btn btn-danger btn-sm "><i class="fa fa-trash"></i>  </button>'
                ],
            ]
        ],
        'tampilkan jalur kabel presale' => [
            'title' => 'Tampilkan Jalur Kabel Presale',
            'step' => [
                'step-1' => [
                    'content' => 'Klik marker presale yang ingin di tampilkan jalur kabelnya'
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" class="btn-action-marker btn btn-primary btn-sm "><i class="fa fa-level-up-alt"></i> </button>'
                ],
                'step-3' => [
                    'content' => 'Klik tombol <button type="button" class="btn-action-marker btn btn-danger btn-sm "><i class="fa fa-level-up-alt"></i> </button> untuk hide jalur kabel.'
                ],
            ]
        ],
        'tampilkan jalur kabel endpoint ke presale' => [
            'title' => 'Tampilkan Jalur Kabel Endpoint Ke Presale',
            'step' => [
                'step-1' => [
                    'content' => 'Klik marker endpoint yang ingin di tampilkan jalur kabelnya'
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" class="btn-action-marker btn btn-primary btn-sm "><i class="fa fa-home"></i> </button>'
                ],
                'step-3' => [
                    'content' => 'Klik tombol <button type="button" class="btn-action-marker btn btn-danger btn-sm "><i class="fa fa-level-up-alt"></i> </button> untuk hide jalur kabel.'
                ],
            ]
        ],
        'hide marker presale' => [
            'title' => 'Sembunyikan Marker Presale',
            'step' => [
                'step-1' => [
                    'content' => 'Klik tombol <button type="button" class="btn btn-secondary"><i class="fa fa-cog"> </i></button>'
                ],
                'step-2' => [
                    'content' => 'Klik slider presale menjadi tidak aktif dan marker presale akan hilang.'
                ],
            ]
        ],
        'menampilkan presale yang dicari' => [
            'title' => 'Menampilkan marker presales sesuai dengan yang dicari',
            'step' => [
                'step-1' => [
                    'content' => 'Klik tombol list presales'
                ],
                'step-2' => [
                    'content' => 'Cari presales yang ingin kamu tampilkan dan tekan tombol tampilkan presale'
                ],
            ]
        ],
        'konfirmasi presale' => [
            'title' => 'Konfirmasi Presales',
            'step' => [
                'step-1' => [
                    'content' => "Klik marker dengan icon <img src='$icon_konfirmasi' style='width: 35px;'>"
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" class="btn btn-info btn-action-marker"><i class="fa fa-check"> </i></button>'
                ],
                'step-3' => [
                    'content' => 'Pilih presale yang ingin di konfirmasi, jika sudah tekan tombol dipilih untuk melakukan proses konfirmasi presale.'
                ],
            ]
        ],
        'order pelanggan' => [
            'title' => 'Order Pelanggan',
            'step' => [
                'step-1' => [
                    'content' => "Klik marker presale sesuai dengan alamat pelanggan."
                ],
                'step-2' => [
                    'content' => 'Klik tombol <button type="button" class="btn btn-primary btn-sm mx-1" style="margin-left:-3.3px;">Order</button>'
                ]
            ]
        ]
    ],
    'modal' => [
        'list guide' => [
            'title' => 'List Panduan'
        ]
    ],
    'cari panduan' => 'Cari panduan',
    'order' => 'Order',
    'jalur kabel' => 'Jalur Kabel'

];
