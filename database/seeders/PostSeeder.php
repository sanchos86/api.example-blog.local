<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Category, Post};
use Illuminate\Http\UploadedFile;

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
            $image = UploadedFile::fake()->image('image_' . $i . '.png', 160, 90);
            $src = $image->store(null);
            Post::factory()->create(['category_id' => $categoryId, 'src' => $src]);
        }
    }
}
