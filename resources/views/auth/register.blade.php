<x-guest-layout>
    <div class="mb-6 text-center">
        <h2
            class="bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 bg-clip-text text-2xl font-bold text-transparent">
            Crie sua conta
        </h2>
        <p class="mt-2 text-sm text-slate-400">Junte-se à comunidade de treinadores</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Nome')" class="font-medium text-slate-200" />
            <x-text-input id="name"
                class="w-full rounded-lg border border-slate-600 bg-slate-700/50 px-4 py-3 text-white placeholder-slate-400 transition-all duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                type="text" name="name" :value="old('name')" placeholder="Seu nome completo" required autofocus
                autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="font-medium text-slate-200" />
            <x-text-input id="email"
                class="w-full rounded-lg border border-slate-600 bg-slate-700/50 px-4 py-3 text-white placeholder-slate-400 transition-all duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                type="email" name="email" :value="old('email')" placeholder="seu@email.com" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Senha')" class="font-medium text-slate-200" />
            <x-text-input id="password"
                class="w-full rounded-lg border border-slate-600 bg-slate-700/50 px-4 py-3 text-white placeholder-slate-400 transition-all duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                type="password" name="password" placeholder="••••••••" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" class="font-medium text-slate-200" />
            <x-text-input id="password_confirmation"
                class="w-full rounded-lg border border-slate-600 bg-slate-700/50 px-4 py-3 text-white placeholder-slate-400 transition-all duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                type="password" name="password_confirmation" placeholder="••••••••" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Register Button -->
        <button type="submit"
            class="w-full transform rounded-lg bg-gradient-to-r from-green-600 via-blue-600 to-purple-600 px-4 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:scale-[1.02] hover:from-green-700 hover:via-blue-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-800">
            <span class="flex items-center justify-center">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Criar conta
            </span>
        </button>

        <!-- Login Link -->
        <div class="border-t border-slate-700 pt-4 text-center">
            <p class="mb-2 text-sm text-slate-400">Já tem uma conta?</p>
            <a href="{{ route('login') }}"
                class="inline-flex items-center rounded-lg bg-slate-700 px-4 py-2 font-medium text-slate-200 transition-all duration-200 hover:scale-105 hover:bg-slate-600">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path>
                </svg>
                Fazer login
            </a>
        </div>
    </form>
</x-guest-layout>
