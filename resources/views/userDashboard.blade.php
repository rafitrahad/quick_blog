@extends('layouts.app')

@section('content')
<div class="container py-2" style="max-width: 900px;">

    <!-- User Header & Profile Overview -->
    <div class="card border-0 shadow-sm p-4 mb-4 bg-white" style="border-radius: 12px;">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <!-- User Avatar Circle -->
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm fw-bold fs-3" style="width: 64px; height: 64px; flex-shrink: 0;">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h4 class="fw-bold text-dark mb-0">{{ $user->name }}</h4>
                        @if($user->isAdmin())
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle small">
                                <i class="bi bi-shield-fill-check me-1"></i> Admin
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle small">
                                Member
                            </span>
                        @endif
                    </div>
                    <div class="text-muted small d-flex flex-wrap align-items-center gap-3">
                        <span><i class="bi bi-envelope me-1"></i> {{ $user->email }}</span>
                        <span><i class="bi bi-calendar3 me-1"></i> Joined {{ $user->created_at ? $user->created_at->format('M d, Y') : 'Recent' }}</span>
                    </div>
                </div>
            </div>

            <!-- Activity Counter Pills -->
            <div class="d-flex gap-2">
                
                @if($user->isAdmin())
                    <div class="border rounded px-3 py-2 text-center bg-light">
                        <span class="d-block text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem;">Articles</span>
                        <span class="h5 fw-bold mb-0 text-success">{{ $postsCount ?? 0 }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Side: Profile & Password Settings -->
        <div class="col-12 col-lg-7">
            
            <!-- 1. Edit Profile Form -->
            <div class="card border-0 shadow-sm p-4 mb-4 bg-white" style="border-radius: 12px;">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0">Profile Information</h5>
                        <p class="text-muted small mb-0">Update your account display name and email address.</p>
                    </div>
                    <i class="bi bi-person-gear fs-4 text-primary"></i>
                </div>

                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold text-secondary">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-person"></i>
                            </span>
                            <input 
                                type="text" 
                                class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                required
                            >
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input 
                                type="email" 
                                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                required
                            >
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                        <i class="bi bi-check2 me-1"></i> Save Changes
                    </button>
                </form>
            </div>

            <!-- 2. Change Password Form -->
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 12px;">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0">Change Password</h5>
                        <p class="text-muted small mb-0">Ensure your account uses a long, random password for security.</p>
                    </div>
                    <i class="bi bi-shield-lock fs-4 text-warning"></i>
                </div>

                <form action="{{ route('user.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label small fw-semibold text-secondary">Current Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-key"></i>
                            </span>
                            <input 
                                type="password" 
                                class="form-control border-start-0 ps-0 @error('current_password') is-invalid @enderror" 
                                id="current_password" 
                                name="current_password" 
                                placeholder="Enter current password"
                                required
                            >
                        </div>
                        @error('current_password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-semibold text-secondary">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input 
                                type="password" 
                                class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Min. 8 characters"
                                required
                            >
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input 
                                type="password" 
                                class="form-control border-start-0 ps-0" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Repeat new password"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark btn-sm px-4 shadow-sm">
                        <i class="bi bi-lock me-1"></i> Update Password
                    </button>
                </form>
            </div>

        </div>

        <!-- Right Side: Account Security & Deactivation (Danger Zone) -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 12px; border-left: 4px solid #dc3545 !important;">
                <div class="border-bottom pb-3 mb-3">
                    <h5 class="fw-bold text-danger mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i> Deactivate Account
                    </h5>
                    <p class="text-muted small mb-0">Deactivate your personal account from this platform.</p>
                </div>

                <div class="alert alert-danger-subtle text-danger small p-3 mb-3" style="border-radius: 8px;">
                    <i class="bi bi-info-circle me-1"></i> 
                    Deactivating your account will immediately log you out and disable your account access. Your profile information will be safely soft-deleted from active view.
                </div>

                <!-- Deactivation Trigger Button / Collapsible Form -->
                <button 
                    class="btn btn-outline-danger btn-sm w-100 py-2 d-flex align-items-center justify-content-center gap-2" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#deactivateCollapse" 
                    aria-expanded="false"
                >
                    <i class="bi bi-person-x"></i>
                    <span>Proceed to Deactivate</span>
                </button>

                <!-- Confirmation Form -->
                <div class="collapse mt-3 @error('confirm_password') show @enderror" id="deactivateCollapse">
                    <div class="card card-body bg-light border-0 p-3">
                        <p class="text-muted small mb-2 fw-semibold">
                            Enter your password to confirm deactivation:
                        </p>

                        <form action="{{ route('user.deactivate') }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to deactivate your account? You will be logged out immediately.');">
                            @csrf
                            @method('DELETE')

                            <div class="mb-3">
                                <input 
                                    type="password" 
                                    name="confirm_password" 
                                    class="form-control form-control-sm @error('confirm_password') is-invalid @enderror" 
                                    placeholder="Enter your current password" 
                                    required
                                >
                                @error('confirm_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-danger btn-sm w-100 shadow-sm">
                                <i class="bi bi-trash3 me-1"></i> Confirm Deactivation
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Quick Account Guidelines -->
            <div class="card border-0 shadow-sm p-4 mt-4 bg-white" style="border-radius: 12px;">
                <h6 class="fw-bold mb-2 text-dark small text-uppercase">Account Security Guidelines</h6>
                <ul class="text-muted small ps-3 mb-0" style="line-height: 1.6;">
                    <li>Keep your password private and never share it with anyone.</li>
                    <li>Use a unique password with a mixture of letters, numbers, and symbols.</li>
                    <li>If you encounter suspicious activity, update your password right away.</li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
