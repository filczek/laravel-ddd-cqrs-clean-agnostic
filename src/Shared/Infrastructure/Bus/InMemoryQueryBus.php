<?php

declare(strict_types=1);

namespace DDD\Shared\Infrastructure\Bus;

use DDD\Shared\Application\Query\Query;
use DDD\Shared\Application\Query\QueryBus;

class InMemoryQueryBus implements QueryBus
{
    /** @var Query[] */
    private $queries = [];

    public function execute(Query $query): mixed
    {
        $this->queries[] = $query;

        return null;
    }

    /** @return Query[] */
    public function all(): array
    {
        return $this->queries;
    }

    public function wasQueried(Query $query): bool
    {
        foreach ($this->queries as $queried) {
            if ($queried instanceof $query) {
                return true;
            }
        }

        return false;
    }
}
