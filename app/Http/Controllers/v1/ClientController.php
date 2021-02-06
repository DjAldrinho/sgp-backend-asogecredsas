<?php

namespace App\Http\Controllers\v1;

use App\Helpers\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json(['clients' => Client::all()], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {

        $validate = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string',
            'document_type' => 'required|string|in:cc,ce,tc,pp',
            'document_number' => 'required|string',
            'sign' => 'required|file',
            'client_type' => 'required|string',
        ]);

        try {

            $client_type = explode(',', $request->client_type);

            Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => bcrypt($request->phone),
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'sign_url' => FileManager::uploadPublicFiles($request->file('sign'), 'clients'),
                'client_type' => json_encode($client_type)
            ]);

            return response()->json(['message' => 'Successfully created client!'], 201);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
