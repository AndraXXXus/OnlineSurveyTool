<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This choice controls the default hash driver that will be used to hash
    | passwords for your application. By default, the bcrypt algorithm is
    | used; however, you remain free to modify this choice if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Choices
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration choices that should be used when
    | passwords are hashed using the Bcrypt algorithm. This will allow you
    | to control the amount of time it takes to hash the given password.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Choices
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration choices that should be used when
    | passwords are hashed using the Argon algorithm. These will allow you
    | to control the amount of time it takes to hash the given password.
    |
    */

    'argon' => [
        'memory' => 1024,
        'threads' => 2,
        'time' => 2,
    ],

];
