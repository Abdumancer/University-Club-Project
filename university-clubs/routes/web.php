<?php

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('home');
});
Route::resource('clubs', 'ClubController');
Route::get('admin/clubs', 'ClubController@adminIndex')->name('admin.clubs');
Route::post('admin/clubs/{club}/approve', 'ClubController@approve')->name('admin.clubs.approve');
Route::post('clubs/{club}/join', 'ClubController@join')->name('clubs.join');
Route::post('clubs/{club}/remove-member/{user}', 'ClubController@removeMember')->name('clubs.removeMember');
Route::post('clubs/{club}/leave', 'ClubController@leave')->name('clubs.leave');
Route::resource('clubs.events', 'EventController');
Route::post('events/{event}/rsvp', 'EventController@rsvp')->name('events.rsvp');
Route::post('events/{event}/rsvp/cancel', 'EventController@cancelRsvp')->name('events.rsvp.cancel');
Route::post('clubs/{club}/events/{event}/approve', 'EventController@approveEvent')->name('clubs.events.approve');
Route::post('clubs/{club}/events/{event}/reject', 'EventController@rejectEvent')->name('clubs.events.reject');
Route::get('/clubs/{club}/events/{event}', 'EventController@show')->name('clubs.events.show');
// Route to fetch event participants (AJAX)
Route::get('events/{event}/participants', 'EventController@participants')->name('events.participants');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users', 'AdminController@users')->name('admin.users');
    Route::get('/admin/clubs', 'AdminController@clubs')->name('admin.clubs');
    Route::get('/admin/events', 'AdminController@events')->name('admin.events');
    Route::get('/admin/dashboard', 'AdminController@dashboard')->name('admin.dashboard');
    Route::get('/admin/pending-clubs', 'AdminController@pendingClubs')->name('admin.pendingClubs');
    Route::post('/admin/pending-clubs/{id}/approve', 'AdminController@approveClub')->name('admin.approveClub');
    Route::post('/admin/pending-clubs/{id}/reject', 'AdminController@rejectClub')->name('admin.rejectClub');
    Route::get('/admin/pending-events', 'AdminController@pendingEvents')->name('admin.pendingEvents');
    Route::post('/admin/pending-events/{event}/approve', 'AdminController@approveEvent')->name('admin.pendingEvents.approve');
    Route::post('/admin/pending-events/{event}/reject', 'AdminController@rejectEvent')->name('admin.pendingEvents.reject');
    Route::get('/admin/pending-events/{event}/edit', 'AdminController@editEvent')->name('admin.pendingEvents.edit');
    Route::post('/admin/pending-events/{event}/update', 'AdminController@updateEvent')->name('admin.pendingEvents.update');
    Route::get('/admin/events/{event}/edit', 'AdminController@editEvent')->name('admin.events.edit');
    Route::post('/admin/events/{event}/update', 'AdminController@updateEvent')->name('admin.events.update');
    Route::post('/admin/users/{user}/make-admin', 'AdminController@makeAdmin')->name('admin.users.makeAdmin');
});
