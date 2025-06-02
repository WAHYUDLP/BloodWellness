<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Mail\OTPVerificationMail;
use Illuminate\Support\Facades\Hash;

class LupaPasswordTest extends TestCase
{
    use DatabaseTransactions;  // Ini biar setiap test rollback otomatis

    /** @test */
    public function lupa_password_form_loads_successfully()
    {
        $response = $this->get(route('lupa-password.form'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.lupa_password');
    }

    /** @test */
    public function send_otp_fails_with_invalid_email()
    {
        $response = $this->post(route('lupa-password.sendOtp'), ['email' => 'notanemail']);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function send_otp_fails_when_email_not_found()
    {
        $response = $this->post(route('lupa-password.sendOtp'), ['email' => 'notfound@example.com']);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function send_otp_succeeds_and_stores_otp_in_db_and_session()
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->post(route('lupa-password.sendOtp'), ['email' => 'user@example.com']);

        $response->assertRedirect(route('verifikasi_otp.form'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('password_resets', [
            'email' => 'user@example.com',
        ]);
        $this->assertEquals(session('otp_email'), 'user@example.com');

        Mail::assertSent(OTPVerificationMail::class, function ($mail) use ($user) {
            return $mail->hasTo('user@example.com');
        });
    }

    /** @test */
    public function show_verify_form_loads_successfully()
    {
        $response = $this->get(route('verifikasi_otp.form'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.verifikasi_otp');
    }

    /** @test */
    public function verify_otp_fails_if_no_session_or_no_input()
    {
        $response = $this->post(route('verifikasi_otp.process'), ['otp' => ['0', '0', '0', '0', '0', '0']]);
        $response->assertSessionHas('error');
    }

    /** @test */
    public function verify_otp_fails_if_wrong_or_expired_otp()
    {
        // Simulasi simpan OTP di db
        $email = 'user@example.com';
        session(['otp_email' => $email]);
        DB::table('password_resets')->insert([
            'email' => $email,
            'otp' => '123456',
            'expires_at' => Carbon::now()->subMinute(), // sudah expired
            'created_at' => Carbon::now(),
        ]);

        $response = $this->post(route('verifikasi_otp.process'), ['otp' => ['1', '2', '3', '4', '5', '7']]);
        $response->assertSessionHas('error');

        // OTP valid tapi expired
        $response2 = $this->post(route('verifikasi_otp.process'), ['otp' => ['1', '2', '3', '4', '5', '6']]);
        $response2->assertSessionHas('error');
    }

    /** @test */
    public function verify_otp_succeeds_with_correct_otp()
    {
        $email = 'user@example.com';
        session(['otp_email' => $email]);
        DB::table('password_resets')->insert([
            'email' => $email,
            'otp' => '123456',
            'expires_at' => Carbon::now()->addMinutes(5),
            'created_at' => Carbon::now(),
        ]);

        $response = $this->post(route('verifikasi_otp.process'), ['otp' => ['1', '2', '3', '4', '5', '6']]);
        $response->assertRedirect(route('reset_password.form'));

        $this->assertTrue(session('otp_verified'));
        $this->assertEquals(session('reset_email'), $email);
    }

    /** @test */
    public function reset_password_form_redirects_if_no_otp_verified_session()
    {
        $response = $this->get(route('reset_password.form'));
        $response->assertRedirect(route('verifikasi_otp.form'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function reset_password_form_loads_if_otp_verified()
    {
        $this->withSession(['otp_verified' => true]);

        $response = $this->get(route('reset_password.form'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.reset_password');
    }

    /** @test */
    public function reset_password_fails_validation()
    {
        $this->withSession(['otp_verified' => true, 'reset_email' => 'user@example.com']);

        $response = $this->post(route('reset_password.submit'), [
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function reset_password_fails_if_no_session_or_user_not_found()
    {
        // tanpa session reset_email & otp_verified
        $response = $this->post(route('reset_password.submit'), [
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);
        $response->assertRedirect(route('reset_password.form'));
        $response->assertSessionHasErrors();

        // dengan session tapi user gak ada
        $this->withSession(['otp_verified' => true, 'reset_email' => 'notfound@example.com']);
        $response2 = $this->post(route('reset_password.submit'), [
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);
        $response2->assertRedirect(route('reset_password.form'));
        $response2->assertSessionHasErrors();
    }

    /** @test */
    public function reset_password_succeeds_and_clears_session_and_db()
    {
        $user = User::factory()->create(['email' => 'user@example.com', 'password' => bcrypt('oldpass')]);

        DB::table('password_resets')->insert([
            'email' => 'user@example.com',
            'otp' => '123456',
            'expires_at' => Carbon::now()->addMinutes(5),
            'created_at' => Carbon::now(),
        ]);

        $this->withSession(['otp_verified' => true, 'reset_email' => 'user@example.com']);

        $response = $this->post(route('reset_password.submit'), [
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        // Password di DB berubah
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));

        // OTP dihapus
        $this->assertDatabaseMissing('password_resets', ['email' => 'user@example.com']);

        // Session dihapus otomatis
        $this->assertFalse(session()->has('reset_email'));
        $this->assertFalse(session()->has('otp_verified'));
    }

    /** @test */
    public function resend_otp_fails_if_no_session_email()
    {
        $response = $this->get(route('otp.resend'));
        $response->assertRedirect(route('lupa-password.form'));
        $response->assertSessionHas('error');
    }


    /** @test */
    public function resend_otp_succeeds_and_updates_db_and_sends_email()
    {
        Mail::fake();

        $email = 'user@example.com';
        session(['otp_email' => $email]);

        DB::table('password_resets')->insert([
            'email' => $email,
            'otp' => '111111',
            'expires_at' => Carbon::now()->addMinutes(5),
            'created_at' => Carbon::now(),
        ]);

        $response = $this->get(route('otp.resend'));

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('password_resets', ['email' => $email]);

        Mail::assertSent(\App\Mail\OTPVerificationMail::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }
}
