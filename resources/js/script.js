// script.js
import PageItem from './PageItem.js';

async function initializePagination() {
    try {
        const pagination = document.getElementById('pagination');
        const eventsContainer = document.getElementById('events-container');
        
        if (!pagination || !eventsContainer) {
            throw new Error('Required elements not found');
        }

        const pageItem = new PageItem(pagination);

        const response = await fetch('/event');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        
        renderEvents(data.items.data);
        pageItem.add(data.items, async (url) => {
            try {
                const pageResponse = await fetch(url);
                if (!pageResponse.ok) {
                    throw new Error('Page fetch failed');
                }
                
                const newData = await pageResponse.json();
                renderEvents(newData.items.data);
                pageItem.add(newData.items);
            } catch (error) {
                console.error('Error fetching page:', error);
            }
        });

    } catch (error) {
        console.error('Initialization error:', error);
    }
}

function renderEvents(events) {
    const eventsContainer = document.getElementById('events-container');
    eventsContainer.innerHTML = '';  
    
    events.forEach(event => {
        const eventHtml = `
            <div class="col">
                <div class="card h-100">
                    ${event.image ? 
                        `<img src="/storage/${event.image}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="${event.name}">` :
                        `<div class="bg-light" style="height: 200px;"></div>`
                    }
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${event.name}</h5>
                        <p class="card-text flex-grow-1">${event.description.substring(0, 100)}...</p>
                        <div class="mt-auto">
                            <p class="text-muted mb-3">
                                <i class="bi bi-calendar"></i> ${new Date(event.date).toLocaleDateString()} ${new Date(event.date).toLocaleTimeString()}<br>
                                <i class="bi bi-geo-alt"></i> ${event.location}<br>
                                <i class="bi bi-people"></i> Available Seats: ${event.available_seats}
                            </p>
                            <a href="/events/${event.id}" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        eventsContainer.insertAdjacentHTML('beforeend', eventHtml);
    });
}

document.addEventListener('DOMContentLoaded', initializePagination);