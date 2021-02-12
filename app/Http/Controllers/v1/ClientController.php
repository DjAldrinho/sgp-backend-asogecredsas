<?php

namespace App\Http\Controllers\v1;

use App\Helpers\FileManager;
use App\Helpers\ImportExcel;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $per_page = isset($request->per_page) ? $request->per_page : 50;

        $clients = Client::paginate($per_page);

        $clients->appends(['per_page' => $per_page]);

        return response()->json(['clients' => $clients], 200);
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
            'phone' => 'string',
            'document_type' => 'required|string|in:cc,ce,ti,pp',
            'document_number' => 'required|string|unique:clients',
            'sign' => 'file',
            'client_type' => 'required|string',
            'position' => 'required|string',
            'salary' => 'required|integer',
            'start_date' => 'required|date',
            'bonding' => 'required|string',
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
                'client_type' => json_encode($client_type),
                'position' => $request->position,
                'salary' => $request->salary,
                'start_date' => $request->start_date,
                'bonding' => $request->bonding
            ]);

            return response()->json(['message' => 'Successfully created client!', 'client' => $client], 201);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function createMassive(Request $request)
    {
        $request->validate([
            'document' => 'required|file'
        ]);

        try {

            $import = new ImportExcel;

            Excel::import($import, request()->file('document'));

            return response()->json(['message' => 'Imported document, ' . $import->getRowCount() . ' clients have been created'], 201);

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
            'name' => 'required|string',
            'email' => 'string|email',
            'phone' => 'string',
            'client_type' => 'required|string',
            'position' => 'required|string',
            'salary' => 'required|integer',
            'start_date' => 'required|date',
            'bonding' => 'required|string',
            'sign' => 'file',
            'document_number' => 'required|string|unique:clients,id,' . $client->id,
        ]);

        try {
            $client_type = explode(',', $request->client_type);
            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->client_type = json_encode($client_type);
            $client->sign = FileManager::uploadPublicFiles($request->file('sign'), 'clients');
            $client->position = $request->position;
            $client->salary = $request->salary;
            $client->start_date = $request->start_date;
            $client->bonding = $request->bonding;
            $client->document_number = $request->document_number;
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

    public function getTemplate()
    {
        return asset('storage') . '/template.xls';
    }
}
