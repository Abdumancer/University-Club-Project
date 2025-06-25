@extends('layouts.app')

@section('content')
    <h2>Pending Events (Club Approved)</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Event Title</th>
                <th>Description</th>
                <th>Date</th>
                <th>Location</th>
                <th>Club</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->description }}</td>
                    <td>{{ $event->event_date }}</td>
                    <td>{{ $event->location }}</td>
                    <td>{{ $event->club->name }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.pendingEvents.approve', $event->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.pendingEvents.reject', $event->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                        <a href="{{ route('admin.pendingEvents.edit', $event->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection