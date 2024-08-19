<?php

return [
    'list resource' => 'List companies',
    'create resource' => 'Create companies',
    'edit resource' => 'Edit companies',
    'destroy resource' => 'Destroy companies',
    'detail resource' => 'Detail companies',
    'title' => [
        'companies' => 'Company',
        'create company' => 'Buat company',
        'edit company' => 'Edit company',
        'dayoff' => "Hari libur",
        'dayon' => "Hari kerja",
        'workingday' => 'Hari kerja',
        'profile' => 'Profile',
        'day selected' => 'Hari terpilih',
        'add dayoff' => 'Tambah hari libur',
        'isp' => 'ISP',
        'osp' => 'OSP',
        'total tagihan bulanan' => 'Total Tagihan Bulanan'
    ],
    'button' => [
        'create company' => 'Buat Company',
        'create working-day' => 'Tambah hari kerja',
        'create rekening' => 'Tambah rekening',
        'save company' => 'Simpan Company',
        'update company' => 'Update Company'
    ],
    'table' => [
        'logo'      => "Logo",
        'name'      => 'Name',
        'type'      => 'Type',
        'rating' => [
            'rater_name' => 'Rater name',
            'company_name' => 'Company name',
            'description' => 'Description',
            'created_at' => 'Rated at',
            'rating' => 'Rating'
        ],
        'rekening' => [
            'bank' => 'Bank',
            'rekening' => 'Rekening',
            'atas nama' => 'Atas nama',
            'created_at' => 'Created at',
        ],
    ],
    'form' => [
        'title' => [
            'company_profile' => 'Company Profile',
            'address' => 'Address',
            'rekening' => 'Rekening Form'
        ],
        'pop_address' => 'POP Address',
        'name' => 'Name',
        'type' => 'Type',
        'address' => 'Address',
        'provinces_id' => 'Provinsi',
        'regencies_id' => 'Kabupaten',
        'districts_id' => 'Kecamatan',
        'villages_id' => 'Desa',
        'post_code' => 'Kode pos',
        'display_name' => 'Display name',
        'total_endpoint' => 'Total endpoint',
        'company_picture' => "Logo company",
        'pop_location' => 'Lokasi POP',
        'ppn' => 'Apakah menerapkan PPN ?',
        'admin' => [
            'fullname' => 'Fullname',
            'email' => 'Email',
            'password' => 'Password',
            'phone' => 'Phone',
            'check-pass' => "Re-password"
        ],
        'rekening' => [
            'bank' => 'Bank',
            'rekening' => 'Nomor rekening',
            'atas nama' => 'Atas nama'
        ],
        'pop_address_equal_company' => "Alamat POP sama dengan alamat perusahaan?"
    ],
    'messages' => [
        'error' => 'There are some errors in the form.',
        'picture error format' => 'Avatar picture must be JPG or PNG format!',
        'picture error size' => 'Avatar picture size can not exceed 2MB!',
        "company updated" => "Company successfully updated.",
        "company created" => "Company successfully created.",
        "company deleted" => "Company successfully deleted.",
        "passed day" => "Tidak bisa memilih hari yang sudah berlalu."
    ],
    'validation' => [
        'required' => ':field is required',
        'valid' => 'Please correct :field address',
        'not match' => 'Two inputs don\'t match!',
        'max' => "Maximum limit(s) of :field",
        'used-email' => ":field already registered",
    ],
    'rating' => [
        'list resource' => 'List Rating'
    ],
    'tab' => [
        'title' => [
            'company' => 'Company',
            'work-time' => 'Work Time',
            'working-day' => 'Hari kerja',
            'day-off' => 'Hari libur',
            'admin-user' => 'Admin user',
            'rating' => 'Rating',
            'rekening' => 'Rekening'
        ]
    ],

    'log' => [
        'create' => 'Menambah Data Company :data',
        'update' => 'Melakukan Perubahan Data Company :data',
        'delete' => 'Menghapus Data Company :data',

        'create dayoff' => 'Menambah Data dayoff',
        'update workday' => 'Melakukan Update Data Workday',
        'delete dayoff' => 'Menghapus Data dayoff',
    ],
    'logs' => [
        'destroy' => [
            'tipe' => 'Hapus Company',
            'description' => 'Penghapusan Company :company_name'
        ],
        'edit' => [
            'tipe' => 'Ubah Company',
            'description' => 'Mengubah data Company :company_name'
        ],
        'create' => [
            'tipe' => 'Tambah Company',
            'description' => 'Menambah company  :company_name'
        ]
    ],
    'mail' => [
        'company created' => [
            'subject' => 'Perusahaan anda berhasil didaftarkan',
            'successfull created company' => 'Perusahaan :company_name telah berhasil didaftarkan di Openaccess :',
            'detail company' => 'Silahkan klik link berikut untuk mereset password : ',
            'title' => 'Daftar Openaccess'
        ]
    ],
    'placeholder' => [
        'cari company' => 'Cari company...'
    ]

];
