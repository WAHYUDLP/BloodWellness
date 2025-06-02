<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KalkulatorTest extends TestCase
{
    /** @test */
    public function user_can_view_kalkulator_page()
    {
        $response = $this->get('/kalkulator');

        $response->assertStatus(200);
        $response->assertViewIs('pages.kalkulator');
    }

    /** @test */
    public function kalkulator_hitung_returns_valid_session_data()
    {
        $response = $this->post('/kalkulator/hitung', [
            'gender' => 'Pria',
            'age' => 25,
            'weight' => 70,
            'height' => 175,
            'activity' => 'Sedang',
        ]);

        $response->assertRedirect('/kalkulator');

        $this->assertEquals(session('gender'), 'Pria');
        $this->assertEquals(session('age'), 25);
        $this->assertEquals(session('weight'), 70);
        $this->assertEquals(session('height'), 175);
        $this->assertEquals(session('activity'), 'Sedang');
        $this->assertEquals(session('diet'), 'pertahankan');
        $this->assertNotEmpty(session('kalori'));
        $this->assertEquals(session('calories'), session('kalori')['pertahankan']);
    }

    /** @test */
    public function test_kalkulator_hitung_returns_valid_session_data()
    {
        $response = $this->post('/kalkulator/hitung', [
            'gender' => 'female',
            'age' => 25,
            'weight' => 60,
            'height' => 165,
            'activity' => 'medium',
        ]);

        $response->assertSessionHas('kalori'); // misal kalkulator menyimpan ini
        $response->assertRedirect('/kalkulator'); // sesuaikan dengan route
    }


    /** @test */

    /** @test */
    public function kalkulator_reset_clears_session()
    {
        session([
            'gender' => 'Wanita',
            'age' => 30,
            'weight' => 60,
            'height' => 165,
            'body_fat' => 25,
            'activity' => 'Ringan',
            'kalori' => ['pertahankan' => 1800],
            'diet' => 'pertahankan'
        ]);

        $response = $this->post('/kalkulator/reset');

        $response->assertRedirect('/kalkulator');
        $this->assertNull(session('gender'));
        $this->assertNull(session('kalori'));
    }
}
