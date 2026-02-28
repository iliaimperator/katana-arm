<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\Request;

class SparePartController extends Controller
{
    public function index()
    {
        $parts = SparePart::orderBy('part_name')->get();
        return view('spare-parts.index', compact('parts'));
    }

    public function create()
    {
        $categories = [
            'Двигатель',
            'КПП',
            'Тормозная система',
            'Подвеска',
            'Электрика',
            'Кузов',
            'Салон',
            'Прочее'
        ];

        return view('spare-parts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'article_number' => 'required|string|max:100|unique:spare_parts',
            'part_name' => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100'
        ]);

        SparePart::create($request->all());

        return redirect()->route('spare-parts.index')
            ->with('success', 'Запчасть успешно добавлена.');
    }

    public function show(SparePart $sparePart)
    {
        return view('spare-parts.show', compact('sparePart'));
    }

    public function edit(SparePart $sparePart)
    {
        $categories = [
            'Двигатель',
            'КПП',
            'Тормозная система',
            'Подвеска',
            'Электрика',
            'Кузов',
            'Салон',
            'Прочее'
        ];

        return view('spare-parts.edit', compact('sparePart', 'categories'));
    }

    public function update(Request $request, SparePart $sparePart)
    {
        $request->validate([
            'article_number' => 'required|string|max:100|unique:spare_parts,article_number,' . $sparePart->part_id . ',part_id',
            'part_name' => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100'
        ]);

        $sparePart->update($request->all());

        return redirect()->route('spare-parts.index')
            ->with('success', 'Запчасть успешно обновлена.');
    }

    public function destroy(SparePart $sparePart)
    {
        $sparePart->delete();

        return redirect()->route('spare-parts.index')
            ->with('success', 'Запчасть успешно удалена.');
    }
}
