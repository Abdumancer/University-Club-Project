@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4 font-weight-bold mb-0">All Approved Events</h1>
    </div>
    <div class="mb-4 position-relative d-flex" style="max-width:400px;">
        <input type="text" id="event-search" class="form-control" placeholder="Search events...">
        <button id="event-search-btn" class="btn btn-secondary ml-2" type="button" style="min-width:80px;">Filter</button>
    </div>
    <div class="row justify-content-center" id="events-list">
        <div class="col-md-8">
            @forelse($events as $event)
                <div class="card mb-3 shadow-sm border-0 bg-info text-white event-card" data-title="{{ strtolower($event->title) }}">
                    <div class="card-body d-flex flex-column flex-md-row align-items-md-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title font-weight-bold mb-1">{{ $event->title }}</h5>
                            <p class="mb-1"><span class="badge badge-light text-info">Club:</span> <span class="font-weight-bold">{{ $event->club->name }}</span></p>
                            <p class="mb-0">{{ $event->description }}</p>
                        </div>
                        <div class="ml-md-4 mt-3 mt-md-0">
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-warning text-white font-weight-bold">Edit Event</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">No events found.</div>
            @endforelse
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('event-search');
    const searchBtn = document.getElementById('event-search-btn');
    if (!searchInput || !searchBtn) {
        alert('Arama çubuğu veya buton bulunamadı!');
        return;
    }
    const events = @json($events);
    function filterEventsBtn() {
        const query = searchInput.value.toLowerCase();
        let found = false;
        document.querySelectorAll('.event-card').forEach(card => {
            const eventTitle = (card.getAttribute('data-title') || '').toLowerCase();
            const clubName = card.querySelector('.font-weight-bold')?.textContent?.toLowerCase() || '';
            if (
                (eventTitle && eventTitle.includes(query)) ||
                (clubName && clubName.includes(query))
            ) {
                card.style.display = 'block';
                found = true;
            } else {
                card.style.display = 'none';
            }
        });
        // Sonuç yoksa info mesajı göster
        const noEvents = document.querySelector('.alert-info.text-center');
        if (noEvents) {
            noEvents.style.display = found ? 'none' : 'block';
        }
    }
    searchBtn.addEventListener('click', filterEventsBtn);
    searchInput.addEventListener('keydown', function(e) {
        if(e.key === 'Enter') filterEventsBtn();
    });
});
</script>
@endpush
@endsection