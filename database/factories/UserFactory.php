<?php

namespace Database\Factories;

use App\Helpers\HelperFunctions;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->firstName();
        $surname = fake()->lastName();
        $id_prefix = ['M', 'G', 'A'];
        return [
            'name' => $name . " " . $surname,
            'abbreviation' => HelperFunctions::getInitials($name, $surname),
            'email' => fake()->unique()->safeEmail(),
            'id_card_number' => random_int(11111, 99999) . "(" . $id_prefix[array_rand($id_prefix)] . ")",
            'mobile' => fake()->phoneNumber(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
