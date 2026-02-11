<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\VercelOrchestrator;

class SyncVercelEnvironment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $projectId;
    protected $variables;
    protected $targets;

    /**
     * Create a new job instance.
     */
    public function __construct($projectId, array $variables, array $targets = ['production', 'preview'])
    {
        $this->projectId = $projectId;
        $this->variables = $variables;
        $this->targets = $targets;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orchestrator = new VercelOrchestrator($this->projectId);
        
        // 1. Sync Variables
        $orchestrator->syncEnvironmentVariables($this->variables, $this->targets);

        // 2. Trigger Deployment (Auto-Handshake)
        // We catch exception here so the job doesn't fail if just the trigger fails, 
        // or we could let it fail to retry. For now, logging would be best but simple catch is fine.
        try {
            $orchestrator->triggerDeployment();
        } catch (\Exception $e) {
            // In a real app, log this: Log::error("Failed to auto-trigger deployment: " . $e->getMessage());
        }
    }
}
