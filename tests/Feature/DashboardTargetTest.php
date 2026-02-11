<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\VercelConfig;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Models\User;
use App\Jobs\SyncVercelEnvironment;

class DashboardTargetTest extends TestCase
{
    use RefreshDatabase;

    public function test_manual_sync_dispatches_job_with_targets()
    {
        Queue::fake();

        $user = User::factory()->create();

        $config = VercelConfig::create([
            'user_id' => $user->id,
            'project_id' => 'test_project',
            'api_token' => Crypt::encryptString('token'),
        ]);

        $response = $this->actingAs($user)->post('/panel/sync', [
            'env_key' => 'TEST_KEY',
            'env_value' => 'TEST_VALUE',
            'env_target' => 'preview,development',
        ]);

        $response->assertRedirect();
        
        Queue::assertPushed(SyncVercelEnvironment::class, function ($job) {
             // Accessing protected property via reflection for testing
             $reflection = new \ReflectionClass($job);
             $property = $reflection->getProperty('targets');
             $property->setAccessible(true);
             $targets = $property->getValue($job);
             
             return $targets === ['preview', 'development'];
        });
    }
}
