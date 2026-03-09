<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F8FAFC]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mekong CyberUnit SaaS') }} - Super Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-item { transition: all 0.2s ease; }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); color: #ffffff; }
        .sidebar-item-active { background-color: #3B82F6; color: #ffffff; font-weight: 500; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5); }
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .premium-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .premium-card:hover {
            box-shadow: 0 10px 25px -3px rgba(15, 23, 42, 0.08);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 flex overflow-hidden">

    <!-- Left Sidebar - Dark Theme Premium -->
    <aside class="w-72 bg-[#0F172A] flex-col hidden md:flex z-20 shadow-xl relative text-slate-300">
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-8 border-b border-white/10">
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 w-full">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-xl tracking-tight text-white leading-none">Flow<span class="text-blue-400">HR</span></span>
                    <span class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold mt-1 text-left">Super Admin</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <div class="flex-1 py-8 overflow-y-auto px-4 space-y-8">
            
            <!-- Dashboard Menu -->
            <div>
                <div class="px-4 mb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Overview</div>
                <nav class="space-y-1">
                    <a href="{{ route('superadmin.dashboard') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm {{ request()->routeIs('superadmin.dashboard') ? 'sidebar-item-active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                </nav>
            </div>

            <!-- Management Menu -->
            <div>
                <div class="px-4 mb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">System Management</div>
                <nav class="space-y-1">
                    <a href="{{ route('superadmin.companies.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm {{ request()->routeIs('superadmin.companies.*') ? 'sidebar-item-active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Tenants (Companies)
                    </a>
                    
                    <a href="{{ route('superadmin.subscription-plans.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm {{ request()->routeIs('superadmin.subscription-plans.*') ? 'sidebar-item-active' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Subscription Plans
                    </a>
                    
                    <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm opacity-50 cursor-not-allowed">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Platform Admins
                    </a>
                </nav>
            </div>
            
        </div>

        <!-- User Profile Footer -->
        <div class="p-4 border-t border-white/10 mx-4 mb-4 rounded-2xl bg-white/5">
            <div class="flex items-center gap-3 p-2">
                <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 font-bold border border-blue-500/30 flex-shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 rounded-xl text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
        
        <!-- Mobile Header (Hidden on Desktop) -->
        <div class="md:hidden glass-header h-16 flex items-center justify-between px-4 z-20">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="font-bold text-lg text-slate-800">Mekong CyberUnit</span>
            </div>
            <button class="text-slate-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <!-- Top Header for Desktop -->
        <header class="hidden md:flex glass-header h-20 items-center justify-between px-8 z-10 sticky top-0">
            <div class="flex-1">
                @yield('header')
            </div>
            <div class="flex items-center gap-6">
                <!-- Status indicator -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-sm font-medium">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    System Operational
                </div>
                
                <!-- Notification Bell -->
                <button class="relative p-2 text-slate-400 hover:text-slate-600 transition-colors bg-white rounded-full shadow-sm border border-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="flex-1 overflow-y-auto w-full relative">
            <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 w-full pb-20">
                @yield('content')
            </div>
        </div>
        
    </main>

</body>
</html>
