<?php

use Illuminate\Routing\Router;
$router->group(['prefix' => '/presale', 'middleware' => ['api.token', 'auth.admin']], function(Router $router){

    $router->post('/region', [
        'as' => 'api.presale.region.index',
        'uses' => 'presaleController@region',
        'middleware' => 'token-can:presale.presales.index'
    ]);
        
    $router->get('/endpoint', [
        'as' => 'api.presale.endpoint.index',
        'uses' => 'endpointController@index',
        'middleware' => 'token-can:presale.endpoints.index'
    ]);

    $router->post('/endpoint/validation-name', [
        'as' => 'api.presale.endpoint.check-name', 
        'uses' => 'endpointController@validationName',
        'middleware' => 'token-can:presale.endpoints.create'
    ]);

    $router->post('/endpoint/store', [
        'as' => 'api.presale.endpoint.store',
        'uses' => 'endpointController@updateOrCreate',
        'middleware' => 'token-can:presale.endpoints.create'
    ]);

    $router->post('/endpoint/bayar/{wilayah}', [
        'as' => 'api.presale.endpoint.bayar',
        'uses' => 'endpointController@bayar'
    ]);

    $router->post('/endpoint/location', [
        'as' => 'api.presale.endpoint.location',
        'uses' => 'endpointController@getEndpointLocation',
        'middleware' => 'token-can:presale.endpoints.index'
    ]);

    $router->post('/endpoint/{endpoint}', [
        'as' => 'api.presale.endpoint.detail',
        'uses' => 'endpointController@getEndpointDetail',
        'middleware' => 'token-can:presale.endpoints.index'
    ]);
    $router->post('/endpoint/html/{endpoint}', [
        'as' => 'api.presale.endpoint.detail.html',
        'uses' => 'endpointController@getEndpointDetailHtml',
        'middleware' => 'token-can:presale.endpoints.index'
    ]);

    $router->post('/endpoint/update/{endpoint}', [
        'as' => 'api.presale.endpoint.update',
        'uses' => 'endpointController@updateOrCreate',
        'middleware' => 'token-can:presale.endpoints.edit'
    ]);

    $router->delete('/endpoint/{endpoint}', [
        'as' => 'api.presale.endpoint.delete',
        'uses' => 'endpointController@delete',
        'middleware' => 'token-can:presale.endpoints.delete'
    ]);

    $router->get('/endpoint/routes/{endpoint}', [
        'as' => 'api.presale.endpoint.routes',
        'uses' => 'endpointController@getRoutes',
        'middleware' => 'token-can:presale.endpoints.index'
    ]);

    $router->post('/presales', [
        'as' => 'api.presale.presales.index',
        'uses' => 'presaleController@index',
        'middleware' => 'token-can:presale.presales.index'
    ]);

    $router->get('/presales/list', [
        'as' => 'api.presale.presales.list',
        'uses' => 'presaleController@list',
        'middleware' => 'token-can:presale.presales.index'
    ]);

    $router->post('/presales/location', [
        'as' => 'api.presale.presales.location',
        'uses' => 'presaleController@getPresalesLocation',
        'middleware' => 'token-can:presale.presales.index'
    ]);
    
    $router->post('/presales/html/{presale}', [
        'as' => 'api.presale.presales.html',
        'uses' => 'presaleController@getPresaleHtml',
        'middleware' => 'token-can:presale.presales.index'
    ]);
    
    $router->post('/presale/confirmation/{wilayah}', [
        'as' => 'api.presale.presales.email_confirmation',
        'uses' => 'presaleController@email_confirmation',
        'middleware' => 'token-can:presale.presales.confirm'
    ]);

    $router->post('/presale/store', [
        'as' => 'api.presale.presales.store',
        'uses' => 'presaleController@updateOrCreate',
        'middleware' => 'token-can:presale.presales.create'
    ]);

    $router->post('/presale/update/{presale}', [
        'as' => 'api.presale.presales.update',
        'uses' => 'presaleController@updateOrCreate',
        'middleware' => 'token-can:presale.presales.edit'
    ]);

    $router->post('/presale/konfirmasi', [
        'as' => 'api.presale.presales.konfirmasi',
        'uses' => 'presaleController@konfirmasi',
    ]);

    $router->delete('/presale/{presale}', [
        'as' => 'api.presale.presales.delete',
        'uses' => 'presaleController@delete',
        'middleware' => 'token-can:presale.presales.delete'
    ]);

    $router->post('/range-biaya-kabel', [
        'as' => 'api.presale.biayakabel.index',
        'uses' => 'presaleController@biayakabel',
        'middleware' => 'token-can:presale.presales.index'
    ]);

    $router->get('/class', [
        'as' => 'api.presale.class.index',
        'uses' => 'presaleController@presaleClass',
        'middleware' => 'token-can:presale.presales.index'
    ]);

    $router->get('/status', [
        'as' => 'api.presale.status.index',
        'uses' => 'presaleController@presaleStatus',
        'middleware' => 'token-can:presale.presales.index'
    ]);

    $router->post('presales/location/{presale}', [
        'as' => 'api.presale.presale.location.update',
        'uses' => 'presaleController@presaleUpdateLocation',
        'middleware' => 'token-can:presale.presales.index'
    ]);

    $router->get('presales/{presale}', [
        'as' => 'api.presale.presale.show',
        'uses' => 'presaleController@show',
        'middleware' => 'token-can:presale.presales.index'
    ]);

    $router->post('/presales/status/{presale}', [
        'as' => 'api.presale.presale.update.status',
        'uses' => 'presaleController@updateStatus',
        'middleware' => 'token-can:presale.presales.edit'
    ]); 

    $router->get('presale/routes/{presale}', [
        'as' => 'api.presale.presale.routes',
        'uses' => 'presaleController@getRoutes',
        'middleware' => 'token-can:presale.presales.index'
    ]);


});
