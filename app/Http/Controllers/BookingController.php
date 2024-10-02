<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::where('user_id', auth()->id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'trado_id' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after_or_equal:check_in',
            'quantity' => 'required|integer|min:1'
        ]);
    
        // Cek ketersediaan trado
        $existingBookings = Booking::where('trado_id', $request->trado_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('check_in', [$request->check_in, $request->check_out])
                      ->orWhereBetween('check_out', [$request->check_in, $request->check_out]);
            })->sum('quantity');
    
        // Misalkan jumlah trado yang tersedia adalah 10
        $totalAvailable = 10;
    
        if ($existingBookings + $request->quantity > $totalAvailable) {
            return response()->json(['error' => 'Trado not available for the selected dates'], 400);
        }
    
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'trado_id' => $request->trado_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'quantity' => $request->quantity,
            'status' => 'pending'
        ]);
    
        return response()->json($booking, 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after_or_equal:check_in',
            'quantity' => 'required|integer|min:1'
        ]);

        $booking->update($request->all());

        return response()->json($booking);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted']);
    }
}
