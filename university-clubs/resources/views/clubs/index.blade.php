@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4 font-weight-bold mb-0">Clubs</h1>
        <a href="{{ route('clubs.create') }}" class="btn btn-primary btn-lg shadow">+ New Club Application</a>
    </div>
    <div class="mb-4 position-relative" style="max-width:400px;">
        <input type="text" id="club-search" class="form-control" placeholder="Search clubs...">
    </div>
    <div class="row" id="clubs-list">
        @forelse($clubs as $club)
            <div class="col-md-4 mb-4 club-card" data-name="{{ strtolower($club->name) }}">
                <div class="card h-100 shadow-sm border-0 bg-danger text-white">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title font-weight-bold mb-2">{{ $club->name }}</h5>
                        <p class="card-text flex-grow-1">{{ $club->description }}</p>
                        <a href="{{ route('clubs.show', $club->id) }}" class="btn btn-light mt-2">View Club</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">No clubs found.</div>
            </div>
        @endforelse
    </div>
</div>
@push('scripts')
<script>
    const searchInput = document.getElementById('club-search');
    const clubsList = document.getElementById('clubs-list');
    const searchBtn = document.createElement('button');
   
   

    function filterClubs() {
        const query = searchInput.value.toLowerCase();
        let found = false;
        document.querySelectorAll('.club-card').forEach(card => {
            const clubName = (card.getAttribute('data-name') || '').toLowerCase();
            if (clubName.includes(query)) {
                card.style.display = 'block';
                found = true;
            } else {
                card.style.display = 'none';
            }
        });
        const noClubs = document.querySelector('.alert-info.text-center');
        if (noClubs) {
            noClubs.style.display = found ? 'none' : 'block';
        }
    }
    searchBtn.addEventListener('click', filterClubs);
    searchInput.addEventListener('keydown', function(e) {
        if(e.key === 'Enter') filterClubs();
    });
    searchInput.addEventListener('input', filterClubs);
</script>
@endpush
@endsection