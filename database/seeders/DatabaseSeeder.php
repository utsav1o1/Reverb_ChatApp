<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Utsav Karki',
            'email' => 'utsav@example.com',
        ]);
        User::factory()->create([
            'name' => 'Sambit Rimal',
            'email' => 'sambit@example.com',
        ]);
        User::factory()->create([
            'name' => 'Rakesh Thapa',
            'email' => 'rakesh@example.com',
        ]);
    }
}
