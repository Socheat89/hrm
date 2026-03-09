@extends('superadmin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pt-2">
        <div class="flex flex-col">
            <h2 class="font-extrabold text-3xl text-[#0F172A] tracking-[-0.02em] leading-tight flex items-center gap-3">
                <a href="{{ route('superadmin.companies.index') }}" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors shadow-sm border border-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                Modify Company Settings
            </h2>
            <p class="text-slate-500 text-sm mt-1 ml-14 group flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Editing "{{ $company->name }}"
            </p>
        </div>
    </div>
@endsection

@section('content')

<div class="premium-card overflow-hidden bg-white mb-8 group/card">
    <div class="p-8">
        <form action="{{ route('superadmin.companies.update', $company) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Basic Info -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4">Account Configuration</h3>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Company Name</label>
                        <input type="text" value="{{ $company->name }}" disabled
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl leading-5 bg-slate-100/50 text-slate-500 cursor-not-allowed font-bold">
                        <p class="text-xs text-slate-400 mt-1.5 font-medium flex items-center gap-1">Company name cannot be changed here.</p>
                    </div>

                    <div>
                        <label for="subscription_plan_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Subscription Plan</label>
                        <select name="subscription_plan_id" id="subscription_plan_id" required
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl leading-5 bg-slate-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all text-slate-900 font-bold">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('subscription_plan_id', $company->subscription_plan_id) == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} (${{ $plan->price }}/m)
                                </option>
                            @endforeach
                        </select>
                        @error('subscription_plan_id')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">Account Status</label>
                        <select name="status" id="status" required
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl leading-5 bg-slate-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all text-slate-900 font-bold">
                            <option value="active" {{ old('status', $company->status) === 'active' ? 'selected' : '' }}>Active - Operational</option>
                            <option value="suspended" {{ old('status', $company->status) === 'suspended' ? 'selected' : '' }}>Suspended - Blocked</option>
                        </select>
                        @error('status')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Expiration & Limits -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4">Expiration & Limits</h3>
                    </div>

                    <div>
                        <label for="expiry_date" class="block text-sm font-semibold text-slate-700 mb-1.5">Expiration Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $company->expiry_date ? $company->expiry_date->format('Y-m-d') : '') }}"
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all font-bold text-slate-900">
                        <p class="text-xs text-slate-400 mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Leave empty for lifetime access. After expiration, company admin will receive notifications and usage will be blocked.
                        </p>
                        @error('expiry_date')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                    </div>

                    <!-- Extra decorative box for SaaS -->
                    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 mt-8">
                        <h4 class="text-sm font-bold text-blue-800 mb-2 border-b border-blue-100 pb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Impact of Expiration
                        </h4>
                        <ul class="text-xs text-blue-700/80 font-medium space-y-1.5 mt-2 pl-3 list-disc">
                            <li>All users in the company will be logged out.</li>
                            <li>Users attempting to log in will see an expiration notice.</li>
                            <li>Company data will remain intact but inaccessible.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3 mt-8">
                <a href="{{ route('superadmin.companies.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-800 font-bold rounded-xl text-sm transition-colors shadow-sm">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#0F172A] hover:bg-slate-800 text-white font-bold rounded-xl text-sm shadow-[0_4px_14px_0_rgba(15,23,42,0.39)] hover:shadow-[0_6px_20px_rgba(15,23,42,0.23)] hover:-translate-y-0.5 transition-all flex items-center gap-2 border border-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Configuration
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
