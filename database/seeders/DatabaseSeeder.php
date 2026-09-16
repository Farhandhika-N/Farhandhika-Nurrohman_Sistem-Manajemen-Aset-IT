<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            AssetSeeder::class,
        ]);
        
        // Akun Admin
        User::create([
            'name' => 'Administrator IT',
            'email' => 'admin@aset.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Akun Staff
        User::create([
            'name' => 'Staff Manajemen',
            'email' => 'staff@aset.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);
    }
}
