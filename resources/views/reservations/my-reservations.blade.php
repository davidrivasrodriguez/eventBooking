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
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('events.show', $reservation->event) }}" 
                               class="btn btn-primary btn-sm">View Event</a>
                            <button class="btn btn-danger btn-sm delete-reservation" 
                                    data-id="{{ $reservation->id }}">
                                Cancel Reservation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">You have no reservations yet.</p>
                <a href="{{ route('events.index') }}" class="btn btn-primary">Browse Events</a>
            </div>
        @endforelse
    </div>
</div>


@push('scripts')
<script>
document.querySelectorAll('.delete-reservation').forEach(button => {
    button.addEventListener('click', async function() {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!'
        });

        if (result.isConfirmed) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch(`{{ route('reservations.destroy', '') }}/${this.dataset.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (response.ok) {
                    await Swal.fire('Cancelled!', data.message, 'success');
                    this.closest('.col-md-6').remove();
                } else {
                    throw new Error(data.message || 'Failed to cancel reservation');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error!', error.message, 'error');
            }
        }
    });
});
</script>
@endpush

@endsection