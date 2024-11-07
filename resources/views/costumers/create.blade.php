@extends('layouts.index')

@section('title', 'Customer')

@section('content')
    <div class="create-costumers-container">
        <h1 class="create-costumers-title">Add User</h1>
        <form action="{{ route('costumers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="create-costumers-form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="create-costumers-form-control" required>
            </div>
            <div class="create-costumers-form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="create-costumers-form-control" required>
            </div>
            <div class="create-costumers-form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="create-costumers-form-control" required>
            </div>
            <div class="create-costumers-form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="create-costumers-form-control" required>
            </div>
            <div class="create-costumers-form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="create-costumers-form-control" required>
            </div>
            <div class="create-costumers-form-group">
                <label for="image">User Image</label>
                <input type="file" class="create-costumers-form-control-file" id="image" name="image">
            </div>
            <button type="submit" class="create-costumers-btn create-costumers-btn-primary">Add</button>
            <a href="{{ route('costumers.index') }}" class="create-costumers-btn create-costumers-btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
