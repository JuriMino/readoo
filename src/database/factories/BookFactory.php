<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title'       => fake()->sentence(3),
            'author'      => fake()->name(),
            'publisher'   => fake()->company(),
            'status'      => fake()->randomElement(array_keys(Book::STATUSES)),
            'genre'       => fake()->randomElement(array_keys(Book::GENRES)),
            'started_at'  => null,
            'finished_at' => null,
            'summary'     => fake()->paragraph(),
            'memo'        => null,
        ];
    }
}
