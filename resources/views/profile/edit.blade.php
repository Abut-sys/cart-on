@extends('layouts.index')

@section('title', 'Edit Profile')

@section('content')
    <div class="container mt-5 bg-custom p-4 rounded">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                    <div class="card-header bg-gradient-primary py-4 text-center">
                        <h3 class="mb-0">Edit Profile</h3>
                    </div>

                    <div class="card-body p-5">
                        @if (session('msg'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('msg') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf

                            <!-- Name Field -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fas fa-user me-2"></i> Name
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-2"></i> Email Address
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone Number Field -->
                            <div class="mb-4">
                                <label for="phone_number" class="form-label fw-bold">
                                    <i class="fas fa-phone me-2"></i> Phone Number
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text"
                                        class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                                        id="phone_number" name="phone_number"
                                        value="{{ old('phone_number', $user->phone_number) }}">
                                </div>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- New Password Field (optional) -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">
                                    <i class="fas fa-lock me-2"></i> Change Password
                                    <small class="text-muted">(optional)</small>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        id="password" name="password">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-bold">
                                    <i class="fas fa-lock me-2"></i> Confirm New Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control form-control-lg" id="password_confirmation"
                                        name="password_confirmation">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-lg btn-primary shadow-lg">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>

                            <!-- Back Button -->
                            <div class="d-grid">
                                <a href="{{ url('/dashboard') }}" class="btn btn-lg btn-secondary shadow-lg">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <p class="text-muted">Want to return? <a href="{{ url()->previous() }}"
                            class="text-decoration-none fw-bold text-primary">Cancel</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
