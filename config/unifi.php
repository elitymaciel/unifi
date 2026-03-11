<?php

return [
    'url' => env('UNIFI_URL', 'https://unifi.solartechsolutions.com.br'),
    'user' => env('UNIFI_USER'),
    'password' => env('UNIFI_PASSWORD'),
    'site_id' => env('UNIFI_SITE_ID', 'default'),
    'version' => env('UNIFI_CONTROLLER_VERSION', '8.0.7'),
    'debug' => env('UNIFI_CONTROLLER_DEBUG', false),
];
