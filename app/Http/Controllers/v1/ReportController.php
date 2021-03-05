<?php

namespace App\Http\Controllers\v1;

use App\Exports\CreditExportsPDF;
use App\Exports\CreditsExportsExcel;
use App\Exports\TransactionsExportsExcel;
use App\Exports\TransactionsExportsPDF;
use App\Http\Controllers\Controller;
use App\Models\Credit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function credits(Request $request)
    {
        $request->validate(
            [
                'type' => 'string|required|in:pdf,excel'
            ]
        );

        try {
            if ($request->type == 'pdf') {
                return CreditExportsPDF::handle($request);
            }

            return Excel::download(new CreditsExportsExcel($request), 'credits.xlsx');
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function transactions(Request $request)
    {
        $request->validate(
            [
                'type' => 'string|required|in:pdf,excel'
            ]
        );

        try {
            if ($request->type == 'pdf') {
                return TransactionsExportsPDF::handle($request);
            }
            return Excel::download(new TransactionsExportsExcel($request), 'transactions.xlsx');
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function creditReport(Request $request)
    {
        $request->validate([
            'credit_id' => 'required|integer|exists:credits,id'
        ]);

        try {

            $data = Credit::with(['debtor', 'credit_type'])->where('id', $request->credit_id)->first();

            return \PDF::loadView('pdf.credit', ['credit' => $data])
                ->download("Certificado de credito {$data->code} - {$data->debtor->name}.pdf");
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function peaceAndSave(Request $request)
    {
        $request->validate([
            'credit_id' => 'required|integer|exists:credits,id',
        ]);

        try {

            $data = Credit::with(['debtor', 'credit_type', 'payroll'])->where('id', $request->credit_id)->first();

            \PDF::loadView('pdf.peace', ['credit' => $data])
                ->save(storage_path('app/public/') . 'archivo4.pdf');

            /*return \PDF::loadView('pdf.credit', ['credit' => $data])
                ->download("Certificado de Paz y Salvo {$data->code} - {$data->debtor->name}.pdf");*/

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }
}
