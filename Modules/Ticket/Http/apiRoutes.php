<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/ticket', 'middleware' => ['api.token', 'auth.admin']], function (Router $router) {

    $router->group(['prefix'=>'/sla', 'middleware'=>['api.token','auth.admin']],function (Router $router){
        //route untuk ticket SLA
        $router->get('/data',[
            'as' => 'admin.api.ticket.data',
            'uses' => 'TicketSlaController@data',
            'middleware' => 'can:ticket.tickets.index'
        ]);
        $router->post('/create', [
            'as' => 'admin.api.ticket.create',
            'uses' => 'TicketSlaController@create',
            'middleware' => 'can:ticket.tickets.create'
        ]);

        $router->get('/session/detail/{id}',[
            'as' => 'admin.api.ticket.session.detail',
            'uses' => 'TicketSlaController@detailSession',
            'middleware' => 'can:ticket.tickets.edit'
        ]);

        $router->get('/session/messages/get/{ticket_id}',[
            'as' => 'admin.api.ticket.session.message.get',
            'uses' => 'TicketSlaController@getMessages',
        ]);

        $router->post('/session/message/send/{ticket_id}',[
            'as' => 'admin.api.ticket.session.message.send',
            'uses' => 'TicketSlaController@sendMessages',
            'middleware' => 'can:ticket.tickets.edit'
        ]);

        $router->post('/session/time/update',[
            'as' => 'admin.api.ticket.session.time.update',
            'uses' => 'TicketSlaController@updateTimeSla',
            'middleware' => 'can:ticket.tickets.edit'
        ]);

        $router->post('/session/time/approve/{ticket_id}',[
            'as' => 'admin.api.ticket.session.time.approve',
            'uses' => 'TicketSlaController@approveTimeSla',
            'middleware' => 'can:ticket.tickets.edit'
        ]);

        $router->post('/session/close/{ticket_id}',[
            'as' => 'admin.api.ticket.session.close',
            'uses' => 'TicketSlaController@closeTicket',
            'middleware' => 'can:ticket.tickets.edit'
        ]);

        $router->delete('/session/destroy/{id}', [
            'as' => 'admin.api.ticket.destroy',
            'uses' => 'TicketSlaController@destroy',
            'middleware' => 'token-can:ticket.tickets.destroy',
        ]);
    });


    //Route untuk ticket suspend
    $router->group(['prefix'=>'/suspend', 'middleware'=>['api.token','auth.admin']],function (Router $router){
        //route untuk ticket SLA
        $router->get('/data',[
            'as' => 'admin.api.ticket.suspend.data',
            'uses' => 'TicketSuspendController@data',
            'middleware' => 'can:ticket.tickets.index'
        ]);
        
        $router->post('/create', [
            'as' => 'admin.api.ticket.suspend.create',
            'uses' => 'TicketSuspendController@create',
            'middleware' => 'can:ticket.tickets.create'
        ]);

        $router->get('/create/{id}', [
            'as' => 'admin.api.ticket.suspend.create.with.params',
            'uses' => 'TicketSuspendController@createWithParams',
            'middleware' => 'can:ticket.tickets.create'
        ]);

        $router->get('/session/detail/{id}',[
            'as' => 'admin.api.ticket.suspend.session.detail',
            'uses' => 'TicketSuspendController@detailSession',
            'middleware' => 'can:ticket.tickets.edit'
        ]);

        $router->get('/session/messages/get/{ticket_id}',[
            'as' => 'admin.api.ticket.suspend.session.message.get',
            'uses' => 'TicketSuspendController@getMessages',
        ]);

        $router->post('/session/message/send/{ticket_id}',[
            'as' => 'admin.api.ticket.suspend.session.message.send',
            'uses' => 'TicketSuspendController@sendMessages',
            'middleware' => 'can:ticket.tickets.edit'
        ]);

        $router->post('/session/time/update',[
            'as' => 'admin.api.ticket.suspend.session.time.update',
            'uses' => 'TicketSuspendController@updateTimeSuspend',
            'middleware' => 'can:ticket.tickets.edit'
        ]);

        $router->post('/session/time/approve/{ticket_id}',[
            'as' => 'admin.api.ticket.suspend.session.time.approve',
            'uses' => 'TicketSuspendController@approveTimeSuspend',
            'middleware' => 'can:ticket.tickets.edit'
        ]);

        $router->post('/session/close/{ticket_id}',[
            'as' => 'admin.api.ticket.suspend.session.close',
            'uses' => 'TicketSuspendController@closeTicket',
            'middleware' => 'can:ticket.tickets.edit'
        ]);

        $router->delete('/session/destroy/{id}', [
            'as' => 'admin.api.ticket.suspend.destroy',
            'uses' => 'TicketSuspendController@destroy',
            'middleware' => 'token-can:ticket.tickets.destroy',
        ]);
    });


});