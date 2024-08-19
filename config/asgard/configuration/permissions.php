<?php

return [
    'configuration.configurations' => [
        'index' => 'configuration::configurations.list resource',
        'edit' => 'configuration::configurations.edit resource',
    ],
    'configuration.bank' => [
        'index' => 'configuration::bank.list resource',
        'create' => 'configuration::bank.create resource',
        'edit' => 'configuration::bank.edit resource',
        'destroy' => 'configuration::bank.destroy resource',
    ],

];
