<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-16 w-16 animate-pulse items-center justify-center rounded-full bg-gradient-to-br from-purple-400 to-pink-500 shadow-lg shadow-purple-500/30">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-white">
                        Meu Time
                        @if(isset($selectedGame) && $selectedGame)
                        <span class="text-lg text-purple-400">({{ $selectedGame }})</span>
                        @endif
                    </h2>
                    <p class="text-purple-100">Selecione seus 6 Pokémon principais</p>
                </div>
            </div>
            <a href="{{ route('pokemons.index') }}"
                class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-3 font-bold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:scale-105 hover:from-blue-600 hover:to-purple-700 hover:shadow-blue-500/50">
                Ver Todos os Pokémon
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
            <div
                class="mb-4 flex items-center gap-2 rounded-xl border border-green-500/50 bg-green-500/20 px-4 py-3 text-green-200 backdrop-blur-sm">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div
                class="mb-4 flex items-center gap-2 rounded-xl border border-red-500/50 bg-red-500/20 px-4 py-3 text-red-200 backdrop-blur-sm">
                {{ session('error') }}
            </div>
            @endif

            <!-- Filtro por Jogo -->
            @if(isset($userGames) && $userGames->count() > 0)
            <div
                class="mb-6 overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm sm:rounded-2xl">
                <div class="p-4">
                    <form method="GET" action="{{ route('pokemons.team') }}" class="flex flex-wrap items-center gap-4">
                        <label for="game" class="text-sm font-medium text-slate-300">Filtrar por Jogo:</label>
                        <select name="game" id="game"
                            class="rounded-md border-slate-600 bg-slate-700 text-white focus:border-yellow-500 focus:ring-yellow-500"
                            onchange="this.form.submit()">
                            <option value="">Todos os Jogos</option>
                            @foreach($userGames as $game)
                            <option value="{{ $game }}" {{ (isset($selectedGame) && $selectedGame==$game) ? 'selected'
                                : '' }}>
                                {{ $game }}
                            </option>
                            @endforeach
                        </select>
                        @if(isset($selectedGame) && $selectedGame)
                        <a href="{{ route('pokemons.team') }}" class="text-sm text-purple-400 hover:text-purple-300">
                            Limpar Filtro
                        </a>
                        @endif
                    </form>
                </div>
            </div>
            @endif

            <div
                class="overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm sm:rounded-2xl">
                <div class="p-6 text-slate-200">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-center text-white mb-2">Formação do Time</h3>
                        <p class="text-center text-slate-400">Máximo de 6 Pokémon no seu time principal</p>
                    </div>

                    <!-- Seção do Trainer -->
                    <div class="mb-8 flex justify-center">
                        <div
                            class="trainer-card bg-gradient-to-br from-slate-700/80 to-slate-800/80 border border-slate-600 rounded-2xl p-6 text-center shadow-xl">
                            <div class="mb-4">
                                <img src="{{ $trainerData['sprite'] }}" alt="{{ $trainerData['name'] }}"
                                    class="w-24 h-24 mx-auto object-contain drop-shadow-lg">
                            </div>
                            <h4 class="text-xl font-bold text-white mb-2">Treinador{{ $trainerData['gender'] ===
                                'female' ? 'a' : '' }}</h4>
                            <p class="text-lg text-purple-300 font-semibold">{{ $trainerData['name'] }}</p>
                            <div class="mt-4 flex items-center justify-center gap-2">
                                <span class="text-sm text-slate-400">{{ Auth::user()->name }}</span>
                                @if($trainerData['gender'] === 'female')
                                <span class="text-pink-400">♀</span>
                                @else
                                <span class="text-blue-400">♂</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @for ($i = 1; $i <= 6; $i++) <div class="team-slot relative">
                            <div class="absolute -top-2 -left-2 z-10">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-sm font-bold text-white shadow-lg">
                                    {{ $i }}
                                </div>
                            </div>

                            @if ($team[$i])
                            @php $pokemon = $team[$i]; @endphp
                            <div class="pokedex-card glow-card group relative p-6">
                                <!-- Cabeçalho do Card -->
                                <div class="mb-6 text-center">
                                    <div class="mb-3 flex items-center justify-center gap-2">
                                        <span
                                            class="rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 px-3 py-1 text-xs font-bold text-white shadow-lg shadow-blue-500/30">
                                            #{{ $pokemon->pokemon_id ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <h3 class="mb-2 text-xl font-bold text-white">
                                        {{ ucfirst($pokemon->pokemon_name) }}
                                    </h3>
                                </div>

                                <!-- Imagem do Pokémon -->
                                <div class="relative mb-6">
                                    @if ($pokemon->sprite_url)
                                    <div
                                        class="relative mx-auto flex h-28 w-28 items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-800 shadow-lg shadow-slate-900/50 ring-2 ring-slate-600">
                                        <div
                                            class="absolute inset-2 rounded-full bg-gradient-to-br from-slate-600 to-slate-700">
                                        </div>
                                        <img src="{{ $pokemon->sprite_url }}" alt="{{ $pokemon->pokemon_name }}"
                                            class="pokemon-sprite relative z-10 h-20 w-20 object-contain">
                                    </div>
                                    @else
                                    <div
                                        class="mx-auto flex h-28 w-28 items-center justify-center rounded-full bg-slate-700">
                                        <span class="text-3xl text-slate-500">?</span>
                                    </div>
                                    @endif
                                </div>

                                <!-- Informações do Jogo -->
                                <div class="mb-6">
                                    <div class="mb-4 flex flex-wrap justify-center gap-2">
                                        <span
                                            class="rounded-full bg-gradient-to-r from-blue-500 to-blue-600 px-3 py-1 text-sm font-semibold text-white shadow-sm shadow-blue-500/30">
                                            {{ $pokemon->game_name }}
                                        </span>
                                        <span
                                            class="rounded-full bg-gradient-to-r from-green-500 to-green-600 px-3 py-1 text-sm font-semibold text-white shadow-sm shadow-green-500/30">
                                            Nv. {{ $pokemon->level }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Botão Remover do Time -->
                                <div class="flex justify-center">
                                    <form action="{{ route('pokemons.remove-from-team', $pokemon) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Tem certeza que deseja remover {{ $pokemon->pokemon_name }} do time?')"
                                            class="w-full rounded-xl bg-red-500 px-6 py-3 text-sm font-bold text-white transition-colors hover:bg-red-600">
                                            Remover do Time
                                        </button>
                                    </form>
                                </div>

                                <!-- Data de Captura -->
                                <div class="mt-4 text-center">
                                    <span class="text-xs text-slate-400">
                                        Capturado em {{ $pokemon->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                            @else
                            <!-- Slot vazio -->
                            <div
                                class="empty-slot rounded-xl border-2 border-dashed border-slate-600 bg-slate-800/30 p-8 text-center transition-all duration-300 hover:border-purple-500 hover:bg-slate-700/30">
                                <div class="flex flex-col items-center justify-center h-48">
                                    <div class="mb-4 rounded-full bg-slate-700 p-6">
                                        <svg class="h-12 w-12 text-slate-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <h4 class="mb-2 text-lg font-semibold text-slate-400">Slot Vazio</h4>
                                    <p class="mb-4 text-sm text-slate-500">Adicione um Pokémon para esta posição</p>
                                    <a href="{{ route('pokemons.index') }}"
                                        class="rounded-lg bg-purple-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-purple-600">
                                        Escolher Pokémon
                                    </a>
                                </div>
                            </div>
                            @endif
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    </div>

    <style>
        .team-slot {
            position: relative;
        }

        .empty-slot {
            min-height: 320px;
        }

        .pokedex-card {
            background: linear-gradient(135deg,
                    rgba(30, 41, 59, 0.8) 0%,
                    rgba(51, 65, 85, 0.6) 50%,
                    rgba(30, 41, 59, 0.8) 100%);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 1rem;
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
            min-height: 320px;
        }

        .pokedex-card:hover {
            transform: translateY(-4px);
            border-color: rgba(168, 85, 247, 0.5);
            box-shadow:
                0 10px 25px -5px rgba(0, 0, 0, 0.3),
                0 0 20px rgba(168, 85, 247, 0.2);
        }

        .pokemon-sprite {
            transition: transform 0.3s ease;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        .group:hover .pokemon-sprite {
            transform: scale(1.1);
        }
    </style>
</x-app-layout>