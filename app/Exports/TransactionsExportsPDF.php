<?php


namespace App\Exports;


use App\Services\TransactionService;

class TransactionsExportsPDF
{
    private $transactionService;

    /**
     * CreditExportsPDF constructor.
     */
    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    public static function handle($request)
    {
        return (new TransactionsExportsPDF)->operation($request);
    }

    private function operation($request)
    {

        $transactions = $this->transactionService->getTransactions($request, true)->get();

        $pdf = \PDF::loadView('pdf.transactions', [
            'transactions' => $transactions,
            'total' => $transactions->sum('value'),
        ])->setPaper('letter', 'landscape');

        // $pdf->save(storage_path('app/public/') . 'archivo2.pdf');

        return $pdf->download('transactions.pdf');
    }
}
