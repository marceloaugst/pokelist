<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="pokedex-title text-2xl text-white">
                <span class="bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">#{{
                    $pokemon->pokemon_id ?? 'N/A' }}</span>
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

                    <!-- Mega Evolução Tabs -->
                    <div id="mega-tabs" class="mb-4 hidden">
                        <div class="flex flex-wrap justify-center gap-2">
                            <button
                                class="mega-tab active rounded-lg bg-gradient-to-r from-blue-500 to-cyan-500 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105"
                                data-form="normal">
                                Normal
                            </button>
                            <!-- Mega tabs serão inseridas dinamicamente aqui -->
                        </div>
                    </div>

                    <!-- Imagem do Pokémon -->
                    <div class="relative mb-8">
                        @if ($pokemon->sprite_url)
                        <div
                            class="relative mx-auto flex h-64 w-64 items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-800 shadow-xl ring-4 ring-slate-600">
                            <div class="absolute inset-4 rounded-full bg-gradient-to-br from-slate-600 to-slate-700">
                            </div>
                            <img id="pokemon-sprite" src="{{ $pokemon->sprite_url }}" alt="{{ $pokemon->pokemon_name }}"
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
                                    ({{ number_format($pokemon->height * 3.28084, 0) }}'{{
                                    number_format(($pokemon->height * 39.3701) % 12, 0) }}")</span>
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
                                        class="text-xs text-slate-400">({{ number_format(($pokemon->catch_rate / 255) *
                                        100, 1) }}%
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
                            <div class="flex justify-between border-b border-slate-700 pb-2">
                                <span class="text-slate-400">Taxa de Crescimento</span>
                                <span class="font-semibold text-green-400">{{ $pokemon->growth_rate }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between">
                                <span class="text-slate-400">Movimentos</span>
                                <button id="open-moves-modal"
                                    class="font-semibold text-blue-400 hover:text-blue-300 transition-colors duration-200 cursor-pointer">
                                    Ver Movimentos →
                                </button>
                            </div>
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
                                        class="rounded-full border border-pink-500/50 bg-pink-500/30 px-3 py-1 text-xs font-semibold text-pink-300">{{
                                        $group }}</span>
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
                                        class="text-xs text-slate-400">({{ number_format($pokemon->hatch_steps_min)
                                        }}–{{ number_format($pokemon->hatch_steps_max) }}
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
                                    <span class="text-{{ $color }}-400 ml-2 w-12 text-xs font-semibold">{{ $percentage
                                        }}%</span>
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
                <div id="evolution-chain" class="flex flex-wrap items-center justify-center gap-4">
                    @foreach ($pokemon->evolution_chain as $index => $evo)
                    <div class="flex items-center">
                        <!-- Pokémon Card -->
                        <div class="evolution-pokemon {{ $evo['is_current'] ?? false ? 'bg-purple-500/20 ring-2 ring-purple-500' : 'bg-slate-600/30 hover:bg-slate-600/50' }} flex flex-col items-center rounded-xl p-4 transition-all duration-300 cursor-pointer hover:scale-105"
                            data-pokemon-id="{{ $evo['id'] ?? 0 }}">
                            @if ($evo['sprite'] ?? null)
                            <img src="{{ $evo['sprite'] }}" alt="{{ $evo['name'] ?? 'Unknown' }}"
                                class="{{ $evo['is_current'] ?? false ? 'drop-shadow-lg' : 'opacity-80' }} h-28 w-28 object-contain">
                            @else
                            <div class="flex h-28 w-28 items-center justify-center rounded-full bg-slate-600">
                                <span class="text-4xl">?</span>
                            </div>
                            @endif
                            <span
                                class="{{ $evo['is_current'] ?? false ? 'text-purple-300' : 'text-slate-300' }} mt-2 text-sm font-semibold">
                                {{ $evo['name'] ?? 'Unknown' }}
                            </span>
                            <span class="text-xs text-slate-500">#{{ str_pad($evo['id'] ?? 0, 3, '0', STR_PAD_LEFT)
                                }}</span>
                        </div>

                        <!-- Seta e condição de evolução -->
                        @if (!$loop->last)
                        <div class="mx-3 flex flex-col items-center">
                            <span class="text-3xl text-purple-400">→</span>
                            @php
                            $nextEvo = $pokemon->evolution_chain[$index + 1] ?? null;
                            @endphp
                            @if ($nextEvo && ($nextEvo['evolution_details'] ?? null))
                            <span class="mt-1 rounded bg-purple-500/30 px-2 py-1 text-xs text-purple-300">
                                {{ $nextEvo['evolution_details'] }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Formas Regionais na Evolução -->
                <div id="regional-forms-evolution" class="hidden mt-6">
                    <h4 class="text-lg text-green-300 mb-4 font-semibold">🌍 Formas Regionais</h4>
                    <div id="regional-forms-chain" class="flex flex-wrap items-center justify-center gap-4">
                        <!-- Conteúdo será inserido dinamicamente -->
                    </div>
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
                    <div class="flex items-center gap-2 rounded-lg border border-pink-500/30 bg-pink-500/20 px-5 py-3">
                        <span class="text-lg font-semibold text-pink-300">{{ $mega['name'] ?? 'Mega Evolução' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Seções de Variedades e Movimentos (carregados via JavaScript) -->
            <div id="varieties-section" class="mt-6" style="display: none;">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Mega Evoluções Dinâmicas -->
                    <div id="mega-evolutions-section"
                        class="rounded-2xl border border-pink-500/30 bg-gradient-to-r from-pink-500/20 to-purple-500/20 p-6 shadow-xl"
                        style="display: none;">
                        <h3 class="pokedex-title mb-4 text-2xl text-pink-300">
                            🌟 Mega Evoluções
                        </h3>
                        <div id="mega-evolutions-content"></div>
                    </div>

                    <!-- Formas Regionais -->
                    <div id="regional-forms-section"
                        class="rounded-2xl border border-green-500/30 bg-gradient-to-r from-green-500/20 to-blue-500/20 p-6 shadow-xl"
                        style="display: none;">
                        <h3 class="pokedex-title mb-4 text-2xl text-green-300">
                            🌍 Formas Regionais
                        </h3>
                        <div id="regional-forms-content"></div>
                    </div>
                </div>
            </div>

            <!-- Movimentos Aprendidos -->
            <div id="moves-section"
                class="mt-6 rounded-2xl border border-yellow-500/30 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 p-6 shadow-xl"
                style="display: none;">
                <h3 class="pokedex-title mb-4 text-2xl text-yellow-300">
                    ⚔️ Movimentos Aprendidos por Level Up
                </h3>
                <div class="text-sm text-yellow-200 mb-4">
                    Os movimentos mostrados abaixo são aprendidos conforme o Pokémon sobe de nível.
                </div>
                <div id="moves-loading" class="text-center py-4">
                    <div class="inline-flex items-center gap-2 text-yellow-300">
                        <div class="h-4 w-4 animate-spin rounded-full border-2 border-yellow-500 border-t-transparent">
                        </div>
                        Carregando movimentos...
                    </div>
                </div>
                <div id="moves-content" class="max-h-96 overflow-y-auto"></div>
            </div>

            <!-- Botões de Ação -->
            <div class="mt-8 flex justify-center gap-4">
                <form action="{{ route('pokemons.destroy', $pokemon) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Tem certeza que deseja remover {{ $pokemon->pokemon_name }} da sua Pokédx?')"
                        class="flex items-center gap-2 rounded-lg bg-red-500 px-6 py-3 font-semibold text-white transition-colors hover:bg-red-600">
                        Remover da Pokédex
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Movimentos -->
    <div id="moves-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75">
        <div class="relative mx-4 w-full max-w-4xl max-h-[90vh] overflow-hidden rounded-2xl bg-slate-800 shadow-2xl">
            <!-- Header do Modal -->
            <div class="flex items-center justify-between bg-gradient-to-r from-yellow-500 to-orange-500 p-6">
                <h2 class="text-2xl font-bold text-white">⚔️ Movimentos Aprendidos por Level Up</h2>
                <button id="close-moves-modal" class="text-white hover:text-gray-300 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Conteúdo do Modal -->
            <div class="max-h-[70vh] overflow-y-auto p-6">
                <div id="modal-moves-loading" class="text-center py-8">
                    <div class="inline-flex items-center gap-2 text-yellow-300">
                        <div class="h-6 w-6 animate-spin rounded-full border-2 border-yellow-500 border-t-transparent">
                        </div>
                        Carregando movimentos...
                    </div>
                </div>
                <div id="modal-moves-content">
                    <!-- Movimentos serão carregados aqui -->
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPokemonData = {
            id: {{ $pokemon->pokemon_id }},
            name: '{{ $pokemon->pokemon_name }}',
            sprite: '{{ $pokemon->sprite_url }}'
        };
        let megaVarieties = [];
        let regionalForms = [];
        let pokemonMoves = [];

        // Carregar variedades e movimentos quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            loadVarietiesAndMoves(currentPokemonData.id);
            setupEventListeners();
        });

        function setupEventListeners() {
            // Modal de movimentos
            const openModalBtn = document.getElementById('open-moves-modal');
            const closeModalBtn = document.getElementById('close-moves-modal');
            const modal = document.getElementById('moves-modal');

            if (openModalBtn && modal) {
                openModalBtn.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    loadMovesInModal();
                });
            }

            if (closeModalBtn && modal) {
                closeModalBtn.addEventListener('click', function() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });

                // Fechar modal clicando fora
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            }

            // Navegação por evolução
            setupEvolutionNavigation();
        }

        // Função para carregar variedades e movimentos
        async function loadVarietiesAndMoves(pokemonId) {
            try {
                // Carregar variedades (mega evoluções e formas regionais)
                const varietiesResponse = await fetch(`{{ route('pokemon.varieties') }}?id=${pokemonId}`);
                const varieties = await varietiesResponse.json();
                megaVarieties = varieties.mega_evolutions || [];
                regionalForms = varieties.regional_forms || [];

                displayMegaTabs(varieties);
                displayRegionalFormsInEvolution(varieties);

                // Carregar movimentos
                const movesResponse = await fetch(`{{ route('pokemon.moves') }}?id=${pokemonId}`);
                const moves = await movesResponse.json();
                pokemonMoves = moves;

            } catch (error) {
                console.error('Erro ao carregar dados adicionais:', error);
            }
        }

        // Função para exibir abas de mega evolução
        function displayMegaTabs(varieties) {
            const megaTabsContainer = document.getElementById('mega-tabs');
            const tabsContainer = megaTabsContainer.querySelector('div');

            if (varieties.has_mega_evolutions && varieties.mega_evolutions.length > 0) {
                // Adicionar tabs das mega evoluções
                varieties.mega_evolutions.forEach(mega => {
                    const button = document.createElement('button');
                    button.className = 'mega-tab rounded-lg bg-gradient-to-r from-pink-500 to-purple-500 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105';
                    button.setAttribute('data-form', mega.form);
                    button.textContent = mega.name.replace(currentPokemonData.name, '').trim();

                    button.addEventListener('click', () => switchMegaForm(mega.form));
                    tabsContainer.appendChild(button);
                });

                megaTabsContainer.classList.remove('hidden');
            }
        }

        // Função para alternar forma mega
        async function switchMegaForm(form) {
            const sprite = document.getElementById('pokemon-sprite');
            const tabs = document.querySelectorAll('.mega-tab');

            // Atualizar classe ativa das abas
            tabs.forEach(tab => {
                tab.classList.remove('active', 'from-blue-500', 'to-cyan-500');
                tab.classList.add('from-pink-500', 'to-purple-500');

                if (tab.dataset.form === form) {
                    tab.classList.add('active', 'from-blue-500', 'to-cyan-500');
                    tab.classList.remove('from-pink-500', 'to-purple-500');
                }
            });

            // Atualizar sprite
            if (form === 'normal') {
                sprite.src = currentPokemonData.sprite;
            } else {
                try {
                    // Buscar dados da mega evolução
                    const response = await fetch(`{{ route('pokemon.varieties') }}?id=${form}`);
                    if (response.ok) {
                        // Para mega evoluções, usar sprite padrão baseado no ID
                        const megaPokemonId = await getMegaPokemonId(form);
                        if (megaPokemonId) {
                            sprite.src = `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${megaPokemonId}.png`;
                        }
                    }
                } catch (error) {
                    console.error('Erro ao carregar sprite da mega evolução:', error);
                }
            }
        }

        // Função auxiliar para obter ID da mega evolução
        async function getMegaPokemonId(form) {
            const megaIds = {
                'venusaur-mega': 10033,
                'charizard-mega-x': 10034,
                'charizard-mega-y': 10035,
                'blastoise-mega': 10036,
                'beedrill-mega': 10090,
                'pidgeot-mega': 10073,
                'alakazam-mega': 10037,
                'slowbro-mega': 10071,
                'gengar-mega': 10038,
                'kangaskhan-mega': 10039,
                'pinsir-mega': 10040,
                'gyarados-mega': 10041,
                'aerodactyl-mega': 10042,
                'mewtwo-mega-x': 10043,
                'mewtwo-mega-y': 10044,
                'ampharos-mega': 10045,
                'steelix-mega': 10072,
                'scizor-mega': 10046,
                'heracross-mega': 10047,
                'houndoom-mega': 10048,
                'tyranitar-mega': 10049,
                'sceptile-mega': 10065,
                'blaziken-mega': 10050,
                'swampert-mega': 10064,
                'gardevoir-mega': 10051,
                'sableye-mega': 10066,
                'mawile-mega': 10052,
                'aggron-mega': 10053,
                'medicham-mega': 10054,
                'manectric-mega': 10055,
                'sharpedo-mega': 10070,
                'camerupt-mega': 10087,
                'altaria-mega': 10067,
                'banette-mega': 10056,
                'absol-mega': 10057,
                'glalie-mega': 10068,
                'salamence-mega': 10089,
                'metagross-mega': 10076,
                'latias-mega': 10062,
                'latios-mega': 10063,
                'kyogre-primal': 10077,
                'groudon-primal': 10078,
                'rayquaza-mega': 10079,
                'lopunny-mega': 10088,
                'garchomp-mega': 10058,
                'lucario-mega': 10059,
                'abomasnow-mega': 10060,
                'gallade-mega': 10074,
                'audino-mega': 10069,
                'diancie-mega': 10075
            };
            return megaIds[form] || null;
        }

        // Função para exibir formas regionais na cadeia de evolução
        function displayRegionalFormsInEvolution(varieties) {
            const regionalSection = document.getElementById('regional-forms-evolution');
            const regionalChain = document.getElementById('regional-forms-chain');

            if (varieties.has_regional_forms && varieties.regional_forms.length > 0) {
                let regionalHtml = '';
                varieties.regional_forms.forEach(form => {
                    const pokemonId = extractIdFromUrl(form.url);
                    const sprite = `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${pokemonId}.png`;

                    regionalHtml += `
                        <div class="evolution-pokemon flex flex-col items-center rounded-xl p-4 bg-green-500/20 hover:bg-green-500/30 transition-all duration-300 cursor-pointer hover:scale-105 ring-2 ring-green-500"
                             data-pokemon-id="${pokemonId}">
                            <img src="${sprite}" alt="${form.name}" class="h-28 w-28 object-contain drop-shadow-lg">
                            <span class="mt-2 text-sm font-semibold text-green-300">${form.name}</span>
                            <span class="text-xs text-green-400">Forma ${form.region}</span>
                            <span class="text-xs text-slate-500">#${String(pokemonId).padStart(3, '0')}</span>
                        </div>
                    `;
                });

                regionalChain.innerHTML = regionalHtml;
                regionalSection.classList.remove('hidden');

                // Adicionar eventos de clique para formas regionais
                setupEvolutionNavigation();
            }
        }

        // Função para configurar navegação por evolução
        function setupEvolutionNavigation() {
            const evolutionCards = document.querySelectorAll('.evolution-pokemon');

            evolutionCards.forEach(card => {
                card.addEventListener('click', function() {
                    const pokemonId = this.dataset.pokemonId;
                    if (pokemonId && pokemonId !== '0') {
                        // Redirecionar para a rota de busca por ID
                        window.location.href = `{{ route('pokemon.search-by-id') }}?pokemon_id=${pokemonId}`;
                    }
                });
            });
        }

        // Função para carregar movimentos no modal
        function loadMovesInModal() {
            const loadingDiv = document.getElementById('modal-moves-loading');
            const contentDiv = document.getElementById('modal-moves-content');

            loadingDiv.style.display = 'block';
            contentDiv.innerHTML = '';

            if (pokemonMoves && pokemonMoves.length > 0) {
                setTimeout(() => {
                    loadingDiv.style.display = 'none';
                    contentDiv.innerHTML = generateMovesTable(pokemonMoves);
                }, 500);
            } else {
                // Tentar carregar movimentos se ainda não foram carregados
                fetch(`{{ route('pokemon.moves') }}?id=${currentPokemonData.id}`)
                    .then(response => response.json())
                    .then(moves => {
                        pokemonMoves = moves;
                        loadingDiv.style.display = 'none';
                        contentDiv.innerHTML = generateMovesTable(moves);
                    })
                    .catch(error => {
                        loadingDiv.style.display = 'none';
                        contentDiv.innerHTML = '<div class="text-center text-red-400 py-8">Erro ao carregar movimentos.</div>';
                    });
            }
        }

        // Função para gerar tabela de movimentos similar ao print
        function generateMovesTable(moves) {
            if (!moves || moves.length === 0) {
                return '<div class="text-center text-slate-400 py-8 text-lg">Nenhum movimento encontrado para este Pokémon.</div>';
            }

            const typeColors = {
                'normal': '#A8A878', 'fire': '#F08030', 'water': '#6890F0', 'electric': '#F8D030',
                'grass': '#78C850', 'ice': '#98D8D8', 'fighting': '#C03028', 'poison': '#A040A0',
                'ground': '#E0C068', 'flying': '#A890F0', 'psychic': '#F85888', 'bug': '#A8B820',
                'rock': '#B8A038', 'ghost': '#705898', 'dragon': '#7038F8', 'dark': '#705848',
                'steel': '#B8B8D0', 'fairy': '#EE99AC'
            };

            const categoryIcons = {
                'physical': '<img src="{{ asset("images/move-physical.png") }}" alt="Physical" class="w-5 h-5 inline-block">',
                'special': '<img src="{{ asset("images/move-special.png") }}" alt="Special" class="w-5 h-5 inline-block">',
                'status': '<img src="{{ asset("images/move-status.png") }}" alt="Status" class="w-5 h-5 inline-block">'
            };

            let tableHtml = `
                <div class="bg-slate-700/50 rounded-xl overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white">
                                <th class="px-4 py-3 text-left font-bold">Nv.</th>
                                <th class="px-4 py-3 text-left font-bold">Movimento</th>
                                <th class="px-4 py-3 text-center font-bold">Tipo</th>
                                <th class="px-4 py-3 text-center font-bold">Cat.</th>
                                <th class="px-4 py-3 text-center font-bold">Poder</th>
                                <th class="px-4 py-3 text-center font-bold">Prec.</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            moves.forEach((move, index) => {
                const typeColor = typeColors[move.type] || '#777';
                const categoryIcon = categoryIcons[move.category] || '❓';
                const isEven = index % 2 === 0;

                tableHtml += `
                    <tr class="${isEven ? 'bg-slate-800/50' : 'bg-slate-700/50'} hover:bg-slate-600/50 transition-colors">
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500/20 text-yellow-300 font-bold text-sm">
                                ${move.level_learned}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-white">${move.name}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white"
                                  style="background-color: ${typeColor}">
                                ${move.type.toUpperCase()}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-lg" title="${move.category}">${categoryIcon}</span>
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-white">
                            ${move.power || '—'}
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-white">
                            ${move.accuracy ? move.accuracy + '%' : '—'}
                        </td>
                    </tr>
                `;
            });

            tableHtml += `
                        </tbody>
                    </table>
                </div>
            `;

            return tableHtml;
        }

        // Função auxiliar para extrair ID da URL
        function extractIdFromUrl(url) {
            const matches = url.match(/\/(\d+)\/?$/);
            return matches ? matches[1] : null;
        }
    </script>
</x-app-layout>