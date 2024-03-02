<?php

namespace Codebarista\LaravelEssentials\Facades;

use Illuminate\Support\Collection;

class DB extends \Illuminate\Support\Facades\DB
{
    public static function collect(string $query, array $bindings): Collection
    {
        return collect(self::select($query, $bindings));
    }
}
