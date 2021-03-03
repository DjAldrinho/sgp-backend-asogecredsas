<?php


namespace App\Exports;


use App\Helpers\TransactionHelper;
use App\Services\TransactionService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionsExportsExcel implements FromCollection, WithHeadings, WithProperties, ShouldAutoSize, WithEvents, WithColumnFormatting
{
    use Exportable;

    private $request, $service;

    /**
     * ClientExportsExcel constructor.
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
        $this->service = new TransactionService();
    }


    public function collection()
    {
        $data = [];

        $transactions = $this->service->getTransactions($this->request, true)->get();

        if ($transactions) {
            foreach ($transactions as $transaction) {
                $data[] = [
                    $transaction->code,
                    ($transaction->account) ? $transaction->account->account_number . ' - ' . $transaction->account->name : '',
                    ($transaction->credit) ? $transaction->credit->code . ' - ' . $transaction->credit->debtor->name : '',
                    TransactionHelper::getOriginName($transaction->origin),
                    $transaction->value,
                    ($transaction->credit_type) ? $transaction->credit_type->name : '',
                    Carbon::parse($transaction->created_at)->isoFormat('DD/MM/Y'),
                    $transaction->commentary
                ];
            }
        }

        $count = count($transactions) + 1;

        array_push($data, [
            'Total', '', '', '',
            '=SUM(E2:E' . $count . ')'
        ]);

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'No. Transaccion', 'Cuenta', 'Credito', 'Tipo', 'Valor', 'Tipo de transaccion', 'Fecha', 'Comentario'
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => 'Devsoft',
            'title' => 'Reporte de Transacciones',
            'company' => 'Maatwebsite',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:H1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()
                    ->setSize(12)
                    ->setBold(true);
            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }


}
