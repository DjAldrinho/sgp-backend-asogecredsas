<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Process;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 50;

        $processes = Process::paginate($per_page);

        $processes->appends(['per_page' => $per_page]);

        return response()->json(['processes' => $processes], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'lawyer_id' => 'integer|required|exists:lawyers,id',
            'credit_id' => 'integer|required|exists:credits,id',
            'court' => 'required|string',
            'demand_value' => 'required|numeric',
            'fees_value' => 'required|numeric'
        ]);

        try {
            $process = Process::create([
                'lawyer_id' => $request->lawyer_id,
                'credit_id' => $request->credit_id,
                'court' => $request->court,
                'demand_value' => $request->demand_value,
                'fees_value' => $request->fees_value,
                'payment' => $request->demand_value
            ]);

            return response()->json([
                'message' => 'Proceso creado correctamente',
                'process' => $process
            ], 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }
}
