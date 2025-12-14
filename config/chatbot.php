<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Chatbot Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default chatbot driver that will be used when
    | your application needs to generate a reply.
    | Supported: "rules", "gemini"
    |
    */

    'default' => env('CHATBOT_DRIVER', 'rules'),

    /*
    |--------------------------------------------------------------------------
    | Chatbot Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the chatbot drivers for your application.
    | An example of each type of driver is provided for you.
    |
    */

    'drivers' => [

        'rules' => [
            'class' => \App\Services\RuleBasedChatbot::class,
        ],

        'gemini' => [
            'class' => \App\Services\GeminiChatbot::class,
        ],

    ],

];
