<?php

namespace Database\Factories;

use App\Models\ItemReport;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'item_report_id' => ItemReport::factory()->approved(),
            'sender_id' => User::factory(),
            'recipient_id' => User::factory(),
            'body' => fake()->paragraph(),
            'read_at' => null,
        ];
    }
}
