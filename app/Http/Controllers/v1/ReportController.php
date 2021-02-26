<?php

namespace App\Http\Controllers\v1;

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

        if ($request->type == 'pdf') {

        }

        return Excel::store(new CreditsExportsExcel($request), 'credits.xlsx');

    }
}
