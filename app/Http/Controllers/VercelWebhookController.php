<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Deployment;

class VercelWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Validate signature (omitted for MVP, but crucial for production)
        
        $payload = $request->all(); // Vercel sends JSON

        if ($request->input('type') === 'deployment.created' || $request->input('type') === 'deployment.ready' || $request->input('type') === 'deployment.error') {
            Log::info('Vercel Deployment Update', $payload);
            
            $projectId = $payload['payload']['projectId'] ?? $payload['projectId'] ?? 'unknown';
            $config = \App\Models\VercelConfig::where('project_id', $projectId)->first();
            
            if ($config) {
                Deployment::updateOrCreate(
                    ['vercel_deployment_id' => $payload['payload']['deployment']['id'] ?? $payload['id']],
                    [
                        'user_id' => $config->user_id,
                        'project_id' => $projectId,
                        'url' => $payload['payload']['deployment']['url'] ?? $payload['url'] ?? null,
                        'state' => strtoupper(explode('.', $request->input('type'))[1]), // CREATED, READY, ERROR
                        'branch' => $payload['payload']['deployment']['meta']['githubCommitRef'] ?? 'main',
                        'created_at_vercel' => now(), // Simplification
                    ]
                );
            }
        }

        return response()->json(['status' => 'received']);
    }
}
