<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VercelConfig;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsDisconnectTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_disconnect_vercel_integration()
    {
        $user = User::factory()->create();
        
        VercelConfig::create([
            'user_id' => $user->id,
            'project_id' => 'test_project',
            'api_token' => Crypt::encryptString('token'),
        ]);

        $this->assertDatabaseCount('vercel_configs', 1);

        $response = $this->actingAs($user)->delete('/panel/settings');

        $response->assertRedirect('/panel/settings');
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('vercel_configs', 0);
    }

    public function test_cannot_disconnect_if_not_configured()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->delete('/panel/settings');

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
