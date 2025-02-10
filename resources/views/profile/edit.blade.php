@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Profile Settings</h2>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Email Verification Status -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title">Email Verification Status</h3>
                    @if(Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !Auth::user()->hasVerifiedEmail())
                        <div class="d-flex align-items-center text-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <strong>Your email is not verified!</strong>
                                <p class="mb-0">Please verify your email address to access all features.</p>
                                <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-warning">
                                        Haven't received the verification email? Click here to request another one.
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center text-success">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>
                                <strong>Email verified!</strong>
                                <p class="mb-0">Your email address has been verified and your account is fully activated.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profile Information -->
            <div class="card mb-4">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="card mb-4">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            @if(!Auth::user()->isAdmin())
                <div class="card border-danger">
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            @else
                <div class="card border-warning">
                    <div class="card-body">
                        <h3 class="card-title text-warning">Delete Account</h3>
                        <p class="text-muted">
                            <i class="bi bi-shield-lock"></i> 
                            Administrator accounts cannot be deleted to maintain system integrity.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection