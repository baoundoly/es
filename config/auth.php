<?php

return [

    'defaults' => [
        'guard' => 'member',
        'passwords' => 'member',
    ],

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],
        'member' => [
            'driver' => 'session',
            'provider' => 'member',
        ],
    ],

    'providers' => [
        'admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'member' => [
            'driver' => 'eloquent',
            'model' => App\Models\Member::class,
        ],
    ],

    'passwords' => [
        'admin' => [
            'provider' => 'admin',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'member' => [
            'provider' => 'member',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];

// To Added new auth then follow those instruction
// 1. update config/auth.php
// 2. update app/Providers/RouteServiceProvider.php
// 3. update new auth Model like app/Models/Member.php
// 4. update new Dashboard Controller function __construct like app/Http/Controllers/Member/DashboardController.php
// 5. update app/Http/Controllers/Auth/LoginController.php