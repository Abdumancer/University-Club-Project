@extends('layouts.app')

@section('content')
    <h2>Pending Club Requests</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Applicant</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingClubs as $club)
                <tr>
                    <td>{{ $club->name }}</td>
                    <td>{{ $club->description }}</td>
                    <td>{{ $club->creator->name ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.approveClub', $club->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.rejectClub', $club->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
