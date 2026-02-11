<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\VercelConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Models\User;
use App\Jobs\SyncVercelEnvironment;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_with_mock_data()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panel');
        $response->assertStatus(200);
        $response->assertSee('Control Plane');
        $response->assertSee('Lat:');
    }

    public function test_manual_sync_dispatches_job()
    {
        // This test now tests the controller directly for the MVP flow which uses sync dispatch
        // Actually, the new controller code calls Orchestrator directly for the sync action in 'sync' method
        // See DashboardController.php: $orchestrator->syncEnvironmentVariables...
        
        Queue::fake();
        $user = User::factory()->create();
        
        $config = VercelConfig::create([
            'user_id' => $user->id,
            'project_id' => 'test_project',
            'api_token' => Crypt::encryptString('token'),
        ]);

        // Mock Http façade to intercept the calls from VercelOrchestrator
        Http::fake([
            'api.vercel.com/*' => Http::response(['id' => 'dep_123', 'status' => 'QUEUED'], 200),
        ]);

        $response = $this->actingAs($user)->post('/panel/sync', [
            'env_key' => 'TEST_KEY',
            'env_value' => 'TEST_VALUE',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        Queue::assertPushed(SyncVercelEnvironment::class);
    }

    public function test_deployments_displayed_correctly()
    {
        $user = User::factory()->create();
        
        // Create known deployments
        \App\Models\Deployment::create([
            'user_id' => $user->id,
            'vercel_deployment_id' => 'dep_1',
            'project_id' => 'prj_1',
            'url' => 'feature-branch.vercel.app',
            'state' => 'READY',
            'branch' => 'feature/login-page',
            'created_at_vercel' => now()->subMinutes(5),
        ]);

        \App\Models\Deployment::create([
            'user_id' => $user->id,
            'vercel_deployment_id' => 'dep_2',
            'project_id' => 'prj_1',
            'url' => null, // No URL (e.g. error or building)
            'state' => 'ERROR',
            'branch' => 'fix/typo',
            'created_at_vercel' => now()->subHours(1),
        ]);

        $response = $this->actingAs($user)->get('/panel');

        $response->assertOk();
        
        // Assert first deployment details
        $response->assertSee('feature/login-page');
        $response->assertSee('READY');
        $response->assertSee('Visit Preview'); // The link text
        $response->assertSee('feature-branch.vercel.app');

        // Assert second deployment details
        $response->assertSee('fix/typo');
        $response->assertSee('ERROR');
        // We expect to see the placeholder "-" for the deployment without URL
        $response->assertSee('<span class="text-gray-600">-</span>', false);
    }
}
