<?php

return [
    'list resource' => 'List wilayahs',
    'create resource' => 'Create wilayahs',
    'edit resource' => 'Edit wilayahs',
    'destroy resource' => 'Delete wilayahs',
    'update resource' => 'Edit wilayahs',
    'request resource' => 'Request wilayahs',
    'title' => [
        'form' => 'Form Wilayah',
        'wilayahs' => 'Wilayah',
        'create wilayah' => 'Tambah wilayah',
        'edit wilayah' => 'Edit wilayah',
        'latitude'=>'latitude',
        'longitude'=>'longitude'
    ],
    'button' => [
        'create wilayah' => 'Tambah wilayah',
    ],
    'table' => [
        'no' => 'No',
        'name' => 'Name',
        'nama company' => 'Company',
        'post_code' => 'Post Code',
        'provinces' => 'Provinces',
        'regencies' => 'Regencies',
        'villages' => 'Villages',
        'all osp' => 'Seluruh OSP',
        'all isp' => 'Seluruh ISP'
        
    ],
    'form' => [
        'name' => "Wilayah Name",
        'postal_code' => "Postal Code",
        "address" => "Address",
        'endpoint' => 'Endpoint',
        'open wilayah' => 'Open wilayah'
    ],

    'form required'=> [
        'wilayah required'=>'Wilayah name is required',
        'provinsi required'=>'Provinces is required',
        'regencies required'=>'Regencies is required',
        'districts required'=>'District is required',
        'villages required'=>'Villages is required',
        'postal required'=>'Postal Code is required',
        'companies required'=>'Companies is required',
        'endpoint required'=>'Endpoint is required'
    ],

    'placeholder' => [
        "name" => "Enter name..",
        "address" => "Enter address.."
    ],
    'messages' => [
        "wilayah updated" => "Wilayah successfully updated.",
        "wilayah created" => "Wilayah successfully created.",
        "wilayah deleted" => "Wilayah successfully deleted.",
        'request presale successfull' => 'Sukses request presale',
        'request wilayah' => 'Yakin request presale untuk wilayah ini?',
    ],
    'validation' => [],
    'log' => [
        'create' => 'Menambah Data Wilayah',
        'update' => 'Melakukan Perubahan Wilayah',
        'delete' => 'Menghapus Wilayah',
    ],
    'logs' => [
        'create' => [
            'tipe' => 'Tambah Wilayah',
            'description' => 'Penambahan wilayah :wilayah'
        ],
        'edit' => [
            'tipe' => 'Ubah Wilayah',
            'description' => 'Pengubahan wilayah :wilayah'
        ],
        'destroy' => [
            'tipe' => 'Hapus Wilayah',
            'description' => 'Penghapusan wilayah :wilayah'
        ]
    ],
    'mail' => [
        'wilayah created' => [
            'subject' => 'Wilayah telah dibuat',
            'successfull created wilayah' => 'Wilayah :wilayah_name telah berhasil dibuat di Openaccess',
            'detail wilayah' => 'Anda bisa melihat wilayah menggunakan link berikut :',
            'title' => 'Pembuatan wilayah'
        ],
        'wilayah open' => [
            'subject' => 'Wilayah telah dibuka',
            'title' => 'Wilayah',
            'body' => ':company_name telah membuka wilayah :wilayah_name, sekarang ISP dapat melakukan pengajuan untuk wilayah tersebut'
        ],
        'request presale successfull' => [
            'subject' => 'Request Presale',
            'title' => 'Presale',
            'body' => ':company_name telah meminta untuk melakukan presale untuk wilayah :wilayah_name',
            'detail wilayah' => 'Anda bisa presale menggunakan link berikut :',
        ]
    ]
];
