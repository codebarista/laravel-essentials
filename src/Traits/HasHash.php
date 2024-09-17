<?php

namespace Codebarista\LaravelEssentials\Traits;

use Codebarista\LaravelEssentials\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasHash
{
    protected static function bootHasHash(): void
    {
        // create and set a unique hash code for model
        static::creating(static function (Model $model) {
            $table = $model->getTable();
            if (Schema::hasColumn($table, 'hash')) {
                $column = Schema::getColumnType($table, 'hash', true);
                $length = preg_replace('/\D/', '', $column);
                $model->setAttribute('hash', self::getUniqueHash($table, $length));
            }
        });
    }

    protected static function getUniqueHash(string $table, int $length, int $iterations = 0): ?string
    {
        $hash = Str::random($length);

        // we've tried enough at this point
        if ($iterations > 9) {
            return $hash;
        }

        if (DB::table($table)->where('hash', $hash)->exists()) {
            return self::getUniqueHash($table, $length, $iterations + 1);
        }

        return $hash;
    }
}
