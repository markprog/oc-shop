@extends('layouts.storefront')

@section('title', 'Contact Us')

@section('content')

<h1 class="h3 mb-4">Contact Us</h1>

<div class="row">
    <div class="col-md-6">
        <form action="{{ route('contact.send') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="enquiry" class="form-label">Message (min 10 characters)</label>
                <textarea id="enquiry" name="enquiry" class="form-control @error('enquiry') is-invalid @enderror"
                          rows="5" required minlength="10">{{ old('enquiry') }}</textarea>
                @error('enquiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    </div>
</div>

@endsection
