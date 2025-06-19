<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key
    |--------------------------------------------------------------------------
    |
    | This value is your OpenAI API key. You can find this in your OpenAI
    | dashboard under API keys.
    |
    */
    'api_key' => env('OPENAI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Organization
    |--------------------------------------------------------------------------
    |
    | This value is your OpenAI organization ID. This is optional and only
    | needed if you belong to multiple organizations.
    |
    */
    'organization' => env('OPENAI_ORGANIZATION'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for making requests to the OpenAI API.
    |
    */
    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 30),
]; 