<?php

namespace Codebarista\LaravelEssentials\Macros\Array;

class Fill
{
    public function __invoke(array $array, int $count, mixed $value): array
    {
        return array_replace(array_fill(0, $count, $value), $array);
    }
}
