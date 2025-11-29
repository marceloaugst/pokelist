<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-green-400 to-emerald-500 shadow-lg shadow-green-500/30">
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-white">
                        Capturar Pokémon
                    </h2>
                    <p class="text-red-100">Adicione um novo Pokémon à sua Pokédex</p>
                </div>
            </div>
            <a href="{{ route('pokemons.index') }}"
                class="rounded-lg bg-slate-600 px-4 py-2 font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-slate-500">
                ← Voltar para Pokédex
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm sm:rounded-2xl">
                <div class="p-6 text-slate-200">
                    @if (session('error'))
                    <div
                        class="mb-4 flex items-center gap-2 rounded-xl border border-red-500/50 bg-red-500/20 px-4 py-3 text-red-200 backdrop-blur-sm">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div
                        class="mb-4 rounded-xl border border-red-500/50 bg-red-500/20 px-4 py-3 text-red-200 backdrop-blur-sm">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Busca de Pokémon -->
                    <div
                        class="mb-8 rounded-2xl border border-blue-500/30 bg-gradient-to-r from-blue-600/20 to-indigo-600/20 p-6">
                        <h3 class="mb-4 flex items-center text-2xl font-bold text-blue-300">
                            Pesquisar Pokémon
                        </h3>
                        <p class="mb-4 text-blue-200">Digite o nome ou número do Pokémon que você quer adicionar à sua
                            Pokédex</p>
                        <form method="POST" action="{{ route('pokemons.search') }}" id="searchForm" class="relative">
                            @csrf
                            <div class="flex gap-4">
                                <div class="relative flex-1">
                                    <input type="text" name="pokemon_search" id="pokemon_search"
                                        placeholder="Digite o nome (ex: charmander) ou ID (ex: 4)" required
                                        class="block w-full rounded-xl border-2 border-slate-600 bg-slate-700/50 px-6 py-4 text-lg font-semibold text-white placeholder-slate-400 shadow-sm transition-all focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/20"
                                        value="{{ old('pokemon_search') }}" autocomplete="off">
                                    <p class="mt-2 text-sm text-blue-300">
                                        Digite pelo menos 2 letras para ver sugestões em tempo real
                                    </p>

                                    <!-- Lista de Sugestões -->
                                    <div id="suggestions"
                                        class="absolute left-0 right-0 top-full z-10 hidden max-h-60 overflow-y-auto rounded-md border border-slate-600 bg-slate-800 shadow-lg">
                                    </div>
                                </div>
                                <button type="submit" class="pokedex-button px-8 py-4 text-lg">
                                    Pesquisar
                                </button>
                            </div>
                        </form>
                    </div>

                    @isset($pokemonData)
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

                    <!-- Card do Pokémon Encontrado - Estilo Pokémon Database -->
                    <div class="mb-8 rounded-2xl border border-slate-600 bg-slate-800/80 p-8 shadow-xl">
                        <!-- Header com nome e imagem -->
                        <div class="flex flex-col items-center gap-8 lg:flex-row lg:items-start">
                            <!-- Imagem do Pokémon -->
                            @if ($pokemonData['sprite'])
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div
                                        class="flex h-72 w-72 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-700/50 to-slate-800/50 shadow-inner">
                                        <img src="{{ $pokemonData['sprite'] }}" alt="{{ $pokemonData['name'] }}"
                                            class="h-64 w-64 object-contain drop-shadow-2xl transition-transform duration-300 hover:scale-110">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="flex-1 space-y-6">
                                <!-- Nome e Título -->
                                <div>
                                    <h2 class="pokedex-title text-4xl text-white">{{ ucfirst($pokemonData['name']) }}
                                    </h2>
                                    <p class="text-lg text-slate-400">{{ $pokemonData['species'] ?? 'Unknown Pokémon' }}
                                    </p>
                                </div>

                                <!-- Grid: Pokédex Data e Training -->
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- Pokédex Data -->
                                    <div class="rounded-xl bg-slate-700/30 p-5">
                                        <h3 class="pokedex-title mb-4 text-xl text-blue-300">Dados da Pokédex</h3>
                                        <table class="pokedex-table">
                                            <tr>
                                                <th>Nº Nacional</th>
                                                <td><span class="font-bold text-blue-400">{{ str_pad($pokemonData['id'],
                                                        4, '0', STR_PAD_LEFT) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tipo</th>
                                                <td>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach ($pokemonData['types'] as $type)
                                                        <span class="type-badge rounded px-3 py-1"
                                                            style="background-color: {{ $typeColors[$type] ?? '#777' }}">
                                                            {{ strtoupper($type) }}
                                                        </span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Espécie</th>
                                                <td>{{ $pokemonData['species'] ?? 'Desconhecido' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Altura</th>
                                                <td>{{ number_format($pokemonData['height'], 1) }} m
                                                    ({{ floor($pokemonData['height'] * 3.28084) }}'{{
                                                    str_pad(round(($pokemonData['height'] * 39.3701) % 12), 2, '0',
                                                    STR_PAD_LEFT) }}")
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Peso</th>
                                                <td>{{ number_format($pokemonData['weight'], 1) }} kg
                                                    ({{ number_format($pokemonData['weight'] * 2.20462, 1) }} lbs)</td>
                                            </tr>
                                            <tr>
                                                <th>Habilidades</th>
                                                <td>
                                                    @if (!empty($pokemonData['abilities']))
                                                    @foreach ($pokemonData['abilities'] as $index => $ability)
                                                    <div>
                                                        <span class="text-slate-400">{{ $index + 1 }}.</span>
                                                        <span
                                                            class="{{ $ability['is_hidden'] ? 'text-purple-400' : 'text-blue-400' }}">{{
                                                            $ability['name'] }}</span>
                                                        @if ($ability['is_hidden'])
                                                        <span class="text-xs text-slate-500">(habilidade
                                                            oculta)</span>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                            @if (!empty($pokemonData['pokedex_numbers']))
                                            <tr>
                                                <th>Nº Local</th>
                                                <td class="text-sm">
                                                    @php
                                                    $localNumbers = collect($pokemonData['pokedex_numbers'])
                                                    ->filter(fn($v, $k) => $k !== 'national')
                                                    ->take(5);
                                                    @endphp
                                                    @foreach ($localNumbers as $dex => $number)
                                                    <span class="text-slate-300">{{ str_pad($number, 3, '0',
                                                        STR_PAD_LEFT) }}</span>
                                                    <span class="text-slate-500">({{ ucwords(str_replace('-', '/',
                                                        $dex)) }})</span>
                                                    @if (!$loop->last)
                                                    ,
                                                    @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>

                                    <!-- Training -->
                                    <div class="rounded-xl bg-slate-700/30 p-5">
                                        <h3 class="pokedex-title mb-4 text-xl text-orange-300">Treinamento</h3>
                                        <table class="pokedex-table">
                                            <tr>
                                                <th>Rendimento de EV</th>
                                                <td><span class="text-green-400">{{ $pokemonData['ev_yield'] }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Taxa de Captura</th>
                                                <td>
                                                    {{ $pokemonData['catch_rate'] }}
                                                    <span class="text-xs text-slate-500">({{
                                                        number_format(($pokemonData['catch_rate'] / 255) * 100, 1) }}%
                                                        com PokéBall, HP cheio)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Amizade Base</th>
                                                <td>
                                                    {{ $pokemonData['base_friendship'] }}
                                                    <span class="text-xs text-slate-500">(normal)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Exp. Base</th>
                                                <td>{{ $pokemonData['base_experience'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Taxa de Crescimento</th>
                                                <td><span class="text-cyan-400">{{ $pokemonData['growth_rate'] }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Fraquezas -->
                                <div class="rounded-xl bg-slate-700/30 p-5">
                                    <h3 class="pokedex-title mb-3 text-xl text-red-300">Fraquezas</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($pokemonData['weaknesses'] as $weakness)
                                        <span class="type-badge rounded px-3 py-1"
                                            style="background-color: {{ $typeColors[$weakness] ?? '#777' }}">
                                            {{ strtoupper($weakness) }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seção de Evoluções -->
                        @if (!empty($pokemonData['evolution_chain']) && count($pokemonData['evolution_chain']) > 1)
                        <div class="mt-8 rounded-xl bg-slate-700/30 p-6">
                            <h3 class="pokedex-title mb-6 text-xl text-purple-300">Cadeia de Evolução</h3>
                            <div class="flex flex-wrap items-center justify-center gap-4">
                                @foreach ($pokemonData['evolution_chain'] as $index => $evo)
                                <div class="flex items-center">
                                    <!-- Pokémon Card -->
                                    <div
                                        class="{{ $evo['is_current'] ? 'bg-blue-500/20 ring-2 ring-blue-500' : 'bg-slate-600/30 hover:bg-slate-600/50' }} flex flex-col items-center rounded-xl p-4 transition-all duration-300">
                                        @if ($evo['sprite'])
                                        <img src="{{ $evo['sprite'] }}" alt="{{ $evo['name'] }}"
                                            class="{{ $evo['is_current'] ? 'drop-shadow-lg' : 'opacity-80' }} h-24 w-24 object-contain">
                                        @else
                                        <div
                                            class="flex h-24 w-24 items-center justify-center rounded-full bg-slate-600">
                                            <span class="text-3xl">?</span>
                                        </div>
                                        @endif
                                        <span
                                            class="{{ $evo['is_current'] ? 'text-blue-300' : 'text-slate-300' }} mt-2 text-sm font-semibold">
                                            {{ $evo['name'] }}
                                        </span>
                                        <span class="text-xs text-slate-500">#{{ str_pad($evo['id'], 3, '0',
                                            STR_PAD_LEFT) }}</span>
                                    </div>

                                    <!-- Seta e condição de evolução -->
                                    @if (!$loop->last)
                                    <div class="mx-2 flex flex-col items-center">
                                        <span class="text-2xl text-slate-500">→</span>
                                        @php
                                        $nextEvo =
                                        $pokemonData['evolution_chain'][$index + 1] ?? null;
                                        @endphp
                                        @if ($nextEvo && $nextEvo['evolution_details'])
                                        <span class="mt-1 rounded bg-slate-600 px-2 py-1 text-xs text-slate-300">
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
                        @if (!empty($pokemonData['mega_evolutions']))
                        <div class="mt-6 rounded-xl bg-gradient-to-r from-pink-500/20 to-purple-500/20 p-6">
                            <h3 class="pokedex-title mb-4 flex items-center text-xl text-pink-300">
                                Mega Evolução Disponível
                            </h3>
                            <div class="flex flex-wrap gap-4">
                                @foreach ($pokemonData['mega_evolutions'] as $mega)
                                <div class="flex items-center gap-2 rounded-lg bg-pink-500/20 px-4 py-2">
                                    <span class="font-semibold text-pink-300">{{ $mega['name'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Layout Principal: Gráfico + Formulário -->
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                        <!-- Gráfico Interativo -->
                        <div class="lg:order-1">
                            <div class="sticky top-6">
                                <div id="interactive-chart"
                                    class="rounded-lg border border-slate-600 bg-slate-700/50 p-6">
                                    <h3 class="mb-4 text-center text-xl font-bold text-white">Visualização dos Status
                                    </h3>
                                    <p class="mb-6 text-center text-sm text-slate-300">
                                        Os valores em <span class="font-semibold text-blue-400">azul</span> são os
                                        stats
                                        base do Pokémon.<br>
                                        Os valores em <span class="font-semibold text-red-400">vermelho</span> são os
                                        stats que você digitar.
                                    </p>

                                    <!-- SVG Interactive Chart -->
                                    <div class="relative mx-auto" style="width: 350px; height: 350px;">
                                        <svg id="stats-chart" width="350" height="350" viewBox="0 0 350 350"
                                            class="absolute inset-0">
                                            <!-- Grid circles -->
                                            @for ($i = 1; $i
                                            <= 5; $i++) <circle cx="175" cy="175" r="{{ $i * 25 }}" fill="none"
                                                stroke="#475569" stroke-width="1" />
                                            @endfor

                                            <!-- Axis lines -->
                                            <line x1="175" y1="175" x2="175" y2="50" stroke="#64748b"
                                                stroke-width="1" />
                                            <line x1="175" y1="175" x2="280" y2="113" stroke="#64748b"
                                                stroke-width="1" />
                                            <line x1="175" y1="175" x2="237" y2="263" stroke="#64748b"
                                                stroke-width="1" />
                                            <line x1="175" y1="175" x2="113" y2="263" stroke="#64748b"
                                                stroke-width="1" />
                                            <line x1="175" y1="175" x2="70" y2="113" stroke="#64748b"
                                                stroke-width="1" />

                                            <!-- Base stats polygon (blue) -->
                                            <polygon id="base-polygon" fill="rgba(59, 130, 246, 0.3)" stroke="#3b82f6"
                                                stroke-width="2" />

                                            <!-- Game stats polygon (red) -->
                                            <polygon id="game-polygon" fill="rgba(239, 68, 68, 0.3)" stroke="#ef4444"
                                                stroke-width="3" />

                                            <!-- Base stat points -->
                                            <g id="base-points"></g>

                                            <!-- Game stat points -->
                                            <g id="game-points"></g>
                                        </svg>

                                        <!-- Stat Labels -->
                                        <div class="pointer-events-none absolute inset-0">
                                            <div class="absolute"
                                                style="top: 20px; left: 50%; transform: translateX(-50%);">
                                                <span class="text-sm font-semibold text-slate-300">Velocidade</span>
                                            </div>
                                            <div class="absolute" style="top: 80px; right: 15px;">
                                                <span class="text-sm font-semibold text-slate-300">Ataque</span>
                                            </div>
                                            <div class="absolute" style="bottom: 60px; right: 70px;">
                                                <span class="text-sm font-semibold text-slate-300">Atq. Esp.</span>
                                            </div>
                                            <div class="absolute" style="bottom: 60px; left: 70px;">
                                                <span class="text-sm font-semibold text-slate-300">Defesa</span>
                                            </div>
                                            <div class="absolute" style="top: 80px; left: 15px;">
                                                <span class="text-sm font-semibold text-slate-300">HP</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Legend -->
                                    <div class="mt-4 flex justify-center gap-6">
                                        <div class="flex items-center gap-2">
                                            <div class="h-4 w-4 rounded bg-blue-500"></div>
                                            <span class="text-sm font-medium">Stats Base</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="h-4 w-4 rounded bg-red-500"></div>
                                            <span class="text-sm font-medium">Seus Stats</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulário -->
                        <div class="lg:order-2">
                            <form method="POST" action="{{ route('pokemons.store') }}" class="space-y-6"
                                id="pokemonForm">
                                @csrf

                                <input type="hidden" name="pokemon_id" value="{{ $pokemonData['id'] }}">
                                <input type="hidden" name="pokemon_name" value="{{ $pokemonData['name'] }}">
                                <input type="hidden" name="sprite_url" value="{{ $pokemonData['sprite'] }}">

                                @foreach ($pokemonData['stats'] as $statName => $statValue)
                                <input type="hidden" name="base_{{ $statName }}" value="{{ $statValue }}">
                                @endforeach

                                <!-- Novos dados da Pokédex -->
                                <input type="hidden" name="species" value="{{ $pokemonData['species'] ?? '' }}">
                                <input type="hidden" name="height" value="{{ $pokemonData['height'] ?? '' }}">
                                <input type="hidden" name="weight" value="{{ $pokemonData['weight'] ?? '' }}">
                                <input type="hidden" name="abilities"
                                    value="{{ json_encode($pokemonData['abilities'] ?? []) }}">
                                <input type="hidden" name="types"
                                    value="{{ json_encode($pokemonData['types'] ?? []) }}">
                                <input type="hidden" name="ev_yield" value="{{ $pokemonData['ev_yield'] ?? '' }}">
                                <input type="hidden" name="catch_rate" value="{{ $pokemonData['catch_rate'] ?? '' }}">
                                <input type="hidden" name="base_friendship"
                                    value="{{ $pokemonData['base_friendship'] ?? '' }}">
                                <input type="hidden" name="base_experience"
                                    value="{{ $pokemonData['base_experience'] ?? '' }}">
                                <input type="hidden" name="growth_rate" value="{{ $pokemonData['growth_rate'] ?? '' }}">
                                <input type="hidden" name="egg_groups"
                                    value="{{ json_encode($pokemonData['egg_groups'] ?? []) }}">
                                <input type="hidden" name="gender_male_rate"
                                    value="{{ $pokemonData['gender_male_rate'] ?? '' }}">
                                <input type="hidden" name="gender_female_rate"
                                    value="{{ $pokemonData['gender_female_rate'] ?? '' }}">
                                <input type="hidden" name="egg_cycles" value="{{ $pokemonData['egg_cycles'] ?? '' }}">
                                <input type="hidden" name="hatch_steps_min"
                                    value="{{ $pokemonData['hatch_steps_min'] ?? '' }}">
                                <input type="hidden" name="hatch_steps_max"
                                    value="{{ $pokemonData['hatch_steps_max'] ?? '' }}">
                                <input type="hidden" name="type_defenses"
                                    value="{{ json_encode($pokemonData['type_defenses'] ?? []) }}">
                                <input type="hidden" name="evolution_chain"
                                    value="{{ json_encode($pokemonData['evolution_chain'] ?? []) }}">
                                <input type="hidden" name="mega_evolutions"
                                    value="{{ json_encode($pokemonData['mega_evolutions'] ?? []) }}">
                                <input type="hidden" name="pokedex_numbers"
                                    value="{{ json_encode($pokemonData['pokedex_numbers'] ?? []) }}">

                                <!-- Informações do Jogo -->
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="game_name"
                                            class="mb-2 block text-sm font-medium text-slate-300">Nome do Jogo
                                            *</label>
                                        <select name="game_name" id="game_name" required
                                            class="block w-full rounded-md border-slate-600 bg-slate-700/50 text-white shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                            <option value="">Selecione o jogo...</option>
                                            @foreach($games as $gameKey => $gameName)
                                            <option value="{{ $gameKey }}" {{ old('game_name')==$gameKey ? 'selected'
                                                : '' }}>
                                                {{ $gameName }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="level" class="mb-2 block text-sm font-medium text-slate-300">Nível
                                            *</label>
                                        <input type="number" name="level" id="level" min="1" max="100" value="1"
                                            required
                                            class="block w-full rounded-md border-slate-600 bg-slate-700/50 text-white shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                    </div>
                                </div>

                                <!-- Stats Inputs -->
                                <div>
                                    <h3 class="mb-4 text-xl font-bold text-white">Status do seu Pokémon</h3>
                                    <p class="mb-4 text-sm text-slate-400">Digite os valores dos stats do seu Pokémon
                                        capturado:</p>

                                    <div class="space-y-6">
                                        @foreach (['hp' => 'HP', 'attack' => 'Ataque', 'defense' => 'Defesa',
                                        'sp_attack' => 'Ataque Especial', 'speed' => 'Velocidade'] as $statName =>
                                        $statLabel)
                                        @php $baseValue = $pokemonData['stats'][$statName]; @endphp

                                        <div class="stat-input-group rounded-lg border border-slate-600 bg-slate-700/30 p-4"
                                            data-stat="{{ $statName }}" data-base="{{ $baseValue }}">
                                            <div class="mb-3 flex items-center justify-between">
                                                <h4 class="text-lg font-semibold text-white">{{ $statLabel }}
                                                </h4>
                                                <div class="text-right">
                                                    <div class="text-sm text-slate-400">Base: <span
                                                            class="font-bold text-blue-400">{{ $baseValue }}</span>
                                                    </div>
                                                    <div class="comparison-result text-sm">
                                                        <span class="percentage font-bold text-slate-500">-%</span>
                                                        <span class="status text-slate-500">Aguardando...</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex gap-4">
                                                <div class="flex-1">
                                                    <input type="number" name="game_{{ $statName }}"
                                                        id="game_{{ $statName }}" min="0" max="999" required
                                                        placeholder="Digite o valor"
                                                        class="game-stat block w-full rounded-md border-slate-600 bg-slate-600/50 text-lg font-bold text-white placeholder-slate-400 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                                                        oninput="updateComparison(this, {{ $baseValue }}, '{{ $statName }}')">
                                                </div>
                                            </div>

                                            <div class="progress-container mt-3">
                                                <div
                                                    class="progress-bar h-3 w-full overflow-hidden rounded-full bg-slate-600">
                                                    <div class="progress-fill h-3 rounded-full bg-slate-500 transition-all duration-300"
                                                        style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                        <!-- SP Defense Input -->
                                        @php $spDefenseBase = $pokemonData['stats']['sp_defense'] ?? 50; @endphp
                                        <div class="stat-input-group rounded-lg border border-slate-600 bg-slate-700/30 p-4"
                                            data-stat="sp_defense" data-base="{{ $spDefenseBase }}">
                                            <div class="mb-3 flex items-center justify-between">
                                                <h4 class="text-lg font-semibold text-white">Defesa Especial</h4>
                                                <div class="text-right">
                                                    <div class="text-sm text-slate-400">Base: <span
                                                            class="font-bold text-blue-400">{{ $spDefenseBase }}</span>
                                                    </div>
                                                    <div class="comparison-result text-sm">
                                                        <span class="percentage font-bold text-slate-500">-%</span>
                                                        <span class="status text-slate-500">Aguardando...</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex gap-4">
                                                <div class="flex-1">
                                                    <input type="number" name="game_sp_defense" id="game_sp_defense"
                                                        min="0" max="999" placeholder="Digite o valor"
                                                        class="game-stat block w-full rounded-md border-slate-600 bg-slate-600/50 text-lg font-bold text-white placeholder-slate-400 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                                                        oninput="updateComparison(this, {{ $spDefenseBase }}, 'sp_defense')">
                                                </div>
                                            </div>

                                            <div class="progress-container mt-3">
                                                <div
                                                    class="progress-bar h-3 w-full overflow-hidden rounded-full bg-slate-600">
                                                    <div class="progress-fill h-3 rounded-full bg-slate-500 transition-all duration-300"
                                                        style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="base_sp_defense" value="{{ $spDefenseBase }}">
                                    </div>
                                </div>

                                <!-- Notas -->
                                <div>
                                    <label for="notes" class="mb-2 block text-sm font-medium text-slate-300">Notas
                                        (opcional)</label>
                                    <textarea name="notes" id="notes" rows="3"
                                        class="block w-full rounded-md border-slate-600 bg-slate-700/50 text-white placeholder-slate-400 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                                        placeholder="Observações sobre este Pokémon..."></textarea>
                                </div>

                                <div class="flex gap-4 pt-6">
                                    <button type="submit"
                                        class="flex flex-1 transform items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-4 text-lg font-bold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:from-green-600 hover:to-emerald-700">
                                        Adicionar à Pokédex
                                    </button>
                                    <a href="{{ route('pokemons.index') }}"
                                        class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-gray-500 px-8 py-4 text-center text-lg font-bold text-white transition-colors hover:bg-gray-600">
                                        Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- JavaScript for Interactive Chart -->
                    <script>
                        const pokemonStats = {
                                hp: {{ $pokemonData['stats']['hp'] }},
                                attack: {{ $pokemonData['stats']['attack'] }},
                                defense: {{ $pokemonData['stats']['defense'] }},
                                sp_attack: {{ $pokemonData['stats']['sp_attack'] }},
                                speed: {{ $pokemonData['stats']['speed'] }},
                                sp_defense: {{ $pokemonData['stats']['sp_defense'] ?? 50 }}
                            };

                            const gameStats = {
                                hp: 0,
                                attack: 0,
                                defense: 0,
                                sp_attack: 0,
                                speed: 0,
                                sp_defense: 0
                            };

                            const center = {
                                x: 175,
                                y: 175
                            };
                            const radius = 125;
                            const maxStat = 200;

                            // Initialize chart
                            function initChart() {
                                updateChart();
                            }

                            function updateChart() {
                                const statOrder = ['speed', 'attack', 'sp_attack', 'defense', 'hp'];
                                const angleStep = (2 * Math.PI) / 5;
                                const startAngle = -Math.PI / 2;

                                // Calculate base polygon points
                                const basePoints = [];
                                const gamePoints = [];

                                statOrder.forEach((stat, index) => {
                                    const angle = startAngle + (index * angleStep);

                                    // Base stats
                                    const baseScale = Math.min(pokemonStats[stat] / maxStat, 1);
                                    const baseX = center.x + (radius * baseScale * Math.cos(angle));
                                    const baseY = center.y + (radius * baseScale * Math.sin(angle));
                                    basePoints.push(`${baseX.toFixed(1)},${baseY.toFixed(1)}`);

                                    // Game stats
                                    const gameScale = Math.min(gameStats[stat] / maxStat, 1);
                                    const gameX = center.x + (radius * gameScale * Math.cos(angle));
                                    const gameY = center.y + (radius * gameScale * Math.sin(angle));
                                    gamePoints.push(`${gameX.toFixed(1)},${gameY.toFixed(1)}`);
                                });

                                // Update polygons
                                document.getElementById('base-polygon').setAttribute('points', basePoints.join(' '));
                                document.getElementById('game-polygon').setAttribute('points', gamePoints.join(' '));

                                // Update points
                                updatePoints('base-points', statOrder, pokemonStats, '#3b82f6');
                                updatePoints('game-points', statOrder, gameStats, '#ef4444');
                            }

                            function updatePoints(containerId, statOrder, stats, color) {
                                const container = document.getElementById(containerId);
                                container.innerHTML = '';

                                const angleStep = (2 * Math.PI) / 5;
                                const startAngle = -Math.PI / 2;

                                statOrder.forEach((stat, index) => {
                                    const angle = startAngle + (index * angleStep);
                                    const scale = Math.min(stats[stat] / maxStat, 1);
                                    const x = center.x + (radius * scale * Math.cos(angle));
                                    const y = center.y + (radius * scale * Math.sin(angle));

                                    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                                    circle.setAttribute('cx', x);
                                    circle.setAttribute('cy', y);
                                    circle.setAttribute('r', '4');
                                    circle.setAttribute('fill', color);
                                    circle.setAttribute('stroke', 'white');
                                    circle.setAttribute('stroke-width', '2');

                                    container.appendChild(circle);
                                });
                            }

                            function updateComparison(input, baseValue, statName) {
                                const gameValue = parseInt(input.value) || 0;
                                const container = input.closest('.stat-input-group');
                                const percentage = baseValue > 0 ? Math.round((gameValue / baseValue) * 100) : 0;

                                // Update game stats for chart
                                gameStats[statName] = gameValue;
                                updateChart();

                                // Update progress bars and colors
                                const percentageEl = container.querySelector('.percentage');
                                const statusEl = container.querySelector('.status');
                                const progressFill = container.querySelector('.progress-fill');

                                percentageEl.textContent = percentage + '%';

                                let color, bgColor, status;

                                if (percentage >= 90) {
                                    color = 'text-green-600';
                                    bgColor = 'bg-green-500';
                                    status = 'Excelente!';
                                } else if (percentage >= 80) {
                                    color = 'text-green-500';
                                    bgColor = 'bg-green-400';
                                    status = 'Muito Bom';
                                } else if (percentage >= 70) {
                                    color = 'text-blue-500';
                                    bgColor = 'bg-blue-400';
                                    status = 'Bom';
                                } else if (percentage >= 60) {
                                    color = 'text-yellow-600';
                                    bgColor = 'bg-yellow-400';
                                    status = 'Médio';
                                } else if (percentage >= 50) {
                                    color = 'text-orange-600';
                                    bgColor = 'bg-orange-400';
                                    status = 'Fraco';
                                } else {
                                    color = 'text-red-600';
                                    bgColor = 'bg-red-500';
                                    status = 'Muito Fraco';
                                }

                                percentageEl.className = `percentage text-sm font-bold ${color}`;
                                statusEl.textContent = status;
                                statusEl.className = `status text-xs font-semibold ${color}`;

                                progressFill.className = `progress-fill h-3 rounded-full transition-all duration-300 ${bgColor}`;
                                progressFill.style.width = Math.min(percentage, 100) + '%';
                            }

                            // Initialize chart on page load
                            document.addEventListener('DOMContentLoaded', initChart);
                    </script>
                    @else
                    <div class="py-12 text-center">
                        <p class="text-xl text-slate-400">Digite o nome ou ID de um Pokémon acima para começar</p>
                        <p class="mt-2 text-sm text-slate-500">Exemplos: "pikachu", "charizard", "25", "150"</p>
                    </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <script>
        let searchTimeout;
        const searchInput = document.getElementById('pokemon_search');
        const suggestionsDiv = document.getElementById('suggestions');
        const searchForm = document.getElementById('searchForm');

        // Busca em tempo real
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(searchTimeout);

            if (query.length < 2) {
                hideSuggestions();
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('pokemons.search.suggestions') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        showSuggestions(data);
                    })
                    .catch(error => {
                        console.log('Erro na busca:', error);
                        hideSuggestions();
                    });
            }, 300);
        });

        // Mostrar sugestões
        function showSuggestions(suggestions) {
            if (suggestions.length === 0) {
                hideSuggestions();
                return;
            }

            suggestionsDiv.innerHTML = suggestions.map(pokemon => `
                <div class="suggestion-item p-3 hover:bg-slate-700 cursor-pointer border-b border-slate-700 last:border-b-0 text-slate-200"
                     onclick="selectPokemon('${pokemon.name}', '${pokemon.display_name}')">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">${pokemon.display_name}</span>
                        <span class="text-sm text-slate-400">#${pokemon.id}</span>
                    </div>
                </div>
            `).join('');

            suggestionsDiv.classList.remove('hidden');
        }

        // Esconder sugestões
        function hideSuggestions() {
            suggestionsDiv.classList.add('hidden');
        }

        // Selecionar um Pokémon
        function selectPokemon(pokemonName, displayName) {
            searchInput.value = pokemonName;
            hideSuggestions();
            searchForm.submit();
        }

        // Esconder sugestões ao clicar fora
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#pokemon_search') && !e.target.closest('#suggestions')) {
                hideSuggestions();
            }
        });

        // Navegação com teclado
        let selectedIndex = -1;

        searchInput.addEventListener('keydown', function(e) {
            const items = suggestionsDiv.querySelectorAll('.suggestion-item');

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelection(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(items);
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                items[selectedIndex].click();
            } else if (e.key === 'Escape') {
                hideSuggestions();
                selectedIndex = -1;
            }
        });

        function updateSelection(items) {
            items.forEach((item, index) => {
                item.classList.toggle('bg-slate-600', index === selectedIndex);
            });
        }

        // Verificar se há pokemon_id na URL e carregar automaticamente
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const pokemonId = urlParams.get('pokemon_id');

            if (pokemonId && !{{ isset($pokemonData) ? 'true' : 'false' }}) {
                // Se há pokemon_id mas os dados não foram carregados pelo servidor,
                // preencher o campo de busca e enviar o formulário
                searchInput.value = pokemonId;
                searchForm.submit();
            }
        });
    </script>
</x-app-layout>