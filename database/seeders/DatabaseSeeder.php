<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'], // lookup condition
            ['name' => 'Test User']          // values to update/create
        );

        DB::table('statuses')->insert([
            ['name' => 'Not Started'],
            ['name' => 'In Progress'],
            ['name' => 'Stuck'],
            ['name' => 'Complete'],
        ]);
    }
}
