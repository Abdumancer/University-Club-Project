<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        $foundedClubs = \App\Club::where('created_by', $user->id)->get();
        $joinedClubs = \App\Club::whereHas('members', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('created_by', '!=', $user->id)->get();
        $joinedEvents = \App\Event::whereHas('rsvps', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

        return view('home', compact('foundedClubs', 'joinedClubs', 'joinedEvents'));
    }
}
