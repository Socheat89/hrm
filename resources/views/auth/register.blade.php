<x-guest-layout>

    <!-- Header -->
    <div style="margin-bottom:1.75rem">
        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem">
            <div class="auth-step active"></div>
            <div class="auth-step active"></div>
            <div class="auth-step"></div>
        </div>
        <h2 style="font-size:1.65rem;font-weight:900;color:var(--white);letter-spacing:-.025em;margin-bottom:.35rem">
            Create Account
        </h2>
        <p style="font-size:.88rem;font-weight:500;color:#475569">
            Start using Mekong CyberUnit for free — no credit card required
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:1.1rem" id="registerForm">
        @csrf

        <!-- Full Name -->
        <div>
            <x-input-label for="name" value="Full Name" />
            <div class="auth-input-wrap">
                <i class="fa-solid fa-user auth-input-icon"></i>
                <x-text-input
                    id="name"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="e.g. John Doe"
                    style="padding-left:2.6rem"
                />
            </div>
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Work Email" />
            <div class="auth-input-wrap">
                <i class="fa-solid fa-envelope auth-input-icon"></i>
                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autocomplete="username"
                    placeholder="you@company.com"
                    style="padding-left:2.6rem"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Password" />
            <div class="auth-input-wrap">
                <i class="fa-solid fa-lock auth-input-icon"></i>
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="At least 8 characters"
                    style="padding-left:2.6rem;padding-right:2.8rem"
                />
                <button type="button" class="pw-toggle" onclick="togglePw('password','eyeIcon1')" tabindex="-1">
                    <i class="fa-solid fa-eye" id="eyeIcon1"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirm Password" />
            <div class="auth-input-wrap">
                <i class="fa-solid fa-shield-halved auth-input-icon"></i>
                <x-text-input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                    style="padding-left:2.6rem;padding-right:2.8rem"
                />
                <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation','eyeIcon2')" tabindex="-1">
                    <i class="fa-solid fa-eye" id="eyeIcon2"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <!-- Terms -->
        <div style="display:flex;align-items:flex-start;gap:.7rem;padding:.2rem 0">
            <input
                type="checkbox"
                id="terms"
                required
                style="width:16px;height:16px;accent-color:var(--blue);margin-top:2px;flex-shrink:0;cursor:pointer"
            >
            <label for="terms" style="font-size:.8rem;font-weight:500;color:#475569;cursor:pointer;line-height:1.6">
                I agree to the <a href="#" class="auth-link">Terms of Service</a>
                and <a href="#" class="auth-link">Privacy Policy</a> of Mekong CyberUnit
            </label>
        </div>

        <!-- Submit -->
        <div style="padding-top:.35rem">
            <x-primary-button style="width:100%" id="registerBtn">
                <i class="fa-solid fa-rocket" style="font-size:.85rem;margin-right:.5rem"></i>
                Create Account
            </x-primary-button>
        </div>

        <!-- Sign in link -->
        <p style="text-align:center;font-size:.85rem;font-weight:500;color:#475569;margin-top:.25rem">
            Already have an account?
            <a href="{{ route('login') }}" class="auth-link">Log In</a>
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

        document.getElementById('registerForm').addEventListener('submit', function () {
            const btn = document.getElementById('registerBtn');
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin" style="font-size:.85rem;margin-right:.5rem"></i> Processing...';
            btn.disabled = true;
            btn.style.opacity = '.8';
        });
    </script>

</x-guest-layout>
