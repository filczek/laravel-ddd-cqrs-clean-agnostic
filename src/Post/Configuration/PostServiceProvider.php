<?php

namespace DDD\Post\Configuration;

use DDD\Post\Infrastructure\Persistence\Eloquent\EloquentPostGateway;
use DDD\Post\Infrastructure\Persistence\PostGatewayInterface;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PostGatewayInterface::class, EloquentPostGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
