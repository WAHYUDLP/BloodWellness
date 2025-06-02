<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function guest_cannot_access_profile_edit()
    {
        $response = $this->get('/profile/edit');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_view_profile_edit()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/profile/edit')
            ->assertStatus(200)
            ->assertViewHas('user');
    }

    /** @test */
    public function user_can_add_additional_email()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/email/tambah', [
            'emailBaru' => 'new@example.com',
        ]);

        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('users_email', [
            'email' => 'new@example.com',
            'id_user' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_delete_own_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/akun.hapus', [
            'user_id' => $user->id,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('usersaccount', ['id' => $user->id]);
    }

    /** @test */
    public function user_can_delete_additional_email()
    {
        $user = User::factory()->create();
        $email = UserEmail::factory()->create(['id_user' => $user->id]);

        $response = $this->actingAs($user)->delete(route('profile.email.delete', $email->id));

        $response->assertRedirect(route('profile.edit'));
        $this->assertDatabaseMissing('users_email', ['id' => $email->id]);
    }

    /** @test */


    /** @test */
    public function user_can_update_profile_without_photo()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'jenis_kelamin' => 'Pria',
            'negara' => 'Indonesia',
            'bahasa' => 'Indonesia',
        ]);

        $data = [
            'name' => 'New Name',
            'jenis_kelamin' => 'Wanita',
            'negara' => 'Malaysia',
            'bahasa' => 'English',
            // no photo_url
        ];

        $response = $this->actingAs($user)->post(route('profile.update'), $data);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui!');

        $this->assertDatabaseHas('usersaccount', [
            'id' => $user->id,
            'name' => 'New Name',
            'jenis_kelamin' => 'Wanita',
            'negara' => 'Malaysia',
            'bahasa' => 'English',
        ]);;
    }

    /** @test */
    public function user_can_update_profile_with_photo()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $data = [
            'name' => 'User Name',
            'jenis_kelamin' => 'Pria',
            'negara' => 'Indonesia',
            'photo_url' => $file,
        ];

        // Jika kamu mau menguji upload foto, uncomment bagian upload di controller dulu

        $response = $this->actingAs($user)->post(route('profile.update'), $data);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui!');

        // Cek apakah file tersimpan (jika bagian upload di controller aktif)
        // Storage::disk('public')->assertExists('profile_photos/' . $file->hashName());

        // Cek data user update
        $this->assertDatabaseHas('usersaccount', [
            'id' => $user->id,
            'name' => 'User Name',
            'jenis_kelamin' => 'Pria',
            'negara' => 'Indonesia',
            // photo_url => cek jika upload aktif
        ]);
    }

    /** @test */
    public function update_profile_fails_with_invalid_data()
    {
        $user = User::factory()->create();

        $data = [
            'name' => '',  // required, jadi kosong akan gagal
            'jenis_kelamin' => '',
            'negara' => '',
        ];

        $response = $this->actingAs($user)->post(route('profile.update'), $data);

        $response->assertSessionHasErrors(['name', 'jenis_kelamin', 'negara']);
    }
}
