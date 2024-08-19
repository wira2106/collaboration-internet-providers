<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/invoice'], function (Router $router) {
    $router->bind('invoice', function ($id) {
        return app('Modules\Invoice\Repositories\InvoiceRepository')->find($id);
    });
    $router->get('invoices', [
        'as' => 'admin.invoice.invoices.index',
        'uses' => 'InvoiceController@index',
        'middleware' => 'can:invoice.invoices.index'
    ]);

    $router->get('invoices/{invoice}/print', [
        'as' => 'admin.invoice.invoices.print',
        'uses' => 'InvoiceController@print'
    ]);

    $router->get('invoices/create', [
        'as' => 'admin.invoice.invoice.create',
        'uses' => 'InvoiceController@create',
        'middleware' => 'can:invoice.invoices.create'
    ]);
    $router->post('invoices', [
        'as' => 'admin.invoice.invoice.store',
        'uses' => 'InvoiceController@store',
        'middleware' => 'can:invoice.invoices.create'
    ]);
    $router->get('invoices/{invoice}/detail', [
        'as' => 'admin.invoice.invoice.detail',
        'uses' => 'InvoiceController@detail',
    ]);
    $router->put('invoices/{invoice}', [
        'as' => 'admin.invoice.invoice.update',
        'uses' => 'InvoiceController@update',
        'middleware' => 'can:invoice.invoices.edit'
    ]);
    $router->delete('invoices/{invoice}', [
        'as' => 'admin.invoice.invoice.destroy',
        'uses' => 'InvoiceController@destroy',
        'middleware' => 'can:invoice.invoices.destroy'
    ]);
// append

});
