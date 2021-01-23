<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Post, PostView};

class PostViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::all();

        foreach ($posts as $post) {
            $postView = new PostView();
            $post->postView()->save($postView);
        }
    }
}
