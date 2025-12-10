<x-guest-layout>
    <div class="mb-6 text-center">
        <h2
            class="bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 bg-clip-text text-2xl font-bold text-transparent">
            Recuperar Senha
        </h2>
        <p class="mt-2 text-sm text-slate-400">Esqueceu sua senha? Não se preocupe!</p>
    </div>

    <div class="mb-6 rounded-lg border border-blue-700/50 bg-blue-900/20 p-4">
        <p class="text-sm text-blue-200">
            Digite seu email e enviaremos um link para redefinir sua senha.
            Você poderá escolher uma nova senha e voltar a explorar sua Pokédx!
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="font-medium text-slate-200" />
            <x-text-input id="email"
                class="w-full rounded-lg border border-slate-600 bg-slate-700/50 px-4 py-3 text-white placeholder-slate-400 transition-all duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                type="email" name="email" :value="old('email')" placeholder="seu@email.com" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Send Reset Link Button -->
        <button type="submit"
            class="w-full transform rounded-lg bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 px-4 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:scale-[1.02] hover:from-purple-700 hover:via-pink-700 hover:to-red-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-slate-800">
            <span class="flex items-center justify-center">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
                Enviar Link de Recuperação
            </span>
        </button>

        <!-- Back to Login -->
        <div class="border-t border-slate-700 pt-4 text-center">
            <p class="mb-2 text-sm text-slate-400">Lembrou da sua senha?</p>
            <a href="{{ route('login') }}"
                class="inline-flex items-center rounded-lg bg-slate-700 px-4 py-2 font-medium text-slate-200 transition-all duration-200 hover:scale-105 hover:bg-slate-600">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Voltar ao Login
            </a>
        </div>
    </form>
</x-guest-layout>
