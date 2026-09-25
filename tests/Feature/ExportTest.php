<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_is_redirected_from_export_routes(): void
    {
        $this->get(route('assets.export'))->assertRedirect(route('login'));
        $this->get(route('assets.export.pdf'))->assertRedirect(route('login'));
    }

    public function test_staff_can_download_excel_export(): void
    {
        Asset::factory()->count(3)->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('assets.export'))
            ->assertOk();

        $this->assertStringContainsString(
            'Laporan_Aset_IT_',
            $response->headers->get('content-disposition')
        );
        $this->assertStringContainsString(
            '.xlsx',
            $response->headers->get('content-disposition')
        );
    }

    public function test_staff_can_download_pdf_export(): void
    {
        Asset::factory()->count(3)->create();

        $this->actingAs(User::factory()->create())
            ->get(route('assets.export.pdf'))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_excel_export_follows_category_filter(): void
    {
        Asset::factory()->count(2)->create(['category' => 'Laptop']);
        Asset::factory()->count(5)->create(['category' => 'Printer']);

        $response = $this->actingAs($this->admin())
            ->get(route('assets.export', ['category' => 'Laptop']))
            ->assertOk();

        $this->assertStringContainsString(
            'Laporan_Aset_IT_',
            $response->headers->get('content-disposition')
        );
    }

    public function test_pdf_export_follows_category_filter(): void
    {
        Asset::factory()->count(2)->create(['category' => 'Laptop']);
        Asset::factory()->count(5)->create(['category' => 'Printer']);

        $this->actingAs($this->admin())
            ->get(route('assets.export.pdf', ['category' => 'Laptop']))
            ->assertOk();
    }

    public function test_history_page_filters_by_date_range(): void
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        AssetHistory::factory()->create([
            'asset_id' => $asset->id,
            'user_id' => $user->id,
            'notes' => 'Catatan Lama Khusus',
            'created_at' => now()->subDays(10),
        ]);
        AssetHistory::factory()->create([
            'asset_id' => $asset->id,
            'user_id' => $user->id,
            'notes' => 'Catatan Baru Khusus',
            'created_at' => now(),
        ]);

        // Hanya log 3 hari terakhir yang tampil
        $this->actingAs($user)
            ->get(route('assets.history', [
                'start_date' => now()->subDays(3)->format('Y-m-d'),
                'end_date' => now()->format('Y-m-d'),
            ]))
            ->assertOk()
            ->assertSee('Catatan Baru Khusus')
            ->assertDontSee('Catatan Lama Khusus');
    }

    public function test_asset_filter_scope_applies_search(): void
    {
        Asset::factory()->create(['name' => 'Lenovo ThinkPad Unik', 'asset_code' => 'AST-UNIK-001']);
        Asset::factory()->create(['name' => 'Printer Biasa', 'asset_code' => 'AST-22222']);

        $request = \Illuminate\Http\Request::create('/', 'GET', ['search' => 'AST-UNIK-001']);
        $hasil = Asset::filter($request)->get();

        $this->assertCount(1, $hasil);
        $this->assertEquals('Lenovo ThinkPad Unik', $hasil->first()->name);
    }
}
