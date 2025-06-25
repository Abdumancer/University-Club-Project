@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0 mb-4">
                <div class="card-body p-5">
                    <h1 class="display-4 font-weight-bold mb-2">{{ $club->name }}</h1>
                    <p class="lead text-muted mb-4">{{ $club->description }}</p>
                    @if(session('success'))
                        <div class="alert alert-success">{{ str_replace('RSVP başarıyla iptal edildi.', 'RSVP cancelled successfully.', session('success')) }}</div>
                    @endif
                    <div class="mb-3">
                        <form method="POST" action="{{ route('clubs.join', $club->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">Join this Club</button>
                        </form>
                        <a href="{{ route('clubs.events.create', $club->id) }}" class="btn btn-outline-success btn-lg ml-2">Add Event</a>
                    </div>
                    <h3 class="mt-5 mb-3 font-weight-bold">Events</h3>
                    <div class="mb-3" style="max-width:350px;">
                        <input type="text" id="event-search" class="form-control" placeholder="Search events...">
                    </div>
                    @forelse($events as $event)
                        <div class="card mb-3 border-0 shadow-sm event-card" data-title="{{ strtolower($event->title) }}" data-description="{{ strtolower($event->description) }}">
                            <div class="card-body">
                                <h5 class="card-title mb-1">
                                    <a href="{{ route('clubs.events.show', [$club->id, $event->id]) }}" class="font-weight-bold text-primary">{{ $event->title }}</a>
                                    <span class="badge badge-info ml-2">{{ $event->event_date }}</span>
                                </h5>
                                <p class="mb-2">{{ $event->description }}</p>
                                @php
                                    $userRsvped = $event->rsvps->contains('user_id', auth()->id());
                                    $isMember = $club->members->contains('id', auth()->id());
                                    $isOwnerOrAdmin = auth()->id() == $club->created_by || (auth()->user()->role ?? null) === 'admin';
                                @endphp
                                @if($event->club_approved && $event->admin_approved && $isMember)
                                    @if(!$userRsvped)
                                        <form method="POST" action="{{ route('events.rsvp', $event->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">RSVP</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('events.rsvp.cancel', $event->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Cancel RSVP</button>
                                        </form>
                                    @endif
                                @endif
                                @if(!$event->club_approved && auth()->id() == $club->created_by)
                                    <form method="POST" action="{{ route('clubs.events.approve', [$club->id, $event->id]) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Approve Event</button>
                                    </form>
                                    <form method="POST" action="{{ route('clubs.events.reject', [$club->id, $event->id]) }}" class="d-inline ml-2">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Reject Event</button>
                                    </form>
                                @endif
                                @if($isOwnerOrAdmin)
                                    <button type="button" class="btn btn-info btn-sm ml-2 view-participants-btn" data-event-id="{{ $event->id }}">View Participants</button>
                                    <form method="POST" action="{{ route('clubs.events.destroy', [$club->id, $event->id]) }}" class="d-inline ml-2" onsubmit="return confirm('Are you sure you want to remove this event?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info event-empty">No events found.</div>
                    @endforelse
                    <h3 class="mt-5 mb-3 font-weight-bold">Members</h3>
                    <div class="mb-3" style="max-width:350px;">
                        <input type="text" id="member-search" class="form-control" placeholder="Search members by name or email...">
                    </div>
                    <ul class="list-group list-group-flush" id="members-list">
                        @foreach($club->members as $member)
                            <li class="list-group-item d-flex justify-content-between align-items-center member-item" data-name="{{ strtolower($member->name) }}" data-email="{{ strtolower($member->email) }}">
                                <span class="member-name">{{ $member->name }}</span> <span class="text-muted">({{ $member->email }})</span>
                                @php
                                    $canRemove = (auth()->id() == $club->created_by || (auth()->user()->role ?? null) === 'admin') && $member->id != $club->created_by;
                                @endphp
                                @if($canRemove)
                                    <form method="POST" action="{{ route('clubs.removeMember', [$club->id, $member->id]) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    @if($club->members->contains(auth()->id()) && auth()->id() != $club->created_by)
                        <form method="POST" action="{{ route('clubs.leave', $club->id) }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">Leave Club</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventInput = document.getElementById('event-search');
    if (eventInput) {
        eventInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            let found = false;
            document.querySelectorAll('.event-card').forEach(card => {
                const title = (card.getAttribute('data-title') || '').toLowerCase();
                const desc = (card.getAttribute('data-description') || '').toLowerCase();
                if (title.includes(query) || desc.includes(query)) {
                    card.style.display = 'block';
                    found = true;
                } else {
                    card.style.display = 'none';
                }
            });
            const emptyMsg = document.querySelector('.event-empty');
            if (emptyMsg) emptyMsg.style.display = found ? 'none' : 'block';
        });
    }
    
    const memberInput = document.getElementById('member-search');
    if (memberInput) {
        document.querySelectorAll('.member-item').forEach(item => item.classList.remove('d-none'));
        function filterMembers() {
            const query = memberInput.value.trim().toLowerCase();
            let noRow = document.getElementById('no-members-row');
            if (noRow) noRow.remove();
            let found = false;
            document.querySelectorAll('.member-item').forEach(item => {
                const name = (item.getAttribute('data-name') || '').toLowerCase();
                const email = (item.getAttribute('data-email') || '').toLowerCase();
                if (query === '' || name.includes(query) || email.includes(query)) {
                    item.classList.remove('d-none');
                    found = true;
                } else {
                    item.classList.add('d-none');
                }
            });
            if (!found) {
                noRow = document.createElement('li');
                noRow.id = 'no-members-row';
                noRow.className = 'list-group-item text-center text-muted';
                noRow.textContent = 'No members found.';
                document.getElementById('members-list').appendChild(noRow);
            }
        }
        memberInput.addEventListener('keydown', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                filterMembers();
            }
        });
        memberInput.addEventListener('input', function() {
            filterMembers();
        });
        filterMembers();
    }

    // View Participants logic
    let participantsData = [];
    document.querySelectorAll('.view-participants-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var eventId = this.getAttribute('data-event-id');
            var modal = $('#participantsModal');
            var list = document.getElementById('participants-list');
            var emptyMsg = document.getElementById('participants-empty');
            var searchInput = document.getElementById('participants-search');
            list.innerHTML = '';
            emptyMsg.style.display = 'none';
            if (searchInput) searchInput.value = '';
            var baseUrl = window.location.origin;
            var fetchUrl = baseUrl + '/university-clubs/public/events/' + eventId + '/participants';
            fetch(fetchUrl)
                .then(response => response.json())
                .then(data => {
                    participantsData = data;
                    renderParticipantsList(data);
                    modal.modal('show');
                })
                .catch(() => {
                    emptyMsg.textContent = 'Failed to load participants.';
                    emptyMsg.style.display = 'block';
                    modal.modal('show');
                });
        });
    });
    function renderParticipantsList(data) {
        var list = document.getElementById('participants-list');
        var emptyMsg = document.getElementById('participants-empty');
        list.innerHTML = '';
        if (data.length > 0) {
            data.forEach(function(user) {
                var li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = user.name + ' (' + user.email + ')';
                list.appendChild(li);
            });
            emptyMsg.style.display = 'none';
        } else {
            emptyMsg.style.display = 'block';
        }
    }
    var participantsSearch = document.getElementById('participants-search');
    if (participantsSearch) {
        participantsSearch.addEventListener('input', function() {
            var query = this.value.trim().toLowerCase();
            var filtered = participantsData.filter(function(user) {
                return user.name.toLowerCase().includes(query) || user.email.toLowerCase().includes(query);
            });
            renderParticipantsList(filtered);
        });
    }
});
</script>
@endpush

<div class="modal fade" id="participantsModal" tabindex="-1" role="dialog" aria-labelledby="participantsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="participantsModalLabel">Event Participants</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" id="participants-search" class="form-control mb-2" placeholder="Search participants by name or email...">
        <ul id="participants-list" class="list-group"></ul>
        <div id="participants-empty" class="text-muted text-center" style="display:none;">No participants found.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>