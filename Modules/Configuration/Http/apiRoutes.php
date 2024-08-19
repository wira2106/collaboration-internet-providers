<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/bank', 'middleware' => ['api.token', 'auth.admin']], function (Router $router) {
   $router->post('/',[
        'as' => 'api.bank.list',
        'uses' => 'BankController@listBank'
   ]);
   $router->get('list',[
        'as' => 'admin.configuration.bank.list',
        'uses' => 'BankController@pagination',
        'middleware' => 'can:configuration.bank.index'
    ]);

   $router->delete('{id}',[
        'as' => 'api.configuration.bank.destroy',
        'uses' => 'BankController@destroy',
        'middleware' => 'can:configuration.bank.destroy'
   ]);
   $router->post('find/{id}',[
        'as' => 'api.configuration.bank.find',
        'uses' => 'BankController@find',
        'middleware' => 'can:configuration.bank.edit'
   ]);
   $router->post('submit',[
        'as' => 'api.configuration.bank.submit',
        'uses' => 'BankController@submit',
        'middleware' => 'can:configuration.bank.edit'
   ]);

   $router->get('configuration', [
          'as' => 'api.configuration.index',
          'uses' => 'ConfigurationController@index'
   ]);
});
