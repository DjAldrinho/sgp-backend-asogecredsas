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
    public function run(): void
    {
        $types_transactions = [
            'Transferencia', 'Consignación',
            'Oficina', 'Otro', 'No Aplica'
        ];

        foreach ($types_transactions as $key => $types_transaction) {
            TypeTransaction::create([
                'name' => $types_transaction
            ]);
        }
    }
}
