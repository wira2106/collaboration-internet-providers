<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/presale'], function (Router $router) {
    $router->bind('presale', function ($id) {
        return app('Modules\Presale\Repositories\presaleRepository')->find($id);
    });
    $router->get('presales', [
        'as' => 'admin.presale.presale.index',
        'uses' => 'presaleController@index',
        'middleware' => 'can:presale.presales.index'
    ]);
    $router->get('presales/create', [
        'as' => 'admin.presale.presale.create',
        'uses' => 'presaleController@create',
        'middleware' => 'can:presale.presales.create'
    ]);
    $router->post('presales', [
        'as' => 'admin.presale.presale.store',
        'uses' => 'presaleController@store',
        'middleware' => 'can:presale.presales.create'
    ]);
    $router->get('presales/{presale}/edit', [
        'as' => 'admin.presale.presale.edit',
        'uses' => 'presaleController@edit',
        'middleware' => 'can:presale.presales.edit'
    ]);
    $router->put('presales/{presale}', [
        'as' => 'admin.presale.presale.update',
        'uses' => 'presaleController@update',
        'middleware' => 'can:presale.presales.edit'
    ]);
    $router->delete('presales/{presale}', [
        'as' => 'admin.presale.presale.destroy',
        'uses' => 'presaleController@destroy',
        'middleware' => 'can:presale.presales.destroy'
    ]);
    $router->bind('endpoint', function ($id) {
        return app('Modules\Presale\Repositories\endpointRepository')->find($id);
    });
    $router->get('endpoints', [
        'as' => 'admin.presale.endpoint.index',
        'uses' => 'endpointController@index',
        'middleware' => 'can:presale.endpoints.index'
    ]);
    $router->get('endpoints/create', [
        'as' => 'admin.presale.endpoint.create',
        'uses' => 'endpointController@create',
        'middleware' => 'can:presale.endpoints.create'
    ]);
    $router->post('endpoints', [
        'as' => 'admin.presale.endpoint.store',
        'uses' => 'endpointController@store',
        'middleware' => 'can:presale.endpoints.create'
    ]);
    $router->get('endpoints/{endpoint}/edit', [
        'as' => 'admin.presale.endpoint.edit',
        'uses' => 'endpointController@edit',
        'middleware' => 'can:presale.endpoints.edit'
    ]);
    $router->put('endpoints/{endpoint}', [
        'as' => 'admin.presale.endpoint.update',
        'uses' => 'endpointController@update',
        'middleware' => 'can:presale.endpoints.edit'
    ]);
    $router->delete('endpoints/{endpoint}', [
        'as' => 'admin.presale.endpoint.destroy',
        'uses' => 'endpointController@destroy',
        'middleware' => 'can:presale.endpoints.destroy'
    ]);

});

