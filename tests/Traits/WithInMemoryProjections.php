<?php

declare(strict_types=1);

namespace Tests\Traits;

use DDD\Shared\Application\Projection\ProjectionBus;
use DDD\Shared\Infrastructure\Bus\InMemoryProjectionBus;
use Illuminate\Support\Facades\App;

trait WithInMemoryProjections
{
    public function setupProjectionBus(): void
    {
        App::bind(ProjectionBus::class, InMemoryProjectionBus::class, true);
    }

    public function getProjectionBus(): InMemoryProjectionBus
    {
        return App::make(ProjectionBus::class);
    }
}
