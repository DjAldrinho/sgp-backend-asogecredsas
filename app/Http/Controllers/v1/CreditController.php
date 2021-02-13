<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use Illuminate\Http\Request;

class CreditController extends Controller
{

    public function create(Request $request)
    {

        $request->validate([
            ''
        ]);

        try {
            $count = Credit::count();
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 50;


        $transactions = Credit::byAccount($request->account)->byClient($request->client)
            ->orderBy('created_at', 'desc')->paginate($per_page);

        $transactions->appends(['per_page' => $per_page]);

        return response()->json(['transactions' => $transactions], 200);
    }
}
