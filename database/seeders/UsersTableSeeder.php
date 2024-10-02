<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'beni',
            'email' => 'beni@gmail.com',
            'password' => Hash::make('password1'),
        ]);

        User::create([
            'name' => 'beni syach',
            'email' => 'benisyach@example.com',
            'password' => Hash::make('password2'),
        ]);
    }
}
