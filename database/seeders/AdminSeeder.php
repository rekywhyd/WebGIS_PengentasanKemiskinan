<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => env('ADMIN_NAME', 'Admin'),
            'email' => env('ADMIN_EMAIL', 'admin@gmail.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'qwerty#123')), // Ganti dengan password yang aman
            'role' => 'admin', // Menyesuaikan kolom role yang Anda buat di tabel users
        ]);
    }
}
