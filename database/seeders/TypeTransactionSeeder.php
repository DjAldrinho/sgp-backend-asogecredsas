<?php

namespace Database\Seeders;

use App\Models\TypeTransaction;
use Illuminate\Database\Seeder;

class TypeTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeTransaction::create([
           'name' => 'Transferencia'
        ]);

        TypeTransaction::create([
            'name' => 'Consignación'
        ]);

        TypeTransaction::create([
            'name' => 'Oficina'
        ]);

        TypeTransaction::create([
            'name' => 'Otro'
        ]);

        TypeTransaction::create([
            'name' => 'No Aplica'
        ]);
    }
}
