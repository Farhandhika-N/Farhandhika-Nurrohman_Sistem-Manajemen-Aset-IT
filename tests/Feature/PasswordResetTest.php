<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_loads_for_guest(): void
    {
        $this->get('/forgot-password')
            ->assertOk()
            ->assertSee('Lupa Password?');
    }

    public function test_login_page_shows_forgot_password_link(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Lupa password?');
    }

    public function test_reset_link_is_emailed_to_registered_user(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_unknown_email_is_rejected(): void
    {
        $this->from('/forgot-password')
            ->post('/forgot-password', ['email' => 'tidak-ada@aset.com'])
            ->assertRedirect('/forgot-password')
            ->assertSessionHasErrors('email');
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('password-baru-123', $user->fresh()->password));
    }

    public function test_reset_with_invalid_token_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->from('/reset-password/token-salah')
            ->post('/reset-password', [
                'token' => 'token-salah',
                'email' => $user->email,
                'password' => 'password-baru-123',
                'password_confirmation' => 'password-baru-123',
            ])
            ->assertRedirect('/reset-password/token-salah')
            ->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_reset_password_shorter_than_eight_chars_is_rejected(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'pendek',
            'password_confirmation' => 'pendek',
        ])->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_authenticated_user_is_redirected_to_dashboard_from_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect('/assets/dashboard');
    }
}
