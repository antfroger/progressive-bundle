<?php

return [
    'features' => [
        'dark-theme' => true,
        'light-theme' => [
            'enabled' => true,
        ],
        'homepage-v2' => [
            'roles' => ['ROLE-DEV'],
        ],
        'strategy-unanimous' => [
            'unanimous' => [
                'env' => ['dev', 'preprod'],
                'between-hours' => ['start' => 9, 'end' => 18],
            ],
        ],
        'strategy-partial' => [
            'partial' => [
                'env' => ['dev', 'preprod'],
                'roles' => ['ROLE_ADMIN'],
                'between-hours' => ['start' => 9, 'end' => 18],
            ],
        ],
    ],
];
