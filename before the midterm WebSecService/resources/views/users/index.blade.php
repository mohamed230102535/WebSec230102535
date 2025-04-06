@extends('layouts.master')

@section('title', 'Users List')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-primary">Users</h1>
        <a href="{{ route('users.create') }}" class="btn btn-success">+ Create User</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-info">{{ ucfirst($user->role) }}</span></td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}">Delete</button>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-none" id="delete-form-{{ $user->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- jQuery for Delete Confirmation --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".delete-btn").click(function() {
            var userId = $(this).data("id");
            if (confirm("Are you sure you want to delete this user?")) {
                $("#delete-form-" + userId).submit();
            }
        });
    });
</script>
@endsection
