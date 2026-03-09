@extends('superadmin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pt-2">
        <div class="flex flex-col">
            <h2 class="font-extrabold text-3xl text-[#0F172A] tracking-[-0.02em] leading-tight flex items-center gap-3">
                <a href="{{ route('superadmin.subscription-plans.index') }}" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors shadow-sm border border-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                Create Subscription Plan
            </h2>
            <p class="text-slate-500 text-sm mt-1 ml-14 group flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Define a new pricing tier and limits.
            </p>
        </div>
    </div>
@endsection

@section('content')

<div class="premium-card overflow-hidden bg-white mb-8 group/card">
    <div class="p-8">
        <form action="{{ route('superadmin.subscription-plans.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Basic Info -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4">Basic Details</h3>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Plan Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all"
                            placeholder="e.g. Starter, Professional, Enterprise">
                        @error('name')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-semibold text-slate-700 mb-1.5">Monthly Price ($)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 sm:text-sm font-medium">$</span>
                            </div>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', '0.00') }}" required
                                class="block w-full pl-8 pr-4 py-3 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all text-slate-900 font-bold"
                                placeholder="0.00">
                        </div>
                        @error('price')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500/30 focus:ring-2">
                        </div>
                        <label for="is_active" class="text-sm font-semibold text-slate-700 select-none">Make this plan Active immediately</label>
                    </div>
                </div>

                <!-- Limits & Features -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4">Quotas & Limits</h3>
                    </div>

                    <div>
                        <label for="employee_limit" class="block text-sm font-semibold text-slate-700 mb-1.5">Employee Limit</label>
                        <input type="number" name="employee_limit" id="employee_limit" value="{{ old('employee_limit') }}"
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all"
                            placeholder="Leave empty for unlimited">
                        <p class="text-xs text-slate-400 mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Maximum number of employees allowed for this tenant.
                        </p>
                        @error('employee_limit')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="branch_limit" class="block text-sm font-semibold text-slate-700 mb-1.5">Branch Limit</label>
                        <input type="number" name="branch_limit" id="branch_limit" value="{{ old('branch_limit') }}"
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all"
                            placeholder="Leave empty for unlimited">
                        @error('branch_limit')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>

                    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4">
                        <h4 class="text-sm font-bold text-blue-800 mb-2">Notice</h4>
                        <p class="text-xs text-blue-600/80 font-medium">Additional features checklist mapping will be implemented in future phases. Currently, the system uses limits to restrict tenant access.</p>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3 mt-8">
                <a href="{{ route('superadmin.subscription-plans.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-800 font-bold rounded-xl text-sm transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.23)] hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save & Create Plan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
