@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">My Reservations</h2>
    
    <div class="row">
        @forelse($reservations as $reservation)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $reservation->event->name }}</h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-calendar"></i> {{ $reservation->event->date->format('d/m/Y H:i') }}
                            <br>
                            <i class="bi bi-geo-alt"></i> {{ $reservation->event->location }}
                            <br>
                            <i class="bi bi-person"></i> {{ $reservation->user_name }}
                            <br>
                            <i class="bi bi-envelope"></i> {{ $reservation->email }}
                            <br>
                            <i class="bi bi-telephone"></i> {{ $reservation->phone }}
                        </p>
                        <button 
                            class="btn btn-danger btn-sm delete-reservation" 
                            data-id="{{ $reservation->id }}">
                            Cancel Reservation
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">No reservations found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection