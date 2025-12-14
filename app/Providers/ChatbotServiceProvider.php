<?php

namespace App\Providers;

use App\Contracts\Chatbot;
use Illuminate\Support\ServiceProvider;

class ChatbotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Chatbot::class, function ($app) {
            $driverName = $app['config']['chatbot.default'] ?? 'rules';
            $driverClass = $app['config']["chatbot.drivers.{$driverName}.class"]
                ?? \App\Services\RuleBasedChatbot::class;

            return $app->make($driverClass);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
