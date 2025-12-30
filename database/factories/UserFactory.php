<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'full_name' => $this->faker->name,
            'cpf'       => $this->faker->numerify('###########'),
            'email'     => $this->faker->unique()->safeEmail,
            'password'  => Hash::make('password'),
            'type'      => 'COMMON',
        ];
    }

    // Hook para criar a carteira automaticamente quando criar o user
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);
        });
    }
}
