<?php

namespace Codebarista\LaravelEssentials\Macros\String;

class Chop
{
    public function __invoke(string $value, int $limit = 100, string $center = '...'): string
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        $length = round($limit / 2);

        return mb_substr($value, 0, $length, encoding: 'UTF-8').$center
            .mb_substr($value, -$length, encoding: 'UTF-8');
    }
}
