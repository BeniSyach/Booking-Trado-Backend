<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trado;
use Carbon\Carbon;
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

    public function checkAvailability(Request $request)
    {

        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after_or_equal:check_in',
        ]);
    

        $checkInDate = Carbon::parse($request->check_in)->format('Y-m-d');
        $checkOutDate = Carbon::parse($request->check_out)->format('Y-m-d');
    
        $trados = Trado::all();
    
        $availableTrados = [];
    
        foreach ($trados as $trado) {
            $existingBookings = Booking::where('trado_id', $trado->id)
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->whereBetween('check_in', [$checkInDate, $checkOutDate])
                          ->orWhereBetween('check_out', [$checkInDate, $checkOutDate]);
                })
                ->sum('quantity');
    
            $availableQuantity = $trado->available_quantity - $existingBookings;
    
            $availableTrados[] = [
                'id' => $trado->id,
                'name' => $trado->name,
                'type' => $trado->type,
                'capacity' => $trado->capacity,
                'price' => $trado->price,
                'available_quantity' => $availableQuantity,
                'status' => $availableQuantity > 0 ? 'Tersedia' : 'Tidak Tersedia'
            ];
        }
    
        return response()->json($availableTrados, 200);
    }
    
}
