<?php

namespace Tests\Feature;

use App\Models\ItemReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_require_admin_user(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    public function test_admin_can_update_report_status(): void
    {
        $admin = User::factory()->admin()->create();
        $report = ItemReport::factory()->create(['status' => 'pending']);

        $this->actingAs($admin)->patch("/admin/reports/{$report->id}", [
            'status' => 'approved',
            'admin_notes' => 'Verified at front desk.',
        ])->assertRedirect();

        $this->assertDatabaseHas('item_reports', [
            'id' => $report->id,
            'status' => 'approved',
            'admin_notes' => 'Verified at front desk.',
        ]);
    }

    public function test_admin_can_export_reports_csv(): void
    {
        $admin = User::factory()->admin()->create();
        ItemReport::factory()->create(['title' => 'Exported item']);

        $response = $this->actingAs($admin)->get('/admin/reports/export');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Exported item', $response->streamedContent());
    }
}
