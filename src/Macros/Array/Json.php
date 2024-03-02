<?php

namespace Codebarista\LaravelEssentials\Macros\Array;

class Json
{
    public function __invoke(array $value, bool $pretty = false): bool|string
    {
        $flags = JSON_NUMERIC_CHECK
            | JSON_THROW_ON_ERROR
            | JSON_UNESCAPED_SLASHES;

        if ($pretty === true) {
            $flags |= JSON_PRETTY_PRINT;
        }

        return json_encode($value, $flags);
    }
}
