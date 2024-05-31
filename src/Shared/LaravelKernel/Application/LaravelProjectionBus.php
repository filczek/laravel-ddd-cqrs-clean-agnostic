<?php

declare(strict_types=1);

namespace DDD\Shared\LaravelKernel\Application;

use DDD\Shared\Application\Projection\Projection;
use DDD\Shared\Application\Projection\ProjectionBus;
use Illuminate\Support\Facades\App;

final class LaravelProjectionBus implements ProjectionBus
{
    public function project(Projection $projection): void
    {
        dispatch(fn () => $this->handleProjection($projection))
            ->onConnection('rabbitmq')
            ->onQueue('projections');
    }

    public function projectAll(iterable $projections): void
    {
        foreach ($projections as $projection) {
            $this->project($projection);
        }
    }

    private function handleProjection(Projection $projection): void
    {
        $handler_class = $projection::class . "Handler";
        $handler = App::make($handler_class);

        $handler($projection);
    }
}
