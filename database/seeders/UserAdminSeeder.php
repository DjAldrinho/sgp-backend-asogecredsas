<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        $users = [
            ['name' => 'Alex Galeano', 'email' => 'alexhgaleanoc@gmail.com', 'document_number' => 1234567891],
        ];

        foreach ($users as $key => $user) {
            User::create([
                'name' => $user["name"],
                'email' => $user["email"],
                'email_verified_at' => now(),
                'document_type' => 'cc',
                'document_number' => $user["document_number"],
                'password' => bcrypt('Adm!n'),
                'remember_token' => Str::random(10),
                'is_administrator' => true
            ]);
        }
    }
}
