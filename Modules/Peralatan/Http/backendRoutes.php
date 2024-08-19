<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => '/peralatan'], function (Router $router) {
    $router->bind('alat', function ($id) {
        return app('Modules\Peralatan\Repositories\AlatRepository')->find($id);
    });
    $router->get('alats', [
        'as' => 'admin.peralatan.alat.index',
        'uses' => 'AlatController@index',
        'middleware' => 'can:peralatan.alats.index'
    ]);
    $router->get('alats/create', [
        'as' => 'admin.peralatan.alat.create',
        'uses' => 'AlatController@create',
        'middleware' => 'can:peralatan.alats.create'
    ]);
    $router->post('alats', [
        'as' => 'admin.peralatan.alat.store',
        'uses' => 'AlatController@store',
        'middleware' => 'can:peralatan.alats.create'
    ]);
    $router->get('alats/{alat}/edit', [
        'as' => 'admin.peralatan.alat.edit',
        'uses' => 'AlatController@edit',
        'middleware' => 'can:peralatan.alats.edit'
    ]);
    $router->post('alats/{alat}', [
        'as' => 'admin.peralatan.alat.update',
        'uses' => 'AlatController@update',
        'middleware' => 'can:peralatan.alats.edit'
    ]);
    $router->delete('alats/{alat}', [
        'as' => 'admin.peralatan.alat.destroy',
        'uses' => 'AlatController@destroy',
        'middleware' => 'can:peralatan.alats.destroy'
    ]);
    $router->bind('perangkat', function ($id) {
        return app('Modules\Peralatan\Repositories\PerangkatRepository')->find($id);
    });
    $router->get('perangkats', [
        'as' => 'admin.peralatan.perangkat.index',
        'uses' => 'PerangkatController@index',
        'middleware' => 'can:peralatan.perangkats.index'
    ]);
    $router->get('perangkats/create', [
        'as' => 'admin.peralatan.perangkat.create',
        'uses' => 'PerangkatController@create',
        'middleware' => 'can:peralatan.perangkats.create'
    ]);
    $router->post('perangkats', [
        'as' => 'admin.peralatan.perangkat.store',
        'uses' => 'PerangkatController@store',
        'middleware' => 'can:peralatan.perangkats.create'
    ]);
    $router->get('perangkats/{perangkat}/edit', [
        'as' => 'admin.peralatan.perangkat.edit',
        'uses' => 'PerangkatController@edit',
        'middleware' => 'can:peralatan.perangkats.edit'
    ]);
    $router->put('perangkats/{perangkat}', [
        'as' => 'admin.peralatan.perangkat.update',
        'uses' => 'PerangkatController@update',
        'middleware' => 'can:peralatan.perangkats.edit'
    ]);
    $router->delete('perangkats/{perangkat}', [
        'as' => 'admin.peralatan.perangkat.destroy',
        'uses' => 'PerangkatController@destroy',
        'middleware' => 'can:peralatan.perangkats.destroy'
    ]);

    $router->bind('barang', function ($id) {
        return app('Modules\Peralatan\Repositories\BarangRepository')->find($id);
    });
    $router->get('barangs', [
        'as' => 'admin.peralatan.barang.index',
        'uses' => 'BarangController@index',
        'middleware' => 'can:peralatan.barangs.index'
    ]);
    $router->get('barangs/create', [
        'as' => 'admin.peralatan.barang.create',
        'uses' => 'BarangController@create',
        'middleware' => 'can:peralatan.barangs.create'
    ]);
    $router->post('barangs', [
        'as' => 'admin.peralatan.barang.store',
        'uses' => 'BarangController@store',
        'middleware' => 'can:peralatan.barangs.create'
    ]);
    $router->get('barangs/{barang}/edit', [
        'as' => 'admin.peralatan.barang.edit',
        'uses' => 'BarangController@edit',
        'middleware' => 'can:peralatan.barangs.edit'
    ]);
    $router->put('barangs/{barang}', [
        'as' => 'admin.peralatan.barang.update',
        'uses' => 'BarangController@update',
        'middleware' => 'can:peralatan.barangs.edit'
    ]);
    $router->delete('barangs/{barang}', [
        'as' => 'admin.peralatan.barang.destroy',
        'uses' => 'BarangController@destroy',
        'middleware' => 'can:peralatan.barangs.destroy'
    ]);

    $router->bind('peralatan', function ($id) {
        return app('Modules\Peralatan\Repositories\PeralatanRepository')->find($id);
    });
    $router->get('peralatans', [
        'as' => 'admin.peralatan.peralatan.index',
        'uses' => 'PeralatanController@index',
        'middleware' => 'can:peralatan.peralatans.index'
    ]);
    $router->get('peralatans/create', [
        'as' => 'admin.peralatan.peralatan.create',
        'uses' => 'PeralatanController@create',
        'middleware' => 'can:peralatan.peralatans.create'
    ]);
    $router->post('peralatans', [
        'as' => 'admin.peralatan.peralatan.store',
        'uses' => 'PeralatanController@store',
        'middleware' => 'can:peralatan.peralatans.create'
    ]);
    $router->get('peralatans/{peralatan}/edit', [
        'as' => 'admin.peralatan.peralatan.edit',
        'uses' => 'PeralatanController@edit',
        'middleware' => 'can:peralatan.peralatans.edit'
    ]);
    $router->put('peralatans/{peralatan}', [
        'as' => 'admin.peralatan.peralatan.update',
        'uses' => 'PeralatanController@update',
        'middleware' => 'can:peralatan.peralatans.edit'
    ]);
    $router->delete('peralatans/{peralatan}', [
        'as' => 'admin.peralatan.peralatan.destroy',
        'uses' => 'PeralatanController@destroy',
        'middleware' => 'can:peralatan.peralatans.destroy'
    ]);
    // as gunanya untuk nama lain dari route name
    // uses digunakan untuk menagmbil method pada controller
    //  middleware digunakan untuk memberikan hak akses 

    $router->get('alat/{alat}/find', [
        'as' => 'admin.peralatan.alat.find',
        'uses' => 'alatController@find'
    ]);
    $router->get('barang/view', [
        'as' => 'admin.peralatan.barang.view',
        'uses' => 'BarangController@viewBarang'
    ]);
    $router->get('perangkat/view', [
        'as' => 'admin.peralatan.perangkat.view',
        'uses' => 'PerangkatController@viewPerangkat'
    ]);
    // append




});
