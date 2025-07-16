<?php

namespace Database\Factories;

use App\Models\Information;
use Illuminate\Database\Eloquent\Factories\Factory;

class InformationFactory extends Factory
{
    protected $model = Information::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(1),
            'description' => $this->faker->paragraph(3),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
}
