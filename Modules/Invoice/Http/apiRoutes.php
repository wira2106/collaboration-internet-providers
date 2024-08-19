<?php

use Illuminate\Routing\Router;

$router->group(['prefix' =>'/invoice', 'middleware' => ['api.token', 'auth.admin']], function (Router $router) {
    $router->bind('invoice', function ($id) {
        return app('Modules\Invoice\Repositories\InvoiceRepository')->find($id);
    });
    $router->get('invoices', [
        'as' => 'api.invoice.invoices.index',
        'uses' => 'InvoiceController@index'
    ]);
    $router->get('invoices/{invoice}', [
        'as' => 'api.invoice.invoices.show',
        'uses' => 'InvoiceController@show'
    ]);

    $router->get('invoices/bayar/{invoice}', [
        'as' => 'api.invoice.invoices.bayar',
        'uses' => 'InvoiceController@bayar'
    ]);


    $router->get('invoices/pelanggan/list/', [
        'as' => 'api.invoice.pelanggan.list',
        'uses' => 'InvoiceController@listInvoicePelanggan'
    ]);

    $router->get('invoices/pelanggan/jumlah_pending/{invoice}', [
        'as' => 'api.invoice.pelanggan.jumlah.pending',
        'uses' => 'InvoiceController@getJumlahPendingInvoicePelanggan'
    ]);
        
    $router->post('/invoices/monthly-charge', [
        'as' => 'api.invoice.invoices.monthly-charge',
        'uses' => 'InvoiceController@monthly_charge'
    ]);

    $router->post('/invoices/hitung-pengembalian', [
        'as' => 'api.invoice.invoices.hitung-pengembalian',
        'uses' => 'InvoiceController@hitung_pengembalian',
    ]);
});