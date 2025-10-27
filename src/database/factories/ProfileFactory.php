<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ProfileFactory extends Factory
{
    protected $model = \App\Models\Profile::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'postal_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'building' => $this->faker->optional()->buildingNumber(),
            'profile_image' => 'images/' . $this->faker->image('public/storage/images', 400, 400, null, false),
        ];
    }
}
