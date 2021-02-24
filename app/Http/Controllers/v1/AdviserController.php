<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Adviser;
use Illuminate\Http\Request;

class AdviserController extends Controller
{
    public function index(Request $request)
    {

        $per_page = isset($request->per_page) ? $request->per_page : 50;

        $advisers = Adviser::byNameOrPhone($request->search)->paginate($per_page);

        $advisers->appends(['per_page' => $per_page]);

        return response()->json(['advisers' => $advisers], 200);
    }

    public function show(Adviser $adviser)
    {
        return response()->json(['adviser' => $adviser], 200);
    }


    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'phone' => 'string|unique:advisers'
        ]);

        try {
            $adviser = Adviser::create([
                'name' => $request->name,
                'phone' => $request->phone
            ]);

            return response()->json(['message' => __('messages.advisers.register'), 'adviser' => $adviser], 201);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function update(Request $request, Adviser $adviser)
    {

        $request->validate([
            'name' => 'required|string',
            'phone' => 'string|unique:advisers,phone,' . $adviser->id,
        ]);

        try {
            $adviser->name = $request->name;
            $adviser->phone = $request->phone;
            $adviser->save();
            $adviser->refresh();
            return response()->json(['message' => __('messages.advisers.updated'), 'adviser' => $adviser], 200);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 409]);
        }
    }

    public function destroy(Adviser $adviser)
    {
        try {
            $adviser->delete();
            return response()->json(['message' => __('messages.advisers.deleted')], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
