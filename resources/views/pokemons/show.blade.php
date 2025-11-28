<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="pokedex-title text-2xl text-white">
                <span
                    class="bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">#{{ $pokemon->pokemon_id ?? 'N/A' }}</span>
                {{ ucfirst($pokemon->pokemon_name) }}
            </h2>
            <a href="{{ route('pokemons.index') }}"
                class="rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2 font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:scale-105 hover:from-blue-600 hover:to-blue-700">
                ← Voltar para Pokédex
            </a>
        </div>
    </x-slot>

    @php
        $typeColors = [
            'normal' => '#A8A878',
            'fire' => '#F08030',
            'water' => '#6890F0',
            'electric' => '#F8D030',
            'grass' => '#78C850',
            'ice' => '#98D8D8',
            'fighting' => '#C03028',
            'poison' => '#A040A0',
            'ground' => '#E0C068',
            'flying' => '#A890F0',
            'psychic' => '#F85888',
            'bug' => '#A8B820',
            'rock' => '#B8A038',
            'ghost' => '#705898',
            'dragon' => '#7038F8',
            'dark' => '#705848',
            'steel' => '#B8B8D0',
            'fairy' => '#EE99AC',
        ];
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Layout principal estilo Pokédex -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Seção da Imagem e Informações Básicas -->
                <div
                    class="rounded-2xl border border-blue-500/30 bg-gradient-to-br from-blue-600/20 to-indigo-600/20 p-8 shadow-xl">
                    <div class="mb-6 text-center">
                        <h1 class="pokedex-title mb-2 text-4xl text-white">{{ ucfirst($pokemon->pokemon_name) }}</h1>
                        <div class="flex items-center justify-center gap-4 text-sm">
                            <span
                                class="rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 px-3 py-1 font-semibold text-white shadow-lg shadow-blue-500/30">
                                National № {{ $pokemon->pokemon_id ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <!-- Imagem do Pokémon -->
                    <div class="relative mb-8">
                        @if ($pokemon->sprite_url)
                            <div
                                class="relative mx-auto flex h-64 w-64 items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-800 shadow-xl ring-4 ring-slate-600">
                                <div
                                    class="absolute inset-4 rounded-full bg-gradient-to-br from-slate-600 to-slate-700">
                                </div>
                                <img src="{{ $pokemon->sprite_url }}" alt="{{ $pokemon->pokemon_name }}"
                                    class="relative z-10 h-56 w-56 object-contain drop-shadow-lg transition-transform duration-300 hover:scale-110">
                            </div>
                        @else
                            <div class="mx-auto flex h-64 w-64 items-center justify-center rounded-full bg-slate-700">
                                <span class="text-6xl text-slate-500">?</span>
                            </div>
                        @endif
                    </div>

                    <!-- Pokédex Data -->
                    <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800/50 p-6 shadow-sm">
                        <h3 class="pokedex-title mb-4 flex items-center text-lg text-blue-300">
                            Dados da Pokédex
                        </h3>
                        <div class="space-y-3">
                            @if ($pokemon->species)
                                <div class="flex justify-between border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">Espécie</span>
                                    <span class="font-semibold text-white">{{ $pokemon->species }}</span>
                                </div>
                            @endif

                            @if ($pokemon->types && count($pokemon->types) > 0)
                                <div class="flex items-center justify-between border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">Tipo</span>
                                    <div class="flex gap-2">
                                        @foreach ($pokemon->types as $type)
                                            <span
                                                class="rounded-full px-3 py-1 text-xs font-bold uppercase text-white shadow-lg"
                                                style="background-color: {{ $typeColors[$type] ?? '#777' }}">
                                                {{ $type }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($pokemon->height)
                                <div class="flex justify-between border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">Altura</span>
                                    <span class="font-semibold text-white">{{ number_format($pokemon->height, 1) }} m
                                        ({{ number_format($pokemon->height * 3.28084, 0) }}'{{ number_format(($pokemon->height * 39.3701) % 12, 0) }}")</span>
                                </div>
                            @endif

                            @if ($pokemon->weight)
                                <div class="flex justify-between border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">Peso</span>
                                    <span class="font-semibold text-white">{{ number_format($pokemon->weight, 1) }} kg
                                        ({{ number_format($pokemon->weight * 2.20462, 1) }} lbs)</span>
                                </div>
                            @endif

                            @if ($pokemon->abilities && count($pokemon->abilities) > 0)
                                <div class="flex justify-between border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">Habilidades</span>
                                    <div class="text-right">
                                        @foreach ($pokemon->abilities as $index => $ability)
                                            <div class="text-white">
                                                <span class="text-slate-400">{{ $index + 1 }}.</span>
                                                <span
                                                    class="{{ $ability['is_hidden'] ? 'text-purple-400' : '' }} font-semibold">
                                                    {{ $ability['name'] }}
                                                </span>
                                                @if ($ability['is_hidden'])
                                                    <span class="text-xs text-slate-500">(habilidade oculta)</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informações do Jogo -->
                    <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800/50 p-6 shadow-sm">
                        <h3 class="pokedex-title mb-4 flex items-center text-lg text-green-300">
                            Dados do Jogo
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-slate-400">Jogo</p>
                                <p class="font-semibold text-white">{{ $pokemon->game_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-400">Nível</p>
                                <p class="text-xl font-semibold text-yellow-400">{{ $pokemon->level }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-slate-400">Capturado em</p>
                            <p class="font-medium text-slate-300">{{ $pokemon->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if ($pokemon->notes)
                        <div class="rounded-lg border-l-4 border-yellow-400 bg-yellow-500/10 p-4">
                            <h4 class="mb-2 flex items-center font-semibold text-yellow-300">
                                Notas
                            </h4>
                            <p class="italic text-yellow-200">{{ $pokemon->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Seção de Estatísticas -->
                <div class="space-y-6">
                    <!-- Training Section -->
                    <div
                        class="rounded-2xl border border-orange-500/30 bg-gradient-to-br from-orange-600/20 to-amber-600/20 p-6 shadow-xl">
                        <h2 class="pokedex-title mb-4 text-xl text-orange-300">
                            Treinamento
                        </h2>
                        <div class="space-y-3">
                            @if ($pokemon->ev_yield)
                                <div class="flex justify-between border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">Rendimento de EV</span>
                                    <span class="font-semibold text-orange-300">{{ $pokemon->ev_yield }}</span>
                                </div>
                            @endif

                            @if ($pokemon->catch_rate)
                                <div class="flex justify-between border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">Taxa de Captura</span>
                                    <span class="font-semibold text-white">{{ $pokemon->catch_rate }} <span
                                            class="text-xs text-slate-400">({{ number_format(($pokemon->catch_rate / 255) * 100, 1) }}%
                                            com PokéBall, HP cheio)</span></span>
                                </div>
                            @endif

                            @if ($pokemon->base_friendship)
                                <div class="flex justify-between border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">Amizade Base</span>
                                    <span class="font-semibold text-white">{{ $pokemon->base_friendship }} <span
                                            class="text-xs text-slate-400">(normal)</span></span>
                                </div>
                            @endif

                            @if ($pokemon->base_experience)
                                <div class="flex justify-between border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">Exp. Base</span>
                                    <span class="font-semibold text-white">{{ $pokemon->base_experience }}</span>
                                </div>
                            @endif

                            @if ($pokemon->growth_rate)
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Taxa de Crescimento</span>
                                    <span class="font-semibold text-green-400">{{ $pokemon->growth_rate }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Breeding Section -->
                    <div
                        class="rounded-2xl border border-pink-500/30 bg-gradient-to-br from-pink-600/20 to-purple-600/20 p-6 shadow-xl">
                        <h2 class="pokedex-title mb-4 text-xl text-pink-300">
                            Reprodução
                        </h2>
                        <div class="space-y-3">
                            @if ($pokemon->egg_groups && count($pokemon->egg_groups) > 0)
                                <div class="flex items-center justify-between border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">Grupos de Ovos</span>
                                    <div class="flex gap-2">
                                        @foreach ($pokemon->egg_groups as $group)
                                            <span
                                                class="rounded-full border border-pink-500/50 bg-pink-500/30 px-3 py-1 text-xs font-semibold text-pink-300">{{ $group }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-between border-b border-slate-700 pb-2">
                                <span class="text-slate-400">Gênero</span>
                                <div class="flex items-center gap-3">
                                    @if ($pokemon->gender_male_rate !== null && $pokemon->gender_female_rate !== null)
                                        <span class="font-bold text-blue-400">♂
                                            {{ number_format($pokemon->gender_male_rate, 1) }}%</span>
                                        <span class="font-bold text-pink-400">♀
                                            {{ number_format($pokemon->gender_female_rate, 1) }}%</span>
                                    @else
                                        <span class="text-slate-500">Sem gênero</span>
                                    @endif
                                </div>
                            </div>

                            @if ($pokemon->egg_cycles)
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Ciclos de Ovo</span>
                                    <span class="font-semibold text-white">{{ $pokemon->egg_cycles }} <span
                                            class="text-xs text-slate-400">({{ number_format($pokemon->hatch_steps_min) }}–{{ number_format($pokemon->hatch_steps_max) }}
                                            passos)</span></span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Type Defenses Section -->
                    @if ($pokemon->type_defenses && count($pokemon->type_defenses) > 0)
                        <div class="rounded-2xl border border-slate-700 bg-slate-800/50 p-6 shadow-xl backdrop-blur-sm">
                            <h2 class="pokedex-title mb-4 text-xl text-cyan-300">
                                Defesas de Tipo
                            </h2>
                            <p class="mb-4 text-sm text-slate-400">A efetividade de cada tipo contra
                                {{ ucfirst($pokemon->pokemon_name) }}.</p>

                            <div class="grid grid-cols-6 gap-2 sm:grid-cols-9">
                                @foreach ($pokemon->type_defenses as $type => $multiplier)
                                    @php
                                        $bgColor = $typeColors[$type] ?? '#777';
                                        $textColor = 'white';
                                        $multiplierDisplay = '';
                                        $multiplierBg = '';

                                        if ($multiplier == 0) {
                                            $multiplierDisplay = '0';
                                            $multiplierBg = 'bg-black/60';
                                        } elseif ($multiplier == 0.25) {
                                            $multiplierDisplay = '¼';
                                            $multiplierBg = 'bg-green-600';
                                        } elseif ($multiplier == 0.5) {
                                            $multiplierDisplay = '½';
                                            $multiplierBg = 'bg-green-500';
                                        } elseif ($multiplier == 2) {
                                            $multiplierDisplay = '2';
                                            $multiplierBg = 'bg-red-500';
                                        } elseif ($multiplier == 4) {
                                            $multiplierDisplay = '4';
                                            $multiplierBg = 'bg-red-600';
                                        }
                                    @endphp
                                    <div class="flex flex-col items-center">
                                        <div class="w-full rounded-t-md px-1 py-1 text-center text-xs font-bold uppercase text-white"
                                            style="background-color: {{ $bgColor }}">
                                            {{ strtoupper(substr($type, 0, 3)) }}
                                        </div>
                                        @if ($multiplierDisplay)
                                            <div
                                                class="{{ $multiplierBg }} w-full rounded-b-md py-1 text-center text-xs font-bold text-white">
                                                {{ $multiplierDisplay }}
                                            </div>
                                        @else
                                            <div
                                                class="w-full rounded-b-md bg-slate-600 py-1 text-center text-xs font-bold text-slate-400">
                                                -
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Base Stats Section -->
                    <div
                        class="rounded-2xl border border-blue-500/30 bg-gradient-to-r from-blue-600/20 to-blue-500/20 p-6 shadow-xl">
                        <h3 class="pokedex-title mb-4 text-lg text-blue-300">Status Base</h3>
                        <div class="space-y-3">
                            @php
                                $baseStats = [
                                    'HP' => $pokemon->base_hp,
                                    'Ataque' => $pokemon->base_attack,
                                    'Defesa' => $pokemon->base_defense,
                                    'Atq. Esp.' => $pokemon->base_sp_attack,
                                    'Def. Esp.' => $pokemon->base_sp_defense ?? 50,
                                    'Velocidade' => $pokemon->base_speed,
                                ];
                                $totalBaseStats = array_sum($baseStats);
                            @endphp
                            @foreach ($baseStats as $statName => $value)
                                <div class="flex items-center justify-between">
                                    <span class="w-16 text-sm font-medium text-slate-300">{{ $statName }}</span>
                                    <div class="ml-4 flex flex-1 items-center">
                                        <span class="w-8 text-sm font-bold text-blue-400">{{ $value }}</span>
                                        <div class="ml-3 flex-1">
                                            <div class="h-2 overflow-hidden rounded-full bg-slate-700">
                                                <div class="h-full rounded-full bg-blue-500 transition-all duration-500"
                                                    style="width: {{ min(($value / 200) * 100, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="mt-4 flex justify-between border-t border-slate-600 pt-3">
                                <span class="text-sm font-bold text-slate-300">Total</span>
                                <span class="text-sm font-bold text-blue-400">{{ $totalBaseStats }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Training Stats Section -->
                    <div
                        class="rounded-2xl border border-green-500/30 bg-gradient-to-r from-green-600/20 to-emerald-500/20 p-6 shadow-xl">
                        <h3 class="pokedex-title mb-4 text-lg text-green-300">Status do Seu Pokémon</h3>
                        <div class="space-y-3">
                            @php
                                $gameStats = [
                                    'HP' => $pokemon->game_hp,
                                    'Ataque' => $pokemon->game_attack,
                                    'Defesa' => $pokemon->game_defense,
                                    'Atq. Esp.' => $pokemon->game_sp_attack,
                                    'Def. Esp.' => $pokemon->game_sp_defense ?? 50,
                                    'Velocidade' => $pokemon->game_speed,
                                ];
                            @endphp
                            @foreach ($gameStats as $statName => $value)
                                @php
                                    $baseValue = $baseStats[$statName];
                                    $percentage = $baseValue > 0 ? round(($value / $baseValue) * 100) : 0;
                                    $color = $percentage >= 90 ? 'green' : ($percentage >= 70 ? 'yellow' : 'red');
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span class="w-16 text-sm font-medium text-slate-300">{{ $statName }}</span>
                                    <div class="ml-4 flex flex-1 items-center">
                                        <span class="w-8 text-sm font-bold text-green-400">{{ $value }}</span>
                                        <div class="ml-3 flex-1">
                                            <div class="h-2 overflow-hidden rounded-full bg-slate-700">
                                                <div class="bg-{{ $color }}-500 h-full rounded-full transition-all duration-500"
                                                    style="width: {{ min(($value / 250) * 100, 100) }}%"></div>
                                            </div>
                                        </div>
                                        <span
                                            class="text-{{ $color }}-400 ml-2 w-12 text-xs font-semibold">{{ $percentage }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Gráfico Radar -->
                    <div class="rounded-2xl border border-slate-600 bg-slate-700/50 p-6 shadow-xl">
                        <h3 class="pokedex-title mb-4 text-center text-lg text-yellow-300">Visualização de Status</h3>
                        @php
                            $gameStatsForChart = [
                                'game_hp' => $pokemon->game_hp,
                                'game_attack' => $pokemon->game_attack,
                                'game_defense' => $pokemon->game_defense,
                                'game_sp_attack' => $pokemon->game_sp_attack,
                                'game_speed' => $pokemon->game_speed,
                            ];
                        @endphp
                        <x-pokemon-stats-chart :pokemon="$pokemon" :gameStats="$gameStatsForChart" />
                    </div>
                </div>
            </div>

            <!-- Seção de Evoluções -->
            @if ($pokemon->evolution_chain && count($pokemon->evolution_chain) > 1)
                <div
                    class="mt-8 rounded-2xl border border-purple-500/30 bg-gradient-to-br from-purple-600/20 to-indigo-600/20 p-6 shadow-xl">
                    <h3 class="pokedex-title mb-6 text-2xl text-purple-300">Cadeia de Evolução</h3>
                    <div class="flex flex-wrap items-center justify-center gap-4">
                        @foreach ($pokemon->evolution_chain as $index => $evo)
                            <div class="flex items-center">
                                <!-- Pokémon Card -->
                                <div
                                    class="{{ $evo['is_current'] ?? false ? 'bg-purple-500/20 ring-2 ring-purple-500' : 'bg-slate-600/30 hover:bg-slate-600/50' }} flex flex-col items-center rounded-xl p-4 transition-all duration-300">
                                    @if ($evo['sprite'] ?? null)
                                        <img src="{{ $evo['sprite'] }}" alt="{{ $evo['name'] ?? 'Unknown' }}"
                                            class="{{ $evo['is_current'] ?? false ? 'drop-shadow-lg' : 'opacity-80' }} h-28 w-28 object-contain">
                                    @else
                                        <div
                                            class="flex h-28 w-28 items-center justify-center rounded-full bg-slate-600">
                                            <span class="text-4xl">?</span>
                                        </div>
                                    @endif
                                    <span
                                        class="{{ $evo['is_current'] ?? false ? 'text-purple-300' : 'text-slate-300' }} mt-2 text-sm font-semibold">
                                        {{ $evo['name'] ?? 'Unknown' }}
                                    </span>
                                    <span
                                        class="text-xs text-slate-500">#{{ str_pad($evo['id'] ?? 0, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>

                                <!-- Seta e condição de evolução -->
                                @if (!$loop->last)
                                    <div class="mx-3 flex flex-col items-center">
                                        <span class="text-3xl text-purple-400">→</span>
                                        @php
                                            $nextEvo = $pokemon->evolution_chain[$index + 1] ?? null;
                                        @endphp
                                        @if ($nextEvo && ($nextEvo['evolution_details'] ?? null))
                                            <span
                                                class="mt-1 rounded bg-purple-500/30 px-2 py-1 text-xs text-purple-300">
                                                {{ $nextEvo['evolution_details'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Mega Evoluções -->
            @if ($pokemon->mega_evolutions && count($pokemon->mega_evolutions) > 0)
                <div
                    class="mt-6 rounded-2xl border border-pink-500/30 bg-gradient-to-r from-pink-500/20 to-purple-500/20 p-6 shadow-xl">
                    <h3 class="pokedex-title mb-4 flex items-center text-2xl text-pink-300">
                        Mega Evolução Disponível
                    </h3>
                    <div class="flex flex-wrap gap-4">
                        @foreach ($pokemon->mega_evolutions as $mega)
                            <div
                                class="flex items-center gap-2 rounded-lg border border-pink-500/30 bg-pink-500/20 px-5 py-3">
                                <span
                                    class="text-lg font-semibold text-pink-300">{{ $mega['name'] ?? 'Mega Evolução' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Botões de Ação -->
            <div class="mt-8 flex justify-center gap-4">
                <form action="{{ route('pokemons.destroy', $pokemon) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Tem certeza que deseja remover {{ $pokemon->pokemon_name }} da sua Pokédex?')"
                        class="flex items-center gap-2 rounded-lg bg-red-500 px-6 py-3 font-semibold text-white transition-colors hover:bg-red-600">
                        Remover da Pokédex
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
