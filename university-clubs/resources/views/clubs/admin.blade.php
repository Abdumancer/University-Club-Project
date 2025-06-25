@extends('layouts.app')

@section('content')
    <h1>Pending Club Applications</h1>
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    <ul>
        @foreach($clubs as $club)
            <li>
                {{ $club->name }} - {{ $club->description }}
                <form method="POST" action="{{ route('admin.clubs.approve', $club->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit">Approve</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection