<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_loads()
    {
        $this->get('/login')->assertStatus(200)->assertSee('Iniciar sesión');
    }

    /** @test */
    public function register_route_is_disabled()
    {
        $this->get('/register')->assertStatus(404);
    }
}
