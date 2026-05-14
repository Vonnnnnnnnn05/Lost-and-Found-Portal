<?php

namespace Tests\Feature;

use App\Models\ItemReport;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_message_owner_of_approved_report(): void
    {
        $owner = User::factory()->create();
        $sender = User::factory()->create();
        $report = ItemReport::factory()->approved()->create(['user_id' => $owner->id]);

        $this->actingAs($sender)->post("/reports/{$report->id}/messages", [
            'body' => 'I may have found this item.',
        ])->assertRedirect('/messages');

        $this->assertDatabaseHas('messages', [
            'item_report_id' => $report->id,
            'sender_id' => $sender->id,
            'recipient_id' => $owner->id,
            'body' => 'I may have found this item.',
        ]);
    }

    public function test_message_requires_login_and_approved_report(): void
    {
        $pending = ItemReport::factory()->create(['status' => 'pending']);

        $this->post("/reports/{$pending->id}/messages", ['body' => 'Hello'])->assertRedirect('/login');
        $this->actingAs(User::factory()->create())
            ->post("/reports/{$pending->id}/messages", ['body' => 'Hello'])
            ->assertNotFound();
    }

    public function test_message_inbox_shows_user_messages(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $message = Message::factory()->create([
            'sender_id' => $other->id,
            'recipient_id' => $user->id,
            'body' => 'Please check the report details.',
        ]);

        $this->actingAs($user)
            ->get('/messages')
            ->assertOk()
            ->assertSee('Please check the report details.')
            ->assertSee($message->report->title);
    }
}
