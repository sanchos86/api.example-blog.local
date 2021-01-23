<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{UserRole, User};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create(['role' => UserRole::ADMIN]);
        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            PostSeeder::class,
        ]);
    }
}
