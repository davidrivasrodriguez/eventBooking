@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Dashboard</h2>

        <div class="row">


            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">My Recent Reservations</h5>
                    </div>
                    <div class="card-body">
                        @forelse(Auth::user()->reservations()->latest()->take(5)->get() as $reservation)
                            <div class="mb-3">
                                <h6>{{ $reservation->event->name }}</h6>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-calendar"></i> {{ $reservation->event->date->format('d/m/Y H:i') }}
                                </p>
                                <a href="{{ route('events.show', $reservation->event) }}" class="btn btn-sm btn-primary">View Event</a>
                            </div>
                            @unless($loop->last)
                                <hr>
                            @endunless
                        @empty
                            <p class="text-muted">No recent reservations</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Upcoming Events</h5>
                    </div>
                    <div class="card-body">
                        @forelse(App\Models\Event::where('date', '>', now())->take(5)->get() as $event)
                            <div class="mb-3">
                                <h6>{{ $event->name }}</h6>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-calendar"></i> {{ $event->date->format('d/m/Y H:i') }}
                                    <br>
                                    <i class="bi bi-people"></i> {{ $event->getAvailableSeats() }} seats available
                                </p>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-primary">View Details</a>
                            </div>
                            @unless($loop->last)
                                <hr>
                            @endunless
                        @empty
                            <p class="text-muted">No upcoming events</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection