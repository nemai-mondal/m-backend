<?php

return [

    'driver'        => env('MAIL_DRIVER', "smtp"),
    'auth_mode'     => env('MAIL_AUTH_MODE', null),
    'verify_peer'   => env('MAIL_VERIFY_PEER', false),
    'host'          => env('MAIL_HOST', null),
    'port'          => env('MAIL_PORT', 587),
    'from'          => [
        'address'   => env('MAIL_FROM_ADDRESS', ''),
        'name'      => env('MAIL_FROM_NAME', 'MagicHRMS'),
    ],
    'encryption'    => env('MAIL_ENCRYPTION', 'tls'),
    'username'      => env('MAIL_USERNAME', ''),
    'password'      => env('MAIL_PASSWORD', ''),
    'sendmail'      => '/usr/sbin/sendmail -bs',

];
