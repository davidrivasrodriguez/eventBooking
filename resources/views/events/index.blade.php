@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Events</h2>
        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('events.create') }}" class="btn btn-primary">Create Event</a>
            @endif
        @endauth
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" id="search" class="form-control" placeholder="Search events...">
                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="row row-cols-1 row-cols-md-3 g-4" id="events-container">
        @foreach($events as $event)
            <div class="col">
                <div class="card h-100">
                    @if($event->image)
                        <img src="{{ asset('storage/'.$event->image) }}" 
                             class="card-img-top" 
                             style="height: 200px; object-fit: cover;" 
                             alt="{{ $event->name }}">
                    @else
                        <div class="bg-light" style="height: 200px;"></div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $event->name }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($event->description, 100) }}</p>
                        <div class="mt-auto">
                            <p class="text-muted mb-3">
                                <i class="bi bi-calendar"></i> {{ $event->date->format('d/m/Y H:i') }}<br>
                                <i class="bi bi-geo-alt"></i> {{ $event->location }}<br>
                                <i class="bi bi-people"></i> Available Seats: {{ $event->getAvailableSeats() }}
                            </p>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $events->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventsContainer = document.getElementById('events-container');
    const paginationContainer = document.querySelector('.pagination').parentElement;
    const baseUrl = '{{ url("/") }}';
    const searchInput = document.getElementById('search');
    const searchBtn = document.getElementById('searchBtn');
    let currentPage = {{ $events->currentPage() }};
    let lastPage = {{ $events->lastPage() }};
    let isSearching = false;

    function generatePagination(data) {
        const links = data.items.links;
        let paginationHtml = `
        <nav>
            <ul class="pagination">`;
        
        links.forEach(link => {
            if (link.url === null) {
                paginationHtml += `
                    <li class="page-item disabled">
                        <span class="page-link">${link.label}</span>
                    </li>`;
            } else {
                paginationHtml += `
                    <li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="${link.url}" ${link.label === '&laquo; Previous' ? 'rel="prev"' : ''} ${link.label === 'Next &raquo;' ? 'rel="next"' : ''}>${link.label}</a>
                    </li>`;
            }
        });

        paginationHtml += `</ul></nav>`;
        paginationContainer.innerHTML = paginationHtml;
        attachPaginationListeners();
    }

    function renderEvents(events) {
        eventsContainer.innerHTML = events.map(event => `
            <div class="col">
                <div class="card h-100">
                    ${event.image ? 
                        `<img src="${baseUrl}/storage/${event.image}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="${event.name}">` :
                        `<div class="bg-light" style="height: 200px;"></div>`
                    }
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${event.name}</h5>
                        <p class="card-text flex-grow-1">${event.description.substring(0, 100)}...</p>
                        <div class="mt-auto">
                            <p class="text-muted mb-3">
                                <i class="bi bi-calendar"></i> ${new Date(event.date).toLocaleDateString()}<br>
                                <i class="bi bi-geo-alt"></i> ${event.location}<br>
                                <i class="bi bi-people"></i> Available Seats: ${event.available_seats}
                            </p>
                            <a href="${baseUrl}/events/${event.id}" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    async function performSearch() {
        const query = searchInput.value;
        const url = `${baseUrl}/events/search?query=${encodeURIComponent(query)}`;
        isSearching = query.length > 0;

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();
            if (data.items && data.items.data) {
                renderEvents(data.items.data);
                generatePagination(data);
                window.history.pushState({}, '', url);
                lastPage = data.items.last_page;
                currentPage = data.items.current_page;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function updatePaginationState(page) {
        currentPage = parseInt(page);
        
        paginationContainer.querySelectorAll('.page-item').forEach(item => {
            const link = item.querySelector('.page-link');
            if (!link) return;

            item.classList.remove('active', 'disabled');

            if (link.getAttribute('rel') === 'prev') {
                if (currentPage === 1) {
                    item.classList.add('disabled');
                }
            } else if (link.getAttribute('rel') === 'next') {
                if (currentPage === lastPage) {
                    item.classList.add('disabled');
                }
            } else {
                const pageNumber = parseInt(link.textContent);
                if (!isNaN(pageNumber)) {
                    if (pageNumber === currentPage) {
                        item.classList.add('active');
                    }
                    link.href = isSearching ? 
                        `${baseUrl}/events/search?query=${encodeURIComponent(searchInput.value)}&page=${pageNumber}` :
                        `${baseUrl}/events?page=${pageNumber}`;
                }
            }
        });
    }

    async function handlePaginationClick(e) {
        const link = e.target.closest('.page-link');
        if (!link || link.parentElement.classList.contains('disabled')) {
            e.preventDefault();
            return;
        }

        e.preventDefault();
        let url = link.href;

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();
            if (data.items && data.items.data) {
                renderEvents(data.items.data);
                generatePagination(data);
                window.scrollTo(0, 0);
                window.history.pushState({}, '', url);
                lastPage = data.items.last_page;
                currentPage = data.items.current_page;
                updatePaginationState(currentPage);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function attachPaginationListeners() {
        const newPaginationContainer = document.querySelector('.pagination');
        if (newPaginationContainer) {
            newPaginationContainer.addEventListener('click', handlePaginationClick);
        }
    }

    let timeoutId = null;
    searchInput.addEventListener('input', () => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(performSearch, 300);
    });

    searchBtn.addEventListener('click', performSearch);

    updatePaginationState(currentPage);

    if (paginationContainer) {
        paginationContainer.addEventListener('click', handlePaginationClick);
    }
});
</script>
@endpush

@endsection