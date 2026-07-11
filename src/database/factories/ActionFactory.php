<?php

namespace Database\Factories;

use App\Models\Action;
use App\Models\Book;
use App\Models\Knowledge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Action>
 */
class ActionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id'       => Book::factory(),
            'knowledge_id'  => null,
            'title'         => fake()->sentence(3),
            'book_page'     => 'P.'.fake()->numberBetween(1,500),
            'timing'        => fake()->word(),
            'place'         => fake()->word(),
            'target_person' => null,
            'detail'        => fake()->sentence(),
            'reason'        => fake()->sentence(),
            'method'        => fake()->sentence(),
            'tag1'          => fake()->word(),
            'tag2'          => null,
            'tag3'          => null,
        ];
    }

    public function forKnowledge(Knowledge $knowledge): static
    {
        return $this->state(fn() => [
            'book_id'      => $knowledge->book_id,
            'knowledge_id' => $knowledge->id,
        ]);
    }
}
