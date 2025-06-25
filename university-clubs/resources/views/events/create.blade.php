@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h2 class="mb-4 text-center font-weight-bold">New Event for <span class="text-primary">{{ $club->name }}</span></h2>
                    <form method="POST" action="{{ route('clubs.events.store', $club->id) }}">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="event_date">Date</label>
                            <input type="datetime-local" class="form-control" id="event_date" name="event_date" required>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location">
                        </div>
                        <button type="submit" class="btn btn-success btn-block btn-lg mt-4">Create Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection