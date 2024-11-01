<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CLAUDE AI API KEY
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the locking mechanism to us when altering
    | wallet balance to avoid race condition
    |
    */
    'api_key' => env('CLAUDE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | CLAUDE AI API VERSION
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the locking mechanism to us when altering
    | wallet balance to avoid race condition
    |
    */
    'api_version' => env('CLAUDE_API_VERSION', '2023-06-01'),

    /*
    |--------------------------------------------------------------------------
    | CLAUDE AI MODEL
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the locking mechanism to us when altering
    | wallet balance to avoid race condition
    |
    */
    'model' => env('CLAUDE_MODEL', 'claude-3-5-sonnet-20241022'),

    /*
    |--------------------------------------------------------------------------
    | CLAUDE AI MAX TOKENS
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the locking mechanism to us when altering
    | wallet balance to avoid race condition
    |
    */
    'max_tokens' => env('CLAUDE_MAX_TOKENS', 1024),
];