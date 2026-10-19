@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0 p-4">
            <h2 class="h4 text-center fw-bold mb-2">Reset Password</h2>
            <p class="text-center text-muted small mb-4">Enter your registered email address and we'll send you a password reset link.</p>

            <form action= method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        class="form-control @error('email') is-invalid @enderror"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Send Reset Link</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('loginView') }}" class="small text-decoration-none">&larr; Back to Log In</a>
            </div>
        </div>
    </div>
</div>
@endsection