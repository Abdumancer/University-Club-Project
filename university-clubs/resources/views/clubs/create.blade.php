@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h2 class="mb-4 text-center font-weight-bold">New Club Application</h2>
                    <form method="POST" action="{{ route('clubs.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Club Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block btn-lg mt-4">Apply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection