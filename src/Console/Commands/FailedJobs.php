<?php

namespace Codebarista\LaravelEssentials\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;
use Laravel\Horizon\Jobs\RetryFailedJob;
use Laravel\Horizon\Repositories\RedisJobRepository;

use function dispatch;

class FailedJobs extends Command implements Isolatable, PromptsForMissingInput
{
    /**
     * @var string
     */
    protected $signature = 'codebarista:failed-jobs {action : The action: retry or delete}';

    /**
     * @var string
     */
    protected $description = 'Retry or delete all failed jobs.';

    public function handle(RedisJobRepository $repository): int
    {
        return match ($this->argument('action')) {
            'delete' => $this->deleteFailedJobs($repository),
            'retry' => $this->retryFailedJobs($repository),
            default => self::INVALID,
        };
    }

    private function deleteFailedJobs(RedisJobRepository $repository): int
    {
        if (! $this->confirm('Really delete all failed jobs?')) {
            $this->components->info('Process terminated by user');

            return self::SUCCESS;
        }

        $i = $repository->getFailed()->count();

        // What a hack...but it works
        $repository->recentFailedJobExpires = 0;
        $repository->trimRecentJobs();

        $repository->failedJobExpires = 0;
        $repository->trimFailedJobs();

        $this->components->info(
            sprintf('%d %s deleted.', $i, Str::plural(' job', $i))
        );

        return self::SUCCESS;
    }

    private function retryFailedJobs(RedisJobRepository $repository): int
    {
        if (! $this->confirm('Really retry all failed jobs?')) {
            $this->components->info('Process terminated by user');

            return self::SUCCESS;
        }

        $i = $repository->getFailed()->each(function ($job) {
            dispatch(new RetryFailedJob($job->id));
        })->count();

        $this->components->info(
            sprintf('%d %s retried.', $i, Str::plural(' job', $i))
        );

        return self::SUCCESS;
    }
}
