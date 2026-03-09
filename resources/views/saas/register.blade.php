<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Your Workspace | Mekong CyberUnit SaaS</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 flex overflow-hidden">
    
    <!-- Left Pattern/Image side (hidden on small) -->
    <div class="hidden lg:flex lg:w-1/2 bg-[#0F172A] relative flex-col justify-between overflow-hidden">
        
        <!-- Abstract gradient circles -->
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] rounded-full bg-blue-900/30 blur-[120px]"></div>
        <div class="absolute bottom-[0%] -right-[10%] w-[40vw] h-[40vw] rounded-full bg-indigo-900/30 blur-[100px]"></div>

        <div class="relative z-10 p-12">
            <a href="{{ route('pricing') }}" class="flex items-center gap-3 w-fit text-white hover:opacity-80 transition-opacity">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="font-bold text-2xl tracking-tight leading-none">Flow<span class="text-blue-400">HR</span></span>
            </a>
        </div>

        <div class="relative z-10 p-12 pb-24">
            <div class="mb-4 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-sm font-bold uppercase tracking-widest shadow-inner">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Auto-Provisioning
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-tight mb-6">
                Your isolated <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">workspace</span> is almost ready.
            </h1>
            <p class="text-xl text-slate-400 font-medium max-w-md">
                You've selected the <strong class="text-white">{{ $plan->name }}</strong> plan. Register your company below, and our engine will instantly deploy your dedicated database schema and admin portal.
            </p>

            <div class="mt-12 flex items-center gap-4">
                <div class="flex -space-x-4">
                    <div class="w-12 h-12 rounded-full border-4 border-[#0F172A] bg-blue-100 flex items-center justify-center text-blue-800 font-bold shrink-0">TC</div>
                    <div class="w-12 h-12 rounded-full border-4 border-[#0F172A] bg-purple-100 flex items-center justify-center text-purple-800 font-bold shrink-0">SW</div>
                    <div class="w-12 h-12 rounded-full border-4 border-[#0F172A] bg-emerald-100 flex items-center justify-center text-emerald-800 font-bold shrink-0">MB</div>
                </div>
                <div class="text-slate-400 font-medium text-sm">
                    Join 4,000+ companies<br>already using Mekong CyberUnit SaaS.
                </div>
            </div>
        </div>
    </div>

    <!-- Right Form Side -->
    <div class="flex-1 w-full lg:w-1/2 flex flex-col items-center justify-center relative overflow-y-auto px-6 py-12 md:px-12">
        
        <!-- Mobile Logo -->
        <a href="{{ route('pricing') }}" class="lg:hidden flex items-center justify-center gap-3 w-full mb-10 text-slate-900">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <span class="font-bold text-2xl tracking-tight leading-none">Flow<span class="text-blue-600">HR</span></span>
        </a>

        <div class="w-full max-w-lg mx-auto bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 md:p-10 border border-slate-100">
            
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-[#0F172A] mb-2 tracking-tight">Create your workspace</h2>
                <p class="text-slate-500 font-medium text-sm">Provide your company details to set up the admin account for the <span class="text-blue-600 font-bold">{{ $plan->name }}</span> plan.</p>
            </div>

            @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <span class="font-medium text-sm leading-relaxed">{{ session('error') }}</span>
            </div>
            @endif

            <form action="{{ route('register.company.store', $plan->id) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Company Info -->
                <div class="space-y-4">
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">1. Company Profile</h3>
                    
                    <div>
                        <label for="company_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required autofocus
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all"
                            placeholder="Acme Corp">
                        @error('company_name')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="company_email" class="block text-sm font-semibold text-slate-700 mb-1.5">Company Email</label>
                            <input type="email" name="company_email" id="company_email" value="{{ old('company_email') }}"
                                class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all"
                                placeholder="hello@acme.com">
                            @error('company_email')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all"
                                placeholder="+1 (555) 000-0000">
                            @error('phone')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Admin Info -->
                <div class="space-y-4 pt-4">
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">2. Administrator Setup</h3>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Your Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all"
                            placeholder="John Doe">
                        @error('name')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Admin Login Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all"
                            placeholder="john@acme.com">
                        @error('email')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password" required
                                class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all"
                                placeholder="Min. 8 characters">
                            @error('password')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all"
                                placeholder="Match password">
                            @error('password_confirmation')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-base shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.23)] hover:-translate-y-0.5 transition-all group">
                        Provision Workspace
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    
                    <p class="text-[12px] text-slate-400 font-medium text-center mt-6">
                        By clicking "Provision Workspace", you agree to our <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>.
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
