<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-8 py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 font-heading lowercase tracking-tight">Account Settings.</h2>
                <p class="mt-2 text-slate-500 font-medium">Manage your personal information, security, and workspace preferences.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-16 h-16 rounded-2xl bg-blue-600/10 flex items-center justify-center text-blue-600 shadow-sm border border-blue-100">
                    <i class="fa-solid fa-user-shield text-2xl"></i>
                </div>
            </div>
        </div>

        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span class="font-bold text-sm">Action completed successfully!</span>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8">
            
            <!-- Profile Information Card -->
            <div class="bg-white rounded-[32px] overflow-hidden border border-slate-100 shadow-xl shadow-slate-200/20">
                <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-address-card"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Personal Information</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">General details</p>
                    </div>
                </div>
                
                <div class="p-8">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Display Name')" />
                                <x-text-input id="name" name="name" type="text" class="block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Work Email')" />
                                <x-text-input id="email" name="email" type="email" class="block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <x-text-input id="phone" name="phone" type="text" class="block w-full" :value="old('phone', $user->phone)" placeholder="e.g. +855 12 345 678" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <x-primary-button>{{ __('Update Profile') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Security Card -->
            <div class="bg-white rounded-[32px] overflow-hidden border border-slate-100 shadow-xl shadow-slate-200/20">
                <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-emerald-600">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Security Credentials</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password Management</p>
                    </div>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="current_password" :value="__('Current Password')" />
                                <x-text-input id="current_password" name="current_password" type="password" class="block w-full" autocomplete="current-password" placeholder="••••••••" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('New Password')" />
                                <x-text-input id="password" name="password" type="password" class="block w-full" autocomplete="new-password" placeholder="••••••••" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block w-full" autocomplete="new-password" placeholder="••••••••" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700 shadow-emerald-200 focus:ring-emerald-500">{{ __('Change Password') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone Card -->
            <div class="bg-red-50/30 rounded-[32px] overflow-hidden border border-red-100 shadow-xl shadow-red-200/10">
                <div class="bg-red-50/50 px-8 py-6 border-b border-red-100 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-red-600">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-900">System Governance</h3>
                        <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Account Deletion</p>
                    </div>
                </div>

                <div class="p-8">
                    <div class="max-w-xl">
                        <p class="text-sm font-medium text-red-700/80 leading-relaxed mb-6">
                            Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data or information that you wish to retain before proceeding.
                        </p>
                        
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" 
                                class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold text-sm tracking-widest uppercase rounded-[20px] shadow-lg shadow-red-200 transition-all">
                            {{ __('Deactivate Account') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deletion Modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-extrabold text-slate-900 font-heading lowercase">Are you absolutely sure?</h2>
            <p class="mt-4 text-sm text-slate-500 font-medium">
                This action cannot be undone. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <div class="mt-8">
                <x-input-label for="password" value="{{ __('Verification Password') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="block w-full" placeholder="{{ __('••••••••') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-red-200 transition-all">
                    {{ __('Permanently Delete') }}
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
