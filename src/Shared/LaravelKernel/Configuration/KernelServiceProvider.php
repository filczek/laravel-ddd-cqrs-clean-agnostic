<?php

namespace DDD\Shared\LaravelKernel\Configuration;

use DDD\Shared\Application\Command\CommandBus;
use DDD\Shared\Application\Event\EventBus;
use DDD\Shared\Application\Projection\ProjectionBus;
use DDD\Shared\Application\Query\QueryBus;
use DDD\Shared\Domain\DomainEvent;
use DDD\Shared\LaravelKernel\Application\LaravelCommandBus;
use DDD\Shared\LaravelKernel\Application\LaravelEventBus;
use DDD\Shared\LaravelKernel\Application\LaravelProjectionBus;
use DDD\Shared\LaravelKernel\Application\LaravelQueryBus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class KernelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CommandBus::class, LaravelCommandBus::class);
        $this->app->bind(QueryBus::class, LaravelQueryBus::class);
        $this->app->bind(EventBus::class, LaravelEventBus::class);
        $this->app->bind(ProjectionBus::class, LaravelProjectionBus::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict();

        Event::listen(DomainEvent::class, StoreEveryEvent::class);
    }
}
