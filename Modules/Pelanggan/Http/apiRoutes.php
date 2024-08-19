<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => '/pelanggan', 'middleware' => ['api.token', 'auth.admin']], function (Router $router) {

    $router->post('/form/status', [
        'as' => 'api.pelanggan.form.status',
        'uses' => 'PelangganController@getStatus',
    ]);

    // PENGAJUAN JADWAL SURVEY
    $router->get('/jadwal/survey/{pelanggan_id}', [
        'as' => 'api.pelanggan.form.jadwal.survey',
        'uses' => 'PengajuanSurveyController@loadjadwalPengajuanSurvey',
    ]);
    $router->post('/jadwal/survey/{pelanggan_id}', [
        'as' => 'api.pelanggan.form.jadwal.survey.submit',
        'uses' => 'PengajuanSurveyController@submitPengajuanSurvey',
    ]);
    $router->post('/jadwal/survey/pilih_rekomendasi/{pelanggan_id}/{pengajuan_id}', [
        'as' => 'api.pelanggan.form.jadwal.survey.pilih_rekomendasi',
        'uses' => 'PengajuanSurveyController@pilihRekomendasiSurvey',
    ]);

    // PENGAJUAN JADWAL INSTALASI
    $router->get('/jadwal/instalasi/{pelanggan_id}', [
        'as' => 'api.pelanggan.form.jadwal.instalasi',
        'uses' => 'PengajuanInstalasiController@loadjadwalPengajuanInstalasi',
    ]);
    $router->post('/jadwal/instalasi/{pelanggan_id}', [
        'as' => 'api.pelanggan.form.jadwal.instalasi.submit',
        'uses' => 'PengajuanInstalasiController@submitPengajuanInstalasi',
    ]);
    $router->post('/jadwal/instalasi/pilih_rekomendasi/{pelanggan_id}/{pengajuan_id}', [
        'as' => 'api.pelanggan.form.jadwal.instalasi.pilih_rekomendasi',
        'uses' => 'PengajuanInstalasiController@pilihRekomendasiInstalasi',
    ]);


    // SALES ORDER
    $router->post('/form/detail/so', [
        'as' => 'api.pelanggan.form.detail.so',
        'uses' => 'SalesOrderController@detailSalesOrder',
    ]);

    $router->post('/form/submit/so', [
        'as' => 'api.pelanggan.form.submit.so',
        'uses' => 'SalesOrderController@submitSalesOrder',
    ]);

    // INSTALASI STEP

    $router->get('/form/step/data/instalasi', [
        'as' => 'api.load.data.pelanggan.instalasi',
        'uses' => 'InstalasiController@ShowDataPelangganSelected'
    ]);

    $router->post('/form/step/instalasi/perubahan', [
        'as' => 'api.pelanggan.form.perubahan.harga',
        'uses' => 'PerubahanHargaController@ShowDataPerubahanHargaSelected',
    ]);

    $router->post('/form/step/instalasi/ajukan', [
        'as' => 'api.pelanggan.form.ajukan.perubahan',
        'uses' => 'PerubahanHargaController@ajukanPerubahanHarga'
    ]);

    $router->post('/form/step/instalasi/terima', [
        'as' => 'api.pelanggan.form.terima.perubahan',
        'uses' => 'PerubahanHargaController@terimaPerubahanHarga'
    ]);

    $router->post('/form/step/instalasi/tolak', [
        'as' => 'api.pelanggan.form.tolak.perubahan',
        'uses' => 'PerubahanHargaController@tolakPerubahanHarga'
    ]);

    $router->get('/form/step/instalasi/data', [
        'as' => 'api.pelanggan.form.data.instalasi',
        'uses' => 'InstalasiController@showDataInstalasiSelected'
    ]);

    $router->post('form/step/instalasi/save', [
        'as' => 'api.pelanggan.form.data.instalasi.save',
        'uses' => 'InstalasiController@SaveData'
    ]);

    $router->post('form/step/instalasi/save_teknisi', [
        'as' => 'api.pelanggan.form.teknisi.instalasi.save',
        'uses' => 'InstalasiController@saveTeknisiInstalasi'
    ]);

    $router->post('form/step/instalasi/delete', [
        'as' => 'api.pelanggan.form.data.instalasi.delete',
        'uses' => 'InstalasiController@DeleteAlatOrPerangkatOrDokumentasi'
    ]);

    $router->get('form/step/instalasi/data_instalasi', [
        'as' => 'api.pelanggan.form.data.instalasi.data.instalasi',
        'uses' => 'InstalasiController@showDataPerangkatAlatTeknisiDokumentasi'
    ]);

    //SURVEY STEP

    $router->get('/form/step/survey/{pelanggan_id}', [
        'as' => 'api.perangkat.survey.index',
        'uses' => 'SurveyController@index'
    ]);
    $router->get('/form/step/survey/getAllBarang', [
        'as' => 'api.perangkat.barang.all',
        'uses' => 'SurveyController@getAllBarangPerangkat'
    ]);

    $router->post('/form/step/survey/submit', [
        'as' => 'api.pelanggan.submit.survey',
        'uses' => 'SurveyController@store'
    ]);

    $router->post('/teknisi/update/{survey_id}', [
        'as' => 'api.pelanggan.teknisi.update',
        'uses' => 'SurveyController@updateTeknisi'
    ]);

    // INSTALASI STEP
    $router->post('/form/step/instalasi', [
        'as' => 'api.pelanggan.form.instalasi',
        'uses' => 'PelangganController@ShowDataPelangganSelected',
    ]);

    $router->post('/form/step/instalasi/perubahan', [
        'as' => 'api.pelanggan.form.perubahan.harga',
        'uses' => 'PerubahanHargaController@ShowDataPerubahanHargaSelected',
    ]);

    $router->post('/form/step/instalasi/ajukan', [
        'as' => 'api.pelanggan.form.ajukan.perubahan',
        'uses' => 'PerubahanHargaController@ajukanPerubahanHarga'
    ]);

    // AKTIVASI
    $router->get('/form/step/detail/aktivasi/{pelanggan_id}', [
        'as' => 'api.pelanggan.form.detail.aktivasi',
        'uses' => 'AktivasiController@detailAktivasi'
    ]);

    $router->post('/form/submit/activation', [
        'as' => 'api.pelanggan.form.submit.activation',
        'uses' => 'AktivasiController@submitActivation',
    ]);

    $router->post('/form/submit/activation/unapprove', [
        'as' => 'api.pelanggan.form.submit.activation.unapprove',
        'uses' => 'AktivasiController@unApproveActivation'
    ]);

    $router->get('/rating/{pelanggan}', [
        'as' => 'api.pelanggan.rating',
        'uses' => 'PelangganController@rating'
    ]);

    // PELANGGAN
    $router->get('/list', [
        'as' => 'api.pelanggan.list',
        'uses' => 'PelangganController@listPelanggan'
    ]);

    $router->get('/list/cancel', [
        'as' => 'api.pelanggan.list.cancel',
        'uses' => 'PelangganController@listPelangganCancel'
    ]);

    $router->get('/list/all', [
        'as' => 'api.pelanggan.list.all',
        'uses' => 'PelangganController@getAllPelanganForSelect'
    ]);

    $router->get('/list/suspend', [
        'as' => 'api.pelanggan.list.open.suspend',
        'uses' => 'PelangganController@getPelanganOpenSuspendForSelect'
    ]);

    $router->get('list/jumlah', [
        'as' => 'api.pelanggan.list.jumlah',
        'uses' => 'PelangganController@jumlahList',
    ]);

    $router->post('/suspend', [
        'as' => 'api.pelanggan.suspend',
        'uses' => 'PelangganController@suspendPelanggan'
    ]);

    $router->get('/detail/{pelanggan_id}', [
        'as' => 'api.pelanggan.form.detail',
        'uses' => 'PelangganController@detailPelanggan'
    ]);

    $router->post('/detail/submit', [
        'as' => 'api.pelanggan.form.submit',
        'uses' => 'PelangganController@submitPelanggan'
    ]);

    $router->post('/get_biaya/{paket_id}', [
        'as' => 'api.pelanggan.get_biaya.paket',
        'uses' => 'PelangganController@getBiayaPaket'
    ]);

    $router->get('/check/saldo_mengendap/{pelanggan_id}', [
        'as' => 'api.pelanggan.saldo.check',
        'uses' => 'PelangganController@checkSaldoPelanggan'
    ]);

    $router->post('/saldo/submit/{pelanggan_id}', [
        'as' => 'api.pelanggan.saldo.submit',
        'uses' => 'PelangganController@submitSaldoPelanggan'
    ]);

    $router->get('/saldo/detail/{pelanggan_id}', [
        'as' => 'api.pelanggan.saldo.detail',
        'uses' => 'PelangganController@detailSaldoPelanggan'
    ]);

    $router->post('/status/step/cancel', [
        'as' => 'api.pelanggan.status.step.cancel',
        'uses' => 'PelangganController@submitCancelStatusStepPelanggan'
    ]);

    $router->post('/status/step/activate', [
        'as' => 'api.pelanggan.status.step.activate',
        'uses' => 'PelangganController@submitActivateStatusStepPelanggan'
    ]);

    $router->get('/history/sla/{pelanggan_id}', [
        'as' => 'api.pelanggan.history.sla',
        'uses' => 'PelangganController@dataHistorySla'
    ]);

    $router->get('/history/ticket/{pelanggan_id}', [
        'as' => 'api.pelanggan.history.ticket.support',
        'uses' => 'PelangganController@ticketSupportBulanIni'
    ]);

    $router->get('/pemakaian/history/{pelanggan_id}', [
        'as' => 'api.pelanggan.pemakaian.bulan.ini',
        'uses' => 'PelangganController@getPemakaianBulanini'
    ]);

    $router->delete('/{pelanggan}', [
        'as' => 'api.pelanggan.destroy',
        'uses' => 'PelangganController@delete_pelanggan'
    ]);

    // TIMER
    $router->get('/timer/osp/{pelanggan}', [
        'as' => 'api.pelanggan.timer.osp',
        'uses' => 'PelangganController@getTimerOSP'
    ]);

    // STEP INFO
    $router->get('/info/step/{pelanggan}', [
        'as' => 'api.pelanggan.info.step',
        'uses' => 'PelangganController@getInfoStepPelanggan'
    ]);
});
$router->get('/reminders', [
    'as' => 'api.pelanggan.reminders',
    'uses' => 'PelangganController@reminders'
]);
