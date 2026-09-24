<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asset_code' => fake()->unique()->bothify('AST-#####'),
            'name' => fake()->randomElement([
                'Lenovo ThinkPad T14',
                'Dell Latitude 3420',
                'HP EliteDesk 800',
                'Epson EcoTank L3210',
                'MikroTik hAP AC2',
            ]) . ' ' . fake()->unique()->numberBetween(100, 999),
            'category' => fake()->randomElement(['Laptop', 'PC Desktop', 'Printer', 'Router']),
            'condition' => fake()->randomElement(['Baik', 'Perbaikan', 'Rusak']),
            'problem_description' => null,
            'image' => null,
            'assigned_to' => null,
        ];
    }

    /**
     * Aset yang sedang dipinjamkan ke seseorang.
     */
    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'assigned_to' => fake()->name(),
        ]);
    }
}
