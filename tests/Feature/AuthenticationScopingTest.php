<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VercelConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;

class AuthenticationScopingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/panel');
        $response->assertRedirect('/login');
    }

    public function test_user_can_login_and_access_panel()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/panel');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_see_others_config()
    {
        $user1 = User::create(['name' => 'User 1', 'email' => 'u1@ex.com', 'password' => bcrypt('123')]);
        $user2 = User::create(['name' => 'User 2', 'email' => 'u2@ex.com', 'password' => bcrypt('123')]);

        VercelConfig::create([
            'user_id' => $user1->id,
            'project_id' => 'project_1',
            'api_token' => Crypt::encryptString('token1'),
        ]);

        $this->actingAs($user2);
        
        $response = $this->get('/panel/settings');
        $response->assertOk();
        $response->assertDontSee('project_1');
    }
}
