<x-public-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('pokedex.index') }}" 
                   class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-700 hover:bg-slate-600 transition-colors duration-200">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-3xl font-bold text-white">
                        {{ $pokemonData['name'] }}
                    </h2>
                    <p class="text-blue-100">#{{ str_pad($pokemonData['id'], 3, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            
            <!-- Card Principal do Pokémon -->
            <div class="pokemon-detail-card mb-8 overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm rounded-2xl">
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Sprite e Informações Básicas -->
                        <div class="text-center">
                            <div class="pokemon-sprite-container relative mx-auto mb-6 flex h-48 w-48 items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-800 shadow-lg shadow-slate-900/50 ring-4 ring-slate-600">
                                <div class="absolute inset-4 rounded-full bg-gradient-to-br from-slate-600 to-slate-700"></div>
                                @if($pokemonData['sprite'])
                                    <img src="{{ $pokemonData['sprite'] }}" alt="{{ $pokemonData['name'] }}" 
                                         class="relative z-10 h-40 w-40 object-contain drop-shadow-lg">
                                @else
                                    <span class="text-6xl text-slate-500">?</span>
                                @endif
                            </div>
                            
                            <!-- Tipos -->
                            <div class="mb-4 flex flex-wrap justify-center gap-2">
                                @foreach($pokemonData['types'] as $type)
                                    <span class="type-badge-hover rounded-full px-4 py-2 text-sm font-semibold text-white shadow-lg"
                                          style="background-color: {{ $pokemonData['type_colors'][$type] ?? '#777' }}">
                                        {{ ucfirst($type) }}
                                    </span>
                                @endforeach
                            </div>

                            <!-- Dados Físicos -->
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div class="rounded-lg bg-slate-700/50 p-4">
                                    <div class="text-sm text-slate-400">Altura</div>
                                    <div class="text-xl font-bold text-white">{{ number_format($pokemonData['height'] / 10, 1) }}m</div>
                                </div>
                                <div class="rounded-lg bg-slate-700/50 p-4">
                                    <div class="text-sm text-slate-400">Peso</div>
                                    <div class="text-xl font-bold text-white">{{ number_format($pokemonData['weight'] / 10, 1) }}kg</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Base -->
                        <div class="lg:col-span-2">
                            <h3 class="mb-6 text-2xl font-bold text-white">Status Base</h3>
                            <div class="space-y-4">
                                @php
                                    $stats = [
                                        'hp' => ['label' => 'HP', 'color' => 'bg-red-500'],
                                        'attack' => ['label' => 'Ataque', 'color' => 'bg-orange-500'],
                                        'defense' => ['label' => 'Defesa', 'color' => 'bg-yellow-500'],
                                        'sp_attack' => ['label' => 'Ataque Especial', 'color' => 'bg-blue-500'],
                                        'sp_defense' => ['label' => 'Defesa Especial', 'color' => 'bg-green-500'],
                                        'speed' => ['label' => 'Velocidade', 'color' => 'bg-pink-500']
                                    ];
                                @endphp

                                @foreach($stats as $stat => $config)
                                    @php $value = $pokemonData['stats'][$stat] @endphp
                                    <div class="flex items-center gap-4">
                                        <div class="w-32 text-sm font-medium text-slate-300">{{ $config['label'] }}</div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-1 bg-slate-700 rounded-full h-3 overflow-hidden">
                                                    <div class="{{ $config['color'] }} status-bar h-full rounded-full transition-all duration-1000 ease-out"
                                                         style="width: {{ min(($value / 255) * 100, 100) }}%"></div>
                                                </div>
                                                <span class="text-white font-bold w-12 text-right">{{ $value }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Total -->
                                @php $total = array_sum($pokemonData['stats']) @endphp
                                <div class="border-t border-slate-600 pt-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-32 text-sm font-bold text-white">Total</div>
                                        <div class="flex-1">
                                            <span class="text-xl font-bold text-white">{{ $total }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid de Informações Detalhadas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                
                <!-- Treinamento -->
                <div class="pokemon-detail-card overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm rounded-2xl">
                    <div class="p-6">
                        <h3 class="mb-4 text-xl font-bold text-white flex items-center gap-2">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Treinamento
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-300">Taxa de Captura</span>
                                <span class="text-white font-semibold">{{ $pokemonData['catch_rate'] ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-slate-300">Amizade Base</span>
                                <span class="text-white font-semibold">{{ $pokemonData['base_friendship'] ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-slate-300">EXP Base</span>
                                <span class="text-white font-semibold">{{ $pokemonData['base_experience'] ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-slate-300">Grupo de Crescimento</span>
                                <span class="text-white font-semibold">{{ ucfirst(str_replace('-', ' ', $pokemonData['growth_rate'] ?? 'N/A')) }}</span>
                            </div>
                            
                            @if(isset($pokemonData['ev_yield']) && !empty($pokemonData['ev_yield']))
                                <div>
                                    <span class="text-slate-300 block mb-2">EV Yield</span>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($pokemonData['ev_yield'] as $stat => $value)
                                            @if($value > 0)
                                                <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs">
                                                    {{ ucfirst(str_replace('_', ' ', $stat)) }}: +{{ $value }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Fraquezas -->
                <div class="pokemon-detail-card overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm rounded-2xl">
                    <div class="p-6">
                        <h3 class="mb-4 text-xl font-bold text-white flex items-center gap-2">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Defesas de Tipos
                        </h3>
                        
                        @if(isset($pokemonData['type_defenses']) && !empty($pokemonData['type_defenses']))
                            <div class="space-y-3">
                                @php
                                    $defenseGroups = [
                                        'Super Eficaz (2x)' => collect($pokemonData['type_defenses'])->filter(fn($v) => $v == 2)->keys(),
                                        'Muito Eficaz (4x)' => collect($pokemonData['type_defenses'])->filter(fn($v) => $v == 4)->keys(),
                                        'Pouco Eficaz (0.5x)' => collect($pokemonData['type_defenses'])->filter(fn($v) => $v == 0.5)->keys(),
                                        'Muito Pouco Eficaz (0.25x)' => collect($pokemonData['type_defenses'])->filter(fn($v) => $v == 0.25)->keys(),
                                        'Sem Efeito (0x)' => collect($pokemonData['type_defenses'])->filter(fn($v) => $v == 0)->keys(),
                                    ];
                                @endphp

                                @foreach($defenseGroups as $label => $types)
                                    @if($types->isNotEmpty())
                                        <div>
                                            <div class="text-sm text-slate-400 mb-1">{{ $label }}</div>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($types as $type)
                                                    @php
                                                        $bgColor = match($label) {
                                                            'Super Eficaz (2x)', 'Muito Eficaz (4x)' => 'bg-red-600',
                                                            'Pouco Eficaz (0.5x)', 'Muito Pouco Eficaz (0.25x)' => 'bg-green-600',
                                                            'Sem Efeito (0x)' => 'bg-gray-600',
                                                            default => 'bg-slate-600'
                                                        };
                                                    @endphp
                                                    <span class="{{ $bgColor }} text-white px-2 py-1 rounded text-xs">
                                                        {{ ucfirst($type) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-slate-400">Dados de defesa não disponíveis.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cadeia de Evolução -->
            @if(isset($pokemonData['evolution_chain']) && !empty($pokemonData['evolution_chain']))
                <div class="pokemon-detail-card mb-8 overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm rounded-2xl">
                    <div class="p-6">
                        <h3 class="mb-6 text-xl font-bold text-white flex items-center gap-2">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            Cadeia de Evolução
                        </h3>
                        
                        <div class="overflow-x-auto">
                            <div class="flex items-center gap-4 min-w-max">
                                @foreach($pokemonData['evolution_chain'] as $index => $evolution)
                                    <div class="text-center flex-shrink-0">
                                        <div class="relative">
                                            <!-- Destaque para o Pokémon atual -->
                                            @if(strtolower($evolution['name']) === strtolower($pokemonData['name']))
                                                <div class="absolute -inset-2 bg-blue-500/20 rounded-full ring-2 ring-blue-400"></div>
                                            @endif
                                            
                                            <div class="relative w-20 h-20 mx-auto mb-2 flex items-center justify-center rounded-full bg-slate-700">
                                                @if(isset($evolution['sprite']))
                                                    <img src="{{ $evolution['sprite'] }}" alt="{{ $evolution['name'] }}" 
                                                         class="w-16 h-16 object-contain">
                                                @else
                                                    <span class="text-2xl text-slate-400">?</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="text-white font-semibold text-sm">{{ ucfirst($evolution['name']) }}</div>
                                        
                                        @if($evolution['stage'] > 1)
                                            <div class="text-xs text-slate-400 mt-1">
                                                Nível {{ $evolution['level'] ?? '?' }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Seta para próxima evolução -->
                                    @if($index < count($pokemonData['evolution_chain']) - 1)
                                        <div class="flex-shrink-0 text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Habilidades -->
            @if(isset($pokemonData['abilities']) && !empty($pokemonData['abilities']))
                <div class="pokemon-detail-card mb-8 overflow-hidden border border-slate-700 bg-slate-800/50 shadow-xl backdrop-blur-sm rounded-2xl">
                    <div class="p-6">
                        <h3 class="mb-4 text-xl font-bold text-white flex items-center gap-2">
                            <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            Habilidades
                        </h3>
                        
                        <div class="grid gap-3">
                            @foreach($pokemonData['abilities'] as $ability)
                                <div class="flex items-center justify-between bg-slate-700/50 rounded-lg p-3">
                                    <span class="text-white font-medium">{{ ucfirst(str_replace('-', ' ', $ability['name'])) }}</span>
                                    @if($ability['is_hidden'])
                                        <span class="bg-purple-600 text-white px-2 py-1 rounded text-xs">Oculta</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Dados do Pokémon do Usuário (apenas se logado e possuir o pokémon) -->
            @auth
                @if($userPokemon)
                    <div class="overflow-hidden border border-green-600/50 bg-green-900/20 shadow-xl backdrop-blur-sm rounded-2xl">
                        <div class="bg-gradient-to-r from-green-600 to-green-500 px-6 py-4">
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Seu Pokémon
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                <!-- Informações do Jogo -->
                                <div>
                                    <h4 class="text-lg font-semibold text-white mb-3">Informações do Jogo</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-slate-300">Jogo</span>
                                            <span class="text-white font-semibold">{{ $userPokemon->game_name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-300">Nível</span>
                                            <span class="text-white font-bold text-lg">{{ $userPokemon->level }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status do Jogo -->
                                <div class="md:col-span-2">
                                    <h4 class="text-lg font-semibold text-white mb-3">Status no Jogo</h4>
                                    <div class="space-y-3">
                                        @foreach($stats as $stat => $config)
                                            @php
                                                $gameValue = $userPokemon->{"game_$stat"};
                                                $baseValue = $userPokemon->{"base_$stat"};
                                                $percentage = $baseValue > 0 ? ($gameValue / $baseValue) * 100 : 0;
                                            @endphp
                                            <div class="flex items-center gap-4">
                                                <div class="w-32 text-sm font-medium text-slate-300">{{ $config['label'] }}</div>
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex-1 bg-slate-700 rounded-full h-2 overflow-hidden">
                                                            <div class="{{ $config['color'] }} h-full rounded-full transition-all duration-500"
                                                                 style="width: {{ min($percentage, 150) }}%"></div>
                                                        </div>
                                                        <div class="text-right">
                                                            <span class="text-white font-bold">{{ $gameValue }}</span>
                                                            <span class="text-slate-400 text-sm ml-1">({{ number_format($percentage, 1) }}%)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @if($userPokemon->notes)
                                <div class="mt-6 pt-6 border-t border-slate-600">
                                    <h4 class="text-lg font-semibold text-white mb-2">Notas</h4>
                                    <p class="text-slate-300">{{ $userPokemon->notes }}</p>
                                </div>
                            @endif

                            <!-- Botão para ver detalhes completos -->
                            <div class="mt-6 text-center">
                                <a href="{{ route('pokemons.show', $userPokemon) }}" 
                                   class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver Detalhes Completos
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <style>
        /* Animações para as barras de progresso */
        .transition-all {
            transition: all 1s ease-out;
        }
        
        /* Entrada suave dos elementos */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .overflow-hidden > .p-6 > * {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .overflow-hidden > .p-8 > * {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Animação do sprite */
        .pokemon-sprite-container {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .pokemon-sprite-container:hover {
            transform: scale(1.05);
        }

        /* Efeito de gradiente nos cards */
        .pokemon-detail-card {
            background: linear-gradient(135deg,
                    rgba(30, 41, 59, 0.95) 0%,
                    rgba(51, 65, 85, 0.8) 50%,
                    rgba(30, 41, 59, 0.95) 100%);
            backdrop-filter: blur(12px);
        }

        /* Hover effects para os badges */
        .type-badge-hover {
            transition: all 0.2s ease;
        }
        
        .type-badge-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Animação das barras de status */
        .status-bar {
            animation: fillBar 1.5s ease-out;
        }

        @keyframes fillBar {
            from {
                width: 0%;
            }
        }

        /* Responsividade melhorada */
        @media (max-width: 768px) {
            .pokemon-detail-card {
                margin: 0 1rem;
            }
        }
    </style>
</x-public-layout>
