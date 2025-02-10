@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">Total Events</h5>
                        <p class="card-text display-4">{{ App\Models\Event::count() }}</p>
                    </div>
                    <div>
                        <a href="{{ route('events.index') }}" class="btn btn-primary">View Events</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text display-4">{{ App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">Total Reservations</h5>
                        <p class="card-text display-4">{{ App\Models\Reservation::count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Latest Events</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse(App\Models\Event::latest()->take(5)->get() as $event)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $event->name }}</h6>
                                    <small>{{ $event->date->format('d/m/Y') }}</small>
                                </div>
                                <p class="mb-1">{{ Str::limit($event->description, 100) }}</p>
                                <small>
                                    <i class="bi bi-people"></i> {{ $event->getAvailableSeats() }} seats available
                                </small>
                                <div class="mt-2">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-primary">View</a>
                                    <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-secondary">Edit</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No events found</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Latest Reservations</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse(App\Models\Reservation::with(['user', 'event'])->latest()->take(5)->get() as $reservation)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $reservation->user_name }}</h6>
                                    <small>{{ $reservation->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="mb-1">Reserved for: {{ $reservation->event->name }}</p>
                                <small>
                                    <i class="bi bi-envelope"></i> {{ $reservation->email }}
                                    @if($reservation->phone)
                                        <br><i class="bi bi-telephone"></i> {{ $reservation->phone }}
                                    @endif
                                </small>
                            </div>
                        @empty
                            <p class="text-muted">No reservations found</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection