<?php

if (! function_exists('encode_json')) {
    function encode_json(array $value): bool|string
    {
        return \Illuminate\Support\Arr::toJson($value);
    }
}

if (! function_exists('decode_json')) {
    function decode_json(string $value): array
    {
        return \Illuminate\Support\Str::toJson($value);
    }
}
