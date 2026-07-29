<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 dark:text-slate-100 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="w-full">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </x-card>

            <x-card>
                <div class="w-full">
                    @include('profile.partials.update-password-form')
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>