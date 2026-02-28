<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('service_type')->orderBy('service_name')->get();
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_type' => 'required|string|max:255',
            'service_name' => 'required|string|max:255',
            'standard_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        Service::create($request->all());

        return redirect()->route('services.index')
            ->with('success', 'Услуга успешно добавлена.');
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'service_type' => 'required|string|max:255',
            'service_name' => 'required|string|max:255',
            'standard_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $service->update($request->all());

        return redirect()->route('services.index')
            ->with('success', 'Услуга успешно обновлена.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Услуга успешно удалена.');
    }
}
