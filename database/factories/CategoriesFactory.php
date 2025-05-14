<?php

namespace Database\Factories;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoriesFactory extends Factory
{
    protected $model = Categories::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word;

        return [
            'name' => $name,
            'description' => $this->faker->sentence(10), // Generate a random sentence for description
            'slug' => Str::slug($name), // Generate a slug from the name
            'parent_id' => null, // Adjust if you have parent-child relationships
            'active' => $this->faker->boolean,
            'created_by' => 1, // Replace with a valid user ID
            'updated_by' => 1, // Replace with a valid user ID
        ];
    }
}
