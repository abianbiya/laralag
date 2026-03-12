@section('title')
    Login
@endsection

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
            max-width: 420px;
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

        .forgot-link {
            font-size: 0.8125rem;
            color: var(--bs-primary);
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
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

        .form-check-label {
            font-size: 0.875rem;
            color: #4a5568;
        }

        .alert {
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
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
            <h1 class="login-title">Sign in</h1>
            <p class="login-subtitle mb-0">Welcome back. Enter your credentials to continue.</p>
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
        <form method="POST" wire:submit="submit">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input id="email"
                       type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       wire:model.live="email"
                       value="{{ old('email') }}"
                       required
                       autocomplete="email"
                       autofocus
                       placeholder="you@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label mb-0" for="password-input">Password</label>
                    @if (Route::has('password.reset'))
                        <a class="forgot-link" href="{{ route('password.reset') }}">Forgot password?</a>
                    @endif
                </div>
                <div class="password-wrapper auth-pass-inputgroup">
                    <input id="password-input"
                           type="password"
                           class="form-control password-input @error('password') is-invalid @enderror"
                           wire:model.live="password"
                           required
                           autocomplete="current-password"
                           placeholder="Enter your password">
                    <button class="password-toggle password-addon" type="button" id="password-addon" tabindex="-1">
                        <i class="ri-eye-fill"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember me --}}
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Keep me signed in</label>
            </div>

            {{-- Submit --}}
            <button class="btn btn-primary w-100 btn-signin" type="submit">
                Sign In
            </button>

        </form>

        {{-- Sign up --}}
        <div class="text-center mt-4">
            <p class="signup-text mb-0">
                Don't have an account?
                <a href="{{ url('register') }}" class="text-primary">Create one</a>
            </p>
        </div>

    </div>
</div>

@section('script')
    <script src="{{ URL::asset('lag/js/pages/password-addon.init.js') }}"></script>
@endsection
