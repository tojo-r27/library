<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'published_year' => $this->faker->numberBetween(1600, (int) date('Y')),
            'summary' => $this->faker->optional()->paragraph(),
            'available' => $this->faker->boolean(),
        ];
    }
}
