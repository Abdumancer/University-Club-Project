<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $users = \App\User::all();
        return view('admin.users', compact('users'));
    }

    public function makeAdmin($userId)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        $user = \App\User::findOrFail($userId);
        $user->role = 'admin';
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User is now admin!');
    }

    public function dashboard()
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $totalUsers = \App\User::count();
        $totalClubs = \App\Club::count();
        $pendingClubs = \App\Club::where('approved', false)->count();
        $pendingEvents = \App\Event::where('club_approved', true)->where('admin_approved', false)->count();
        $totalEvents = \App\Event::where('club_approved', true)->where('admin_approved', true)->count();

        // Most Active Clubs (top 5)
        $mostActiveClubs = \App\Club::withCount('events')->orderByDesc('events_count')->take(5)->get();
        // Clubs with Most Members (top 5)
        $mostMemberClubs = \App\Club::withCount('members')->orderByDesc('members_count')->take(5)->get();
        // Events with Most Attendance (top 5)
        $mostAttendedEvents = \App\Event::withCount('rsvps')->with('club')->orderByDesc('rsvps_count')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalClubs', 'pendingClubs', 'pendingEvents', 'totalEvents',
            'mostActiveClubs', 'mostMemberClubs', 'mostAttendedEvents'
        ));
    }

    public function pendingClubs()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $pendingClubs = \App\Club::where('approved', false)->get();
        return view('admin.pending_clubs', compact('pendingClubs'));
    }

    public function approveClub($id)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $club = \App\Club::findOrFail($id);
        $club->approved = true;
        $club->save();

        \App\Membership::firstOrCreate([
            'user_id' => $club->created_by,
            'club_id' => $club->id,
        ]);

        return back()->with('success', 'Club approved!');
    }

    public function rejectClub($id)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $club = \App\Club::findOrFail($id);
        $club->delete();
        return back()->with('success', 'Club rejected and deleted!');
    }

    public function pendingEvents()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $events = \App\Event::where('club_approved', true)->where('admin_approved', false)->get();
        return view('admin.pending_events', compact('events'));
    }

    public function editEvent($eventId)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $event = \App\Event::findOrFail($eventId);
        return view('admin.edit_event', compact('event'));
    }

    public function updateEvent(Request $request, $eventId)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $event = \App\Event::findOrFail($eventId);
        $event->title = $request->title;
        $event->description = $request->description;
        $event->event_date = $request->event_date;
        $event->location = $request->location;
        $event->save();
        return redirect()->route('admin.events')->with('success', 'Event updated!');
    }

    public function approveEvent($eventId)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $event = \App\Event::findOrFail($eventId);
        $event->admin_approved = true;
        $event->save();
        return back()->with('success', 'Event approved by admin!');
    }

    public function rejectEvent($eventId)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $event = \App\Event::findOrFail($eventId);
        $event->delete();
        return back()->with('success', 'Event rejected and deleted!');
    }

    public function clubs()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $clubs = \App\Club::all();
        return view('admin.clubs', compact('clubs'));
    }

    public function events()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $events = \App\Event::with('club')->where('club_approved', true)->where('admin_approved', true)->get();
        return view('admin.events', compact('events'));
    }
}
