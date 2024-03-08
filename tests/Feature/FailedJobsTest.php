<?php

it('returns invalid', function () {
    $this->artisan('codebarista:failed-jobs')
        ->expectsQuestion('What is the action: retry or delete?', 'invalid')
        ->assertExitCode(2);
});

it('aborts retry failed jobs', function () {
    $this->artisan('codebarista:failed-jobs')
        ->expectsQuestion('What is the action: retry or delete?', 'retry')
        ->expectsConfirmation('Really retry all failed jobs?')
        ->expectsOutputToContain('Process terminated by user')
        ->assertExitCode(0);
});

it('aborts delete failed jobs', function () {
    $this->artisan('codebarista:failed-jobs')
        ->expectsQuestion('What is the action: retry or delete?', 'delete')
        ->expectsConfirmation('Really delete all failed jobs?')
        ->expectsOutputToContain('Process terminated by user')
        ->assertExitCode(0);
});

it('retries failed jobs', function () {
    $this->artisan('codebarista:failed-jobs')
        ->expectsQuestion('What is the action: retry or delete?', 'retry')
        ->expectsConfirmation('Really retry all failed jobs?', 'yes')
        ->expectsOutputToContain('jobs retried')
        ->assertExitCode(0);
});

it('deletes failed jobs', function () {
    $this->artisan('codebarista:failed-jobs')
        ->expectsQuestion('What is the action: retry or delete?', 'delete')
        ->expectsConfirmation('Really delete all failed jobs?', 'yes')
        ->expectsOutputToContain('jobs deleted')
        ->assertExitCode(0);
});
