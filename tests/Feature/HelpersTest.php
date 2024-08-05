<?php

it('has the callable helpers', function () {
    expect('encode_json')->toBeCallable()
        ->and('preg_split_trim')->toBeCallable()
        ->and('decode_json')->toBeCallable()
        ->and('crush_png')->toBeCallable()
        ->and('str_chop')->toBeCallable();
});

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

it('chops a string', function () {
    expect(str_chop(md5(microtime()), 20))
        ->toBeString()
        ->toContain('...')
        ->toHaveLength(23);

})->covers('str_chop');

it('splits and trims a string', function () {
    $value = 'one, two; three,;
        four;five,,';

    expect(preg_split_trim($value))
        ->toBeArray()
        ->toContain('three')
        ->toHaveLength(5);

})->covers('preg_split_trim');

it('has pngcrush installed', function () {
    exec(' LC_ALL=C type pngcrush', $output, $result);
    expect($output)
        ->toBeArray()
        ->and($result)
        ->toBeInt()
        ->toBe(0);
});
