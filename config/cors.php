<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => ['Content-Type', 'X-Requested-With', 'Access-Control-Allow-Origin'],
    'allowedMethods' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,
    
    // 'supportsCredentials' => true,
    // 'allowedOrigins' => ['*'],
    // 'allowedOriginsPatterns' => [],
    // 'allowedHeaders' => ['*'], // Content-Type', 'X-Requested-With', 'X-Auth-Token', 'Authorization'],
    // 'allowedMethods' => ['*'],
    // 'exposedHeaders' => [],
    // 'maxAge' => 0,

];
