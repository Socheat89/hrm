<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-slate-100 z-40 sticky top-0 h-20 flex items-center shadow-sm shadow-slate-900/[0.02]">
    <div class="w-full px-4 sm:px-8">
        <div class="flex justify-between items-center h-full">
            <div class="flex items-center gap-8 flex-1">
                <!-- Mobile Menu Button -->
                <div class="flex items-center lg:hidden">
                    <button @click="open = ! open" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all">
                        <i class="fa-solid fa-bars-staggered text-xl"></i>
                    </button>
                </div>

                <!-- Search Mockup (Desktop) -->
                <div class="hidden md:flex items-center max-w-md w-full relative group">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input type="text" placeholder="Search employees, records, tasks..." 
                           class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border-none rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-400" />
                    <div class="absolute right-4 text-[10px] font-black text-slate-300 border border-slate-200 px-1.5 py-0.5 rounded-md">CMD K</div>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-5">
                <!-- Notifications -->
                <button class="relative p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all">
                    <i class="fa-solid fa-bell text-xl"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <!-- Divider -->
                <div class="h-8 w-px bg-slate-100 hidden sm:block"></div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition-all">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Super Admin</p>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=3b82f6&color=fff" class="w-10 h-10 rounded-xl shadow-sm border border-slate-100" />
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-2">
                            <x-dropdown-link :href="route('profile.edit')" class="rounded-xl flex items-center gap-3 py-3">
                                <i class="fa-solid fa-circle-user text-slate-400"></i>
                                <span>{{ __('Profile Settings') }}</span>
                            </x-dropdown-link>

                            <x-dropdown-link href="#" class="rounded-xl flex items-center gap-3 py-3">
                                <i class="fa-solid fa-shield-halved text-slate-400"></i>
                                <span>{{ __('Security') }}</span>
                            </x-dropdown-link>

                            <div class="my-2 border-t border-slate-50"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        class="rounded-xl flex items-center gap-3 py-3 text-red-600 hover:bg-red-50"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>{{ __('Sign Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden fixed inset-0 z-50 bg-slate-950/20 backdrop-blur-sm" @click="open = false">
        <div class="w-72 h-full bg-slate-900 p-6 flex flex-col" @click.stop>
            <div class="flex items-center justify-between mb-10">
                <span class="font-extrabold text-2xl text-white">Mekong<span class="text-blue-500">CyberUnit</span></span>
                <button @click="open = false" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <div class="flex-1 space-y-2">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-2xl py-4 border-none text-slate-400 hover:text-white hover:bg-white/5">
                    <i class="fa-solid fa-grid-2 mr-3"></i> {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <!-- Add more mobile links if needed -->
            </div>
        </div>
    </div>
</nav>
