<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_staff_cannot_access_user_management(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)->get('/users')->assertForbidden();
        $this->actingAs($staff)->get('/users/create')->assertForbidden();
        $this->actingAs($staff)->post('/users', [
            'name' => 'Hacker',
            'email' => 'hacker@perusahaan.com',
            'role' => 'admin',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'hacker@perusahaan.com']);
    }

    public function test_admin_can_view_and_create_user(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/users')->assertOk();
        $this->actingAs($admin)->get('/users/create')->assertOk();

        $this->actingAs($admin)->post('/users', [
            'name' => 'Karyawan Baru',
            'email' => 'karyawan@perusahaan.com',
            'role' => 'staff',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('users.index'));

        $user = User::where('email', 'karyawan@perusahaan.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('staff', $user->role);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    public function test_duplicate_email_is_rejected(): void
    {
        $admin = $this->admin();
        User::factory()->create(['email' => 'duplikat@perusahaan.com']);

        $this->actingAs($admin)->post('/users', [
            'name' => 'Duplikat',
            'email' => 'duplikat@perusahaan.com',
            'role' => 'staff',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertSessionHasErrors('email');
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = $this->admin();
        $target = User::factory()->create();

        $this->actingAs($admin)->put('/users/' . $target->id, [
            'name' => $target->name,
            'email' => $target->email,
            'role' => 'admin',
        ])->assertRedirect(route('users.index'));

        $this->assertSame('admin', $target->fresh()->role);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->delete('/users/' . $admin->id)
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $admin = $this->admin();
        $target = User::factory()->create();

        $this->actingAs($admin)->delete('/users/' . $target->id)
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }
}
