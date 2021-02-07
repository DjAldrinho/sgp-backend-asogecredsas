<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index()
    {
        return response()->json(['suppliers' => Supplier::all()], 200);
    }


    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string|unique:suppliers'
        ]);

        try {
            $supplier = Supplier::create([
                'name' => $request->name
            ]);

            return response()->json(['message' => 'Successfully created supplier!', 'supplier' => $supplier], 201);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function update(Request $request, Supplier $supplier)
    {

        $request->validate([
            'name' => 'required|string|unique:suppliers,name,' . $supplier->id,
        ]);

        try {
            $supplier->name = $request->name;
            $supplier->save();
            $supplier->refresh();
            return response()->json(['message' => 'Supplier Updated!', 'supplier' => $supplier], 200);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 409]);
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return response()->json(['message' => 'Supplier deleted!'], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
