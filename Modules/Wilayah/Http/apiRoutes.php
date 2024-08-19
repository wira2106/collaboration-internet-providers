<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['middleware' => ['api.token', 'auth.admin']], function (Router $router) {

    $router->get("provinces", "RegionController@provinces")->name("api.provinces");
    $router->get("json-kabupaten", "RegionController@regencies")->name("api.regencies");
    $router->get("json-kecamatan", "RegionController@districts")->name("api.districts");
    $router->get("json-kelurahan", "RegionController@villages")->name("api.villages");


    $router->post('wilayahs', [
        'as' => 'api.wilayah.wilayah.store',
        'uses' => 'WilayahController@submitFormWilayah',
        'middleware' => 'token-can:wilayah.wilayahs.create'
    ]);

    $router->post('wilayahs/{wilayah}', [
        'as' => 'api.wilayah.wilayah.update',
        'uses' => 'WilayahController@submitFormWilayah',
        'middleware' => 'token-can:wilayah.wilayahs.update'
    ]);

    $router->delete('wilayahs/{wilayah}', [
        'as' => 'api.wilayah.wilayah.destroy',
        'uses' => 'WilayahController@deleteWilayah',
        'middleware' => 'token-can:wilayah.wilayahs.destroy'
    ]);

    $router->post('wilayahs/{wilayah}/find', [
        'as' => 'api.wilayah.wilayah.find',
        'uses' => 'WilayahController@find',
    ]);


    $router->get('wilayahs/pagination', [
        'as' => 'api.wilayah.wilayah.pagination',
        'uses' => 'WilayahController@pagination',
        'middleware' => 'token-can:wilayah.wilayahs.index'
    ]);

    $router->get('wilayahs/isp', [
        'as' => 'api.wilayah.wilayah.isp',
        'uses' => 'WilayahController@isp',
    ]);


    $router->post('wilayahs/load/titik', [
        'as' => 'api.wilayah.wilayah.load_titik',
        'uses' => 'WilayahController@loadTitik'
    ]);

    $router->get('wilayah/{company_id}/get',[
        'as' => 'api.wilayah.company.get',
        'uses' => 'WilayahController@getCompanyWilayah'
    ]);

    $router->post('wilayah/request-presale/{wilayah}', [
        'as' => 'api.wilayah.company.request_presale',
        'uses' => 'WilayahController@request_presale'
    ]);


    // pengajuan
    $router->post('pengajuan/submit/{wilayah_id}', [
        'as' => 'api.wilayah.pengajuan.submit',
        'uses' => 'PengajuanController@submit'
    ]);

    $router->post('pengajuan/status', [
        'as' => 'api.wilayah.pengajuan.status',
        'uses' => 'PengajuanController@setStatus'
    ]);

    $router->get('pengajuan/pagination', [
        'as' => 'api.wilayah.pengajuan.pagination',
        'uses' => 'PengajuanController@pagination',
        'middleware' => 'token-can:wilayah.pengajuans.index'
    ]);
    $router->delete('pengajuan/{id}', [
        'as' => 'api.wilayah.pengajuan.destroy',
        'uses' => 'PengajuanController@deletePengajuan',
    ]);
    $router->delete('pengajuan/paket/{id}/{pengajuan_id}', [
        'as' => 'api.wilayah.pengajuan.paket.destroy',
        'uses' => 'PengajuanController@deletePaketPengajuan',
    ]);
    $router->post('pengajuan/detail/{id}', [
        'as' => 'api.wilayah.pengajuan.detail',
        'uses' => 'PengajuanController@detail',
    ]);
    $router->post('pengajuan/paket/', [
        'as' => 'api.wilayah.pengajuan.paket.submit',
        'uses' => 'PengajuanController@paketIsp',
    ]);

    $router->get('pengajuan/paket/check/{id}', [
        'as' => 'api.wilayah.pengajuan.paket.check',
        'uses' => 'PengajuanController@checkPaket',
    ]);
    $router->post('pengajuan/paket/all/{pengajuan_id}', [
        'as' => 'api.wilayah.pengajuan.paket',
        'uses' => 'PengajuanController@paketIspAll',
    ]);
    $router->post('pengajuan/paket/pagination', [
        'as' => 'api.wilayah.pengajuan.paket.pagination',
        'uses' => 'PengajuanController@paketPagination'
    ]);

    //upload File

    $router->get('pengajuan/form/{id}', [
        'as' => 'api.wilayah.pengajuan.form',
        'uses' => 'PengajuanController@dataKontrak'
    ]);

    $router->post('pengajuan/perpanjang-kontrak/{request_wilayah}', [
        'as' => 'api.wilayah.pengajuan.perpanjang',
        'uses' => 'PengajuanController@perpanjang_kontrak'
    ]);

    $router->get('/pengajuan/alasan/{request_wilayah}', [
        'as' => 'api.wilayah.pengajuan.alasan',
        'uses' => 'PengajuanController@pagination_alasan'
    ]);
});
