<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'text' => $this->faker->text,
            'slug' => $this->faker->unique()->slug,
            'published_at' => rand(0, 10) > 5 ? date('Y-m-d') : null,
            'src' => '',
            'plain_text' => $this->faker->realText(200)
        ];
    }
}
