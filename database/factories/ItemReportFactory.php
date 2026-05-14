<?php

namespace Database\Factories;

use App\Models\ItemReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemReport>
 */
class ItemReportFactory extends Factory
{
    protected $model = ItemReport::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(ItemReport::TYPES),
            'title' => fake()->words(3, true),
            'category' => fake()->randomElement(['Electronics', 'Documents', 'Keys', 'Bags', 'Clothing']),
            'description' => fake()->paragraph(),
            'location' => fake()->city(),
            'item_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'image_path' => null,
            'status' => 'pending',
            'admin_notes' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }
}
