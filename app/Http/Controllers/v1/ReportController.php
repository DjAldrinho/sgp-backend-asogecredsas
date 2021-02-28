<?php

namespace App\Http\Controllers\v1;

use App\Exports\CreditExportsPDF;
use App\Exports\CreditsExportsExcel;
use App\Http\Controllers\Controller;
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
}
