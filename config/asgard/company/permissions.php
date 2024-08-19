<?php

return [
    'company.companies' => [
        'index' => 'company::companies.list resource',
        'create' => 'company::companies.create resource',
        'edit' => 'company::companies.edit resource',
        'destroy' => 'company::companies.destroy resource',
        'detail' => 'company::companies.detail resource'
    ],
    // 'company.staff' => [
    //     'index' => 'company::staff.list resource',
    //     'create' => 'company::staff.create resource',
    //     'edit' => 'company::staff.edit resource',
    //     'destroy' => 'company::staff.destroy resource',
    // ],
    'company.paketberlangganans' => [
        'index' => 'company::paketberlangganans.list resource',
        'create' => 'company::paketberlangganans.create resource',
        'edit' => 'company::paketberlangganans.edit resource',
        'destroy' => 'company::paketberlangganans.destroy resource',
    ],
    'company.biaya_kabel' => [
        'index' => 'company::biaya_kabel.index resource',
        'edit' => 'company::biaya_kabel.edit resource',
        'create' => 'company::biaya_kabel.create resource',
        'destroy' => 'company::biaya_kabel.destroy resource'
    ],
    'company.slot_instalasi' => [
        'index' => 'company::slot_instalasi.index resource',
        'edit' => 'company::slot_instalasi.edit resource'
    ],


];
