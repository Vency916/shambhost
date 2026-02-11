<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\VercelConfig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_loads()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/panel/settings');
        $response->assertStatus(200);
        $response->assertSee('Vercel Configuration');
    }

    public function test_can_save_configuration()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/panel/settings', [
            'project_id' => 'new_project',
            'api_token' => 'new_token',
        ]);

        $response->assertRedirect('/panel');
        
        $this->assertDatabaseHas('vercel_configs', [
            'project_id' => 'new_project',
            'user_id' => $user->id,
        ]);
    }
}
