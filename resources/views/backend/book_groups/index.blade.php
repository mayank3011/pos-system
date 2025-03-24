@extends('admin_dashboard')
@section('admin')
<div class="container mt-4">
    <h1 class="text-center mb-4">Book Groups</h1>
    
    @if(session('message'))
        <div class="alert alert-{{ session('alert-type') }}">
            {{ session('message') }}
        </div>
    @endif
    
    <a href="{{ route('book-groups.create') }}" class="btn btn-success mb-3">Add New Group</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Group ID</th>
                <th>Group Name</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
            <tr>
                <td>{{ $group->id }}</td>
                <td>{{ $group->group_name }}</td>
                <td>{{ $group->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('book-groups.edit', $group->id) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('book-groups.destroy', $group->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
