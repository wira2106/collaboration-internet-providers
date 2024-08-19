<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' => '/mail'], function (Router $router) {
    $router->get('/send', [
        'uses' => 'MailController@sendMail'
    ]);
    $router->get('/testing', [
        'uses' => 'MailController@test'
    ]);
});
