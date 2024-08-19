<?php

return [
    'list resource' => 'List invoices',
    'create resource' => 'Create invoices',
    'edit resource' => 'Edit invoices',
    'destroy resource' => 'Destroy invoices',
    'title' => [
        'invoices' => 'Invoice',
        'create invoice' => 'Create a invoice',
        'edit invoice' => 'Edit a invoice',
        'detail' => 'Detail invoice',
        'pembayaran invoice' => 'Pembayaran invoice :invoice'
    ],
    'button' => [
        'create invoice' => 'Create a invoice',
        'detail' => 'Detail',
        'bayar' => 'Bayar',
        'print' => 'Print'
    ],
    'table' => [
        'invoice_no' => 'Nomor Invoice',
        'periode' => 'Periode',
        'status' => 'Status',
        'created_at' => 'Created at',
        'actions' => 'actions',
        'total tagihan' => 'Total Tagihan',
        'sisa saldo' => 'Sisa Saldo',
        'invoice' => 'Invoice',
        'amount' => 'Amount',
        'payment at' => 'Payment at',
        'pelanggan' => 'Pelanggan',
        'paket' => 'Paket Berlangganan',
        'mrc' => 'MRC',
    ],
    'tooltip' => [
        'bayar invoice' => 'Bayar Invoice',
        'cetak invoice' => 'Cetak Invoice',
        'pending invoice' => ':jumlah Invoice pelanggan berstatus Pending'
    ],
    'ppn' => 'PPN (:ppn%) : :amount',
    'total' => 'Total',
    'print' => 'Print',
    'tagihan pelanggan' => 'Tagihan Pelanggan : :name', 
    'deskripsi' => 'Deskripsi',
    'tanggal invoice' => 'Tanggal Invoice',
    'expired' => 'Jatuh Tempo',
    'quantity' => 'Kuantitas',
    'harga' => 'Harga',
    'belum bayar' => 'Belum bayar',
    'lunas' => 'Lunas',
    'pending' => 'Pending',
    'mails' => [
        'refund email' => [
            'subject' => 'Pengembalian dana',
            'title' => 'Invoice'
        ]
    ],
    'options' => [
        'status' => [
            'all' => 'Seluruh Status',
            'belum bayar' => 'Belum bayar',
            'pending' => 'Pending',
            'settlement' => 'Settlement'
        ],
        'type' => [
            'all' => 'Seluruh Tipe',
            'mrc pelanggan' => 'MRC Pelanggan',
            'presales' => 'Presales',
            'end point' => 'End Point'
        ]
    ],
    'tab' => [
        'detail invoice' => 'Detail Invoice',
        'invoice pelanggan' => 'Invoice Pelanggan'
    ],
    'pembayaran pelanggan' => 'Tagihan Bulanan Pelanggan',
    'tagihan bulanan pelanggan' => 'Tagihan Pelanggan Bulan :bulan',
    'tagihan bulanan pelanggan dari isp' => 'Pemindah bukuan pembayaran MRC pelanggan bulan :bulan di wilayah :wilayah dari :isp',
    'refund item suspend' => "Pengembalian dana sebesar :jumlah dari suspend pelanggan :name",
    'refund item sla' => "Pengembalian dana sebesar :jumlah dari SLA mati koneksi pelanggan a/n :name selama :waktu",
    'refund item prorata' => "Pengembalian dana sebesar :jumlah dari Prorata pelanggan a/n :name",
    'saldo tidak mencukupi' => 'Saldo tidak menucukupi silahkan Topup terlebih dahulu',
    'pembayaran sukses' => 'Pembayaran sukses'
];
