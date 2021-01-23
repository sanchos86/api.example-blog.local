<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Post, Tag};

class PostTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::all();
        $tags = Tag::all()->modelKeys();
        $tagsCount = count($tags);

        foreach ($posts as $post) {
            $indices = array_rand($tags, rand(1, $tagsCount - 1));
            $indices = is_int($indices) ? [$indices] : $indices;
            $values = [];
            foreach ($indices as $index) {
                $values[] = $tags[$index];
            }
            $post->tags()->attach($values);
        }
    }
}
