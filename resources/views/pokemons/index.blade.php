<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-16 w-16 animate-pulse items-center justify-center rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 shadow-lg shadow-yellow-500/30">
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-white">
                        Pokédex Nacional
                    </h2>
                    <p class="text-red-100">{{ $pokemons->count() }} Pokémon capturados</p>
                </div>
            </div>
            <a href="{{ route('pokemons.create') }}"
                class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-3 font-bold text-white shadow-lg shadow-green-500/30 transition-all duration-300 hover:scale-105 hover:from-green-600 hover:to-emerald-700 hover:shadow-green-500/50">
                Capturar Pokémon
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

            <div
                class="overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm sm:rounded-2xl">
                <div class="p-6 text-slate-200">
                    @if ($pokemons->count() > 0)
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach ($pokemons as $pokemon)
                        <div class="pokedex-card glow-card group relative p-6">
                            <!-- Cabeçalho do Card -->
                            <div class="mb-6 text-center">
                                <div class="mb-3 flex items-center justify-center gap-2">
                                    <span
                                        class="rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 px-3 py-1 text-xs font-bold text-white shadow-lg shadow-blue-500/30">
                                        #{{ $pokemon->pokemon_id ?? 'N/A' }}
                                    </span>
                                </div>
                                <h3 class="mb-2 text-2xl font-bold text-white">
                                    {{ ucfirst($pokemon->pokemon_name) }}
                                </h3>
                            </div>

                            <!-- Imagem do Pokémon -->
                            <div class="relative mb-6">
                                @if ($pokemon->sprite_url)
                                <div
                                    class="relative mx-auto flex h-32 w-32 items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-800 shadow-lg shadow-slate-900/50 ring-2 ring-slate-600">
                                    <div
                                        class="absolute inset-2 rounded-full bg-gradient-to-br from-slate-600 to-slate-700">
                                    </div>
                                    <img src="{{ $pokemon->sprite_url }}" alt="{{ $pokemon->pokemon_name }}"
                                        class="pokemon-sprite relative z-10">
                                </div>
                                @else
                                <div
                                    class="mx-auto flex h-32 w-32 items-center justify-center rounded-full bg-slate-700">
                                    <span class="text-4xl text-slate-500">?</span>
                                </div>
                                @endif
                            </div>

                            <!-- Informações do Jogo -->
                            <div class="mb-6">
                                <div class="mb-4 flex flex-wrap justify-center gap-2">
                                    <span
                                        class="rounded-full bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-blue-500/30">
                                        {{ $pokemon->game_name }}
                                    </span>
                                    <span
                                        class="rounded-full bg-gradient-to-r from-green-500 to-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-green-500/30">
                                        Nv. {{ $pokemon->level }}
                                    </span>
                                </div>
                            </div>

                            <!-- Status Geral -->
                            @php
                            $totalStats =
                            $pokemon->game_hp +
                            $pokemon->game_attack +
                            $pokemon->game_defense +
                            $pokemon->game_sp_attack +
                            ($pokemon->game_sp_defense ?? 0) +
                            $pokemon->game_speed;
                            $baseTotalStats =
                            $pokemon->base_hp +
                            $pokemon->base_attack +
                            $pokemon->base_defense +
                            $pokemon->base_sp_attack +
                            ($pokemon->base_sp_defense ?? 0) +
                            $pokemon->base_speed;
                            $overallPercentage =
                            $baseTotalStats > 0 ? round(($totalStats / $baseTotalStats) * 100) : 0;

                            if ($overallPercentage >= 90) {
                            $overallColor = 'green';
                            $overallStatus = 'Excelente';
                            } elseif ($overallPercentage >= 80) {
                            $overallColor = 'blue';
                            $overallStatus = 'Muito Bom';
                            } elseif ($overallPercentage >= 70) {
                            $overallColor = 'indigo';
                            $overallStatus = 'Bom';
                            } elseif ($overallPercentage >= 60) {
                            $overallColor = 'yellow';
                            $overallStatus = 'Médio';
                            } else {
                            $overallColor = 'red';
                            $overallStatus = 'Fraco';
                            }
                            @endphp
                            <div class="mb-6">
                                <div
                                    class="rounded-xl border border-slate-600 bg-slate-700/50 p-4 shadow-sm backdrop-blur-sm">
                                    <div class="mb-3 flex items-center justify-between">
                                        <span class="text-sm font-semibold text-slate-300">Status Geral</span>
                                        <span class="text-{{ $overallColor }}-400 text-sm font-bold">{{ $overallStatus
                                            }}</span>
                                    </div>
                                    <div class="stat-bar">
                                        <div class="stat-fill bg-{{ $overallColor }}-500"
                                            style="width: {{ min($overallPercentage, 100) }}%"></div>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between text-xs text-slate-400">
                                        <span>Total: {{ $totalStats }}</span>
                                        <span>{{ $overallPercentage }}%</span>
                                    </div>
                                </div>

                                @if ($pokemon->notes)
                                <div class="mt-4 rounded-lg border-l-4 border-yellow-400 bg-yellow-500/10 p-3">
                                    <span class="text-xs italic text-yellow-300">{{ Str::limit($pokemon->notes, 50)
                                        }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Botões de Ação -->
                            <div class="flex gap-3">
                                <a href="{{ route('pokemons.show', $pokemon) }}"
                                    class="pokedex-button flex-1 py-3 text-center text-sm">
                                    Detalhes
                                </a>
                                @if ($pokemon->isInTeam())
                                <span
                                    class="flex-1 rounded-xl bg-green-600 py-3 text-center text-sm font-bold text-white">
                                    No Time
                                </span>
                                @else
                                <form action="{{ route('pokemons.add-to-team', $pokemon) }}" method="POST"
                                    class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-xl bg-purple-500 py-3 text-sm font-bold text-white transition-colors hover:bg-purple-600">
                                        Adicionar ao Time
                                    </button>
                                </form>
                                @endif
                            </div>

                            <!-- Data de Captura -->
                            <div class="mt-4 text-center">
                                <span class="text-xs text-slate-400">
                                    Capturado em {{ $pokemon->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $pokemons->links() }}
                    </div>
                    @else
                    <div class="pokedex-card py-16 text-center">
                        <div class="mb-8">
                            <div
                                class="mx-auto mb-6 flex h-32 w-32 items-center justify-center rounded-full border border-slate-600 bg-gradient-to-br from-blue-500/20 to-purple-500/20 shadow-lg">
                            </div>
                            <div class="relative">
                                <h3 class="mb-4 text-3xl font-bold text-white">Pokédex Vazia!</h3>
                            </div>
                        </div>
                        <p class="mx-auto mb-2 max-w-md text-xl text-slate-300">
                            Sua aventura Pokémon está apenas começando!
                        </p>
                        <p class="mx-auto mb-8 max-w-lg text-slate-400">
                            Você ainda não capturou nenhum Pokémon. Que tal começar sua jornada e capturar seu
                            primeiro companheiro?
                        </p>
                        <a href="{{ route('pokemons.create') }}"
                            class="inline-flex transform items-center gap-3 rounded-2xl bg-gradient-to-r from-green-500 to-emerald-600 px-10 py-5 text-xl font-bold text-white shadow-xl shadow-green-500/30 transition-all duration-300 hover:scale-105 hover:from-green-600 hover:to-emerald-700 hover:shadow-green-500/50">
                            <span>Capturar Primeiro Pokémon</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>