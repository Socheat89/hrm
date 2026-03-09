@extends('superadmin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pt-2">
        <div class="flex flex-col">
            <h2 class="font-extrabold text-3xl text-[#0F172A] tracking-[-0.02em] leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shadow-sm border border-purple-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                Subscription Plans
            </h2>
            <p class="text-slate-500 text-sm mt-1 ml-14 group flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Manage pricing tiers and quotas.
            </p>
        </div>
        <a href="{{ route('superadmin.subscription-plans.create') }}" class="group px-5 py-2.5 bg-[#0F172A] hover:bg-slate-800 text-white font-bold rounded-xl text-sm shadow-[0_4px_14px_0_rgba(15,23,42,0.39)] hover:shadow-[0_6px_20px_rgba(15,23,42,0.23)] hover:-translate-y-0.5 transition-all flex items-center gap-2 border border-slate-700">
            <svg class="w-4 h-4 text-slate-300 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create New Plan
        </a>
    </div>
@endsection

@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center gap-3">
    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    </div>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 flex items-center gap-3">
    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </div>
    <span class="font-medium">{{ session('error') }}</span>
</div>
@endif

<div class="premium-card overflow-hidden bg-white mb-8 group/card hover:shadow-[0_10px_30px_-10px_rgba(15,23,42,0.08)]">
    
    <div class="overflow-x-auto w-full">
        <table class="w-full whitespace-nowrap text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <th scope="col" class="px-6 py-4 rounded-tl-xl pl-8">Plan Details</th>
                    <th scope="col" class="px-6 py-4">Price</th>
                    <th scope="col" class="px-6 py-4">Limits (Emp / Branch)</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 rounded-tr-xl flex justify-end pr-8">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50/80">
                @forelse($plans as $plan)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-4 pl-8">
                        <div class="flex flex-col">
                            <span class="text-[15px] font-bold text-slate-800">{{ $plan->name }}</span>
                            <span class="text-xs font-medium text-slate-500 mt-0.5">Created {{ $plan->created_at->format('M d, Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-[15px] font-bold text-slate-900">${{ number_format($plan->price, 2) }} <span class="text-xs font-medium text-slate-400">/mo</span></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-slate-500 uppercase tracking-widest mb-1">Employees</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-600">
                                    {{ $plan->employee_limit ?? 'Unlimited' }}
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-slate-500 uppercase tracking-widest mb-1">Branches</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-600">
                                    {{ $plan->branch_limit ?? 'Unlimited' }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($plan->is_active)
                            <div class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-[0_2px_10px_-2px_rgba(16,185,129,0.1)] gap-1.5">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Active
                            </div>
                        @else
                            <div class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-50 text-slate-500 border border-slate-200">
                                Inactive
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex justify-end pr-8">
                        <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('superadmin.subscription-plans.edit', $plan) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-100" title="Edit Plan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('superadmin.subscription-plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-100" title="Delete Plan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center mb-4 border-2 border-dashed border-slate-200">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">No Plans Configured</h3>
                            <p class="text-sm font-medium text-slate-500 text-center mb-6">Create subscription tiers to start offering your SaaS platform to tenants.</p>
                            <a href="{{ route('superadmin.subscription-plans.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.23)] hover:-translate-y-0.5 transition-all w-full flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Create First Plan
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(method_exists($plans, 'links') && $plans->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
        <div class="scale-90 origin-right">
            {{ $plans->links() }}
        </div>
    </div>
    @endif
</div>

@endsection
