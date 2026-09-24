<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_profile_page_loads_for_logged_in_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/profile')
            ->assertOk()
            ->assertSee('Ganti Password');
    }

    public function test_user_can_update_name_and_email(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile', [
            'name' => 'Nama Baru',
            'email' => 'baru@perusahaan.com',
        ])->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'email' => 'baru@perusahaan.com',
        ]);
    }

    public function test_password_requires_correct_current_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'password-salah',
            'password' => 'barubaru123',
            'password_confirmation' => 'barubaru123',
        ])->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'password',
            'password' => 'barubaru123',
            'password_confirmation' => 'barubaru123',
        ])->assertRedirect(route('profile.edit'));

        $this->assertTrue(Hash::check('barubaru123', $user->fresh()->password));
        $this->assertFalse(Hash::check('password', $user->fresh()->password));
    }
}
