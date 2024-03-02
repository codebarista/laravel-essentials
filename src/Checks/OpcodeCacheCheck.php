<?php

namespace Codebarista\LaravelEssentials\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class OpcodeCacheCheck extends Check
{
    public function run(): Result
    {
        if ($status = opcache_get_status(false)) {
            if ((bool) $status['opcache_enabled'] === true) {
                return Result::make()->ok();
            }

            return Result::make()->warning('Disabled');
        }

        return Result::make()->failed('Absent');
    }
}
