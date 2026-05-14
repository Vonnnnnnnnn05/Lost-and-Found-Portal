<?php

namespace Tests\Feature;

use App\Models\ItemReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_pending_report(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/reports', [
            'type' => 'lost',
            'title' => 'Black wallet',
            'category' => 'Money',
            'description' => 'Small black leather wallet with cards inside.',
            'location' => 'Library',
            'item_date' => now()->format('Y-m-d'),
            'image' => UploadedFile::fake()->image('wallet.jpg'),
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('item_reports', [
            'user_id' => $user->id,
            'title' => 'Black wallet',
            'status' => 'pending',
        ]);
        Storage::disk('public')->assertExists(ItemReport::first()->image_path);
    }

    public function test_report_validation_requires_core_fields(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/reports', [])
            ->assertSessionHasErrors(['type', 'title', 'category', 'description', 'location', 'item_date']);
    }

    public function test_only_approved_reports_are_publicly_visible(): void
    {
        $approved = ItemReport::factory()->approved()->create(['title' => 'Blue laptop']);
        $pending = ItemReport::factory()->create(['title' => 'Pending phone']);

        $this->get('/reports')->assertOk()->assertSee('Blue laptop')->assertDontSee('Pending phone');
        $this->get("/reports/{$approved->id}")->assertOk()->assertSee('Blue laptop');
        $this->get("/reports/{$pending->id}")->assertNotFound();
    }

    public function test_search_filters_approved_reports(): void
    {
        ItemReport::factory()->approved()->create([
            'title' => 'Red backpack',
            'type' => 'lost',
            'category' => 'Bags',
            'location' => 'Gym',
            'item_date' => '2026-05-01',
        ]);
        ItemReport::factory()->approved()->create([
            'title' => 'Silver keys',
            'type' => 'found',
            'category' => 'Keys',
            'location' => 'Library',
            'item_date' => '2026-05-02',
        ]);

        $this->get('/reports?q=backpack&type=lost&category=Bags&location=Gym&date_from=2026-05-01&date_to=2026-05-01')
            ->assertOk()
            ->assertSee('Red backpack')
            ->assertDontSee('Silver keys');
    }

    public function test_only_owner_can_edit_report(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $report = ItemReport::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)->get("/reports/{$report->id}/edit")->assertForbidden();
        $this->actingAs($owner)->get("/reports/{$report->id}/edit")->assertOk();
    }

    public function test_owner_update_returns_report_to_pending(): void
    {
        $owner = User::factory()->create();
        $report = ItemReport::factory()->approved()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)->put("/reports/{$report->id}", [
            'type' => 'found',
            'title' => 'Updated title',
            'category' => 'Documents',
            'description' => 'Updated description for the item.',
            'location' => 'Front desk',
            'item_date' => now()->format('Y-m-d'),
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('item_reports', [
            'id' => $report->id,
            'title' => 'Updated title',
            'status' => 'pending',
        ]);
    }
}
