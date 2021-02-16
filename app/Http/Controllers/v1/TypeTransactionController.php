<?php

namespace App\Http\Controllers\v1;


use App\Http\Controllers\Controller;
use App\Models\TypeTransaction;
use Illuminate\Http\Request;

class TypeTransactionController extends Controller
{
    public function index()
    {

        $per_page = isset($request->per_page) ? $request->per_page : 50;

        $types = TypeTransaction::paginate($per_page);

        $types->appends(['per_page' => $per_page]);

        return response()->json(['types' => $types], 200);
    }


    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string|unique:type_transaction'
        ]);

        try {
            $type = TypeTransaction::create([
                'name' => $request->name
            ]);

            return response()->json(['message' => __('messages.type_transaction.register'), 'type' => $type], 201);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function update(Request $request, TypeTransaction $typeTransaction)
    {

        $request->validate([
            'name' => 'required|string|unique:type_transaction,name,' . $typeTransaction->id,
        ]);

        try {
            $typeTransaction->name = $request->name;
            $typeTransaction->save();
            $typeTransaction->refresh();
            return response()->json(['message' => __('messages.type_transaction.updated'), 'type' => $typeTransaction], 200);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 409]);
        }
    }

    public function destroy(TypeTransaction $typeTransaction)
    {
        try {
            $typeTransaction->delete();
            return response()->json(['message' => __('messages.type_transaction.deleted')], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
