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
    protected $signature = 'codebarista:failed-jobs {action}';

    /**
     * @var string
     */
    protected $description = 'Retry, delete or reset failed jobs';

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'action' => ['Which action should be executed?', 'retry, delete or reset'],
        ];
    }

    public function handle(RedisJobRepository $repository): int
    {
        $amount = $repository->totalFailed();

        return match ($this->argument('action')) {
            'delete' => $this->deleteFailedJobs($repository, $amount),
            'retry' => $this->retryFailedJobs($repository, $amount),
            'reset' => $this->resetFailedJobs($repository),
            default => self::INVALID,
        };

    }

    private function deleteFailedJobs(RedisJobRepository $repository, int $amount): int
    {
        if ($this->confirm(sprintf('Really delete %d failed %s?', $amount, Str::plural('job', $amount)))) {
            $failedJobs = $this->getFailedJobs($repository, $amount);
            $counter = 0;

            foreach ($failedJobs as $jobId) {
                $counter += $repository->deleteFailed($jobId);
            }

            $this->resetFailedJobs($repository);

            $this->components->info(sprintf('%d of %d %s deleted.',
                $counter, $amount, Str::plural('failed job', $amount)
            ));
        }

        return self::SUCCESS;
    }

    private function retryFailedJobs(RedisJobRepository $repository, int $amount): int
    {
        $chunk = (int) $this->ask('How many failed jobs should be retried at once?', $amount);

        $failedJobs = $this->getFailedJobs($repository, $chunk);
        $counter = 0;

        foreach ($failedJobs as $jobId) {
            dispatch(new RetryFailedJob($jobId));
            $counter += $repository->deleteFailed($jobId);
        }

        $this->components->info(sprintf('%d of %d %s retried.',
            $counter, $amount, Str::plural('failed job', $amount)
        ));

        return self::SUCCESS;
    }

    private function resetFailedJobs(RedisJobRepository $repository): int
    {
        if ($this->confirm('Reset all failed jobs and their counters?')) {
            $repository->recentFailedJobExpires = 0;
            $repository->trimRecentJobs();

            $repository->failedJobExpires = 0;
            $repository->trimFailedJobs();
        }

        return self::SUCCESS;
    }

    private function getFailedJobs(RedisJobRepository $repository, int $amount): array
    {
        $failedJobs = $repository->getFailed($afterIndex = -1);
        $jobs = [];

        while ($failedJobs->count() > 0) {
            foreach ($failedJobs as $failedJob) {
                $jobs[] = $failedJob->id;
            }
            $failedJobs = $repository->getFailed($afterIndex += 50);
        }

        // sort all jobs in descending order
        $jobs = array_reverse($jobs);

        return array_slice($jobs, 0, $amount);
    }
}
