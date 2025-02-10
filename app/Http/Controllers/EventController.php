<?php
namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        // $events = Event::orderBy('date')->paginate(9);
    
        // if (request()->ajax()) {
        //     return response()->json([
        //         'events' => view('events._events_list', compact('events'))->render(),
        //         'pagination' => $events->links()->toHtml()
        //     ]);
        // }
    
        // return view('events.index', compact('events'));
        $events = Event::orderBy('date')->paginate(9);

        if (request()->wantsJson()) {
            return response()->json([
                'items' => $events
            ]);
        }
    
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:today',
            'location' => 'required|string',
            'available_seats' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path;
        }
    
        $event = Event::create($validated);
    
        return redirect()
            ->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $events = Event::where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('location', 'like', "%{$query}%")
                      ->orderBy('date')
                      ->paginate(9);
    
        if ($request->wantsJson()) {
            return response()->json([
                'items' => $events
            ]);
        }
    
        return view('events.index', compact('events'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }
    
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string',
            'available_seats' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }
    
        $event->update($validated);
    
        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Event updated successfully.');
    }
    
    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();
    
        return redirect()
            ->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }
}