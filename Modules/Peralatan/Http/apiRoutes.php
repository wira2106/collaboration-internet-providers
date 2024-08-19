<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/peralatan', 'middleware' =>  ['api.token', 'auth.admin']], function (Router $router) {
    $router->group(['prefix' => '/alat'], function (Router $router) {
        $router->get('/', [
            'as' => 'api.peralatan.alat.index',
            'uses' => 'AlatController@index'
        ]);
        $router->post('/store', [
            'as' => 'api.peralatan.alat.store',
            'uses' => 'AlatController@store'
        ]);
        $router->get('/{alat}/find', [
            'as' => 'api.peralatan.alat.find',
            'uses' => 'AlatController@find'
        ]);
        $router->post('/{alat}/update', [
            'as' => 'api.peralatan.alat.update',
            'uses' => 'AlatController@update'
        ]);
    });
    $router->group(['prefix' => '/barang'], function (Router $router) {
        $router->get('/', [
            'as' => 'api.peralatan.barang.index',
            'uses' => 'BarangController@index'
        ]);
        $router->post('/store', [
            'as' => 'api.peralatan.barang.store',
            'uses' => 'BarangController@store'
        ]);
        $router->get('/{barang}/find', [
            'as' => 'api.peralatan.barang.find',
            'uses' => 'BarangController@find'
        ]);
        $router->post('/{barang}/update', [
            'as' => 'api.peralatan.barang.update',
            'uses' => 'BarangController@update'
        ]);
        $router->delete('/{barang}', [
            'as' => 'api.peralatan.barang.destroy',
            'uses' => 'BarangController@destroy'
        ]);
    });
    $router->group(['prefix' => '/perangkat'], function (Router $router) {
        $router->get('/', [
            'as' => 'api.peralatan.perangkat.index',
            'uses' => 'PerangkatController@index'
        ]);
        $router->post('/store', [
            'as' => 'api.peralatan.perangkat.store',
            'uses' => 'PerangkatController@store'
        ]);
        $router->get('/{perangkat}/find', [
            'as' => 'api.peralatan.perangkat.find',
            'uses' => 'PerangkatController@find'
        ]);
        $router->post('/{perangkat}/update', [
            'as' => 'api.peralatan.perangkat.update',
            'uses' => 'PerangkatController@update'
        ]);
        $router->delete('/{perangkat}', [
            'as' => 'api.peralatan.perangkat.destroy',
            'uses' => 'PerangkatController@destroy'
        ]);
    });
});
