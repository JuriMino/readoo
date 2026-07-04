<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Knowledge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Knowledge>
 */
class KnowledgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id'   => Book::factory(),
            'title'     => fake()->sentence(3),
            'book_page' => 'P.'.fake()->numberBetween(1,500),
            'content'   => fake()->paragraph(),
            'tag1'      => fake()->word(),
            'tag2'      => null,
            'tag3'      => null,
        ];
    }
}
