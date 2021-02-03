<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Category, Post};

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all()->modelKeys();

        for ($i = 0; $i < 25; $i++) {
            $categoryId = $categories[array_rand($categories)];
            Post::factory()->create(['category_id' => $categoryId]);
        }
    }
}
