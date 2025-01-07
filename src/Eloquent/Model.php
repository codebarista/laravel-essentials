<?php

namespace Codebarista\LaravelEssentials\Eloquent;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    public function loadOnly(array|string $relations): array
    {
        return $this->load($relations)->only($relations);
    }
}
