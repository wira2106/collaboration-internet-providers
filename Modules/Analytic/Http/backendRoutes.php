<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/analytic'], function (Router $router) {
    // PELANGGAN
    $router->get('pelanggan', [
        'as' => 'admin.analytic.pelanggan.index',
        'uses' => 'AnalyticController@indexPelanggan',
        'middleware' => 'can:analytic.analytics.index'
    ]);
    $router->get('pelanggan/detail/{pelanggan}', [
        'as' => 'admin.analytic.pelanggan.detail',
        'uses' => 'AnalyticController@detailPelanggan',
        'middleware' => 'can:analytic.analytics.index'
    ]);
});
