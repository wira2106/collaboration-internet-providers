<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => '/', 'middleware' => ['api.token', 'auth.admin']], function (Router $router) {
    $router->post('companies/{company}/find', [
        'as' => 'api.company.company.find',
        'uses' => 'CompanyController@find',
    ]);
    $router->post('companies/find-new', [
        'as' => 'api.company.company.find-new',
        'uses' => 'CompanyController@findNew',
        'middleware' => 'token-can:company.companies.create'
    ]);

    $router->post('companies/{company}/edit', [
        'as' => 'api.company.company.update',
        'uses' => 'CompanyController@updateApi',
        'middleware' => 'token-can:company.companies.edit'
    ]);
    $router->post('companies/store', [
        'as' => 'api.company.company.store',
        'uses' => 'CompanyController@updateApi',
        'middleware' => 'token-can:company.companies.create',
    ]);

    $router->delete('companies/{company}', [
        'as' => 'api.company.company.destroy',
        'uses' => 'CompanyController@destroyApi',
        'middleware' => 'token-can:company.companies.destroy',
    ]);

    $router->post('companies/list', [
        'as' => 'api.company.company.list',
        'uses' => 'CompanyController@companies',
    ]);

    $router->get('companies/dayoff/find/{company}', [
        'as' => 'api.company.company.dayoff.index',
        'uses' => 'CompanyController@getDayOff',
        'middleware' => 'token-can:company.companies.detail'
    ]);
    $router->post('companies/dayoff/{company}', [
        'as' => 'api.company.company.dayoff',
        'uses' => 'CompanyController@dayoff',
        'middleware' => 'token-can:company.companies.edit'
    ]);

    $router->post('companies/dayon/{company}', [
        'as' => 'api.company.company.dayon',
        'uses' => 'CompanyController@dayon',
        'middleware' => 'token-can:company.companies.edit'
    ]);

    $router->post('companies/workday/{company}', [
        'as' => 'api.company.company.workday',
        'uses' => 'CompanyController@workday',
        'middleware' => 'token-can:company.companies.edit'
    ]);

    $router->get('companies/workday/find/{company}', [
        'as' => 'api.company.company.workday.find',
        'uses' => 'CompanyController@getWorkDay',
        'middleware' => 'token-can:company.companies.detail'
    ]);

    $router->delete('companies/workday/{id}', [
        'as' => 'api.company.company.workday.delete',
        'uses' => 'CompanyController@deleteWorkDay',
        'middleware' => 'token-can:company.companies.edit',
    ]);

    $router->get('companies/pagination', [
        'as' => 'api.company.company.pagination',
        'uses' => 'CompanyController@pagination',
        'middleware' => 'token-can:company.companies.index'
    ]);
    $router->post('/companies/profile', [
        'as' => 'api.company.company.profile',
        'uses' => 'CompanyController@profile',
        'middleware' => 'token-can:company.companies.detail'
    ]);
    $router->post('/companies/rating/{company}', [
        'as' => 'api.company.company.rating',
        'uses' => 'CompanyController@rating',
        'middleware' => 'token-can:company.companies.detail'
    ]);

    // Route Paket Berlangganan
    $router->get('/paket', [
        'as' => 'api.company.paketberlangganan.index',
        'uses' => 'PaketBerlanggananController@index'
    ]);
    $router->get('/paket/osp/option', [
        'as' => 'api.company.paketberlangganan.option',
        'uses' => 'PaketBerlanggananController@option',
    ]);
    $router->post('/paket/store', [
        'as' => 'api.company.paketberlangganan.store',
        'uses' => 'PaketBerlanggananController@store',
    ]);
    $router->get('/paket/{paketberlangganan}/find', [
        'as' => 'api.company.paketberlangganan.find',
        'uses' => 'PaketBerlanggananController@find',
    ]);
    $router->post('/paket/{paketberlangganan}/update', [
        'as' => 'api.company.paketberlangganan.update',
        'uses' => 'PaketBerlanggananController@update',
    ]);

    $router->delete('/paket/{paketberlangganan}', [
        'as' => 'api.company.paketberlangganan.delete',
        'uses' => 'PaketBerlanggananController@destroy',
    ]);

    $router->post('/paket/listPaket/{company}', [
        'as' => 'api.company.paketberlangganan.list',
        'uses' => 'PaketBerlanggananController@list',
    ]);

    $router->get('/paket/wilayah/{wilayah}', [
        'as' => 'api.company.paketberlangganan.wilayah.list',
        'uses' => 'PaketBerlanggananController@list_paket_wilayah'
    ]);


    //Route Paket For ISP
    // $router->get('/paket/isp', [
    //     'as' => 'api.company.paketforisp.index',
    //     'uses' => 'PaketForIspController@index'
    // ]);
    // $router->get('/paket/isp.option', [
    //     'as' => 'api.company.paketforisp.option',
    //     'uses' => 'PaketForIspController@option'
    // ]);
    // $router->post('/paket/isp/store', [
    //     'as' => 'api.company.paketforisp.store',
    //     'uses' => 'PaketForIspController@store'
    // ]);

    //Route Diskon Pelanggan
    $router->post('/paket/diskon/list/{paket_id}', [
        'as' => 'api.company.paketberlangganan.diskon',
        'uses' => 'DiskonPaketBerlanggananController@list',
    ]);
    $router->get('/paket/diskon/{diskon}', [
        'as' => 'api.company.diskonpaketberlangganan.index',
        'uses' => 'DiskonPaketBerlanggananController@index',
    ]);
    $router->post('/paket/diskon/{diskon}/store', [
        'as' => 'api.company.diskonpaketberlangganan.store',
        'uses' => 'DiskonPaketBerlanggananController@store',
    ]);
    $router->get('/paket/diskon/{diskon}/find', [
        'as' => 'api.company.diskonpaketberlangganan.find',
        'uses' => 'DiskonPaketBerlanggananController@find',
    ]);
    $router->post('/paket/diskon/{diskon}/update', [
        'as' => 'api.company.diskonpaketberlangganan.update',
        'uses' => 'DiskonPaketBerlanggananController@update',
    ]);
    $router->delete('/paket/diskon/{diskon}', [
        'as' => 'api.company.diskonpaketberlangganan.deleted',
        'uses' => 'DiskonPaketBerlanggananController@destroy',
    ]);

    $router->get('/biaya-kabel', [
        'as' => 'api.company.biayakabel.index',
        'uses' => 'BiayaKabelController@index',
        'middleware' => 'token-can:company.biaya_kabel.index'
    ]);

    $router->get('/biaya-kabel-presale', [
        'as' => 'api.company.biayakabel.index.presale',
        'uses' => 'BiayaKabelController@getBiayaKabel',
    ]);

    $router->post('/biaya-kabel/update/{biaya_kabel}', [
        'as' => 'api.company.biayakabel.update',
        'uses' => 'BiayaKabelController@update',
        'middleware' => 'token-can:company.biaya_kabel.edit'
    ]);

    $router->get('/range-biaya-kabel/find-new', [
        'as' => 'api.company.biayakabel.range.find-new',
        'uses' => 'BiayaKabelController@findNew',
        'middleware' => 'token-can:company.biaya_kabel.index'
    ]);

    $router->get('/range-biaya-kabel', [
        'as' => 'api.company.biayakabel.range.index',
        'uses' => 'BiayaKabelController@indexRangeBiayaKabel',
        'middleware' => 'token-can:company.biaya_kabel.index'
    ]);

    $router->post('/range-biaya-kabel/create', [
        'as' => 'api.company.biayakabel.range.create',
        'uses' => 'BiayaKabelController@create',
        'middleware' => 'token-can:company.biaya_kabel.create'
    ]);


    $router->get('/range-biaya-kabel/{range_biaya_kabel}/find', [
        'as' => 'api.company.biayakabel.range.find',
        'uses' => 'BiayaKabelController@find',
        'middleware' => 'token-can:company.biaya_kabel.index'
    ]);

    $router->post('/range-biaya-kabel/update/{range_biaya_kabel}', [
        'as' => 'api.company.biayakabel.range.update',
        'uses' => 'BiayaKabelController@updateRangeBiayaKabel',
        'middleware' => 'token-can:company.biaya_kabel.edit'
    ]);



    $router->delete('/range-biaya-kabel/{range_biaya_kabel}', [
        'as' => 'api.company.biayakabel.range.delete',
        'uses' => 'BiayaKabelController@delete',
        'middleware' => 'token-can:company.biaya_kabel.destroy'
    ]);

    $router->post('/slot-instalasi', [
        'as' => 'api.company.slotinstalasi.index',
        'uses' => 'CompanyController@slotInstalasi',
        'middleware' => 'token-can:company.slot_instalasi.index'
    ]);

    $router->put('/slot-instalasi', [
        'as' => 'api.company.slotinstalasi.update',
        'uses' => 'CompanyController@updateOrCreateSlot',
        'middleware' => 'token-can:company.slot_instalasi.edit'
    ]);

    $router->get('/slot-instalasi/get-range-time', [
        'as' => 'api.company.slotinstalasi.get-range-time',
        'uses' => 'CompanyController@getRangeTime',
        // 'middleware' => 'token-can:company.slot_instalasi.index'
    ]);

    $router->delete('/slot-instalasi/{slot}', [
        'as' => 'api.company.slotinstalasi.delete',
        'uses' => 'CompanyController@deleteSlot',
        'middleware' => 'token-can:company.slot_instalasi.edit'
    ]);




    // Rekening   
    $router->post('company/rekening/{company}/list', [
        'as' => 'api.company.rekening.list',
        'uses' => 'RekeningController@list',
        'middleware' => 'token-can:company.companies.detail'
    ]);
    $router->post('company/rekening/{rekening}/update', [
        'as' => 'api.company.rekening.submit',
        'uses' => 'RekeningController@submit',
        'middleware' => 'token-can:company.companies.detail'
    ]);

    $router->post('company/rekening/create', [
        'as' => 'api.company.rekening.create',
        'uses' => 'RekeningController@submit',
        'middleware' => 'token-can:company.companies.detail'
    ]);

    $router->delete('company/rekening/{rekening}', [
        'as' => 'api.company.rekening.destroy',
        'uses' => 'RekeningController@destroy',
        'middleware' => 'token-can:company.companies.detail'
    ]);
    $router->get('company/rekening/pagination', [
        'as' => 'api.company.rekening.pagination',
        'uses' => 'RekeningController@pagination',
        'middleware' => 'token-can:company.companies.detail'
    ]);
});
