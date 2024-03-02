<?php

namespace Codebarista\LaravelEssentials\Macros\String;

class Json
{
    public function __invoke(
        string $value,
        bool $associative = true,
        int $depth = 512,
        int $flags = JSON_THROW_ON_ERROR
    ): array|object {
        return json_decode($value, $associative, $depth, $flags);
    }
}
