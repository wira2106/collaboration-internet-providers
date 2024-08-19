<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/analytic', 'middleware' => ['api.token', 'auth.admin']], function (Router $router) {
    
    $router->get('pelanggan/index', [
        'as' => 'admin.api.analytic.pelanggan.index',
        'uses' => 'AnalyticController@indexPelanggan',
        'middleware' => 'can:analytic.analytics.index'
    ]);

    $router->get('pelanggan/detail/{pelanggan}', [
        'as' => 'admin.api.analytic.pelanggan.detail',
        'uses' => 'AnalyticController@detailPelanggan',
        'middleware' => 'can:analytic.analytics.index'
    ]);
});
