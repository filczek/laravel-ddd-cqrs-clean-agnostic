<?php

declare(strict_types=1);

namespace DDD\Shared\LaravelKernel\Application;

use DDD\Shared\Application\Query\Query;
use DDD\Shared\Application\Query\QueryBus;
use Illuminate\Support\Facades\App;

final class LaravelQueryBus implements QueryBus
{
    public function execute(Query $query): mixed
    {
        $executor_class = $query::class . "Handler";
        $executor = App::make($executor_class);

        return $executor($query);
    }
}
