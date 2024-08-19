<?php

use Illuminate\Routing\Router;


/** @var Router $router */
$router->group(['prefix' => '/', 'middleware' => ['api.token', 'auth.admin']], function (Router $router) {
    $router->get('saldo', [
        'as' => 'api.saldo.saldo',
        'uses' => 'SaldoController@getSaldo'
    ]);
    $router->get('topups/rekening/openaccess', [
        'as' => 'api.saldo.topup.rekening',
        'uses' => 'TopupController@getRekening'
    ]);
    $router->get('topups/index', [
        'as' => 'api.saldo.topup.index',
        'uses' => 'TopupController@index'
    ]);
    $router->post('topups/store', [
        'as' => 'api.saldo.topup.store',
        'uses' => 'TopupController@store'
    ]);
    $router->post('topups/update', [
        'as' => 'api.saldo.topup.update',
        'uses' => 'TopupController@update'
    ]);
    $router->post('topups/briva/create', [
        'as' => 'api.saldo.topup.briva.create',
        'uses' => 'TopupController@createBriva'
    ]);
    $router->post('topups/briva/delete', [
        'as' => 'api.saldo.topup.briva.delete',
        'uses' => 'TopupController@deleteBriva'
    ]);
    

    // withdraw
    $router->get('withdraws/rekening/openaccess', [
        'as' => 'api.saldo.withdraw.rekening',
        'uses' => 'WithdrawController@getRekening'
    ]);
    $router->get('withdraws/index', [
        'as' => 'api.saldo.withdraw.index',
        'uses' => 'WithdrawController@index'
    ]);
    $router->post('withdraws/store', [
        'as' => 'api.saldo.withdraw.store',
        'uses' => 'WithdrawController@store'
    ]);
    $router->post('withdraws/update', [
        'as' => 'api.saldo.withdraw.update',
        'uses' => 'WithdrawController@update'
    ]);

    // mutasi
    $router->get('mutasi/pagination', [
        'as' => 'api.saldo.mutasi.pagination',
        'uses' => 'mutasiController@pagination',
        'middleware' => 'can:saldo.mutasi'
    ]);

    $router->post('mutasi/getsaldo', [
        'as' => 'api.saldo.mutasi.getsaldo',
        'uses' => 'mutasiController@getSaldo',
        'middleware' => 'can:saldo.mutasi'
    ]);
});


// settlement API
$router->post('saldo/topup/briva/settlement', [
    'as' => 'api.saldo.topup.briva.settlement',
    'uses' => 'TopupController@settlementBriva',
]);
// Expired API
$router->post('saldo/topup/briva/expired', [
    'as' => 'api.saldo.topup.briva.expired',
    'uses' => 'TopupController@expiredBriva',
]);
