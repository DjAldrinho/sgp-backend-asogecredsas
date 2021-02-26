<?php


namespace App\Exports;


use App\Services\CreditService;
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

class CreditsExportsExcel implements FromCollection, WithHeadings, WithProperties, ShouldAutoSize, WithEvents, WithColumnFormatting
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

        if ($credits) {
            foreach ($credits as $credit) {
                $data[] = [
                    $credit->code,
                    $credit->payroll->name,
                    $credit->credit_type->name,
                    Carbon::parse($credit->start_date)->isoFormat('DD/MM/Y'),
                    ($credit->approval_date) ? Carbon::parse($credit->approval_date)->isoFormat('DD/MM/Y') : '',
                    ($credit->end_date) ? Carbon::parse($credit->end_date)->isoFormat('DD/MM/Y') : '',
                    ($credit->debtor) ? $credit->debtor->document_number . ' - ' . $credit->debtor->name : '',
                    ($credit->first_co_debtor_) ? $credit->first_co_debtor_->document_number . ' - ' . $credit->first_co_debtor_->name : '',
                    ($credit->second_co_debtor_) ? $credit->second_co_debtor_->document_number . ' - ' . $credit->second_co_debtor_->name : '',
                    $credit->capital_value,
                    $credit->transport_value,
                    $credit->other_value,
                    $credit->interest,
                    floatval($credit->liquidate['total_interest']),
                    $credit->liquidate['fees'][0]['fee_value'],
                    $credit->fee,
                    $credit->liquidate['total_credit'],
                    $credit->payment,
                    ($credit->adviser) ? $credit->adviser->name : '',
                    $credit->commission,
                    ($credit->approvalUser) ? $credit->approvalUser->name : ''
                ];
            }
        }

        $count = count($credits) + 1;

        array_push($data, ['Total', '', '', '', '', '', '', '', '',
            '=SUM(J2:J' . $count . ')',
            '=SUM(K2:K' . $count . ')',
            '=SUM(L2:L' . $count . ')',
            '',
            '=SUM(N2:N' . $count . ')',
            '=SUM(O2:O' . $count . ')',
            '',
            '=SUM(Q2:Q' . $count . ')',
            '=SUM(R2:R' . $count . ')',
            '=SUM(S2:S' . $count . ')',
        ]);

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'No. Credito', 'Pagaduria', 'Tipo', 'Fecha Inicio', 'Fecha Aprobacion', 'Fecha Finalizacion', 'Titular',
            'Primer Codeudor', 'Segundo Codeudor', 'Valor Capital', 'Valor Transporte', 'Otros Valores',
            '% Interes', 'Total Intereses', 'Valor Cuota', 'Plazo', 'Total Credito', 'Saldo',
            'Asesor', '% Comision', 'Aprobado por'
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => 'Devsoft',
            'title' => 'Reporte de Clientes',
            'company' => 'Maatwebsite',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:V1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()
                    ->setSize(12)
                    ->setBold(true);
            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'K' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'L' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'N' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'O' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'Q' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'R' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'S' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }
}
