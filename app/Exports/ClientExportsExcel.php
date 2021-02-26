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
     * @param CreditService $creditService
     */
    public function __construct($request, CreditService $creditService)
    {
        $this->request = $request;
        $this->creditService = $creditService;
    }


    public function collection()
    {
        $credits = $this->creditService->getCredits($this->request)->get();
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
