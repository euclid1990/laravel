<?php

return [
    'role' => [
        'admin' => 'admin',
    ],

    'permission' => [
        'user' => [
            'view' => 'view-user',
            'create' => 'create-user',
            'update' => 'update-user',
            'delete' => 'delete-user',
        ],
    ],

    'role_permission' => [
        'admin' => [
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
        ],
    ],
];
