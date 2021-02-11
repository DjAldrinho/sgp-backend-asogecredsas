<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $names = [
            'Oficina', 'Otro', 'No Aplica'
        ];

        foreach ($names as $key => $name) {
            Supplier::create([
                'name' => $name
            ]);
        }
    }
}
