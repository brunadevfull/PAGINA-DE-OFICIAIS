<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::where('username', 'admin')->exists()) {
            return;
        }

        $bootstrap = env('ADMIN_BOOTSTRAP_PASSWORD', '');

        if (!is_string($bootstrap) || strlen($bootstrap) < 8) {
            $this->command->warn(
                'Admin não criado: defina ADMIN_BOOTSTRAP_PASSWORD (mín. 8 chars) no .env e rode db:seed novamente.'
            );
            return;
        }

        User::create([
            'username' => 'admin',
            'password' => Hash::make($bootstrap),
        ]);

        $this->command->info('Usuário admin criado com a senha definida em ADMIN_BOOTSTRAP_PASSWORD.');
    }
}
