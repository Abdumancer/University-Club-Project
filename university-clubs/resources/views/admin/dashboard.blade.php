@extends('layouts.app')

@section('content')
    <h1>Admin Panel</h1>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">
                    <a href="{{ route('admin.users') }}" style="color:inherit;text-decoration:underline;">Total Users</a>
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ $totalUsers }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">
                    <a href="/university-clubs/public/clubs" style="color:inherit;text-decoration:underline;">Total Clubs</a>
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ $totalClubs }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">
                    <a href="{{ route('admin.pendingClubs') }}" style="color:inherit;text-decoration:underline;">Pending Clubs</a>
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ $pendingClubs }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">
                    <a href="{{ route('admin.pendingEvents') }}" style="color:inherit;text-decoration:underline;">Pending Events</a>
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ $pendingEvents }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">
                    <a href="{{ route('admin.events') }}" style="color:inherit;text-decoration:underline;">Total Events</a>
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ $totalEvents }}</h4>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user() && Auth::user()->isAdmin())
    <div class="mt-5">
        <h2>Statistics</h2>
        <div class="mb-5">
            <h5>Most Active Clubs</h5>
            <canvas id="mostActiveClubsChart" height="100"></canvas>
        </div>
        <div class="mb-5">
            <h5>Clubs with Most Members</h5>
            <canvas id="mostMemberClubsChart" height="100"></canvas>
        </div>
        <div class="mb-5">
            <h5>Events with Most Attendance</h5>
            <canvas id="mostAttendedEventsChart" height="100"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ asset('js/admin-stats.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.renderBarChart(
                    'mostActiveClubsChart',
                    @json($mostActiveClubs->pluck('name')),
                    @json($mostActiveClubs->pluck('events_count')),
                    'Event Count'
                );
                window.renderBarChart(
                    'mostMemberClubsChart',
                    @json($mostMemberClubs->pluck('name')),
                    @json($mostMemberClubs->pluck('members_count')),
                    'Member Count'
                );
                window.renderBarChart(
                    'mostAttendedEventsChart',
                    @json($mostAttendedEvents->pluck('title')),
                    @json($mostAttendedEvents->pluck('rsvps_count')),
                    'Attendance'
                );
            });
        </script>
    </div>
    @endif
@endsection