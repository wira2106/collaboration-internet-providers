 <?php


    return [
        'title' => [
            'detail topup' => "Detail Top Up",
            'konfirmasi topup' => 'Konfirmasi Top Up',
            'top up saldo' => 'Top Up Saldo',
            'menunggu pembayaran' => 'Pending payment',
            'amount' => 'Nominal',
            'brivaNo' => 'Nomor BRIVA',
            'created_at' => 'Dibuat pada',
            'expired_at' => 'Tgl Expired',
        ],
        'form' => [
            'masukan rekening lain' => "Masukan Rekening lain...",
        ],
        'messages' => [
            'confirm topup' => 'Konfirmasi Top Up Saldo?',
            'delete topup' => 'Hapus Transaksi?',
            'cancel topup' => 'Tolak Top Up Saldo?',
            'success' => 'Berhasil Top Up Saldo',
            'error delete' => 'Terjadi kesalahan saat menghapus transaksi',
            'success delete' => 'Berhasil menghapus transaksi',
        ],
        'button' => [
            'topup' => 'Top Up',
            'top up saldo' => 'Top Up Saldo'
        ],

        'placeholder' => [
            'cari topup' => 'Cari top up...'
        ],

        'logs' => [
            'create' => [
                'tipe' => 'Top Up',
                'description' => 'Melakukan Request Top Up Saldo'
            ],
            'create briva' => [
                'tipe' => 'Top Up BRIVA',
                'description' => 'Melakukan Request Top Up Saldo dengan menggunakan BRIVA'
            ],
            'delete briva' => [
                'tipe' => 'Delete BRIVA',
                'description' => 'Menghapus transaksi BRIVA dengan nomor Briva :brivano'
            ],
            'edit' => [
                'tipe' => 'Konfirmasi Top Up',
                'description' => 'Melakukan Konfirmasi Top Up Saldo atas nama :user'
            ],
            'destroy' => [
                'tipe' => 'Batal Top Up',
                'description' => 'Melakukan Pembatalan Top Up Saldo atas nama :user'
            ],
            'reject' => [
                'tipe' => 'Tolak Top Up',
                'description' => 'Melakukan penolakan Top Up untuk Company :user sebesar :data'
            ]
        ]
    ];
