<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => '/pelanggan'], function (Router $router) {
    $router->get('/', [
        'as' => 'admin.pelanggan.pelanggans.index',
        'uses' => 'PelangganController@index',
        'middleware' => 'can:pelanggan.pelanggans.index'
    ]);

    $router->get('/form/step', [
        'as' => 'admin.pelanggan.form.step',
        'uses' => 'PelangganController@steps',
    ]);

    $router->get('/form/detail/{pelanggan_id}', [
        'as' => 'admin.pelanggan.form.detail',
        'uses' => 'PelangganController@detail',
    ]);
});
