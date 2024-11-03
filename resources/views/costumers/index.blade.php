@extends('layouts.index')

@section('content')
    <div class="costumers-container">
        <h1 class="costumers-title">User Management</h1>
        <div class="costumers-search-filter-container">
            <input type="text" class="costumers-search-bar" placeholder="Search users...">
            <select class="costumers-filter-select">
                <option value="all">All Users</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <a href="{{ route('costumers.create') }}" class="costumers-btn costumers-btn-primary">
            <i class="fas fa-plus costumers-icon"></i> Add User
        </a>
        <table class="costumers-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if ($user->image_url)
                                <img src="{{ asset('storage/' . $user->image_url) }}" alt="{{ $user->name }}" class="costumers-user-image">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td>
                            <form action="{{ route('costumers.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="costumers-btn costumers-btn-danger">
                                    <i class="fas fa-trash costumers-icon"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
