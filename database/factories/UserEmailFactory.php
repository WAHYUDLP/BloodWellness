<?php

namespace Database\Factories;

use App\Models\UserEmail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserEmailFactory extends Factory
{
    protected $model = UserEmail::class;

    public function definition()
    {
        return [
            'id_user' => User::factory(),  // otomatis buat user baru
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
