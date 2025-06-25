@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center mb-3">
                        <h1 class="display-4 font-weight-bold mb-0 mr-3 text-primary">{{ $event->title }}</h1>
                    </div>
                    <div class="mb-4">
                        <span class="badge badge-secondary p-2">
                            <i class="fas fa-users mr-1"></i>
                            Club: <a href="{{ route('clubs.show', $club->id) }}" class="text-white font-weight-bold">{{ $club->name }}</a>
                        </span>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-weight-bold mb-2"><i class="far fa-calendar-alt mr-2 text-info"></i>Event Date</h5>
                        <div class="text-dark">{{ $event->event_date }}</div>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-weight-bold mb-2"><i class="fas fa-align-left mr-2 text-info"></i>Description</h5>
                        <div class="text-muted">{{ $event->description }}</div>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-weight-bold mb-2"><i class="fas fa-map-marker-alt mr-2 text-info"></i>Location</h5>
                        <div class="text-dark">{{ $event->location }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('head')
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush