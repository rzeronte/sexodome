<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => storage_path('database.sqlite'),
            'prefix'   => '',
        ],
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'forge'),
            'username'  => env('DB_USERNAME', 'forge'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'assassinsporn' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST2', '82.98.139.161'),
            'database'  => env('DB_DATABASE2', 'assassinsp'),
            'username'  => env('DB_USERNAME2', 'assassinsp'),
            'password'  => env('DB_PASSWORD2', 'rZeronteLabs12'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'mamasfollando' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST3', '82.98.139.161'),
            'database'  => env('DB_DATABASE3', 'mamasfollando'),
            'username'  => env('DB_USERNAME3', 'mamasfollando'),
            'password'  => env('DB_PASSWORD3', 'rZeronteLabs12'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'latinasparadise' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST4', '82.98.139.164'),
            'database'  => env('DB_DATABASE4', 'latinasparadise'),
            'username'  => env('DB_USERNAME4', 'latinasparadise'),
            'password'  => env('DB_PASSWORD4', 'rZeronteLabs12'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'dirtyblow' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST4', '82.98.139.164'),
            'database'  => env('DB_DATABASE4', 'dirtyblow'),
            'username'  => env('DB_USERNAME4', 'dirtyblow'),
            'password'  => env('DB_PASSWORD4', 'rZeronteLabs12'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'masajespornos' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST5', '82.98.134.42'),
            'database'  => env('DB_DATABASE5', 'masajespornos'),
            'username'  => env('DB_USERNAME5', 'masajespornos'),
            'password'  => env('DB_PASSWORD5', '0ItpEc'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'desoltera' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST6', '82.98.134.42'),
            'database'  => env('DB_DATABASE6', 'desoltera'),
            'username'  => env('DB_USERNAME6', 'desoltera'),
            'password'  => env('DB_PASSWORD6', 'N3OdVr'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'justdo' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST7', '82.98.134.42'),
            'database'  => env('DB_DATABASE7', 'justdo'),
            'username'  => env('DB_USERNAME7', 'justdo'),
            'password'  => env('DB_PASSWORD7', 'qprwm34t'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'maniac' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST8', '82.98.134.42'),
            'database'  => env('DB_DATABASE8', 'maniac'),
            'username'  => env('DB_USERNAME8', 'maniac'),
            'password'  => env('DB_PASSWORD8', 'fvb6cm8g'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'pornoseo' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST9', '82.98.139.92'),
            'database'  => env('DB_DATABASE9', 'pornoseo'),
            'username'  => env('DB_USERNAME9', 'pornoseo'),
            'password'  => env('DB_PASSWORD9', 'elnq8j2p'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ],

    ],

];
