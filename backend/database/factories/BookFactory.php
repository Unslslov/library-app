<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
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
        $genres = ['Fiction', 'Non-fiction', 'Fantasy', 'Mystery', 'Thriller', 'Romance', 'Science Fiction', 'Biography', 'History', 'Horror'];

        return [
            'title' => ucwords($this->faker->words(3, true)),
            'author' => $this->faker->name(),
            'genre' => $this->faker->randomElement($genres),
            'publisher' => $this->faker->company(),
            'year_published' => $this->faker->dateTimeBetween('-100 years', 'now'),
            'available_copies' => 100,
            'total_copies' => 100,
            'description' => $this->faker->text(),
        ];
    }
}
