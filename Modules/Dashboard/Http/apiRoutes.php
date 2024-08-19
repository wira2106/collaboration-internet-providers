<?php

use Illuminate\Routing\Router;

$router->group(['prefix' =>'/', 'middleware' => ['api.token', 'auth.admin']], function (Router $router) {
    $router->get('/dashboard/widget', [
        'as' => 'api.dashboard.widget',
        'uses' => 'DashboardController@widget'
    ]);

});