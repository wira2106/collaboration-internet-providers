<?php

return [
    'user.users' => [
        'index' => 'user::users.list user',
        'create' => 'user::users.create user',
        'edit' => 'user::users.edit user',
        'destroy' => 'user::users.destroy user',
    ],
    'user.staff' => [
        'index' => 'user::staff.list staff',
        'create' => 'user::staff.create staff',
        'edit' => 'user::staff.edit staff',
        'destroy' => 'user::staff.destroy staff',
    ],
    'user.roles' => [
        'index' => 'user::roles.list resource',
        'create' => 'user::roles.create resource',
        'edit' => 'user::roles.edit resource',
        'destroy' => 'user::roles.destroy resource',
    ],
    'account.api-keys' => [
        'index' => 'user::users.list api key',
        'create' => 'user::users.create api key',
        'destroy' => 'user::users.destroy api key',
    ],
];
