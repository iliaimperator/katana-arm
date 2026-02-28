<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Client;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::with('client')->orderBy('brand')->orderBy('model')->get();
        return view('cars.index', compact('cars'));
    }

    public function create()
    {
        $clients = Client::orderBy('last_name')->get();
        $transmissions = ['manual' => 'МКПП', 'auto' => 'АКПП'];

         $selectedClientId = request('client_id');

        return view('cars.create', compact('clients', 'transmissions', 'selectedClientId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,client_id',
            'license_plate' => 'required|string|max:20|unique:cars',
            'vin' => 'nullable|string|max:50|unique:cars',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'engine_volume' => 'nullable|numeric|min:0.5|max:10.0',
        ]);

        Car::create($request->all());

        return redirect()->route('cars.index')
            ->with('success', 'Автомобиль успешно добавлен.');
    }

    public function show(Car $car)
    {
        $car->load('client');
        return view('cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        $clients = Client::orderBy('last_name')->get();
        $transmissions = ['manual' => 'МКПП', 'auto' => 'АКПП'];

        return view('cars.edit', compact('car', 'clients', 'transmissions'));
    }

    public function update(Request $request, Car $car)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,client_id',
            'license_plate' => 'required|string|max:20|unique:cars,license_plate,' . $car->car_id . ',car_id',
            'vin' => 'nullable|string|max:50|unique:cars,vin,' . $car->car_id . ',car_id',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'engine_volume' => 'nullable|numeric|min:0.5|max:10.0',
        ]);

        $car->update($request->all());

        return redirect()->route('cars.index')
            ->with('success', 'Данные автомобиля успешно обновлены.');
    }

    public function destroy(Car $car)
    {
        $car->delete();

        return redirect()->route('cars.index')
            ->with('success', 'Автомобиль успешно удален.');
    }
}
