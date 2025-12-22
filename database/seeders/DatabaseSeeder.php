<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'admin@instansi.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@instansi.com'],
            [
                'name' => 'User',
                'password' => Hash::make('user123'),
                'role' => 'user'
            ]
        );

        $this->call(KategoriSkSeeder::class);
    }
}