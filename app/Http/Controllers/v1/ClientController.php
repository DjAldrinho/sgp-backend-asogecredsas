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
    public function index()
    {
        return response()->json(['clients' => Client::paginate(50)], 200);
    }

    public function getByType(Request $request)
    {
        $request->validate([
            'types' => 'required|string'
        ]);

        try {

            $types = explode(',', $request->types);

            $clients = Client::whereJsonContains('client_type', $types)->paginate(50);

            $clients->appends(['types' => $request->types]);

            return response()->json(['clients' => $clients]);
        } catch (\Exception $exception) {
            return response(['message' => $exception->getMessage()], 409);
        }
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
            'email' => 'string|email',
            'phone' => 'required|string',
            'document_type' => 'required|string|in:cc,ce,tc,pp',
            'document_number' => 'required|string',
            'sign' => 'required|file',
            'client_type' => 'required|string',
        ]);

        try {

            $client_type = explode(',', $request->client_type);

            $client = Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'sign' => FileManager::uploadPublicFiles($request->file('sign'), 'clients'),
                'client_type' => json_encode($client_type)
            ]);

            return response()->json(['message' => 'Successfully created client!', 'client' => $client], 201);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Client $client)
    {
        return response()->json(['client' => $client], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Client $client)
    {

        $request->validate([
            'email' => 'string|email',
            'phone' => 'required|string',
            'client_type' => 'required|string'
        ]);

        try {
            $client_type = explode(',', $request->client_type);
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->client_type = json_encode($client_type);
            $client->save();
            $client->refresh();
            return response()->json(['message' => 'Client Updated!', 'client' => $client], 200);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 409]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return response()->json(['message' => 'Client deleted!'], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
