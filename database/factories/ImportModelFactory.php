<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImportModel>
 */
class ImportModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::pluck('id')->random(),
            'first_name' => $this->faker->firstName(2, 50),
            'last_name'  => $this->faker->lastName(2, 50),
            'middle_name' => $this->faker->lastName(8, 20),
            'address_street' => $this->faker->streetAddress(2, 100),
            'address_brgy' => $this->faker->citySuffix(2, 100),
            'address_city' => $this->faker->city(2, 50),
            'address_province' => $this->faker->state(2, 50),
            'contact_phone' => substr($this->faker->phoneNumber, 0, 15),
            'contact_mobile' => '09' . $this->faker->numberBetween(100000000, 999999999),
            'email' => $this->faker->unique()->safeEmail,
        ];
        
    }
}
