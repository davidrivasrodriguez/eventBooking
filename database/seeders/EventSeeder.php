<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run()
    {
        $events = [];
        $eventNames = [
            'Tech Conference', 'Music Festival', 'Business Workshop', 
            'Art Exhibition', 'Food Festival', 'Science Fair',
            'Career Fair', 'Gaming Convention', 'Film Festival'
        ];
        
        $descriptions = [
            'Join us for this exciting event featuring industry experts',
            'A unique experience you won\'t want to miss',
            'Learn and network with professionals',
            'Discover new trends and innovations'
        ];
        
        $locations = [
            'Madrid Convention Center', 'Barcelona Central Park', 
            'Valencia Business School', 'Sevilla Exhibition Hall',
            'Málaga Arts Center', 'Bilbao Conference Center'
        ];

        // Generate 31 unique events
        for ($i = 0; $i < 31; $i++) {
            $events[] = [
            'name' => $eventNames[$i % count($eventNames)] . ' ' . date('Y') . ' - Edition ' . ($i + 1),
            'description' => $descriptions[$i % count($descriptions)],
            'date' => Carbon::now()->addDays(15 + $i * 5),
            'location' => $locations[$i % count($locations)],
            'available_seats' => rand(50, 500),
            'image' => null
            ];
        }

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}