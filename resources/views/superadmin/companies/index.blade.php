@extends('superadmin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pt-2">
        <div class="flex flex-col">
            <h2 class="font-extrabold text-3xl text-[#0F172A] tracking-[-0.02em] leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm border border-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                Platform Tenants
            </h2>
            <p class="text-slate-500 text-sm mt-1 ml-14 group flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Manage and provision instances for your clients.
            </p>
        </div>
        <button class="group px-5 py-2.5 bg-[#0F172A] hover:bg-slate-800 text-white font-bold rounded-xl text-sm shadow-[0_4px_14px_0_rgba(15,23,42,0.39)] hover:shadow-[0_6px_20px_rgba(15,23,42,0.23)] hover:-translate-y-0.5 transition-all flex items-center gap-2 border border-slate-700">
            <svg class="w-4 h-4 text-slate-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Provision Tenant
        </button>
    </div>
@endsection

@section('content')

<!-- Data Table Card -->
<div class="premium-card overflow-hidden bg-white mb-8 group/card hover:shadow-[0_10px_30px_-10px_rgba(15,23,42,0.08)]">
    
    <!-- Utilities Bar -->
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
        
        <!-- Search -->
        <div class="relative w-full max-w-sm group">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" 
                class="block w-full pl-10 pr-4 py-2.5 border-slate-200 rounded-xl leading-5 bg-white shadow-sm placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all" 
                placeholder="Search instances by domain or name...">
        </div>
        
        <!-- Filters -->
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="flex items-center gap-2 text-sm font-medium text-slate-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Status
            </div>
            <select class="block w-full sm:w-40 pl-4 pr-10 py-2.5 border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm font-medium rounded-xl bg-white shadow-sm cursor-pointer text-slate-700 hover:border-slate-300 transition-colors">
                <option value="all">Every Tenant</option>
                <option value="active">Active Plans</option>
                <option value="trial">On Trial</option>
                <option value="expired">Expired/Suspended</option>
            </select>
            
            <button class="p-2.5 bg-white border border-slate-200 rounded-xl shadow-sm hover:bg-slate-50 text-slate-500 hover:text-slate-800 transition-colors focus:ring-2 focus:ring-slate-200 focus:outline-none" title="Refresh List">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
        
    </div>

    <!-- The Data Table -->
    <div class="overflow-x-auto w-full">
        <table class="w-full whitespace-nowrap text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <th scope="col" class="px-6 py-4 rounded-tl-xl pl-8">Company identity</th>
                    <th scope="col" class="px-6 py-4">Domain / DB Schema</th>
                    <th scope="col" class="px-6 py-4">Current Status</th>
                    <th scope="col" class="px-6 py-4">Billing Cycle</th>
                    <th scope="col" class="px-6 py-4 rounded-tr-xl flex justify-end pr-8">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50/80">
                @forelse($companies as $company)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-4 pl-8">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 h-11 w-11 flex items-center justify-center rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-black tracking-tight text-sm shadow-[0_2px_10px_-2px_rgba(59,130,246,0.5)] border border-blue-400/20">
                                {{ strtoupper(substr($company->name, 0, 2)) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[15px] font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $company->name }}</span>
                                <span class="text-xs font-medium text-slate-500 mt-0.5">Provisioned {{ $company->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1 items-start">
                             <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-mono text-xs border border-slate-200 font-medium">
                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                {{ $company->subdomain ?? strtolower(str_replace(' ', '', $company->name)) }}.saashrm.com
                            </div>
                            <!-- Mock DB Name to make it look technical and premium -->
                            <span class="text-[10px] text-slate-400 font-mono pl-1 tracking-wider uppercase">DB: tenant_{{ $company->id }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($company->status === 'active')
                            <div class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-[0_2px_10px_-2px_rgba(16,185,129,0.1)] gap-1.5">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Operational
                            </div>
                        @else
                            <div class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100 shadow-sm gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                Suspended
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($company->expiry_date)
                            <div class="flex flex-col">
                                <span class="text-sm font-bold {{ Carbon\Carbon::parse($company->expiry_date)->isPast() ? 'text-rose-600' : 'text-slate-700' }}">
                                    {{ Carbon\Carbon::parse($company->expiry_date)->format('M d, Y') }}
                                </span>
                                <span class="text-xs font-medium text-slate-400 mt-0.5">
                                    {{ Carbon\Carbon::parse($company->expiry_date)->diffForHumans() }}
                                </span>
                            </div>
                        @else
                            <div class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                LIFETIME
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex justify-end pr-8">
                        <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            
                            <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-100" title="Impersonate Admin">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                            
                            <a href="{{ route('superadmin.companies.edit', $company) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors border border-transparent hover:border-indigo-100" title="Modify Plan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </a>
                            
                            <!-- Dropdown trigger mock -->
                            <button class="p-2 text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors border border-transparent hover:border-slate-200" title="More options">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                            </button>
                            
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center mb-4 border-2 border-dashed border-slate-200">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">No Companies Found</h3>
                            <p class="text-sm font-medium text-slate-500 text-center mb-6">Your SaaS platform doesn't have any active tenants yet. Provision your first instance to get started.</p>
                            <button class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.23)] hover:-translate-y-0.5 transition-all w-full flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add First Tenant
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Footer / Pagination -->
    @if(method_exists($companies, 'links') && $companies->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Showing {{ $companies->firstItem() ?? 0 }} - {{ $companies->lastItem() ?? 0 }} of {{ $companies->total() }} tenants
        </span>
        <div class="scale-90 origin-right">
            {{ $companies->links() }}
        </div>
    </div>
    @endif
</div>

@endsection
