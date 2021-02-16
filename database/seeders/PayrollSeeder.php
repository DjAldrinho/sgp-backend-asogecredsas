<?php

namespace Database\Seeders;

use App\Models\Payroll;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'FER BOLIVAR', 'FER NORTE DE SANTANDER', 'FER SANTANDER',
            'SEM MAGANGUE', 'PRESTAMOS PERSONALES', 'NO APLICA'
        ];

        foreach ($names as $key => $name) {
            Payroll::create([
                'name' => $name
            ]);
        }
    }
}
