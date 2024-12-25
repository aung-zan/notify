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
            'route' => 'email.index',
            'icon' => 'bi bi-envelope',
            'display_name' => 'Email Notification',
            'group' => 'email',
        ],
        [
            'route' => 'push.index',
            'icon' => 'bi bi-app-indicator',
            'display_name' => 'Push Notification',
            'group' => 'push',
        ],
    ]
];
