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

        $pdf = \PDF::loadView('pdf.credits', ['credits' => $credits])->setPaper('letter', 'landscape');

        return $pdf->download('credits.pdf');
    }
}
