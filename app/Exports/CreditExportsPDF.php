<?php


namespace App\Exports;


use App\Services\CreditService;

class CreditExportsPDF
{
    private $creditService;

    /**
     * CreditExportsPDF constructor.
     */
    public function __construct()
    {
        $this->creditService = new CreditService();
    }

    public static function handle($request)
    {
        return (new CreditExportsPDF)->operation($request);
    }

    private function operation($request)
    {
        $credits = $this->creditService->getCredits($request)->get();


        $pdf = \PDF::loadView('pdf.credits', [
            'credits' => $credits,
            'total_capital' => $credits->sum('capital_value'),
            'total_interest' => $credits->sum('liquidate.total_interest'),
            'fee_value' => $credits->sum('liquidate.fees.0.fee_value'),
            'total_credit' => $credits->sum('liquidate.total_credit'),
            'total_payment' => $credits->sum('payment'),
        ])->setPaper('letter', 'landscape');

        return $pdf->download('credits.pdf');

    }
}
