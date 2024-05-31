<?php

declare(strict_types=1);

namespace DDD\Shared\Infrastructure\Bus;

use DDD\Shared\Application\Projection\Projection;
use DDD\Shared\Application\Projection\ProjectionBus;

class InMemoryProjectionBus implements ProjectionBus
{
    /** @var Projection[] */
    private $projections = [];

    public function project(Projection $projection): void
    {
        $this->projections[] = $projection;
    }

    public function projectAll(iterable $projections): void
    {
        foreach ($projections as $projection) {
            $this->project($projection);
        }
    }

    /** @return Projection[] */
    public function all(): iterable
    {
        return $this->projections;
    }

    public function wasProjected(string $projection): bool
    {
        foreach ($this->all() as $projected) {
            if ($projected instanceof $projection) {
                return true;
            }
        }

        return false;
    }
}
