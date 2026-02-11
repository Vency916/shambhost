<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\VercelOrchestrator;
use App\Models\VercelConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VercelOrchestratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_environment_variables_makes_correct_api_calls()
    {
        // Setup
        $projectId = 'prj_12345';
        $token = 'fake_token';
        
        VercelConfig::create([
            'project_id' => $projectId,
            'api_token' => Crypt::encryptString($token),
        ]);

        Http::fake([
            'api.vercel.com/*' => Http::response(['ok' => true], 200),
        ]);

        // Act
        $orchestrator = new VercelOrchestrator($projectId);
        $orchestrator->syncEnvironmentVariables(['DB_HOST' => '10.0.0.1']);

        // Assert
        Http::assertSent(function ($request) use ($projectId, $token) {
            return $request->url() == "https://api.vercel.com/v10/projects/{$projectId}/env"
                && $request->method() == 'POST'
                && $request['key'] == 'DB_HOST'
                && $request['value'] == '10.0.0.1'
                && $request['target'] == ['production', 'preview']
                && $request->hasHeader('Authorization', 'Bearer ' . $token);
        });

        $this->assertNotNull(VercelConfig::first()->last_synced_at);
    }
}
