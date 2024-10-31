<?php

namespace Codebarista\LaravelEssentials\Traits;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasHash
{
    protected static function bootHasHash(): void
    {
        // create and set a unique hash code for model
        static::creating(static function (Model $model) {
            $connection = $model->getConnection();
            $schema = $connection->getSchemaBuilder();
            $table = $model->getTable();

            if ($schema->hasColumn($table, 'hash')) {
                $column = $schema->getColumnType($table, 'hash', true);
                $length = preg_replace('/\D/', '', $column);
                $model->setAttribute('hash', self::getUniqueHash($connection, $table, $length));
            }
        });
    }

    protected static function getUniqueHash(Connection $connection, string $table, int $length, int $iterations = 0): ?string
    {
        $hash = Str::random($length);

        // we've tried enough at this point
        if ($iterations > 9) {
            return $hash;
        }

        if ($connection->table($table)->where('hash', $hash)->exists()) {
            return self::getUniqueHash($table, $length, $iterations + 1);
        }

        return $hash;
    }
}
