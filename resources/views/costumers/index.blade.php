@extends('layouts.index')

@section('title', 'User')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
            <h2 class="text-center w-100 fw-bold">User Management</h2>
            <a href="{{ route('costumers.create') }}" class="btn costumers-index-btn-add-costumers">
                <i class="fas fa-plus"></i> Add User
            </a>
        </div>

        <div class="costumers-index-form">
            <form method="GET" action="{{ route('costumers.index') }}" class="mb-4">
                <div class="costumers-index-head-row align-items-center">
                    <div class="col-md-4 d-flex align-items-center">
                        <input type="text" name="search" class="form-control costumers-index-search-bar"
                            placeholder="Search users..." value="{{ request()->search }}">
                        <button type="button" class="btn costumers-index-filter-btn" onclick="toggleFilterMenu()">
                            <i class="fas fa-filter costumers-index-icon"></i>
                        </button>
                        <div class="costumers-index-filter-menu" id="filterMenu">
                            <button type="submit" name="role" value="all" {{ request()->role == 'all' ? 'class=active' : '' }}>All Users</button>
                            <button type="submit" name="role" value="admin" {{ request()->role == 'admin' ? 'class=active' : '' }}>Admin</button>
                            <button type="submit" name="role" value="user" {{ request()->role == 'user' ? 'class=active' : '' }}>User</button>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex justify-content-end">
                        <button type="submit" class="btn costumers-index-btn-search">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table costumers-index-table">
                <thead class="costumers-index-thead-light">
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
                        <tr class="costumers-index-row">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if ($user->image_url)
                                    <img src="{{ asset('storage/' . $user->image_url) }}" alt="{{ $user->name }}"
                                        class="costumers-index-user-image">
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
                                <form action="{{ route('costumers.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger costumers-index-btn-delete-costumers">
                                        <i class="fas fa-trash costumers-index-icon"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <nav>
                {{ $users->withQueryString()->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>

    <script>
        function toggleFilterMenu() {
            const menu = document.getElementById('filterMenu');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }

        document.addEventListener('click', function(event) {
            const filterMenu = document.getElementById('filterMenu');
            const filterBtn = document.querySelector('.costumers-index-filter-btn');
            if (filterMenu.style.display === 'block' && !filterBtn.contains(event.target) && !filterMenu.contains(event.target)) {
                filterMenu.style.display = 'none';
            }
        });
    </script>
@endsection
