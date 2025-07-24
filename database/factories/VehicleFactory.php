<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModel of \App\Models\Vehicle
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class VehicleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var class-string<TModel>
     */
    protected $model = Vehicle::class;

    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'registration_number' => strtoupper($this->faker->bothify('??? ###')),
        ];
    }
}
