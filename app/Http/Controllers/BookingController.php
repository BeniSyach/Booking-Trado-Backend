<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())->with('Trado')->get();

        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'trado_id' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after_or_equal:check_in',
            'quantity' => 'required|integer|min:1'
        ]);
    
    // Parse tanggal dari format ISO ke format yang diinginkan
        $checkInDate = Carbon::parse($request->check_in)->format('Y-m-d');
        $checkOutDate = Carbon::parse($request->check_out)->format('Y-m-d');

        // Cek ketersediaan trado
        $existingBookings = Booking::where('trado_id', $request->trado_id)
            ->where(function($query) use ($checkInDate, $checkOutDate) {
                $query->whereBetween('check_in', [$checkInDate, $checkOutDate])
                    ->orWhereBetween('check_out', [$checkInDate, $checkOutDate]);
            })->sum('quantity');
    
        // Misalkan jumlah trado yang tersedia adalah 10
        $totalAvailable = 10;
    
        if ($existingBookings + $request->quantity > $totalAvailable) {
            return response()->json(['error' => 'Trado not available for the selected dates'], 400);
        }
    
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'trado_id' => $request->trado_id,
            'check_in' => $checkInDate,
            'check_out' => $checkOutDate,
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
