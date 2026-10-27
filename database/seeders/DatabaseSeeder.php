<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => 'admin123',
            'is_admin' => true,
        ]);

        User::create([
             'name' => 'Janek',
            'username' => 'janek',
            'password' => 'janek123',
            'is_admin' => false,
        ]);

        User::factory(8)->create();
    }
}
