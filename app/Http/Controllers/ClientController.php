<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Car;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount('cars')->orderBy('last_name')->orderBy('first_name')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20|unique:clients',
            'email' => 'nullable|email|unique:clients',
            'address' => 'nullable|string|max:500',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')
            ->with('success', 'Клиент успешно добавлен.');
    }

    public function show(Client $client)
    {
        $client->load('cars');
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20|unique:clients,phone,' . $client->client_id . ',client_id',
            'email' => 'nullable|email|unique:clients,email,' . $client->client_id . ',client_id',
            'address' => 'nullable|string|max:500',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')
            ->with('success', 'Данные клиента успешно обновлены.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Клиент успешно удален.');
    }
}
