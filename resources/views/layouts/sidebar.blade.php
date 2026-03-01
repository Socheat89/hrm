<aside class="flex-shrink-0 w-64 bg-slate-900 border-r border-slate-700 min-h-screen hidden md:block">
    <div class="h-16 flex items-center px-6 w-full bg-slate-800 text-white font-bold text-xl border-b border-slate-700 shadow-sm">
        <svg class="w-8 h-8 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        HRM System
    </div>
    
    <nav class="space-y-1 p-4 mt-2">
        <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Main Menu</p>
        
        <a href="{{ route('dashboard') ?? '#' }}" class="flex items-center space-x-3 text-slate-300 hover:bg-blue-600 hover:text-white px-3 py-2.5 rounded-lg transition-colors text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : '' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-slate-300 hover:bg-blue-600 hover:text-white px-3 py-2.5 rounded-lg transition-colors text-sm font-medium {{ request()->routeIs('employees.*') ? 'bg-blue-600 text-white' : '' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Employees</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-slate-300 hover:bg-blue-600 hover:text-white px-3 py-2.5 rounded-lg transition-colors text-sm font-medium {{ request()->routeIs('attendances.*') ? 'bg-blue-600 text-white' : '' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Attendance Logs</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-slate-300 hover:bg-blue-600 hover:text-white px-3 py-2.5 rounded-lg transition-colors text-sm font-medium {{ request()->routeIs('leaves.*') ? 'bg-blue-600 text-white' : '' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Leave Requests</span>
        </a>
        
        <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mt-6 mb-2">Organization</p>

        <a href="#" class="flex items-center space-x-3 text-slate-300 hover:bg-blue-600 hover:text-white px-3 py-2.5 rounded-lg transition-colors text-sm font-medium">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span>Departments</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-slate-300 hover:bg-blue-600 hover:text-white px-3 py-2.5 rounded-lg transition-colors text-sm font-medium">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Branches</span>
        </a>

        <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mt-6 mb-2">Finance</p>
        
        <a href="#" class="flex items-center space-x-3 text-slate-300 hover:bg-blue-600 hover:text-white px-3 py-2.5 rounded-lg transition-colors text-sm font-medium">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Payroll</span>
        </a>

    </nav>
</aside>