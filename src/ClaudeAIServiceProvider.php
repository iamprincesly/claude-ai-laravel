<?php

namespace Iamprincesly\ClaudeAI;

use Iamprincesly\ClaudeAI\Enums\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class ClaudeAIServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/claude-laravel.php' => config_path('claude-laravel.php'),
        ], 'claude-laravel-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/claude-laravel.php', 'claude-laravel');

        $this->app->singleton(Config::class, function (Application $app): Config {
            return new Config(
                config('claude-laravel.api_key'),
                config('claude-laravel.api_version'),
                Model::from(config('claude-laravel.model')),
                config('claude-laravel.max_tokens'),
            );
        });

        $this->app->singleton(ClaudeAIClient::class, function (Application $app): ClaudeAIClient {
            return new ClaudeAIClient(
                $app->make(Config::class),
            );
        });

        $this->app->singleton(ClaudeAIClient::class, function (Application $app): ClaudeAIClient {
            return new ClaudeAIClient(
                $app->make(Config::class),
            );
        });

        $this->app->singleton(abstract: 'claude-ai', concrete: function (Application $app): ClaudeAI {
            return new ClaudeAI(
                $app->make(ClaudeAIClient::class),
            );
        });
    }
}