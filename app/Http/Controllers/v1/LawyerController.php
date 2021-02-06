<?php

namespace App\Http\Controllers\v1;

use App\Helpers\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use Illuminate\Http\Request;

class LawyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(['laywers' => Lawyer::paginate(50)], 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:lawyers',
            'phone' => 'required|string',
            'document_type' => 'required|string|in:cc,ce,tc,pp',
            'document_number' => 'required|string|unique:lawyers',
            'professional_card' => 'required|file'
        ]);

        try {
            $lawyer = Lawyer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'professional_card' => FileManager::uploadPublicFiles($request->file('professional_card'), 'lawyers'),
            ]);

            return response()->json(['message' => 'Successfully created lawyer!', 'lawyer' => $lawyer], 201);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param Lawyer $lawyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Lawyer $lawyer)
    {
        return response()->json(['lawyer' => $lawyer], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Lawyer $lawyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Lawyer $lawyer)
    {

        $request->validate([
            'email' => 'required|string|email|unique:lawyers,email,' . $lawyer->id,
            'phone' => 'required|string',
        ]);

        try {
            $lawyer->email = $request->email;
            $lawyer->phone = $request->phone;
            $lawyer->save();
            $lawyer->refresh();
            return response()->json(['message' => 'Lawyer Updated!', 'lawyer' => $lawyer], 200);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 409]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lawyer $lawyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Lawyer $lawyer)
    {
        try {
            $lawyer->delete();
            return response()->json(['message' => 'Lawyer deleted!'], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
