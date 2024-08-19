<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/configuration'], function (Router $router) {
    $router->get('data',[
        'as' => 'admin.configuration.configuration.data',
        'uses' => 'ConfigurationController@data',
        'middleware' => 'can:configuration.configurations.index'
    ]);
    $router->get('configurations', [
        'as' => 'admin.configuration.configuration.index',
        'uses' => 'ConfigurationController@index',
        'middleware' => 'can:configuration.configurations.index'
    ]);
    $router->get('configurations/create', [
        'as' => 'admin.configuration.configuration.create',
        'uses' => 'ConfigurationController@create',
        'middleware' => 'can:configuration.configurations.create'
    ]);
    $router->post('configurations', [
        'as' => 'admin.configuration.configuration.store',
        'uses' => 'ConfigurationController@store',
        'middleware' => 'can:configuration.configurations.index'
    ]);
    $router->get('configurations/{configuration}/edit', [
        'as' => 'admin.configuration.configuration.edit',
        'uses' => 'ConfigurationController@edit',
        'middleware' => 'can:configuration.configurations.edit'
    ]);
    $router->post('configurations/{configuration}', [
        'as' => 'admin.configuration.configuration.update',
        'uses' => 'ConfigurationController@update',
        'middleware' => 'can:configuration.configurations.edit'
    ]);
    $router->delete('configurations/{configuration}', [
        'as' => 'admin.configuration.configuration.destroy',
        'uses' => 'ConfigurationController@destroy',
        'middleware' => 'can:configuration.configurations.destroy'
    ]);
});

$router->group(['prefix' =>'/bank'], function (Router $router) {
    $router->get('/',[
        'as' => 'admin.configuration.bank.index',
        'uses' => 'BankController@index',
        'middleware' => 'can:configuration.bank.index'
    ]);
});