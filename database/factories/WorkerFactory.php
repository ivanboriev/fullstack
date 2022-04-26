<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

class WorkerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        try {
            return [
                'name' => $this->faker->name(),
                'salary' => random_int(500, 10000),
            ];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
