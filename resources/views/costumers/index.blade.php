@extends('layouts.index')

@section('title', 'User')

@section('content')
    <div class="costumers-container">
        <div class="costumers-header">
            <h1 class="costumers-title">User Management</h1>
            <a href="{{ route('costumers.create') }}" class="costumers-btn costumers-btn-primary costumers-btn-add">
                <i class="fas fa-plus costumers-icon"></i> Add User
            </a>
        </div>

        <div class="costumers-search-filter-container">
            <form method="GET" action="{{ route('costumers.index') }}" style="display: flex; width: 100%; align-items: center;">
                <input type="text" name="search" class="costumers-search-bar" placeholder="Search users..."
                    value="{{ request()->search }}">
                
                <!-- Filter Icon with Dropdown Menu -->
                <div class="costumers-filter-icon-container">
                    <button type="button" class="costumers-filter-btn" onclick="toggleFilterMenu()">
                        <i class="fas fa-filter costumers-icon"></i>
                    </button>
                    <div class="costumers-filter-menu" id="filterMenu">
                        <button type="submit" name="role" value="all" {{ request()->role == 'all' ? 'class=active' : '' }}>All Users</button>
                        <button type="submit" name="role" value="admin" {{ request()->role == 'admin' ? 'class=active' : '' }}>Admin</button>
                        <button type="submit" name="role" value="user" {{ request()->role == 'user' ? 'class=active' : '' }}>User</button>
                    </div>
                </div>

                <button type="submit" class="costumers-btn costumers-btn-primary">
                    <i class="fas fa-search costumers-icon"></i> Search
                </button>
            </form>
        </div>

        <table class="costumers-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Signed Up</th>
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
    <script>
        function toggleFilterMenu() {
            const menu = document.getElementById('filterMenu');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }

        document.addEventListener('click', function (event) {
            const filterMenu = document.getElementById('filterMenu');
            const filterBtn = document.querySelector('.costumers-filter-btn');
            if (filterMenu.style.display === 'block' && !filterBtn.contains(event.target) && !filterMenu.contains(event.target)) {
                filterMenu.style.display = 'none';
            }
        });
    </script>
@endsection
