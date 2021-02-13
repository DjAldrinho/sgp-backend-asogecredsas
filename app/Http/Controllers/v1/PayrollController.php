<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 50;

        $payrolls = Payroll::paginate($per_page);

        $payrolls->appends(['per_page' => $per_page]);

        return response()->json(['payrolls' => $payrolls], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:payrolls'
        ]);

        try {
            Payroll::create([
                'name' => $request->name,
            ]);

            return response()->json([
                'message' => __('messages.payrolls.register')
            ], 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function update(Request $request, Payroll $payroll)
    {

        $request->validate([
            'name' => 'required|string|unique:payrolls,name,' . $payroll->id,
            'value' => 'integer|required'
        ]);

        try {
            $payroll->name = $request->name;
            $payroll->save();
            $payroll->refresh();
            return response()->json(['message' => __('messages.payrolls.updated'), 'payroll' => $payroll], 200);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 409]);
        }
    }

    public function destroy(Payroll $payroll)
    {
        try {
            $payroll->delete();
            return response()->json(['message' => __('messages.payrolls.deleted')], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
