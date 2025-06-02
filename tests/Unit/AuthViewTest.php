<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AuthViewTest extends TestCase
{
    /** @test */
    public function beranda_page_loads_successfully()
    {
        $response = $this->get(route('beranda'));
        $response->assertStatus(200);
    }

    /** @test */
    public function login_page_loads_successfully()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    /** @test */
    public function register_page_loads_successfully()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    /** @test */
    public function kalkulator_page_loads_successfully()
    {
        $response = $this->get(route('kalkulator'));
        $response->assertStatus(200);
    }

    /** @test */
    public function planner_create_page_loads_successfully()
    {
        $response = $this->get(route('planner.create'));
        $response->assertStatus(200);
    }



    /** @test */
    public function profile_page_requires_authentication()
    {
        // tanpa login, harus redirect ke login
        $response = $this->get(route('profile'));
        $response->assertRedirect(route('login'));

        // login dan akses
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('profile'));
        $response->assertStatus(200);
    }

    /** @test */
    public function profile_edit_page_requires_authentication()
    {
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect(route('login'));

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertStatus(200);
    }

    /** @test */
    public function lupa_password_form_loads_successfully()
    {
        $response = $this->get(route('lupa-password.form'));
        $response->assertStatus(200);
    }

    /** @test */
    public function verifikasi_otp_form_loads_successfully()
    {
        $response = $this->get(route('verifikasi_otp.form'));
        $response->assertStatus(200);
    }
}
