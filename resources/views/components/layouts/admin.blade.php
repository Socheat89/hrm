<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HRM') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=public-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm lg:hidden" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-[#1f2937] text-slate-300 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 lg:flex-shrink-0 flex flex-col border-r border-slate-700/50 shadow-xl">
            
            <div class="flex h-16 shrink-0 items-center px-6 bg-[#111827]">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-blue-500 flex items-center justify-center text-white font-bold text-xl shadow-inner">
                        {{ substr($uiCompanySetting->company_name ?? 'H', 0, 1) }}
                    </div>
                    <span class="text-lg font-bold text-white tracking-wide truncate">{{ $uiCompanySetting->company_name ?? config('app.name') }}</span>
                </div>
            </div>

            <nav class="flex-1 space-y-1 px-3 py-4 overflow-y-auto mt-2 custom-scrollbar">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Main Menu</p>
                
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600/10 text-blue-400' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.employees.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.employees.*') ? 'bg-blue-600/10 text-blue-400' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.employees.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Employees
                </a>

                <details class="space-y-1 relative menu-disclosure" {{ request()->routeIs('admin.attendance.*') || request()->routeIs('admin.attendance-qr.*') || request()->routeIs('admin.schedules.*') ? 'open' : '' }}>
                    <summary class="list-none cursor-pointer w-full group flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.attendance.*') || request()->routeIs('admin.attendance-qr.*') || request()->routeIs('admin.schedules.*') ? 'bg-blue-600/10 text-blue-400' : 'hover:bg-slate-800 hover:text-white' }}">
                        <span class="flex items-center">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.attendance.*') || request()->routeIs('admin.attendance-qr.*') || request()->routeIs('admin.schedules.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Attendance
                        </span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>

                    <div class="space-y-1 pl-10 pr-2 pt-1">
                        <a href="{{ route('admin.attendance.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.attendance.*') ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            Attendance Logs
                        </a>

                        <a href="{{ route('admin.schedules.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.schedules.*') ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            Work Time & Late Rule
                        </a>

                        <a href="{{ route('admin.attendance-qr.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.attendance-qr.*') ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            QR Generator
                        </a>
                    </div>
                </details>

                <details class="space-y-1 relative menu-disclosure" {{ request()->routeIs('admin.leave-requests.*') || request()->routeIs('admin.leave-types.*') || request()->routeIs('admin.overtime-requests.*') || request()->routeIs('admin.change-dayoff-requests.*') ? 'open' : '' }}>
                    <summary class="list-none cursor-pointer w-full group flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.leave-requests.*') || request()->routeIs('admin.leave-types.*') || request()->routeIs('admin.overtime-requests.*') || request()->routeIs('admin.change-dayoff-requests.*') ? 'bg-blue-600/10 text-blue-400' : 'hover:bg-slate-800 hover:text-white' }}">
                        <span class="flex items-center">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.leave-requests.*') || request()->routeIs('admin.leave-types.*') || request()->routeIs('admin.overtime-requests.*') || request()->routeIs('admin.change-dayoff-requests.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Requests
                        </span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>

                    <div class="space-y-1 pl-10 pr-2 pt-1">
                        <a href="{{ route('admin.leave-requests.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.leave-requests.*') || request()->routeIs('admin.leave-types.*') ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            Leave
                        </a>
                        
                        <a href="{{ route('admin.overtime-requests.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.overtime-requests.*') ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            Overtime
                        </a>

                        <a href="{{ route('admin.change-dayoff-requests.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.change-dayoff-requests.*') ? 'text-white bg-slate-800/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                            Change Dayoff
                        </a>
                    </div>
                </details>
                
                <a href="{{ route('admin.payrolls.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.payrolls.*') ? 'bg-blue-600/10 text-blue-400' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.payrolls.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Payroll
                </a>

                @if(auth()->user()->hasRole('Super Admin'))
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Organization</p>
                    <a href="{{ route('admin.branches.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.branches.*') || request()->routeIs('admin.departments.*') ? 'bg-blue-600/10 text-blue-400' : 'hover:bg-slate-800 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.branches.*') || request()->routeIs('admin.departments.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        Branches & Depts
                    </a>

                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">System</p>
                    <a href="{{ route('admin.settings.edit') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600/10 text-blue-400' : 'hover:bg-slate-800 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Settings
                    </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content area -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="flex h-16 flex-shrink-0 items-center justify-between border-b border-slate-200 bg-white px-4 sm:px-6 shadow-sm z-10">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 lg:hidden mr-4 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-slate-800">Administrator Panel</h1>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-right mr-2">
                        <div class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-500">{{ auth()->user()->roles->first()->name ?? 'User' }}</div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Logout
                        </button>
                    </form>
                    
                    <div class="relative" x-data="{ userMenuOpen: false }">
                        <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="flex items-center justify-center h-9 w-9 rounded-full bg-slate-100 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </button>
                        
                        <div x-show="userMenuOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50 hidden" :class="{ 'hidden': !userMenuOpen }">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Sign out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
                @if (session('status') || session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200 flex items-center shadow-sm" x-data="{ show: true }" x-show="show">
                        <svg class="w-5 h-5 mr-3 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        {{ session('status') ?? session('success') }}
                        <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200 shadow-sm" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-3 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <span class="font-bold">Errors occurred</span>
                            <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button>
                        </div>
                        <ul class="list-disc list-inside ml-8">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4b5563; }
        .menu-disclosure summary::-webkit-details-marker { display: none; }
        .menu-disclosure > summary { outline: none; }
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
