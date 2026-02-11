<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\VercelConfig;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\FetchVercelDeployments;
use Illuminate\Support\Facades\Http;

class DashboardRefreshTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_refresh_deployments()
    {
        Queue::fake();
        Http::fake([
            'api.vercel.com/*' => Http::response(['deployments' => []], 200),
        ]);

        $user = \App\Models\User::factory()->create();

        $config = VercelConfig::create([
            'user_id' => $user->id,
            'project_id' => 'test_project',
            'api_token' => Crypt::encryptString('token'),
        ]);

        $response = $this->actingAs($user)->post('/panel/refresh');

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
