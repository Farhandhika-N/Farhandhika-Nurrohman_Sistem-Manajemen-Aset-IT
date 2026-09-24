<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssetHistory>
 */
class AssetHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asset_id' => Asset::factory(),
            'user_id' => User::factory(),
            'action' => fake()->randomElement([
                'Registrasi Aset Baru',
                'Mutasi Pemakai',
                'Perubahan Kondisi',
                'Perubahan Data',
                'Penghapusan Aset',
            ]),
            'notes' => fake()->sentence(),
        ];
    }
}
