<?php

it('decodes json string to array', function () {
    $value = <<<'JSON'
        {
            "greeting": "Gude Moin"
        }
    JSON;

    expect(decode_json($value))
        ->toBeArray()
        ->toHaveKey('greeting')
        ->toContain('Gude Moin');

})->covers('decode_json');

it('encodes array to json string', function () {
    $value = [
        'greeting' => 'Gude Moin',
    ];

    expect(encode_json($value))
        ->toBeString()
        ->toContain('greeting');

})->covers('encode_json');

it('can crush png', function () {
    $version = null;

    if ($pngcrush = exec('which pngcrush')) {
        $version = exec($pngcrush.' --version');
    }

    expect($version)->toBeString();
    // ->toContain('pngcrush');

})->covers('crush_png');
