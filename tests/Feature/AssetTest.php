<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    private function staff(): User
    {
        return User::factory()->create();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/assets/dashboard')->assertRedirect('/login');
    }

    public function test_staff_can_view_index_show_and_edit(): void
    {
        $asset = Asset::factory()->create();

        $this->actingAs($this->staff())->get('/assets')->assertOk()->assertSee($asset->asset_code);
        $this->actingAs($this->staff())->get('/assets/' . $asset->id)->assertOk();
        $this->actingAs($this->staff())->get('/assets/' . $asset->id . '/edit')->assertOk();
    }

    public function test_staff_is_forbidden_from_create_store_and_destroy(): void
    {
        $staff = $this->staff();
        $asset = Asset::factory()->create();

        $this->actingAs($staff)->get('/assets/create')
            ->assertForbidden()
            ->assertSee('Akses Ditolak');

        $this->actingAs($staff)->post('/assets', [
            'asset_code' => 'X-0001',
            'name' => 'Tidak Boleh',
            'category' => 'Laptop',
            'condition' => 'Baik',
        ])->assertForbidden();

        $this->actingAs($staff)->delete('/assets/' . $asset->id)->assertForbidden();

        $this->assertNull($asset->fresh()->deleted_at);
        $this->assertDatabaseMissing('assets', ['asset_code' => 'X-0001']);
    }

    public function test_staff_can_update_asset_and_optional_fields_are_saved(): void
    {
        $staff = $this->staff();
        $asset = Asset::factory()->create();

        $this->actingAs($staff)->put('/assets/' . $asset->id, [
            'asset_code' => $asset->asset_code,
            'name' => $asset->name,
            'category' => $asset->category,
            'condition' => $asset->condition,
            'assigned_to' => 'Dedi Kurniawan',
            'problem_description' => 'Keyboard kurang responsif',
        ])->assertRedirect(route('assets.index'));

        $fresh = $asset->fresh();
        $this->assertSame('Dedi Kurniawan', $fresh->assigned_to);
        $this->assertSame('Keyboard kurang responsif', $fresh->problem_description);

        $this->assertDatabaseHas('asset_histories', [
            'asset_id' => $asset->id,
            'action' => 'Mutasi Pemakai',
        ]);
    }

    public function test_admin_can_create_asset_with_registration_log(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/assets', [
            'asset_code' => 'TEST-001',
            'name' => 'Laptop Uji',
            'category' => 'Laptop',
            'condition' => 'Baik',
            'assigned_to' => 'Peserta Uji',
        ])->assertRedirect(route('assets.index'));

        $this->assertDatabaseHas('assets', ['asset_code' => 'TEST-001', 'assigned_to' => 'Peserta Uji']);
        $this->assertDatabaseHas('asset_histories', [
            'action' => 'Registrasi Aset Baru',
            'user_id' => $admin->id,
        ]);
    }

    public function test_update_logs_condition_mutasi_and_previously_missing_fields(): void
    {
        $admin = $this->admin();
        $asset = Asset::factory()->create(['name' => 'Nama Lama', 'condition' => 'Baik']);

        $this->actingAs($admin)->put('/assets/' . $asset->id, [
            'asset_code' => 'CHG-001',
            'name' => 'Nama Baru',
            'category' => $asset->category,
            'condition' => 'Rusak',
            'problem_description' => 'Layar retak',
            'assigned_to' => 'Teknisi Uji',
        ])->assertRedirect(route('assets.index'));

        $this->assertDatabaseHas('asset_histories', ['asset_id' => $asset->id, 'action' => 'Mutasi Pemakai']);
        $this->assertDatabaseHas('asset_histories', ['asset_id' => $asset->id, 'action' => 'Perubahan Kondisi']);

        $log = AssetHistory::where('asset_id', $asset->id)
            ->where('action', 'Perubahan Data')
            ->first();

        $this->assertNotNull($log);
        $this->assertStringContainsString('Kode Aset', $log->notes);
        $this->assertStringContainsString('CHG-001', $log->notes);
        $this->assertStringContainsString('Nama Barang', $log->notes);
        $this->assertStringContainsString('Nama Baru', $log->notes);
    }

    public function test_delete_moves_asset_to_trash_and_can_be_restored(): void
    {
        $admin = $this->admin();
        $asset = Asset::factory()->create();

        // 1. Hapus → soft delete, log tercatat & tetap terhubung ke aset
        $this->actingAs($admin)->delete('/assets/' . $asset->id)
            ->assertRedirect(route('assets.index'));

        $this->assertSoftDeleted('assets', ['id' => $asset->id]);
        $this->assertDatabaseHas('asset_histories', [
            'asset_id' => $asset->id,
            'action' => 'Penghapusan Aset',
        ]);
        $this->assertTrue(
            AssetHistory::where('asset_id', $asset->id)
                ->where('action', 'Penghapusan Aset')
                ->where('notes', 'like', '%Kotak Sampah%')
                ->exists()
        );

        // 2. Hilang dari index, muncul di Kotak Sampah
        $this->actingAs($admin)->get('/assets')->assertOk()->assertDontSee($asset->asset_code);
        $this->actingAs($admin)->get('/assets/trash')->assertOk()->assertSee($asset->asset_code);

        // 3. Relasi history tetap menampilkan kode aset (withTrashed)
        $log = AssetHistory::where('asset_id', $asset->id)->first();
        $this->assertNotNull($log->asset);
        $this->assertSame($asset->asset_code, $log->asset->asset_code);
        $this->actingAs($admin)->get('/assets-history')->assertOk();

        // 4. Pulihkan
        $this->actingAs($admin)->post('/assets/' . $asset->id . '/restore')
            ->assertRedirect(route('assets.trash'));

        $this->assertNull($asset->fresh()->deleted_at);
        $this->actingAs($admin)->get('/assets')->assertOk()->assertSee($asset->asset_code);
        $this->assertTrue(
            AssetHistory::where('asset_id', $asset->id)
                ->where('action', 'Perubahan Data')
                ->where('notes', 'like', '%dipulihkan%')
                ->exists()
        );
    }

    public function test_admin_can_force_delete_from_trash(): void
    {
        $admin = $this->admin();
        $asset = Asset::factory()->create();
        $asset->delete();

        $this->actingAs($admin)->delete('/assets/' . $asset->id . '/force')
            ->assertRedirect(route('assets.trash'));

        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
        $this->assertDatabaseHas('asset_histories', [
            'action' => 'Penghapusan Aset',
            'asset_id' => null,
        ]);
        $this->assertTrue(
            AssetHistory::where('action', 'Penghapusan Aset')
                ->where('notes', 'like', '%dihapus permanen%')
                ->exists()
        );
    }

    public function test_staff_cannot_access_trash_routes(): void
    {
        $staff = $this->staff();
        $asset = Asset::factory()->create();
        $asset->delete();

        $this->actingAs($staff)->get('/assets/trash')->assertForbidden();
        $this->actingAs($staff)->post('/assets/' . $asset->id . '/restore')->assertForbidden();
        $this->actingAs($staff)->delete('/assets/' . $asset->id . '/force')->assertForbidden();

        $this->assertSoftDeleted('assets', ['id' => $asset->id]);
    }
}
