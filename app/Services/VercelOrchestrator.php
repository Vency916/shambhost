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
        // 1. Fetch latest deployment to get Git Metadata and Name
        $latest = $this->getDeployments(1);
        
        $payload = [
            'name' => 'shambhost-auto-trigger',
            'project' => $this->projectId,
            'target' => 'production',
        ];

        if (!empty($latest['deployments'][0])) {
            $lastDeployment = $latest['deployments'][0];
            
            // Reuse Git Source if available (Critical for Git-connected projects)
            if (isset($lastDeployment['meta']['githubCommitRef'])) {
               // Vercel sometimes puts git info in meta
               $payload['gitSource'] = [
                   'type' => 'github',
                   'ref' => $lastDeployment['meta']['githubCommitRef'],
                   'repoId' => $lastDeployment['meta']['githubRepoId'] ?? null,
                   'sha' => $lastDeployment['meta']['githubCommitSha'] ?? null,
               ];
            } elseif (isset($lastDeployment['gitSource'])) {
                // Or directly in gitSource object
                $payload['gitSource'] = $lastDeployment['gitSource'];
            }
            
            // Reuse name to keep project grouping clean
            if (isset($lastDeployment['name'])) {
                $payload['name'] = $lastDeployment['name'];
            }
        }

        return Http::withoutVerifying()
            ->timeout(60)
            ->withOptions([
                'connect_timeout' => 60,
                'force_ip_resolve' => 'v4',
            ])
            ->withToken($this->token)
            ->post("{$this->baseUrl}/v13/deployments", $payload);
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
