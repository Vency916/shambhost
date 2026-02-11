<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_landing_page_loads_and_contains_key_elements()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ShamHosts');
        $response->assertSee('Build, Deploy,');
        $response->assertSee('Ship globally.');
        $response->assertSee('VercelOrchestrator'); // Check for the code preview
        
        // Navigation Links
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
    }
}
