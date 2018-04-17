<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Cord\Settings Preferences
    | --------------------------------------------------------------------------
    | What files and specific config options within the config files
    | should we exclude from the web interface?
    |
    | By default we ignore some files and config options due to the fact
    | they are set within the '.env' file or culd cause encrytion issues.
    |--------------------------------------------------------------------------
    */

    /*
    |------------
    | Which files should we ignore for the settings.
    |------------
    */

    'files' => [
      'auth',
      'broadcasting',
      'database',
      'filesystems',
      'mail',
      'queue',
      'services',
      'cord.settings'
    ],

    /*
    |------------
    | Specific values within an array to be ignored.
    |
    | Example:
    | 'file' => ['key', 'key2', 'key3'];
    |------------
    */

    'ignore' => [

      // config/app
      'app' => [
        'name',
        'env',
        'debug',
        'url',
        'key',
        'cipher',
        'providers',
        'aliases'
      ],

      // config/backpack.crud
      'backpack.crud' => [
        'page_length_menu'
      ],

      // config/cache
      'cache' => [
        'default'
      ],

      // config/logging
      'logging' => [
        'default'
      ],

      // config/session
      'session' => [
        'driver',
        'lifetime',
        'cookie',
        'domain',
        'secure',
      ]

    ],

];
