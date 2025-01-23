<?php

return [
    'sidebar' => [
        [
            'route' => 'app.index',
            'icon' => 'bi bi-box',
            'display_name' => 'App',
            'group' => 'app',
        ],
        [
            'route' => 'push.index',
            'icon' => 'bi bi-app-indicator',
            'display_name' => 'Push Channels',
            'group' => 'push',
        ],
        [
            'route' => 'email.index',
            'icon' => 'bi bi-envelope',
            'display_name' => 'Email Channels',
            'group' => 'email',
        ],
    ]
];
