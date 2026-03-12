@section('title') Register @endsection

<div class="login-wrapper">
    <style>
        body {
            background: #f0f2f5;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07),
                        0 10px 40px -4px rgba(0, 0, 0, 0.10);
            padding: 2.5rem 2.5rem;
        }

        .login-logo {
            height: 30px;
            display: block;
        }

        .login-divider {
            border: none;
            border-top: 1px solid #e9ecef;
            margin: 1.75rem 0;
        }

        .login-title {
            font-size: 1.375rem;
            font-weight: 600;
            color: #1a1d23;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
        }

        .login-subtitle {
            font-size: 0.875rem;
            color: #8a94a6;
        }

        .form-label {
            font-size: 0.8125rem;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.375rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 0.625rem 0.875rem;
            font-size: 0.9375rem;
            color: #1a1d23;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.12);
        }

        .form-control::placeholder {
            color: #b0b8c8;
            font-size: 0.875rem;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 2.75rem;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 2.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            color: #8a94a6;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .password-toggle:hover {
            color: var(--bs-primary);
        }

        .btn-signin {
            border-radius: 8px;
            padding: 0.6875rem 1rem;
            font-size: 0.9375rem;
            font-weight: 500;
            letter-spacing: 0.01em;
            transition: opacity 0.15s ease, transform 0.1s ease;
        }

        .btn-signin:active {
            transform: scale(0.99);
        }

        .signup-text {
            font-size: 0.875rem;
            color: #8a94a6;
        }

        .signup-text a {
            font-weight: 500;
            text-decoration: none;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
        }

        /* Password strength checker */
        .pass-strength-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.875rem 1rem;
            margin-top: 0.5rem;
        }

        .pass-strength-box h6 {
            font-size: 0.75rem;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .pass-strength-box p {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .pass-strength-box p::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #dee2e6;
            flex-shrink: 0;
        }

        .pass-strength-box p.valid {
            color: #0ab39c;
        }

        .pass-strength-box p.valid::before {
            background: #0ab39c;
        }

        .pass-strength-box p.invalid {
            color: #8a94a6;
        }

        .terms-text {
            font-size: 0.8125rem;
            color: #8a94a6;
            line-height: 1.5;
        }
    </style>

    <div class="login-card">

        {{-- Logo --}}
        <div class="mb-1">
            <img src="{{ URL::asset('lag/images/logo-dark-full.png') }}"
                 alt="{{ config('app.name') }}"
                 class="login-logo"
                 onerror="this.style.display='none'">
        </div>

        <hr class="login-divider">

        {{-- Heading --}}
        <div class="mb-4">
            <h1 class="login-title">Create account</h1>
            <p class="login-subtitle mb-0">Fill in the details below to get started.</p>
        </div>

        {{-- Alerts --}}
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible mb-3">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible mb-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Form --}}
        <form class="needs-validation" novalidate enctype="multipart/form-data" wire:submit="submit">
            @csrf

            {{-- Name row --}}
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label for="first_name" class="form-label">First name</label>
                    <input id="first_name" type="text"
                           class="form-control @error('first_name') is-invalid @enderror"
                           wire:model.live="first_name"
                           value="{{ old('first_name') }}"
                           required autocomplete="given-name" autofocus
                           placeholder="Jane">
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="last_name" class="form-label">Last name</label>
                    <input id="last_name" type="text"
                           class="form-control @error('last_name') is-invalid @enderror"
                           wire:model.live="last_name"
                           value="{{ old('last_name') }}"
                           required autocomplete="family-name"
                           placeholder="Doe">
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="useremail" class="form-label">Email address</label>
                <input id="useremail" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       wire:model.live="email"
                       value="{{ old('email') }}"
                       required autocomplete="email"
                       placeholder="you@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Avatar --}}
            <div class="mb-3">
                <label for="avatar" class="form-label">Profile photo</label>
                <input id="avatar" type="file"
                       class="form-control @error('avatar') is-invalid @enderror"
                       wire:model.live="avatar"
                       required accept="image/jpg,image/jpeg,image/png">
                @error('avatar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label" for="password-input">Password</label>
                <div class="password-wrapper auth-pass-inputgroup">
                    <input type="password"
                           class="form-control password-input @error('password') is-invalid @enderror"
                           id="password-input"
                           wire:model.live="password"
                           onpaste="return false"
                           placeholder="Create a password"
                           aria-describedby="passwordInput"
                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                           required>
                    <button class="password-toggle password-addon" type="button" id="password-addon" tabindex="-1">
                        <i class="ri-eye-fill"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                {{-- Password strength --}}
                <div id="password-contain" class="pass-strength-box mt-2">
                    <h6>Password must contain</h6>
                    <p id="pass-length" class="invalid mb-1">Minimum <strong>8 characters</strong></p>
                    <p id="pass-lower" class="invalid mb-1">At least one <strong>lowercase</strong> letter (a–z)</p>
                    <p id="pass-upper" class="invalid mb-1">At least one <strong>uppercase</strong> letter (A–Z)</p>
                    <p id="pass-number" class="invalid mb-0">At least one <strong>number</strong> (0–9)</p>
                </div>
            </div>

            {{-- Confirm password --}}
            <div class="mb-4">
                <label class="form-label" for="confirm-password-input">Confirm password</label>
                <div class="password-wrapper auth-pass-inputgroup">
                    <input id="confirm-password-input" type="password"
                           class="form-control password-input"
                           wire:model.live="password_confirmation"
                           required autocomplete="new-password"
                           placeholder="Repeat your password">
                    <button class="password-toggle password-addon" type="button" tabindex="-1">
                        <i class="ri-eye-fill"></i>
                    </button>
                </div>
            </div>

            {{-- Terms --}}
            <p class="terms-text mb-4">
                By creating an account you agree to our
                <a href="#" class="text-primary fw-medium text-decoration-none">Terms of Use</a>.
            </p>

            {{-- Submit --}}
            <button class="btn btn-primary w-100 btn-signin" type="submit">
                Create Account
            </button>

        </form>

        {{-- Sign in link --}}
        <div class="text-center mt-4">
            <p class="signup-text mb-0">
                Already have an account?
                <a href="{{ url('login') }}" class="text-primary">Sign in</a>
            </p>
        </div>

    </div>
</div>

@section('script')
    <script src="{{ URL::asset('lag/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ URL::asset('lag/js/pages/passowrd-create.init.js') }}"></script>
@endsection
