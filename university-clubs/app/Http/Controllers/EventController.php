<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    
   
    public function create($clubId)
    {
        $club = \App\Club::findOrFail($clubId);
        if (!$club->members->contains(auth()->id())) {
            abort(403, 'Only club members can create events.');
        }
        return view('events.create', ['club' => $club]);
    }

   
    public function store(Request $request, $clubId)
    {
        $club = \App\Club::findOrFail($clubId);
        if (!$club->members->contains(auth()->id())) {
            abort(403, 'Only club members can create events.');
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'event_date' => 'required|date',
            'location' => 'nullable|max:255',
        ]);

        \App\Event::create([
            'club_id' => $clubId,
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'location' => $request->location,
            'club_approved' => false,
            'admin_approved' => false,
            'created_by' => auth()->id(), 
        ]);

        return redirect()->route('clubs.show', $clubId)->with('success', 'Event created!');
    }

   
    public function show($clubId, $eventId)
    {
        $club = \App\Club::findOrFail($clubId);
        $event = $club->events()->where('id', $eventId)->firstOrFail();
        return view('events.show', compact('club', 'event'));
    }

    
    public function edit($id)
    {
        $event = \App\Event::findOrFail($id);
        $club = $event->club;
        $userId = auth()->id();
        $isFounder = \App\Membership::where('user_id', $userId)
            ->where('club_id', $club->id)
            ->where('role', 'kurucu')
            ->exists();
        if ($club->created_by != $userId && !$isFounder) {
            abort(403, 'Only the club founder can edit events.');
        }
        return view('events.edit', compact('event', 'club'));
    }

    
    public function update(Request $request, $id)
    {
        $event = \App\Event::findOrFail($id);
        $club = $event->club;
        $userId = auth()->id();
        $isFounder = \App\Membership::where('user_id', $userId)
            ->where('club_id', $club->id)
            ->where('role', 'kurucu')
            ->exists();
        if ($club->created_by != $userId && !$isFounder) {
            abort(403, 'Only the club founder can update events.');
        }
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'event_date' => 'required|date',
            'location' => 'nullable|max:255',
        ]);
        $event->update($request->only(['title', 'description', 'event_date', 'location']));
        return redirect()->route('clubs.show', $club->id)->with('success', 'Event updated!');
    }

    public function rsvp($eventId)
    {
        $userId = auth()->id();
        $already = \App\Rsvp::where('user_id', $userId)->where('event_id', $eventId)->exists();
        if ($already) {
            return back()->with('success', 'You have already RSVP\'d for this event!');
        }
        \App\Rsvp::create([
            'user_id' => $userId,
            'event_id' => $eventId,
        ]);
        return back()->with('success', 'You have RSVP\'d for this event!');
    }

    public function cancelRsvp($eventId)
    {
        $userId = auth()->id();
        $rsvp = \App\Rsvp::where('user_id', $userId)->where('event_id', $eventId)->first();
        if ($rsvp) {
            $rsvp->delete();
            return back()->with('success', 'RSVP başarıyla iptal edildi.');
        }
        return back()->with('error', 'RSVP kaydınız bulunamadı.');
    }

    public function approveEvent($clubId, $eventId)
    {
        $club = \App\Club::findOrFail($clubId);
        if ($club->created_by != auth()->id()) abort(403);

        $event = $club->events()->findOrFail($eventId);
        $event->club_approved = true;
        $event->save();

        return back()->with('success', 'Event sent to admin for approval.');
    }

    public function rejectEvent($clubId, $eventId)
    {
        $club = \App\Club::findOrFail($clubId);
        if ($club->created_by != auth()->id()) abort(403);

        $event = $club->events()->findOrFail($eventId);
        $event->delete();

        return back()->with('success', 'Event rejected and deleted.');
    }
    public function destroy($clubId, $eventId)
    {
        $club = \App\Club::findOrFail($clubId);
        $event = $club->events()->where('id', $eventId)->firstOrFail();
        $user = auth()->user();
        $isOwnerOrAdmin = $user->id == $club->created_by || ($user->role ?? null) === 'admin';
        if (!$isOwnerOrAdmin) {
            abort(403, 'Only the club owner or admins can delete events.');
        }
        $event->rsvps()->delete();
        $event->delete();
        return redirect()->route('clubs.show', $clubId)->with('success', 'Event deleted successfully.');
    }

    public function participants($eventId)
    {
        $event = \App\Event::findOrFail($eventId);
        $user = auth()->user();
        $club = $event->club;
        $isOwnerOrAdmin = $user && ($user->id == $club->created_by || ($user->role ?? null) === 'admin');
        if (!$isOwnerOrAdmin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $participants = $event->rsvps()->with('user:id,name,email')->get()->pluck('user');
        return response()->json($participants);
    }
}
