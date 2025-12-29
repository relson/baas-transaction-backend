<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Wallet;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Criar um Usuário Comum (Pagador)
        $payer = User::create([
            'full_name' => 'John Doe Common',
            'cpf'       => '111.111.111-11',
            'email'     => 'common@test.com',
            'password'  => Hash::make('123456'),
            'type'      => 'COMMON',
        ]);

        // Criar carteira para ele com saldo de 1000
        Wallet::create([
            'user_id' => $payer->id,
            'balance' => 1000.00
        ]);

        // 2. Criar um Lojista (Recebedor)
        $payee = User::create([
            'full_name' => 'Jane Doe Shop',
            'cpf'       => '222.222.222-22',
            'email'     => 'shop@test.com',
            'password'  => Hash::make('123456'),
            'type'      => 'SHOPKEEPER',
        ]);

        // Criar carteira para o lojista com saldo 0
        Wallet::create([
            'user_id' => $payee->id,
            'balance' => 0.00
        ]);
    }
}
