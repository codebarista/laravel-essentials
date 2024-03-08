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
