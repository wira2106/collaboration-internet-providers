<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => '/saldo'], function (Router $router) {
    $router->bind('topup', function ($id) {
        return app('Modules\Saldo\Repositories\topupRepository')->find($id);
    });
    $router->get('topups', [
        'as' => 'admin.saldo.topup.index',
        'uses' => 'TopupController@index',
        'middleware' => 'can:saldo.topups'
    ]);
    $router->get('topups/create', [
        'as' => 'admin.saldo.topup.create',
        'uses' => 'TopupController@create',
        'middleware' => 'can:saldo.topups'
    ]);
    $router->post('topups', [
        'as' => 'admin.saldo.topup.store',
        'uses' => 'TopupController@store',
        'middleware' => 'can:saldo.topups'
    ]);
    $router->get('topups/{topup}/edit', [
        'as' => 'admin.saldo.topup.edit',
        'uses' => 'TopupController@edit',
        'middleware' => 'can:saldo.topups'
    ]);
    $router->put('topups/{topup}', [
        'as' => 'admin.saldo.topup.update',
        'uses' => 'TopupController@update',
        'middleware' => 'can:saldo.topups'
    ]);
    $router->delete('topups/{topup}', [
        'as' => 'admin.saldo.topup.destroy',
        'uses' => 'TopupController@destroy',
        'middleware' => 'can:saldo.topups'
    ]);
    $router->bind('withdraw', function ($id) {
        return app('Modules\Saldo\Repositories\withdrawRepository')->find($id);
    });
    $router->get('withdraws', [
        'as' => 'admin.saldo.withdraw.index',
        'uses' => 'WithdrawController@index',
        'middleware' => 'can:saldo.withdraws'
    ]);
    $router->get('withdraws/create', [
        'as' => 'admin.saldo.withdraw.create',
        'uses' => 'WithdrawController@create',
        'middleware' => 'can:saldo.withdraws'
    ]);
    $router->post('withdraws', [
        'as' => 'admin.saldo.withdraw.store',
        'uses' => 'WithdrawController@store',
        'middleware' => 'can:saldo.withdraws'
    ]);
    $router->get('withdraws/{withdraw}/edit', [
        'as' => 'admin.saldo.withdraw.edit',
        'uses' => 'WithdrawController@edit',
        'middleware' => 'can:saldo.withdraws'
    ]);
    $router->put('withdraws/{withdraw}', [
        'as' => 'admin.saldo.withdraw.update',
        'uses' => 'WithdrawController@update',
        'middleware' => 'can:saldo.withdraws'
    ]);
    $router->delete('withdraws/{withdraw}', [
        'as' => 'admin.saldo.withdraw.destroy',
        'uses' => 'WithdrawController@destroy',
        'middleware' => 'can:saldo.withdraws'
    ]);

    // mutasi saldo
    $router->get('mutasi', [
        'as' => 'admin.saldo.mutasi',
        'uses' => 'mutasiController@index',
        'middleware' => 'can:saldo.mutasi'
    ]);
});
