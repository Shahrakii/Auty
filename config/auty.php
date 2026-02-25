<?php

return [

    'prefix' => env('AUTY_PREFIX', 'admin'),

    'auth' => [
        'redirect_after_login'  => '/admin/dashboard',
        'redirect_after_logout' => '/admin/login',
    ],

    'throttle' => [
        'enabled'               => true,
        'max_attempts'          => 5,
        'decay_minutes'         => 15,
        'lock_account'          => true,
        'lock_duration_minutes' => 30,
    ],

    'otp' => [
        'enabled'    => false,   // set to true to require email OTP after login
        'length'     => 6,
        'expires_in' => 10,      // minutes
    ],

];
