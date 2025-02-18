<?php

namespace Codebarista\LaravelEssentials;

use Codebarista\LaravelEssentials\Console\Commands\FailedJobs;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class EssentialsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerStringMacros();
        $this->registerArrayMacros();
        $this->commands([
            FailedJobs::class,
        ]);
    }

    private function registerStringMacros(): void
    {
        $macros = [
            'toJson' => \Codebarista\LaravelEssentials\Macros\String\Json::class,
            'chop' => \Codebarista\LaravelEssentials\Macros\String\Chop::class,
        ];

        foreach ($macros as $name => $class) {
            if (! Str::hasMacro($name)) {
                Str::macro($name, app($class));
            }
        }
    }

    private function registerArrayMacros(): void
    {
        $macros = [
            'toJson' => \Codebarista\LaravelEssentials\Macros\Array\Json::class,
            'fill' => \Codebarista\LaravelEssentials\Macros\Array\Fill::class,
        ];

        foreach ($macros as $name => $class) {
            if (! Arr::hasMacro($name)) {
                Arr::macro($name, app($class));
            }
        }
    }
}
