<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PimpinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pimpinan@ung.ac.id'],
            [
                'name' => 'Pimpinan UNG',
                'password' => Hash::make('pimpinan123'),
                'role' => 'pimpinan', 
            ]
        );
    }
}
