<?php

use Illuminate\Routing\Router;

/** @var Router $router */


$router->group(['prefix' => '/wilayah'], function (Router $router) {
    $router->get('/', [
        'as' => 'admin.wilayah.wilayah.index',
        'uses' => 'WilayahController@index',
        'middleware' => 'can:wilayah.wilayahs.index'
    ]);
    $router->get('/create', [
        'as' => 'admin.wilayah.wilayah.create',
        'uses' => 'WilayahController@create',
        'middleware' => 'can:wilayah.wilayahs.create'
    ]);
    $router->get('/{id}/edit', [
        'as' => 'admin.wilayah.wilayah.edit',
        'uses' => 'WilayahController@edit',
        'middleware' => 'can:wilayah.wilayahs.update'
    ]);
});

$router->group(['prefix' => '/pengajuan'], function (Router $router) {
    $router->get('/create', [
        'as' => 'admin.wilayah.pengajuan.create',
        'uses' => 'PengajuanController@create',
        'middleware' => 'can:wilayah.pengajuans.create'
    ]);
    $router->get('/', [
        'as' => 'admin.wilayah.pengajuan.index',
        'uses' => 'PengajuanController@index',
        'middleware' => 'can:wilayah.pengajuans.index'
    ]);

    $router->get('/{id}/detail', [
        'as' => 'admin.wilayah.pengajuan.detail',
        'uses' => 'PengajuanController@detail',
        'middleware' => 'can:wilayah.pengajuans.index'
    ]);

    $router->get('/mail', [
        'as' => 'admin.wilayah.pengajuan.mail',
        'uses' => 'PengajuanController@mail'
    ]);
});
