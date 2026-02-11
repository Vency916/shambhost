<?php

namespace App\Jobs;

use App\Models\Deployment;
use App\Models\VercelConfig;
use App\Services\VercelOrchestrator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchVercelDeployments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $projectId;

    /**
     * Create a new job instance.
     */
    public function __construct($projectId = null)
    {
        $this->projectId = $projectId;
    }

    public function handle(): void
    {
        if ($this->projectId) {
            $config = VercelConfig::where('project_id', $this->projectId)->first();
        } else {
            $config = VercelConfig::first();
        }

        if (!$config) return;

        $orchestrator = new VercelOrchestrator($config->project_id);
        $deployments = $orchestrator->getDeployments(5);

        if (isset($deployments['deployments'])) {
            foreach ($deployments['deployments'] as $deployment) {
                Deployment::updateOrCreate(
                    ['vercel_deployment_id' => $deployment['uid']],
                    [
                        'user_id' => $config->user_id,
                        'project_id' => $config->project_id,
                        'url' => $deployment['url'],
                        'state' => strtoupper($deployment['state']),
                        'branch' => $deployment['meta']['githubCommitRef'] ?? 'manual',
                        'created_at_vercel' => \Carbon\Carbon::createFromTimestamp($deployment['created'] / 1000),
                    ]
                );
            }
        }
    }
}
