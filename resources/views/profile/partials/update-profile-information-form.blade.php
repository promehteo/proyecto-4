<section>
    <header>
        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm font-medium text-slate-700 dark:text-slate-300">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="nombre" :value="__('Nombre')" />
                <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $user->nombre)" required autofocus autocomplete="given-name" />
                <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
            </div>

            <div>
                <x-input-label for="apellido" :value="__('Apellido')" />
                <x-text-input id="apellido" name="apellido" type="text" class="mt-1 block w-full" :value="old('apellido', $user->apellido)" required autocomplete="family-name" />
                <x-input-error class="mt-2" :messages="$errors->get('apellido')" />
            </div>

            <div>
                <x-input-label for="cedula" :value="__('Cédula')" />
                <x-text-input id="cedula" name="cedula" type="number" class="mt-1 block w-full" :value="old('cedula', $user->cedula)" autocomplete="off" />
                <x-input-error class="mt-2" :messages="$errors->get('cedula')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 font-medium text-slate-900 dark:text-slate-100">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-semibold text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-medium text-slate-700 dark:text-slate-300"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
