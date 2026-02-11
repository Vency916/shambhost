<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use App\Models\VercelConfig;

class VercelOrchestrator
{
    protected $baseUrl = 'https://api.vercel.com';
    protected $token;
    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
        $config = VercelConfig::where('project_id', $projectId)->firstOrFail();
        $this->token = Crypt::decryptString($config->api_token);
    }

    /**
     * Sync environment variables to Vercel
     * 
     * @param array $variables Key-value pair of env vars
     * @param array $targets ['production', 'preview', 'development']
     */
    public function syncEnvironmentVariables(array $variables, array $targets = ['production', 'preview'])
    {
        foreach ($variables as $key => $value) {
            $response = Http::withoutVerifying()
                ->timeout(60)
                ->withOptions([
                    'connect_timeout' => 60,
                    'force_ip_resolve' => 'v4',
                ])
                ->withToken($this->token)
                ->post("{$this->baseUrl}/v10/projects/{$this->projectId}/env", [
                    'key' => $key,
                    'value' => $value,
                    'target' => $targets,
                    'type' => 'encrypted',
                ]);

            if ($response->failed()) {
                // Log error or throw
                // handling for existing keys (might need PATCH instead of POST)
                if ($response->status() === 400 && str_contains($response->body(), 'already exists')) {
                     // logic to update existing would go here, omitting for MVP
                }
            }
        }
        
        VercelConfig::where('project_id', $this->projectId)->update(['last_synced_at' => now()]);
    }

    public function getDeployments($limit = 5)
    {
        return Http::withoutVerifying()
            ->timeout(60)
            ->withOptions([
                'connect_timeout' => 60,
                'force_ip_resolve' => 'v4',
            ])
            ->withToken($this->token)
            ->get("{$this->baseUrl}/v6/deployments", [
                'projectId' => $this->projectId,
                'limit' => $limit,
            ])
            ->json();
    }

    /**
     * Trigger a new deployment (Redeploy)
     * For MVP, we try to trigger a production deployment.
     */
    public function triggerDeployment()
    {
        // In a real Vercel integration, we would likely need to specific the Git Source
        // or use a Deploy Hook. For this MVP, we attempt a generic deploy request
        // which often assumes the connected Git repo.
        return Http::withoutVerifying()
            ->timeout(60)
            ->withOptions([
                'connect_timeout' => 60,
                'force_ip_resolve' => 'v4',
            ])
            ->withToken($this->token)
            ->post("{$this->baseUrl}/v13/deployments", [
                'project' => $this->projectId,
                'target' => 'production', 
                'name' => 'shambhost-auto-trigger',
            ]);
    }

    /**
     * Get build logs/events for a specific deployment
     */
    public function getDeploymentLogs($deploymentId)
    {
        return Http::withoutVerifying()
            ->timeout(30)
            ->withOptions([
                'connect_timeout' => 30,
                'force_ip_resolve' => 'v4',
            ])
            ->withToken($this->token)
            ->get("{$this->baseUrl}/v2/deployments/{$deploymentId}/events", [
                'direction' => 'backward', // Get latest logs
                'limit' => 100,
            ])
            ->json();
    }
}
