<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

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
            [
                'name' => 'Test User',
                'password' => Hash::make('asdfasdf'),          // values to update/create
                'is_admin' => 1,
            ],
        );

        DB::table('statuses')->insert([
            ['name' => 'Not Started'],
            ['name' => 'In Progress'],
            ['name' => 'Stuck'],
            ['name' => 'Complete'],
        ]);

        DB::table('departments')->insert([
            ['name' => 'Animation'],
            ['name' => 'Code'],
            ['name' => 'Sound'],
            ['name' => 'Concept'],
            ['name' => 'UI'],
            ['name' => 'Other'],
        ]);
    }
}
