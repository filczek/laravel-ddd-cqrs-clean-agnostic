<?php

declare(strict_types=1);

namespace DDD\Shared\Application\Projection;

interface ProjectionBus
{
    public function project(Projection $projection): void;

    /** @var Projection[] $projections */
    public function projectAll(iterable $projections): void;
}
