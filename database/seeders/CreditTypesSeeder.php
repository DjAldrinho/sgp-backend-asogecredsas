<?php

namespace Database\Seeders;

use App\Models\CreditType;
use Exception;
use Illuminate\Database\Seeder;

class CreditTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $credit_types = [
            "libranza", "credito personal",
            "prima junio", "prima diciembre",
            "[no] personal", "cesantias",
            "pignoración", "credito por ventanilla",
            'No Aplica'
        ];

        foreach ($credit_types as $key => $credit_type) {
            CreditType::create([
                'name' => $credit_type
            ]);
        }
    }
}
