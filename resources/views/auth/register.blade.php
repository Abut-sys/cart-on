@extends('layouts.index')

@section('title', 'Register')

@section('content')
    <div class="container-fluid mt-14">
        <div class="row justify-content-center">
            <div class="col-md-20">
                <!-- Card with rounded corners -->
                <div class="card shadow-lg border-0 rounded" style="border-radius: 20px;">

                    <!-- Logo Section -->
                    <div class="card-header bg-white text-center border-0">
                        <img src="{{ asset('image/logo.png') }}" alt="Logo" style="width: 200px;">
                        <h1 class="font-weight-bold">{{ __('Register') }}</h1>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name Field with Icon -->
                            <div class="form-group mb-4 position-relative">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input id="name" type="text"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required autofocus
                                        placeholder="Enter your name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email Field with Icon -->
                            <div class="form-group mb-4 position-relative">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input id="email" type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required placeholder="Enter your email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Phone Number Field with Icon -->
                            <div class="form-group mb-4 position-relative">
                                <label for="phone_number" class="form-label">{{ __('Phone Number') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input id="phone_number" type="tel"
                                        class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
                                        name="phone_number" value="{{ old('phone_number') }}" required
                                        placeholder="Enter your phone number">
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Field with Icon -->
                            <div class="form-group mb-4 position-relative">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password" type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" required placeholder="Create a password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Confirm Password Field with Icon -->
                            <div class="form-group mb-4 position-relative">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password-confirm" type="password" class="form-control form-control-lg"
                                        name="password_confirmation" required placeholder="Confirm your password">
                                </div>
                            </div>

                            <!-- Submit Button with Icon -->
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success btn-lg btn-block shadow-sm rounded-pill">
                                    {{ __('Sign Up') }}
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-muted">Already have an account? <a href="{{ route('login') }}"
                                        class="text-primary font-weight-bold">{{ __('Login') }}</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
