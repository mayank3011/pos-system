@extends('admin_dashboard')
@section('admin')
<div class="container mt-4">
    <h1 class="text-center mb-4">Add New Book Group</h1>

    @if(session('message'))
        <div class="alert alert-{{ session('alert-type') }}">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('book-groups.store') }}">
        @csrf
        <div class="mb-3">
            <label for="group_name" class="form-label">Group Name</label>
            <input type="text" class="form-control @error('group_name') is-invalid @enderror" name="group_name" id="group_name" placeholder="Enter group name" value="{{ old('group_name') }}" required>
            @error('group_name')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Add Group</button>
    </form>
</div>
@endsection
