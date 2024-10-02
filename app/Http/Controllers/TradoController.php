<?php

namespace App\Http\Controllers;

use App\Models\Trado;
use Illuminate\Http\Request;

class TradoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Trado::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
            'available_quantity' => 'required|integer'
        ]);

        return Trado::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Trado::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'capacity' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'available_quantity' => 'sometimes|required|integer'
        ]);

        $trado = Trado::findOrFail($id);
        $trado->update($request->all());

        return $trado;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $trado = Trado::findOrFail($id);
        $trado->delete();

        return response()->noContent();
    }
}
