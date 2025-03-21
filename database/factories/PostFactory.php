<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'category_id' => 1,
            'judul_berita' => fake()->sentence(),
            'tanggal' => fake()->date(),
            'status' => fake()->randomElement(['draft', 'publish']),
            'jumlah_view' => fake()->numberBetween(0, 1000),
            'images' => null,
            'images_caption' => fake()->sentence(),
            'content_body' => fake()->paragraphs(3, true),
        ];
    }
}
