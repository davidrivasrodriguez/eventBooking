<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = auth()->user()->isAdmin() 
            ? Reservation::with('event')->latest()->get()
            : auth()->user()->reservations()->with('event')->latest()->get();
        
        return view('reservations.index', compact('reservations'));
    }
    
    public function store(Request $request)
    {
        $event = Event::findOrFail($request->event_id);
        
        if ($event->reservations()->where('user_id', auth()->id())->exists()) {
            return response()->json([
                'message' => 'You already have a reservation for this event'
            ], 422);
        }
        
        if ($event->getAvailableSeats() <= 0) {
            return response()->json([
                'message' => 'No seats available for this event'
            ], 422);
        }
    
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'event_id' => 'required|exists:events,id'
        ]);
    
        $validated['email'] = auth()->user()->email;
        $validated['user_id'] = auth()->id();
        $validated['seat_number'] = $event->reservations()->count() + 1;
    
        $reservation = Reservation::create($validated);
    
        return response()->json([
            'message' => 'Reservation created successfully',
            'reservation' => $reservation
        ]);
    }

    public function myReservations()
    {
        $reservations = auth()->user()
            ->reservations()
            ->with('event')
            ->latest()
            ->get();
            
        return view('reservations.my-reservations', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Reservation $reservation)
    {
        if (auth()->id() !== $reservation->user_id && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        try {
            $reservation->delete();
            return response()->json(['message' => 'Reservation cancelled successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to cancel reservation'], 500);
        }
    }
}
