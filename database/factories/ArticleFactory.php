<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id'     =>  '1',
            'date'          =>  $this->faker->date(),
            'name'          =>  $this->faker->sentence(),
            'short_desc'    =>  $this->faker->paragraph(),
            'desc'          =>  $this->faker->text()
        ];
    }
}