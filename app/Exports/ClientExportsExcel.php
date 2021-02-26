<?php


namespace App\Exports;


use App\Services\CreditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientExportsExcel implements FromCollection, WithHeadings, ShouldQueue
{
    use Exportable;

    private $request, $creditService;

    /**
     * ClientExportsExcel constructor.
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
        $this->creditService = new CreditService();
    }


    public function collection()
    {
        $data = [];

        $credits = $this->creditService->getCredits($this->request)->get();

        return collect($data);

    }

    public function headings(): array
    {
        return [
            'Pagaduria', 'No. Credito', 'Tipo', 'Fecha Inicio', 'Fecha Giro', 'Fecha Terminacion', 'Titular',
            'Primer Codeudor', 'Segundo Codeudor', 'Valor Capital', 'Valor Transporte', 'Otros Valores',
            '% Interes', 'Valor Interes', 'Valor Cuota', 'Cuotas', 'Total Credito', 'Abono', 'Saldo', 'Asesor', 'Comision'
        ];
    }
}
