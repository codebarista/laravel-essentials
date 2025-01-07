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

if (! function_exists('str_chop')) {
    function str_chop(string $value, int $limit, string $center = '...'): string
    {
        return \Illuminate\Support\Str::chop($value, $limit, $center);
    }
}

if (! function_exists('array_fill_index_gaps')) {
    function array_fill_index_gaps(array $array, int $count, mixed $value): array
    {
        return \Illuminate\Support\Arr::fill($array, $count, $value);
    }
}

if (! function_exists('preg_split_trim')) {
    function preg_split_trim(string $value, string $pattern = '/,|;|\s+/', int $sort = -1): array
    {
        $items = array_map('trim', preg_split($pattern, $value));
        $items = array_filter($items);

        if ($sort >= SORT_REGULAR) {
            sort($items, $sort);
        }

        return array_values($items);
    }
}

if (! function_exists('crush_png')) {
    function crush_png(string $image): bool
    {
        if ($pngcrush = exec('which pngcrush')) {
            $format = '%s -d %s -q -rem alla %s > /dev/null 2>&1';
            $dir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
            $name = basename($image);

            $command = sprintf($format, $pngcrush, $dir, escapeshellarg($image));

            if (exec($command) !== false) {
                return rename($dir.DIRECTORY_SEPARATOR.$name, $image);
            }
        }

        return false;
    }
}
