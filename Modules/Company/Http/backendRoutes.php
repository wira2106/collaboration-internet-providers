<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => '/company'], function (Router $router) {
    $router->bind('company', function ($id) {
        return app('Modules\Company\Repositories\CompanyRepository')->find($id);
    });

    $router->get('companies', [
        'as' => 'admin.company.company.index',
        'uses' => 'CompanyController@index',
        'middleware' => 'can:company.companies.index'
    ]);



    $router->get('companies/jsonListCompany', [
        'as' => 'admin.company.company.jsonListCompany',
        'uses' => 'CompanyController@jsonListCompany',
        'middleware' => 'can:company.companies.index'
    ]);

    $router->get('companies/create', [
        'as' => 'admin.company.company.create',
        'uses' => 'CompanyController@create',
        'middleware' => 'can:company.companies.create'
    ]);
    $router->post('companies', [
        'as' => 'admin.company.company.store',
        'uses' => 'CompanyController@store',
        'middleware' => 'can:company.companies.create'
    ]);
    $router->get('companies/{company}/edit', [
        'as' => 'admin.company.company.edit',
        'uses' => 'CompanyController@edit',
        'middleware' => 'can:company.companies.edit'
    ]);

    $router->put('companies/{company}', [
        'as' => 'admin.company.company.update',
        'uses' => 'CompanyController@update',
        'middleware' => 'can:company.companies.edit'
    ]);
    $router->delete('companies/{company}', [
        'as' => 'admin.company.company.destroy',
        'uses' => 'CompanyController@destroy',
        'middleware' => 'can:company.companies.destroy'
    ]);

    $router->bind('paketberlangganan', function ($id) {
        return app('Modules\Company\Repositories\PaketBerlanggananRepository')->find($id);
    });
    $router->get('paketberlangganans', [
        'as' => 'admin.company.paketberlangganan.index',
        'uses' => 'PaketBerlanggananController@index',
        'middleware' => 'can:company.paketberlangganans.index'
    ]);
    $router->get('paketberlangganans/create', [
        'as' => 'admin.company.paketberlangganan.create',
        'uses' => 'PaketBerlanggananController@create',
        'middleware' => 'can:company.paketberlangganans.create'
    ]);
    $router->post('paketberlangganans', [
        'as' => 'admin.company.paketberlangganan.store',
        'uses' => 'PaketBerlanggananController@store',
        'middleware' => 'can:company.paketberlangganans.create'
    ]);
    $router->get('paketberlangganans/{paketberlangganan}/edit', [
        'as' => 'admin.company.paketberlangganan.edit',
        'uses' => 'PaketBerlanggananController@edit',
        'middleware' => 'can:company.paketberlangganans.edit'
    ]);
    $router->put('paketberlangganans/{paketberlangganan}', [
        'as' => 'admin.company.paketberlangganan.update',
        'uses' => 'PaketBerlanggananController@update',
        'middleware' => 'can:company.paketberlangganans.edit'
    ]);
    $router->delete('paketberlangganans/{paketberlangganan}', [
        'as' => 'admin.company.paketberlangganan.destroy',
        'uses' => 'PaketBerlanggananController@destroy',
        'middleware' => 'can:company.paketberlangganans.destroy'
    ]);

    $router->get('profile', [
        'as' => 'admin.company.profile.index',
        'uses' => 'CompanyController@profile',
        'middleware' => 'can:company.companies.detail'
    ]);

    $router->get('paket/isp/create', [
        'as' => 'admin.company.paketforisp.create',
        'uses' => 'PaketBerlanggananController@createIsp',
        'middleware' => 'can:company.paketberlangganans.create'
    ]);

    // append
    $router->get('biaya-kabel', [
        'as' => 'admin.company.biayakabel.index',
        'uses' => 'BiayaKabelController@index',
        'middleware' => 'can:company.biaya_kabel.index'
    ]);

    $router->get('biaya-kabel/create', [
        'as' => 'admin.company.biaya-kabel.create',
        'uses' => 'BiayaKabelController@create',
        'middleware' => 'can:company.biaya_kabel.create'
    ]);

    $router->get('/slot-instalasi', [
        'as' => 'admin.company.slotinstalasi.index',
        'uses' => 'CompanyController@slot_instalasi',
        'middleware' => 'can:company.slot_instalasi.index'
    ]);
    // append

    

});
