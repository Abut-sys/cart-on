@extends('layouts.index')

@section('title', 'Login')

@section('content')
    <div class="container-fluid mt-14">
        <div class="row justify-content-center">
            <div class="col-md-20">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-white text-center border-0">
                        <img src="{{ asset('image/logo.png') }}" alt="Logo" style="width: 200px;">
                        <h1 class="font-weight-bold">{{ __('Login') }}</h1>
                    </div>
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Field with Icon -->
                            <div class="form-group mb-4">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fas fa-envelope"></i> <!-- Email Icon -->
                                    </span>
                                    <input id="email" type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autofocus
                                        placeholder="Enter your email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Field with Icon -->
                            <div class="form-group mb-4">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fas fa-lock"></i> <!-- Password Icon -->
                                    </span>
                                    <input id="password" type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password" required placeholder="Enter your password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group d-flex justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                                </div>
                                <a href="{{ url('/kirimemail') }}">{{ __('Forgot Password?') }}</a>
                            </div>

                            <!-- Submit Button with Icon -->
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success btn-lg btn-block shadow-sm rounded-pill">
                                    <i class="fas fa-sign-in-alt"></i>
                                    {{ __('Login') }}
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-muted">Don't have an account yet? <a href="{{ route('register') }}"
                                        class="text-primary font-weight-bold">{{ __('Sign up') }}</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
