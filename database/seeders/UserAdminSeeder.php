<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Aldray Narvaez',
            'email' => 'aldraynarvaez@gmail.com',
            'email_verified_at' => now(),
            'document_type' => 'cc',
            'document_number' => 1234567891,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'is_administrator' => true
        ]);

        User::create([
            'name' => 'Jesus Agamez',
            'email' => 'jesusagamez@gmail.com',
            'email_verified_at' => now(),
            'document_type' => 'cc',
            'document_number' => 1234567892,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'is_administrator' => true
        ]);

        User::create([
            'name' => 'Deiner Vega',
            'email' => 'deinervega@gmail.com',
            'email_verified_at' => now(),
            'document_type' => 'cc',
            'document_number' => 1234567893,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'is_administrator' => true
        ]);
    }
}
