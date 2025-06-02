<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use DatabaseTransactions;  // Ini biar setiap test rollback otomatis

    protected function setUp(): void
    {
        parent::setUp();

        // Membuat data dummy menu_items untuk tiap phase dan blood_group
        $phases = ['Sarapan', 'Makan Siang', 'Camilan', 'Makan Malam'];
        $blood_group = 'O';

        foreach ($phases as $phase) {
            for ($i = 1; $i <= 5; $i++) {
                DB::table('menu_items')->insert([
                    'name' => "Menu $phase $i",
                    'prot_pct' => 30,
                    'carb_pct' => 40,
                    'fat_pct' => 30,
                    'blood_group' => $blood_group,
                    'phase' => $phase,
                    'calories' => 2000,
                    'ingredients' => 'Ingredients',
                    'steps' => 'Steps',
                    'image' => 'image.jpg',
                ]);
            }
        }
    }

    /** @test */
    public function it_redirects_to_planner_create_if_session_is_missing()
    {
        // Session kosong, harus redirect
        $response = $this->get(route('menu.index'));

        $response->assertRedirect(route('planner.create'));
    }

    /** @test */
    public function it_shows_menu_page_with_proper_data()
    {
        // Set session
        Session::put('blood_group', 'O');
        Session::put('calories', 2000);

        $response = $this->get(route('menu.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.menu');
        $response->assertViewHasAll(['group', 'calories', 'grams', 'phases', 'menus']);

        // Cek nilai grams dihitung sesuai formula
        $grams = $response->viewData('grams');
        $this->assertEquals(round((2000 * 25 / 100) / 4), $grams['carb']);
        $this->assertEquals(round((2000 * 25 / 100) / 9), $grams['fat']);
        $this->assertEquals(round((2000 * 50 / 100) / 4), $grams['prot']);
    }
    /** @test */
    public function it_refreshes_only_the_given_phase_when_refresh_query_is_set()
    {
        Session::put('blood_group', 'O');
        Session::put('calories', 2000);

        $dummyMenuItem = (object) [
            'id' => 1,
            'name' => 'Dummy Menu',
            'prot_pct' => 30,
            'carb_pct' => 40,
            'fat_pct' => 30,
            'blood_group' => 'O',
            'phase' => 'Makan Siang',
            'calories' => 2000,
            'ingredients' => 'Ingredients',
            'steps' => 'Steps',
            'image' => 'image.jpg',
        ];

        // Simulasi isi session menu untuk fase selain 'Sarapan'
        Session::put('menu.Makan Siang', collect([$dummyMenuItem]));
        Session::put('menu.Camilan', collect([$dummyMenuItem]));
        Session::put('menu.Makan Malam', collect([$dummyMenuItem]));

        $response = $this->get(route('menu.index', ['refresh' => 'Sarapan']));

        $response->assertStatus(200);
        $menus = $response->viewData('menus');

        // Fase Sarapan harus baru (bukan dummy)
        $this->assertNotEquals(collect([$dummyMenuItem]), $menus['Sarapan']);

        // Fase lain harus tetap dummy data
        $this->assertEquals(collect([$dummyMenuItem]), $menus['Makan Siang']);
        $this->assertEquals(collect([$dummyMenuItem]), $menus['Camilan']);
        $this->assertEquals(collect([$dummyMenuItem]), $menus['Makan Malam']);
    }
}
