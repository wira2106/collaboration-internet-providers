<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/ticket'], function (Router $router) {

    //router for sla
    $router->group(['prefix'=>'/sla'], function(Router $router){
        $router->get('/', [
            'as' => 'admin.ticket.sla.index',
            'uses' => 'TicketController@index',
            'middleware' => 'can:ticket.tickets.index'
        ]);
    
        $router->get('/create', [
            'as' => 'admin.ticket.sla.create',
            'uses' => 'TicketController@create',
            'middleware' => 'can:ticket.tickets.create'
        ]);
    
        $router->get('session/{id}', [
            'as' => 'admin.ticket.sla.session',
            'uses' => 'TicketController@session',
            'middleware' => 'can:ticket.tickets.edit'
        ]);
    });

    //router for suspend
    $router->group(['prefix'=>'/suspend'], function(Router $router){
        $router->get('/', [
            'as' => 'admin.ticket.suspend.index',
            'uses' => 'TicketController@index',
            'middleware' => 'can:ticket.tickets.index'
        ]);
    
        $router->get('/create', [
            'as' => 'admin.ticket.suspend.create',
            'uses' => 'TicketController@create',
            'middleware' => 'can:ticket.tickets.create'
        ]);

        $router->get('/create/{id}', [
            'as' => 'admin.ticket.suspend.create',
            'uses' => 'TicketController@create',
            'middleware' => 'can:ticket.tickets.create'
        ]);
    
        $router->get('session/{id}', [
            'as' => 'admin.ticket.suspend.session',
            'uses' => 'TicketController@session',
            'middleware' => 'can:ticket.tickets.edit'
        ]);
    });

    
});