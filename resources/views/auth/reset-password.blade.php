<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">{{ __('Reset Password') }}</h3>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" 
                                 class="form-control" 
                                 type="email" 
                                 name="email" 
                                 :value="old('email', $request->email)" 
                                 required 
                                 autofocus 
                                 autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="invalid-feedback" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <x-input-label for="password" :value="__('New Password')" />
                    <x-text-input id="password" 
                                 class="form-control"
                                 type="password" 
                                 name="password" 
                                 required 
                                 autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="invalid-feedback" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" 
                                 class="form-control"
                                 type="password"
                                 name="password_confirmation" 
                                 required 
                                 autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="invalid-feedback" />
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a class="text-decoration-none" href="{{ route('login') }}">
                        {{ __('Back to login') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>