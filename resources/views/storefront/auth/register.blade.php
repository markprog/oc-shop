@extends('layouts.storefront')

@section('title', 'Register')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="h4 mb-4 text-center">Create Account</h2>

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control @error('firstname') is-invalid @enderror"
                                   value="{{ old('firstname') }}" required>
                            @error('firstname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control @error('lastname') is-invalid @enderror"
                                   value="{{ old('lastname') }}" required>
                            @error('lastname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="telephone" class="form-label">Telephone</label>
                        <input type="text" id="telephone" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                               value="{{ old('telephone') }}" required>
                        @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter" value="1">
                        <label class="form-check-label" for="newsletter">Subscribe to newsletter</label>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input @error('agree') is-invalid @enderror" id="agree" name="agree" value="1" required>
                        <label class="form-check-label" for="agree">
                            I agree to the <a href="#">Privacy Policy</a>
                        </label>
                        @error('agree') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Create Account</button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    Already have an account? <a href="{{ route('login') }}">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
