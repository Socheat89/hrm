<x-guest-layout>

    <!-- Header -->
    <div style="margin-bottom:1.75rem">
        <h2 style="font-size:1.65rem;font-weight:900;color:var(--white);letter-spacing:-.025em;margin-bottom:.35rem">
            Welcome Back 👋
        </h2>
        <p style="font-size:.88rem;font-weight:500;color:#475569">
            Sign in to your Mekong CyberUnit account
        </p>
    </div>

    <!-- Session Status (e.g. password reset success) -->
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:1.1rem" id="loginForm">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email Address" />
            <div class="auth-input-wrap">
                <i class="fa-solid fa-envelope auth-input-icon"></i>
                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@company.com"
                    style="padding-left:2.6rem"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.45rem">
                <x-input-label for="password" value="Password" style="margin-bottom:0" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link" style="font-size:.75rem">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="auth-input-wrap">
                <i class="fa-solid fa-lock auth-input-icon"></i>
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    style="padding-left:2.6rem;padding-right:2.8rem"
                />
                <button type="button" class="pw-toggle" onclick="togglePw('password','pwEye')" tabindex="-1">
                    <i class="fa-solid fa-eye" id="pwEye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div style="display:flex;align-items:center;gap:.65rem">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                style="width:16px;height:16px;accent-color:var(--blue);cursor:pointer;flex-shrink:0"
            >
            <label for="remember_me" style="font-size:.82rem;font-weight:500;color:#475569;cursor:pointer">
                Remember me
            </label>
        </div>

        <!-- Submit -->
        <div style="padding-top:.35rem">
            <x-primary-button style="width:100%" id="loginBtn">
                <i class="fa-solid fa-arrow-right-to-bracket" style="font-size:.85rem;margin-right:.5rem"></i>
                Log In
            </x-primary-button>
        </div>

        <!-- Register link -->
        <p style="text-align:center;font-size:.85rem;font-weight:500;color:#475569;margin-top:.25rem">
            Don't have an account?
            <a href="{{ route('register') }}" class="auth-link">Register for free</a>
        </p>
    </form>

    <script>
        function togglePw(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin" style="font-size:.85rem;margin-right:.5rem"></i> Authenticating...';
            btn.disabled = true;
            btn.style.opacity = '.8';
        });
    </script>

</x-guest-layout>
