<?php

it('returns invalid', function () {
    $this->artisan('codebarista:failed-jobs')
        ->expectsChoice('What action should be taken?', 'invalid', [
            'delete',
            'retry',
        ])
        ->assertExitCode(2);
});

it('aborts retry failed jobs', function () {
    $this->artisan('codebarista:failed-jobs')
        ->expectsChoice('What action should be taken?', 'retry', [
            'delete',
            'retry',
        ])
        ->expectsConfirmation('Really retry all failed jobs?')
        ->expectsOutputToContain('Process terminated by user')
        ->assertExitCode(0);
});

it('aborts delete failed jobs', function () {
    $this->artisan('codebarista:failed-jobs')
        ->expectsChoice('What action should be taken?', 'delete', [
            'delete',
            'retry',
        ])
        ->expectsConfirmation('Really delete all failed jobs?')
        ->expectsOutputToContain('Process terminated by user')
        ->assertExitCode(0);
});

it('retries failed jobs', function () {
    $this->artisan('codebarista:failed-jobs')
        ->expectsChoice('What action should be taken?', 'retry', [
            'delete',
            'retry',
        ])
        ->expectsConfirmation('Really retry all failed jobs?', 'yes')
        ->expectsOutputToContain('jobs retried')
        ->assertExitCode(0);
});

it('deletes failed jobs', function () {
    $this->artisan('codebarista:failed-jobs')
        ->expectsChoice('What action should be taken?', 'delete', [
            'delete',
            'retry',
        ])
        ->expectsConfirmation('Really delete all failed jobs?', 'yes')
        ->expectsOutputToContain('jobs deleted')
        ->assertExitCode(0);
});
