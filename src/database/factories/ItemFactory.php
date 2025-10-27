<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ItemFactory extends Factory
{
    protected $model = \App\Models\Item::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(3, true),
            'brand' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(1000, 100000),
            'condition' => $this->faker->numberBetween(1, 4),
            'image_path' => 'images/' . $this->faker->image('public/storage/images', 640, 480, null, false),
        ];
    }
}
