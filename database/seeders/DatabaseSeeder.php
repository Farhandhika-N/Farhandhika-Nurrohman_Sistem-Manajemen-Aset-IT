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
        $this->call([
            AssetSeeder::class,
        ]);
        
        // Akun Admin
        User::firstOrCreate([
            'email' => 'admin@aset.com',
        ], [
            'name' => 'Administrator IT',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Akun Staff
        User::firstOrCreate([
            'email' => 'staff@aset.com',
        ], [
            'name' => 'Staff Manajemen',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);
    }
}
