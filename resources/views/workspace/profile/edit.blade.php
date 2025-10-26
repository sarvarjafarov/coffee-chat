@extends('workspace.layout')

@section('workspace-content')
    <div class="workspace-header">
        <div>
            <span class="workspace-eyebrow">Account</span>
            <h1>Workspace profile</h1>
            <p class="text-subtle mb-0">Keep your personal details, password, and preferences up to date.</p>
        </div>
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success workspace-section">A new verification link has been sent to your email address.</div>
    @endif

    <div class="row g-4 workspace-section">
        <div class="col-12">
            <div class="workspace-card workspace-form">
                <h2 class="h5 fw-semibold mb-3">Profile information</h2>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name">Name</label>
                            <input id="name" type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required autofocus>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                        <p class="text-subtle small mt-3 mb-0">
                            Your email address is unverified.
                            <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">Resend verification email</button>
                        </p>
                    @endif

                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-primary">Save changes</button>
                        @if (session('status') === 'profile-updated')
                            <span class="text-subtle align-self-center">Saved.</span>
                        @endif
                    </div>
                </form>
                <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <div class="col-12">
            <div class="workspace-card workspace-form">
                <h2 class="h5 fw-semibold mb-3">Update password</h2>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="current_password">Current password</label>
                            <input id="current_password" type="password" name="current_password" class="form-control" required autocomplete="current-password">
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="password">New password</label>
                            <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="password_confirmation">Confirm password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-primary">Save password</button>
                        @if (session('status') === 'password-updated')
                            <span class="text-subtle align-self-center">Saved.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12">
            <div class="workspace-card workspace-form">
                <h2 class="h5 fw-semibold mb-3 text-danger">Delete account</h2>
                <p class="text-subtle small mb-3">Once deleted, all of your data will be permanently removed. Please enter your password to confirm.</p>
                <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account?');">
                    @csrf
                    @method('DELETE')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="delete_password">Password</label>
                            <input id="delete_password" type="password" name="password" class="form-control" required>
                            @error('password', 'userDeletion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-outline-danger mt-3">Delete account</button>
                </form>
            </div>
        </div>
    </div>
@endsection
