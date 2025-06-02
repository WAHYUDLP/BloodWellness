<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class RecipeTest extends TestCase
{
    use DatabaseTransactions;  // Ini biar setiap test rollback otomatis

    /** @test */
    public function it_redirects_to_menu_index_if_id_is_not_a_digit()
    {
        $response = $this->get(route('recipe.show', ['id' => 'abc']));

        $response->assertRedirect(route('menu.index'));
    }

    /** @test */
    public function it_redirects_to_menu_index_if_menu_not_found()
    {
        // Pastikan tidak ada data di tabel menu_items
        DB::table('menu_items')->truncate();

        $response = $this->get(route('recipe.show', ['id' => 9999]));

        $response->assertRedirect(route('menu.index'));
    }

    /** @test */
    /** @test */
    public function it_shows_recipe_view_with_correct_data()
    {
        $id = DB::table('menu_items')->insertGetId([
            'name' => 'Menu Test',
            'prot_pct' => 30,
            'carb_pct' => 40,
            'fat_pct' => 30,
            'blood_group' => 'O',
            'phase' => 'Sarapan',
            'calories' => 2000,
            'ingredients' => 'Example ingredients',
            'steps' => 'Example steps',
            'image' => 'example.jpg',
        ]);

        $response = $this->get(route('recipe.show', ['id' => $id]));

        $response->assertStatus(200);
        $response->assertViewIs('pages.recipe');
        $response->assertViewHas('menu');
        $response->assertViewHas('prot', 30);
        $response->assertViewHas('carb', 40);
        $response->assertViewHas('fat', 30);
    }
}
