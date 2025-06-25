@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px; margin-top:40px;">
    @guest
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <span>Login</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">E-Mail Address</label>
                        <input id="email" type="email" class="form-control" name="email" required autofocus>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <strong>Clubs I Founded</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-2" style="max-width:300px;">
                            <input type="text" id="founded-club-search" class="form-control" placeholder="Search founded clubs...">
                        </div>
                        @if(isset($foundedClubs) && count($foundedClubs))
                            <ul class="list-group list-group-flush" id="founded-clubs-list">
                                @foreach($foundedClubs as $club)
                                    <li class="list-group-item founded-club-item">
                                        <a href="{{ route('clubs.show', $club->id) }}">{{ $club->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">You haven't founded any clubs.</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <strong>Clubs I Joined</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-2" style="max-width:300px;">
                            <input type="text" id="joined-club-search" class="form-control" placeholder="Search joined clubs...">
                        </div>
                        @if(isset($joinedClubs) && count($joinedClubs))
                            <ul class="list-group list-group-flush" id="joined-clubs-list">
                                @foreach($joinedClubs as $club)
                                    <li class="list-group-item joined-club-item">
                                        <a href="{{ route('clubs.show', $club->id) }}">{{ $club->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">You haven't joined any clubs.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-warning text-white">
                        <strong>Events I'll Join </strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-2" style="max-width:300px;">
                            <input type="text" id="joined-event-search" class="form-control" placeholder="Search events...">
                        </div>
                        @if(isset($joinedEvents) && count($joinedEvents))
                            <ul class="list-group list-group-flush" id="joined-events-list">
                                @foreach($joinedEvents as $event)
                                    <li class="list-group-item joined-event-item">
                                        <a href="{{ route('clubs.events.show', [$event->club_id, $event->id]) }}">{{ $event->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">You haven't joined any events.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endguest
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Founded Clubs Filter
    const foundedInput = document.getElementById('founded-club-search');
    if (foundedInput) {
        foundedInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            let found = false;
            document.querySelectorAll('.founded-club-item').forEach(item => {
                const name = item.textContent.toLowerCase();
                if (name.includes(query)) {
                    item.style.display = '';
                    found = true;
                } else {
                    item.style.display = 'none';
                }
            });
            // Sonuç yoksa mesaj
            const list = document.getElementById('founded-clubs-list');
            let noRow = document.getElementById('no-founded-clubs');
            if (!found) {
                if (!noRow) {
                    noRow = document.createElement('li');
                    noRow.id = 'no-founded-clubs';
                    noRow.className = 'list-group-item text-center text-muted';
                    noRow.textContent = 'No clubs found.';
                    list.appendChild(noRow);
                }
            } else {
                if (noRow) noRow.remove();
            }
        });
    }
    // Joined Clubs Filter
    const joinedInput = document.getElementById('joined-club-search');
    if (joinedInput) {
        joinedInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            let found = false;
            document.querySelectorAll('.joined-club-item').forEach(item => {
                const name = item.textContent.toLowerCase();
                if (name.includes(query)) {
                    item.style.display = '';
                    found = true;
                } else {
                    item.style.display = 'none';
                }
            });
            const list = document.getElementById('joined-clubs-list');
            let noRow = document.getElementById('no-joined-clubs');
            if (!found) {
                if (!noRow) {
                    noRow = document.createElement('li');
                    noRow.id = 'no-joined-clubs';
                    noRow.className = 'list-group-item text-center text-muted';
                    noRow.textContent = 'No clubs found.';
                    list.appendChild(noRow);
                }
            } else {
                if (noRow) noRow.remove();
            }
        });
    }
    // Joined Events Filter
    const eventInput = document.getElementById('joined-event-search');
    if (eventInput) {
        eventInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            let found = false;
            document.querySelectorAll('.joined-event-item').forEach(item => {
                const name = item.textContent.toLowerCase();
                if (name.includes(query)) {
                    item.style.display = '';
                    found = true;
                } else {
                    item.style.display = 'none';
                }
            });
            const list = document.getElementById('joined-events-list');
            let noRow = document.getElementById('no-joined-events');
            if (!found) {
                if (!noRow) {
                    noRow = document.createElement('li');
                    noRow.id = 'no-joined-events';
                    noRow.className = 'list-group-item text-center text-muted';
                    noRow.textContent = 'No events found.';
                    list.appendChild(noRow);
                }
            } else {
                if (noRow) noRow.remove();
            }
        });
    }
});
</script>
@endpush


