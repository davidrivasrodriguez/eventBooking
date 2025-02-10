@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
            @if($event->image)
                        <img src="{{ asset('storage/'.$event->image) }}" class="img-fluid rounded" alt="{{ $event->name }}">
                    @endif
                <div class="card-body">

                    <h2 class="card-title">{{ $event->name }}</h2>
                    <p class="text-muted mb-4">
                        <i class="bi bi-calendar"></i> {{ $event->date->format('d/m/Y H:i') }}
                        <br>
                        <i class="bi bi-geo-alt"></i> {{ $event->location }}
                        <br>
                        <i class="bi bi-people"></i> Available Seats: {{ $event->getAvailableSeats() }}
                    </p>
                    
                    <h5>Description</h5>
                    <p class="card-text">{{ $event->description }}</p>

                    @auth
                        @if($event->reservations()->where('user_id', Auth::id())->exists())
                            <p class="text-info">You already have a reservation for this event</p>
                        @elseif($event->getAvailableSeats() > 0)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reservationModal">
                                Make Reservation
                            </button>
                        @else
                            <p class="text-danger">No seats available</p>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Login to Make Reservation</a>
                    @endauth
                    
                    @if(Auth::user() && Auth::user()->isAdmin())
                        <div class="mt-3">
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-secondary">Edit Event</a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete Event</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Current Reservations</h5>
                    @if($event->reservations->count() > 0)
                        <ul class="list-unstyled">
                            @foreach($event->reservations as $reservation)
                                <li class="mb-2">
                                    <i class="bi bi-person"></i> {{ $reservation->user_name }}
                                    @if(Auth::user() && Auth::user()->isAdmin())
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-envelope"></i> {{ $reservation->email }}
                                            @if($reservation->phone)
                                                <br>
                                                <i class="bi bi-telephone"></i> {{ $reservation->phone }}
                                            @endif
                                        </small>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No reservations yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @auth
        <div class="modal fade" id="reservationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reserve for {{ $event->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="reservationForm" action="{{ route('reservations.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                            
                            <div class="mb-3">
                                <label for="user_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone (optional)</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="reservationForm" class="btn btn-primary">Confirm Reservation</button>
                    </div>
                </div>
            </div>
        </div>
    @endauth
</div>
@push('scripts')
<script>
document.getElementById('reservationForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(new FormData(this)))
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message
            }).then(() => {
                window.location.href = '{{ route("reservations.my") }}';
            });
        } else {
            throw new Error(data.message || 'Something went wrong');
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message
        });
    }
});
</script>
@endpush
@endsection