<?php

namespace App\Http\Controllers;

use App\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clubs = \App\Club::where('approved', true)->get();
        return view('clubs.index', compact('clubs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clubs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $userId = auth()->id() ?? 1;
        $club = \App\Club::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => $userId,
            'approved' => false, 
        ]);

        \App\Membership::create([
            'user_id' => $userId,
            'club_id' => $club->id,
            'role' => 'founder',
        ]);

        return redirect()->route('clubs.index')->with('success', 'Your club application has been received!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $club = \App\Club::with('members')->findOrFail($id);

        
        if (auth()->id() == $club->created_by) {
            $events = $club->events;
        } else {

            $events = $club->events()
                ->where('club_approved', true)
                ->where('admin_approved', true)
                ->get();
        }

        return view('clubs.show', compact('club', 'events'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display a listing of the resource for admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $clubs = \App\Club::where('approved', false)->get();
        return view('clubs.admin', compact('clubs'));
    }

    /**
     * Approve the specified club.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $club = \App\Club::findOrFail($id);
        $club->approved = true;
        $club->save();

        return redirect()->route('admin.clubs')->with('success', 'Club approved!');
    }

    /**
     * Join the specified club.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


     public function join($clubId)
    {
        $club = \App\Club::findOrFail($clubId);
        $userId = auth()->id();

        // Zaten üye mi kontrol et
        $already = \App\Membership::where('user_id', $userId)->where('club_id', $clubId)->exists();
        if ($already) {
            return back()->with('success', 'You are already a member of this club!');
        }

        \App\Membership::create([
            'user_id' => $userId,
            'club_id' => $clubId,
            'role' => 'member'
        ]);

        return back()->with('success', 'You have successfully joined the club!');
    }

    /**
     * Remove a member from the specified club.
     *
     * @param  int  $clubId
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function removeMember($clubId, $userId)
    {
        $club = \App\Club::findOrFail($clubId);
        $user = auth()->user();

        $isOwner = $club->created_by == $user->id;
        $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : (($user->role ?? null) === 'admin');
        if (!($isOwner || $isAdmin)) {
            abort(403, 'Only the club creator or an admin can remove members.');
        }

        if ($userId == $club->created_by) {
            return back()->with('error', 'You cannot remove the club creator.');
        }

        \App\Membership::where('user_id', $userId)->where('club_id', $clubId)->delete();

        return back()->with('success', 'Member removed from the club.');
    }

    /**
     * Leave the specified club.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leave($clubId)
    {
        $club = \App\Club::findOrFail($clubId);
        $userId = auth()->id();

        if ($club->created_by == $userId) {
            return back()->with('error', 'You cannot leave a club you created.');
        }

        \App\Membership::where('user_id', $userId)->where('club_id', $clubId)->delete();

        return back()->with('success', 'You have left the club.');
    }
}
