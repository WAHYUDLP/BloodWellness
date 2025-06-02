<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlannerTest extends TestCase
{
    use DatabaseTransactions;  // Ini biar setiap test rollback otomatis

    /** @test */
    public function store_saves_session_and_redirects_to_menu()
    {
        $response = $this->post(route('planner.store'), [
            'blood_group' => 'O',
            'calories' => 2200,
        ]);

        $response->assertRedirect(route('menu.index'));
        $response->assertSessionHas('blood_group', 'O');
        $response->assertSessionHas('calories', 2200);
    }

    /** @test */
    public function index_redirects_to_create_if_session_missing()
    {
        // Harus login karena route pakai middleware auth
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('planner'));

        $response->assertRedirect(route('planner.create'));
    }

    /** @test */
    /** @test */
    public function index_shows_planner_page_with_menus_if_session_available()
    {
        // Kosongkan tabel menu_items dulu agar tidak terjadi duplicate entry
        DB::table('menu_items')->truncate();

        // Insert menu_items dummy
        DB::table('menu_items')->insert([
            [
                'name' => 'Ayam Teriyaki',
                'phase' => 'Sarapan',
                'blood_group' => 'O',
                'calories' => 120,
                'carb_pct' => 35,
                'fat_pct' => 20,
                'prot_pct' => 45,
                'ingredients' => "Ayam 1/4 kg\nBawang 5 siung",
                'steps' => "1. Campur kecap manis, saus tiram, bawang putih halus, garam & lada.\n2. Rendam ayam 30 menit.\n3. Panggang / pan-sear hingga kecokelatan.",
                'image' => 'ayam_teriyaki.jpg'
            ],
            [
                'name' => 'Omlet Rindu',
                'phase' => 'Sarapan',
                'blood_group' => 'O',
                'calories' => 120,
                'carb_pct' => 30,
                'fat_pct' => 25,
                'prot_pct' => 45,
                'ingredients' => "Telur 1/4 kg\nBawang 1 siung",
                'steps' => "1. Kocok telur, tambahkan bawang iris & bumbu.\n2. Tuang ke wajan anti-lengket.\n3. Lipat saat setengah matang, masak sampai matang.",
                'image' => 'omlet_rindu.jpg'
            ],
            [
                'name' => 'Sop Tahu',
                'phase' => 'Sarapan',
                'blood_group' => 'O',
                'calories' => 120,
                'carb_pct' => 20,
                'fat_pct' => 20,
                'prot_pct' => 60,
                'ingredients' => "Tahu 10 iris\nBawang 5 siung",
                'steps' => "1. Rebus kaldu, masukkan bawang & bumbu.\n2. Tambahkan tahu iris, masak 5 menit.\n3. Koreksi rasa, sajikan hangat.",
                'image' => 'sop_tahu.jpg'
            ],
            [
                'name' => 'Roti Bakar O',
                'phase' => 'Sarapan',
                'blood_group' => 'O',
                'calories' => 130,
                'carb_pct' => 55,
                'fat_pct' => 25,
                'prot_pct' => 20,
                'ingredients' => "Roti 2 iris\nMentega 1 sdt",
                'steps' => "1. Oles roti dengan mentega tipis.\n2. Panggang di pan / toaster hingga keemasan.\n3. Sajikan hangat.",
                'image' => 'roti_bakar_o.jpg'
            ],
            [
                'name' => 'Salad Sayur O',
                'phase' => 'Sarapan',
                'blood_group' => 'O',
                'calories' => 110,
                'carb_pct' => 45,
                'fat_pct' => 15,
                'prot_pct' => 40,
                'ingredients' => "Selada 50 gr\nTomat 1 buah",
                'steps' => "1. Cuci sayuran, tiriskan.\n2. Iris tomat & selada, campur dengan dressing ringan.\n3. Sajikan segera.",
                'image' => 'salad_sayur_o.jpg'
            ],
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession([
                'blood_group' => 'O',
                'calories' => 2000
            ])
            ->get(route('planner'))
            ->assertStatus(200)
            ->assertViewHasAll(['group', 'calories', 'grams', 'phases', 'menus']);
    }

}
