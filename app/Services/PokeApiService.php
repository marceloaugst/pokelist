<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PokeApiService
{
    private const BASE_URL = 'https://pokeapi.co/api/v2';

    // Cores mais claras e harmônicas como no Pokémon Database
    private const TYPE_COLORS = [
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

    // Multiplicadores de dano de cada tipo
    private const TYPE_CHART = [
        'normal' => ['fighting' => 2, 'ghost' => 0],
        'fire' => ['water' => 2, 'ground' => 2, 'rock' => 2, 'fire' => 0.5, 'grass' => 0.5, 'ice' => 0.5, 'bug' => 0.5, 'steel' => 0.5, 'fairy' => 0.5],
        'water' => ['electric' => 2, 'grass' => 2, 'fire' => 0.5, 'water' => 0.5, 'ice' => 0.5, 'steel' => 0.5],
        'electric' => ['ground' => 2, 'electric' => 0.5, 'flying' => 0.5, 'steel' => 0.5],
        'grass' => ['fire' => 2, 'ice' => 2, 'poison' => 2, 'flying' => 2, 'bug' => 2, 'water' => 0.5, 'electric' => 0.5, 'grass' => 0.5, 'ground' => 0.5],
        'ice' => ['fire' => 2, 'fighting' => 2, 'rock' => 2, 'steel' => 2, 'ice' => 0.5],
        'fighting' => ['flying' => 2, 'psychic' => 2, 'fairy' => 2, 'bug' => 0.5, 'rock' => 0.5, 'dark' => 0.5],
        'poison' => ['ground' => 2, 'psychic' => 2, 'grass' => 0.5, 'fighting' => 0.5, 'poison' => 0.5, 'bug' => 0.5, 'fairy' => 0.5],
        'ground' => ['water' => 2, 'grass' => 2, 'ice' => 2, 'poison' => 0.5, 'rock' => 0.5, 'electric' => 0],
        'flying' => ['electric' => 2, 'ice' => 2, 'rock' => 2, 'grass' => 0.5, 'fighting' => 0.5, 'bug' => 0.5, 'ground' => 0],
        'psychic' => ['bug' => 2, 'ghost' => 2, 'dark' => 2, 'fighting' => 0.5, 'psychic' => 0.5],
        'bug' => ['fire' => 2, 'flying' => 2, 'rock' => 2, 'grass' => 0.5, 'fighting' => 0.5, 'ground' => 0.5],
        'rock' => ['water' => 2, 'grass' => 2, 'fighting' => 2, 'ground' => 2, 'steel' => 2, 'normal' => 0.5, 'fire' => 0.5, 'poison' => 0.5, 'flying' => 0.5],
        'ghost' => ['ghost' => 2, 'dark' => 2, 'poison' => 0.5, 'bug' => 0.5, 'normal' => 0, 'fighting' => 0],
        'dragon' => ['ice' => 2, 'dragon' => 2, 'fairy' => 2, 'fire' => 0.5, 'water' => 0.5, 'electric' => 0.5, 'grass' => 0.5],
        'dark' => ['fighting' => 2, 'bug' => 2, 'fairy' => 2, 'ghost' => 0.5, 'dark' => 0.5, 'psychic' => 0],
        'steel' => ['fire' => 2, 'fighting' => 2, 'ground' => 2, 'normal' => 0.5, 'grass' => 0.5, 'ice' => 0.5, 'flying' => 0.5, 'psychic' => 0.5, 'bug' => 0.5, 'rock' => 0.5, 'dragon' => 0.5, 'steel' => 0.5, 'fairy' => 0.5, 'poison' => 0],
        'fairy' => ['poison' => 2, 'steel' => 2, 'fighting' => 0.5, 'bug' => 0.5, 'dark' => 0.5, 'dragon' => 0],
    ];

    public function getPokemon($id)
    {
        try {
            // Aceita tanto número quanto nome
            $endpoint = is_numeric($id) ? $id : strtolower(trim($id));
            $url = self::BASE_URL . "/pokemon/{$endpoint}";

            // Usar timeout menor e verificação SSL desabilitada
            $response = Http::withOptions(['verify' => false])
                ->timeout(5)
                ->retry(2, 100) // Retry 2 vezes com 100ms de delay
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $types = collect($data['types'])->pluck('type.name')->toArray();

                // Buscar sprite do official-artwork
                $sprite = $data['sprites']['other']['official-artwork']['front_default']
                    ?? $data['sprites']['front_default']
                    ?? null;

                // Para a Pokédex nacional, vamos usar dados mais simples e rápidos
                if (!isset($data['species'])) {
                    return $this->getBasicPokemonData($data, $types, $sprite);
                }

                // Buscar dados da espécie para informações adicionais (apenas se necessário)
                $speciesData = $this->getSpeciesData($data['species']['url']);

                // Buscar evoluções
                $evolutionData = $this->getEvolutionChain($speciesData['evolution_chain_url'] ?? null, $data['name']);

                // Verificar mega evoluções
                $megaEvolutions = $this->getMegaEvolutions($data['name']);

                // Calcular type defenses
                $typeDefenses = $this->calculateTypeDefenses($types);

                // Calcular EV yield
                $evYield = $this->calculateEvYield($data['stats']);

                // Processar habilidades
                $abilities = collect($data['abilities'])->map(function ($ability) {
                    return [
                        'name' => ucwords(str_replace('-', ' ', $ability['ability']['name'])),
                        'is_hidden' => $ability['is_hidden'],
                    ];
                })->toArray();

                return [
                    'id' => $data['id'],
                    'name' => ucfirst($data['name']),
                    'sprite' => $sprite,
                    'stats' => [
                        'hp' => $this->getStat($data['stats'], 'hp'),
                        'attack' => $this->getStat($data['stats'], 'attack'),
                        'defense' => $this->getStat($data['stats'], 'defense'),
                        'sp_attack' => $this->getStat($data['stats'], 'special-attack'),
                        'sp_defense' => $this->getStat($data['stats'], 'special-defense'),
                        'speed' => $this->getStat($data['stats'], 'speed'),
                    ],
                    'types' => $types,
                    'type_colors' => collect($types)->mapWithKeys(fn($type) => [$type => self::TYPE_COLORS[$type] ?? '#777'])->toArray(),
                    'weaknesses' => $this->getWeaknesses($types),

                    // Pokédex Data
                    'species' => $speciesData['species'] ?? 'Unknown Pokémon',
                    'height' => $data['height'] / 10, // Converter para metros
                    'weight' => $data['weight'] / 10, // Converter para kg
                    'abilities' => $abilities,
                    'pokedex_numbers' => $speciesData['pokedex_numbers'] ?? [],

                    // Training
                    'ev_yield' => $evYield,
                    'catch_rate' => $speciesData['catch_rate'] ?? 45,
                    'base_friendship' => $speciesData['base_happiness'] ?? 50,
                    'base_experience' => $data['base_experience'] ?? 0,
                    'growth_rate' => $speciesData['growth_rate'] ?? 'Medium Slow',

                    // Breeding
                    'egg_groups' => $speciesData['egg_groups'] ?? [],
                    'gender_male_rate' => $speciesData['gender_male_rate'],
                    'gender_female_rate' => $speciesData['gender_female_rate'],
                    'egg_cycles' => $speciesData['egg_cycles'] ?? 20,
                    'hatch_steps_min' => $speciesData['hatch_steps_min'] ?? 5140,
                    'hatch_steps_max' => $speciesData['hatch_steps_max'] ?? 5396,

                    // Type Defenses
                    'type_defenses' => $typeDefenses,

                    // Evoluções
                    'evolution_chain' => $evolutionData,
                    'mega_evolutions' => $megaEvolutions,
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Erro na API Pokémon', ['id' => $id, 'error' => $e->getMessage()]);
            return null;
        }
    }

    private function getBasicPokemonData($data, $types, $sprite)
    {
        return [
            'id' => $data['id'],
            'name' => ucfirst($data['name']),
            'sprite' => $sprite,
            'stats' => [
                'hp' => $this->getStat($data['stats'], 'hp'),
                'attack' => $this->getStat($data['stats'], 'attack'),
                'defense' => $this->getStat($data['stats'], 'defense'),
                'sp_attack' => $this->getStat($data['stats'], 'special-attack'),
                'sp_defense' => $this->getStat($data['stats'], 'special-defense'),
                'speed' => $this->getStat($data['stats'], 'speed'),
            ],
            'types' => $types,
            'type_colors' => collect($types)->mapWithKeys(fn($type) => [$type => self::TYPE_COLORS[$type] ?? '#777'])->toArray(),
            'weaknesses' => $this->getWeaknesses($types),
            'height' => $data['height'] / 10,
            'weight' => $data['weight'] / 10,
            'base_experience' => $data['base_experience'] ?? 0,
        ];
    }

    private function getSpeciesData(string $url): array
    {
        try {
            $response = Http::withOptions(['verify' => false])->timeout(10)->get($url);

            if ($response->successful()) {
                $data = $response->json();

                // Buscar nome da espécie em inglês
                $genusEntry = collect($data['genera'])->firstWhere('language.name', 'en');
                $species = $genusEntry['genus'] ?? 'Unknown Pokémon';

                // Processar egg groups
                $eggGroups = collect($data['egg_groups'])->pluck('name')->map(function ($name) {
                    return ucwords(str_replace('-', ' ', $name));
                })->toArray();

                // Processar growth rate
                $growthRate = ucwords(str_replace('-', ' ', $data['growth_rate']['name'] ?? 'medium-slow'));

                // Calcular gender rate (gender_rate é em oitavos de chance de ser fêmea, -1 = genderless)
                $genderRate = $data['gender_rate'] ?? -1;
                $genderMaleRate = null;
                $genderFemaleRate = null;

                if ($genderRate >= 0) {
                    $genderFemaleRate = ($genderRate / 8) * 100;
                    $genderMaleRate = 100 - $genderFemaleRate;
                }

                // Calcular passos para chocar (egg_cycles * 257)
                $eggCycles = $data['hatch_counter'] ?? 20;
                $hatchStepsMin = ($eggCycles - 1) * 257;
                $hatchStepsMax = $eggCycles * 257;

                // Buscar números da Pokédex de diferentes jogos
                $pokedexNumbers = [];
                foreach ($data['pokedex_numbers'] ?? [] as $entry) {
                    $pokedexName = $entry['pokedex']['name'] ?? '';
                    $pokedexNumbers[$pokedexName] = $entry['entry_number'];
                }

                return [
                    'species' => $species,
                    'catch_rate' => $data['capture_rate'] ?? 45,
                    'base_happiness' => $data['base_happiness'] ?? 50,
                    'growth_rate' => $growthRate,
                    'egg_groups' => $eggGroups,
                    'gender_male_rate' => $genderMaleRate,
                    'gender_female_rate' => $genderFemaleRate,
                    'egg_cycles' => $eggCycles,
                    'hatch_steps_min' => $hatchStepsMin,
                    'hatch_steps_max' => $hatchStepsMax,
                    'evolution_chain_url' => $data['evolution_chain']['url'] ?? null,
                    'pokedex_numbers' => $pokedexNumbers,
                ];
            }
        } catch (\Exception $e) {
            // Em caso de erro, retornar valores padrão
        }

        return [];
    }

    private function getEvolutionChain(?string $url, string $currentPokemon): array
    {
        if (!$url) {
            return [];
        }

        try {
            $response = Http::withOptions(['verify' => false])->timeout(10)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $chain = $data['chain'] ?? null;

                if (!$chain) {
                    return [];
                }

                return $this->parseEvolutionChain($chain, $currentPokemon);
            }
        } catch (\Exception $e) {
            // Em caso de erro, retornar array vazio
        }

        return [];
    }

    private function parseEvolutionChain(array $chain, string $currentPokemon): array
    {
        $evolutions = [];

        // Função recursiva para percorrer a cadeia
        $this->extractEvolutions($chain, $evolutions, 1, $currentPokemon);

        return $evolutions;
    }

    private function extractEvolutions(array $chain, array &$evolutions, int $stage, string $currentPokemon): void
    {
        $pokemonName = $chain['species']['name'] ?? '';
        $pokemonUrl = $chain['species']['url'] ?? '';

        // Extrair ID do Pokémon da URL
        preg_match('/\/(\d+)\/?$/', $pokemonUrl, $matches);
        $pokemonId = $matches[1] ?? 0;

        // Buscar sprite
        $sprite = $pokemonId ? "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/{$pokemonId}.png" : null;

        $evolutions[] = [
            'id' => (int) $pokemonId,
            'name' => ucfirst($pokemonName),
            'sprite' => $sprite,
            'stage' => $stage,
            'is_current' => strtolower($pokemonName) === strtolower($currentPokemon),
            'evolution_details' => $this->getEvolutionDetails($chain['evolution_details'] ?? []),
        ];

        // Processar evoluções seguintes
        foreach ($chain['evolves_to'] ?? [] as $nextEvolution) {
            $this->extractEvolutions($nextEvolution, $evolutions, $stage + 1, $currentPokemon);
        }
    }

    private function getEvolutionDetails(array $details): ?string
    {
        if (empty($details)) {
            return null;
        }

        $detail = $details[0] ?? [];
        $trigger = $detail['trigger']['name'] ?? '';

        switch ($trigger) {
            case 'level-up':
                if ($level = $detail['min_level'] ?? null) {
                    return "Level {$level}";
                }
                if ($happiness = $detail['min_happiness'] ?? null) {
                    return "Happiness ({$happiness})";
                }
                return "Level up";

            case 'use-item':
                $item = $detail['item']['name'] ?? 'item';
                return ucwords(str_replace('-', ' ', $item));

            case 'trade':
                if ($heldItem = $detail['held_item']['name'] ?? null) {
                    return "Trade holding " . ucwords(str_replace('-', ' ', $heldItem));
                }
                return "Trade";

            default:
                return ucwords(str_replace('-', ' ', $trigger));
        }
    }

    /**
     * Busca variedades do Pokémon através da API pokemon-species
     * Inclui mega evoluções e formas regionais
     */
    public function getVarieties($pokemonIdOrName): array
    {
        try {
            $endpoint = is_numeric($pokemonIdOrName) ? $pokemonIdOrName : strtolower(trim($pokemonIdOrName));
            $url = self::BASE_URL . "/pokemon-species/{$endpoint}";

            $response = Http::withOptions(['verify' => false])
                ->timeout(10)
                ->retry(2, 100)
                ->get($url);

            if (!$response->successful()) {
                return [
                    'has_mega_evolutions' => false,
                    'mega_evolutions' => [],
                    'has_regional_forms' => false,
                    'regional_forms' => []
                ];
            }

            $data = $response->json();
            $varieties = $data['varieties'] ?? [];

            $megaEvolutions = [];
            $regionalForms = [];

            foreach ($varieties as $variety) {
                $varietyName = $variety['pokemon']['name'] ?? '';

                // Verificar se é mega evolução
                if (str_contains(strtolower($varietyName), 'mega')) {
                    $megaEvolutions[] = [
                        'name' => ucwords(str_replace('-', ' ', $varietyName)),
                        'form' => $varietyName,
                        'url' => $variety['pokemon']['url'] ?? ''
                    ];
                }

                // Verificar se é forma regional
                $regionalKeywords = ['alola', 'alolan', 'galar', 'galarian', 'hisui', 'hisuian', 'paldea', 'paldean'];
                foreach ($regionalKeywords as $keyword) {
                    if (str_contains(strtolower($varietyName), $keyword)) {
                        $region = ucfirst(str_replace(['ian', 'n'], '', $keyword));
                        $regionalForms[] = [
                            'name' => ucwords(str_replace('-', ' ', $varietyName)),
                            'form' => $varietyName,
                            'region' => $region,
                            'url' => $variety['pokemon']['url'] ?? ''
                        ];
                        break;
                    }
                }
            }

            return [
                'has_mega_evolutions' => !empty($megaEvolutions),
                'mega_evolutions' => $megaEvolutions,
                'has_regional_forms' => !empty($regionalForms),
                'regional_forms' => $regionalForms
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao buscar variedades do Pokémon', [
                'pokemon' => $pokemonIdOrName,
                'error' => $e->getMessage()
            ]);

            return [
                'has_mega_evolutions' => false,
                'mega_evolutions' => [],
                'has_regional_forms' => false,
                'regional_forms' => []
            ];
        }
    }

    /**
     * Busca movimentos aprendidos pelo Pokémon de forma otimizada
     */
    public function getLearnedMoves($pokemonIdOrName): array
    {
        try {
            $endpoint = is_numeric($pokemonIdOrName) ? $pokemonIdOrName : strtolower(trim($pokemonIdOrName));
            $url = self::BASE_URL . "/pokemon/{$endpoint}";

            $response = Http::withOptions(['verify' => false])
                ->timeout(15) // Aumentar timeout para requisições de movimentos
                ->retry(3, 200) // Mais tentativas com delay maior
                ->get($url);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            $moves = $data['moves'] ?? [];

            $learnedMoves = [];
            $processedMoves = []; // Cache local para evitar duplicatas

            // Processar movimentos de forma mais eficiente
            foreach ($moves as $moveData) {
                $moveName = $moveData['move']['name'] ?? '';
                $moveUrl = $moveData['move']['url'] ?? '';

                // Pular se já processamos este movimento
                if (isset($processedMoves[$moveName])) {
                    continue;
                }

                // Buscar detalhes do movimento com cache
                $moveDetails = $this->getMoveDetailsOptimized($moveUrl, $moveName);

                // Processar apenas movimentos level-up
                foreach ($moveData['version_group_details'] ?? [] as $versionDetail) {
                    $learnMethod = $versionDetail['move_learn_method']['name'] ?? '';
                    $levelLearned = $versionDetail['level_learned_at'] ?? null;

                    // Focar apenas em movimentos aprendidos por level up
                    if ($learnMethod === 'level-up' && $levelLearned > 0) {
                        $moveEntry = array_merge($moveDetails, [
                            'level_learned' => $levelLearned,
                            'learn_method' => 'Level Up'
                        ]);

                        $learnedMoves[] = $moveEntry;
                        $processedMoves[$moveName] = true;
                        break; // Pegar apenas a primeira versão level-up
                    }
                }
            }

            // Ordenar por nível aprendido
            usort($learnedMoves, function ($a, $b) {
                return ($a['level_learned'] ?? 0) <=> ($b['level_learned'] ?? 0);
            });

            // Limitar a 50 movimentos para performance
            return array_slice($learnedMoves, 0, 50);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar movimentos do Pokémon', [
                'pokemon' => $pokemonIdOrName,
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Busca detalhes de um movimento específico de forma otimizada
     */
    private function getMoveDetailsOptimized(string $moveUrl, string $moveName): array
    {
        // Cache básico em memória para sessão
        static $moveCache = [];

        if (isset($moveCache[$moveName])) {
            return $moveCache[$moveName];
        }

        try {
            $response = Http::withOptions(['verify' => false])
                ->timeout(8) // Timeout menor para movimentos
                ->get($moveUrl);

            if (!$response->successful()) {
                $defaultMove = [
                    'name' => ucwords(str_replace('-', ' ', $moveName)),
                    'type' => 'normal',
                    'category' => 'status',
                    'power' => null,
                    'accuracy' => null
                ];
                $moveCache[$moveName] = $defaultMove;
                return $defaultMove;
            }

            $data = $response->json();

            $moveDetails = [
                'name' => ucwords(str_replace('-', ' ', $data['name'] ?? $moveName)),
                'type' => $data['type']['name'] ?? 'normal',
                'category' => $data['damage_class']['name'] ?? 'status',
                'power' => $data['power'],
                'accuracy' => $data['accuracy']
            ];

            $moveCache[$moveName] = $moveDetails;
            return $moveDetails;
        } catch (\Exception $e) {
            $defaultMove = [
                'name' => ucwords(str_replace('-', ' ', $moveName)),
                'type' => 'normal',
                'category' => 'status',
                'power' => null,
                'accuracy' => null
            ];
            $moveCache[$moveName] = $defaultMove;
            return $defaultMove;
        }
    }

    /**
     * Busca detalhes de um movimento específico (mantido para compatibilidade)
     */
    private function getMoveDetails(string $moveUrl): array
    {
        $moveName = basename(parse_url($moveUrl, PHP_URL_PATH));
        return $this->getMoveDetailsOptimized($moveUrl, $moveName);
    }

    private function getMegaEvolutions(string $pokemonName): array
    {
        // Lista de Pokémon com Mega Evoluções
        $megaPokemon = [
            'venusaur' => ['venusaur-mega'],
            'charizard' => ['charizard-mega-x', 'charizard-mega-y'],
            'blastoise' => ['blastoise-mega'],
            'beedrill' => ['beedrill-mega'],
            'pidgeot' => ['pidgeot-mega'],
            'alakazam' => ['alakazam-mega'],
            'slowbro' => ['slowbro-mega'],
            'gengar' => ['gengar-mega'],
            'kangaskhan' => ['kangaskhan-mega'],
            'pinsir' => ['pinsir-mega'],
            'gyarados' => ['gyarados-mega'],
            'aerodactyl' => ['aerodactyl-mega'],
            'mewtwo' => ['mewtwo-mega-x', 'mewtwo-mega-y'],
            'ampharos' => ['ampharos-mega'],
            'steelix' => ['steelix-mega'],
            'scizor' => ['scizor-mega'],
            'heracross' => ['heracross-mega'],
            'houndoom' => ['houndoom-mega'],
            'tyranitar' => ['tyranitar-mega'],
            'sceptile' => ['sceptile-mega'],
            'blaziken' => ['blaziken-mega'],
            'swampert' => ['swampert-mega'],
            'gardevoir' => ['gardevoir-mega'],
            'sableye' => ['sableye-mega'],
            'mawile' => ['mawile-mega'],
            'aggron' => ['aggron-mega'],
            'medicham' => ['medicham-mega'],
            'manectric' => ['manectric-mega'],
            'sharpedo' => ['sharpedo-mega'],
            'camerupt' => ['camerupt-mega'],
            'altaria' => ['altaria-mega'],
            'banette' => ['banette-mega'],
            'absol' => ['absol-mega'],
            'glalie' => ['glalie-mega'],
            'salamence' => ['salamence-mega'],
            'metagross' => ['metagross-mega'],
            'latias' => ['latias-mega'],
            'latios' => ['latios-mega'],
            'kyogre' => ['kyogre-primal'],
            'groudon' => ['groudon-primal'],
            'rayquaza' => ['rayquaza-mega'],
            'lopunny' => ['lopunny-mega'],
            'garchomp' => ['garchomp-mega'],
            'lucario' => ['lucario-mega'],
            'abomasnow' => ['abomasnow-mega'],
            'gallade' => ['gallade-mega'],
            'audino' => ['audino-mega'],
            'diancie' => ['diancie-mega'],
        ];

        $name = strtolower($pokemonName);

        if (!isset($megaPokemon[$name])) {
            return [];
        }

        $megas = [];
        foreach ($megaPokemon[$name] as $megaForm) {
            // Extrair o nome bonito da mega
            $displayName = ucwords(str_replace('-', ' ', $megaForm));
            $displayName = str_replace(['Mega X', 'Mega Y'], ['Mega X', 'Mega Y'], $displayName);
            $displayName = str_replace('Primal', 'Forma Primitiva', $displayName);

            $megas[] = [
                'name' => $displayName,
                'form' => $megaForm,
            ];
        }

        return $megas;
    }

    private function calculateEvYield(array $stats): string
    {
        $evStats = [];
        $statNames = [
            'hp' => 'HP',
            'attack' => 'Attack',
            'defense' => 'Defense',
            'special-attack' => 'Sp. Atk',
            'special-defense' => 'Sp. Def',
            'speed' => 'Speed',
        ];

        foreach ($stats as $stat) {
            if ($stat['effort'] > 0) {
                $statName = $statNames[$stat['stat']['name']] ?? $stat['stat']['name'];
                $evStats[] = $stat['effort'] . ' ' . $statName;
            }
        }

        return !empty($evStats) ? implode(', ', $evStats) : 'None';
    }

    private function calculateTypeDefenses(array $pokemonTypes): array
    {
        $allTypes = ['normal', 'fire', 'water', 'electric', 'grass', 'ice', 'fighting', 'poison', 'ground', 'flying', 'psychic', 'bug', 'rock', 'ghost', 'dragon', 'dark', 'steel', 'fairy'];

        $defenses = [];

        foreach ($allTypes as $attackingType) {
            $multiplier = 1;

            foreach ($pokemonTypes as $defendingType) {
                if (isset(self::TYPE_CHART[$defendingType][$attackingType])) {
                    $multiplier *= self::TYPE_CHART[$defendingType][$attackingType];
                }
            }

            $defenses[$attackingType] = $multiplier;
        }

        return $defenses;
    }

    public function searchPokemon($nameOrId)
    {
        return $this->getPokemon($nameOrId);
    }

    public function listPokemons(int $limit = 1000, int $offset = 0)
    {
        try {
            $response = Http::withOptions(['verify' => false])->get(self::BASE_URL . "/pokemon", [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return collect($data['results'])->map(function ($pokemon, $index) use ($offset) {
                    return [
                        'id' => $offset + $index + 1,
                        'name' => $pokemon['name'],
                        'display_name' => ucfirst($pokemon['name']),
                    ];
                });
            }

            return collect();
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function searchPokemonByName(string $query)
    {
        $query = strtolower(trim($query));

        if (empty($query)) {
            return collect();
        }

        // Se é um número, buscar por ID
        if (is_numeric($query)) {
            $pokemonId = (int) $query;
            if ($pokemonId > 0 && $pokemonId <= 1010) {
                return collect([[
                    'id' => $pokemonId,
                    'name' => 'pokemon-' . $pokemonId,
                    'display_name' => 'Pokémon #' . $pokemonId
                ]]);
            }
        }

        // Lista básica de pokémons populares para busca rápida
        $basicPokemonList = $this->getBasicPokemonList();

        $results = collect($basicPokemonList)
            ->filter(function ($pokemon) use ($query) {
                return str_contains(strtolower($pokemon['name']), $query) ||
                    str_contains((string)$pokemon['id'], $query);
            });

        // Se não encontrou resultados na lista básica e o query tem pelo menos 3 caracteres,
        // tentar buscar na API
        if ($results->isEmpty() && strlen($query) >= 3) {
            try {
                $pokemonData = $this->getPokemon($query);
                if ($pokemonData) {
                    $results = collect([[
                        'id' => $pokemonData['id'],
                        'name' => strtolower($pokemonData['name']),
                        'display_name' => $pokemonData['name']
                    ]]);
                }
            } catch (\Exception $e) {
                // Se falhar na busca da API, continuar com resultado vazio
            }
        }

        return $results->take(10);
    }

    private function getBasicPokemonList()
    {
        return [
            ['id' => 1, 'name' => 'bulbasaur', 'display_name' => 'Bulbasaur'],
            ['id' => 2, 'name' => 'ivysaur', 'display_name' => 'Ivysaur'],
            ['id' => 3, 'name' => 'venusaur', 'display_name' => 'Venusaur'],
            ['id' => 4, 'name' => 'charmander', 'display_name' => 'Charmander'],
            ['id' => 5, 'name' => 'charmeleon', 'display_name' => 'Charmeleon'],
            ['id' => 6, 'name' => 'charizard', 'display_name' => 'Charizard'],
            ['id' => 7, 'name' => 'squirtle', 'display_name' => 'Squirtle'],
            ['id' => 8, 'name' => 'wartortle', 'display_name' => 'Wartortle'],
            ['id' => 9, 'name' => 'blastoise', 'display_name' => 'Blastoise'],
            ['id' => 10, 'name' => 'caterpie', 'display_name' => 'Caterpie'],
            ['id' => 25, 'name' => 'pikachu', 'display_name' => 'Pikachu'],
            ['id' => 26, 'name' => 'raichu', 'display_name' => 'Raichu'],
            ['id' => 39, 'name' => 'jigglypuff', 'display_name' => 'Jigglypuff'],
            ['id' => 52, 'name' => 'meowth', 'display_name' => 'Meowth'],
            ['id' => 54, 'name' => 'psyduck', 'display_name' => 'Psyduck'],
            ['id' => 104, 'name' => 'cubone', 'display_name' => 'Cubone'],
            ['id' => 131, 'name' => 'lapras', 'display_name' => 'Lapras'],
            ['id' => 133, 'name' => 'eevee', 'display_name' => 'Eevee'],
            ['id' => 134, 'name' => 'vaporeon', 'display_name' => 'Vaporeon'],
            ['id' => 135, 'name' => 'jolteon', 'display_name' => 'Jolteon'],
            ['id' => 136, 'name' => 'flareon', 'display_name' => 'Flareon'],
            ['id' => 143, 'name' => 'snorlax', 'display_name' => 'Snorlax'],
            ['id' => 150, 'name' => 'mewtwo', 'display_name' => 'Mewtwo'],
            ['id' => 151, 'name' => 'mew', 'display_name' => 'Mew'],
            // Pokémon da 5ª geração
            ['id' => 610, 'name' => 'axew', 'display_name' => 'Axew'],
            ['id' => 611, 'name' => 'fraxure', 'display_name' => 'Fraxure'],
            ['id' => 612, 'name' => 'haxorus', 'display_name' => 'Haxorus'],
            ['id' => 495, 'name' => 'snivy', 'display_name' => 'Snivy'],
            ['id' => 498, 'name' => 'tepig', 'display_name' => 'Tepig'],
            ['id' => 501, 'name' => 'oshawott', 'display_name' => 'Oshawott'],
            ['id' => 570, 'name' => 'zorua', 'display_name' => 'Zorua'],
            ['id' => 571, 'name' => 'zoroark', 'display_name' => 'Zoroark'],
            // Pokémon da 4ª geração
            ['id' => 387, 'name' => 'turtwig', 'display_name' => 'Turtwig'],
            ['id' => 390, 'name' => 'chimchar', 'display_name' => 'Chimchar'],
            ['id' => 393, 'name' => 'piplup', 'display_name' => 'Piplup'],
            ['id' => 447, 'name' => 'riolu', 'display_name' => 'Riolu'],
            ['id' => 448, 'name' => 'lucario', 'display_name' => 'Lucario'],
            // Pokémon da 3ª geração
            ['id' => 252, 'name' => 'treecko', 'display_name' => 'Treecko'],
            ['id' => 255, 'name' => 'torchic', 'display_name' => 'Torchic'],
            ['id' => 258, 'name' => 'mudkip', 'display_name' => 'Mudkip'],
            ['id' => 280, 'name' => 'ralts', 'display_name' => 'Ralts'],
            ['id' => 371, 'name' => 'bagon', 'display_name' => 'Bagon'],
            // Pokémon da 2ª geração
            ['id' => 152, 'name' => 'chikorita', 'display_name' => 'Chikorita'],
            ['id' => 155, 'name' => 'cyndaquil', 'display_name' => 'Cyndaquil'],
            ['id' => 158, 'name' => 'totodile', 'display_name' => 'Totodile'],
            ['id' => 172, 'name' => 'pichu', 'display_name' => 'Pichu'],
            ['id' => 196, 'name' => 'espeon', 'display_name' => 'Espeon'],
            ['id' => 197, 'name' => 'umbreon', 'display_name' => 'Umbreon'],
        ];
    }

    private function getStat(array $stats, string $statName): int
    {
        $stat = collect($stats)->firstWhere('stat.name', $statName);
        return $stat['base_stat'] ?? 0;
    }

    private function getWeaknesses(array $types): array
    {
        $weaknesses = [
            'normal' => ['fighting'],
            'fire' => ['water', 'ground', 'rock'],
            'water' => ['electric', 'grass'],
            'electric' => ['ground'],
            'grass' => ['fire', 'ice', 'poison', 'flying', 'bug'],
            'ice' => ['fire', 'fighting', 'rock', 'steel'],
            'fighting' => ['flying', 'psychic', 'fairy'],
            'poison' => ['ground', 'psychic'],
            'ground' => ['water', 'grass', 'ice'],
            'flying' => ['electric', 'ice', 'rock'],
            'psychic' => ['bug', 'ghost', 'dark'],
            'bug' => ['fire', 'flying', 'rock'],
            'rock' => ['water', 'grass', 'fighting', 'ground', 'steel'],
            'ghost' => ['ghost', 'dark'],
            'dragon' => ['ice', 'dragon', 'fairy'],
            'dark' => ['fighting', 'bug', 'fairy'],
            'steel' => ['fire', 'fighting', 'ground'],
            'fairy' => ['poison', 'steel'],
        ];

        $allWeaknesses = [];
        foreach ($types as $type) {
            if (isset($weaknesses[$type])) {
                $allWeaknesses = array_merge($allWeaknesses, $weaknesses[$type]);
            }
        }

        return array_unique($allWeaknesses);
    }

    public function getGames(): array
    {
        // Lista de jogos Pokémon mais populares
        return [
            'Red/Blue' => 'Pokémon Red/Blue (Gen I)',
            'Yellow' => 'Pokémon Yellow (Gen I)',
            'Gold/Silver' => 'Pokémon Gold/Silver (Gen II)',
            'Crystal' => 'Pokémon Crystal (Gen II)',
            'Ruby/Sapphire' => 'Pokémon Ruby/Sapphire (Gen III)',
            'Emerald' => 'Pokémon Emerald (Gen III)',
            'FireRed/LeafGreen' => 'Pokémon FireRed/LeafGreen (Gen III)',
            'Diamond/Pearl' => 'Pokémon Diamond/Pearl (Gen IV)',
            'Platinum' => 'Pokémon Platinum (Gen IV)',
            'HeartGold/SoulSilver' => 'Pokémon HeartGold/SoulSilver (Gen IV)',
            'Black/White' => 'Pokémon Black/White (Gen V)',
            'Black 2/White 2' => 'Pokémon Black 2/White 2 (Gen V)',
            'X/Y' => 'Pokémon X/Y (Gen VI)',
            'Omega Ruby/Alpha Sapphire' => 'Pokémon Omega Ruby/Alpha Sapphire (Gen VI)',
            'Sun/Moon' => 'Pokémon Sun/Moon (Gen VII)',
            'Ultra Sun/Ultra Moon' => 'Pokémon Ultra Sun/Ultra Moon (Gen VII)',
            'Let\'s Go Pikachu/Eevee' => 'Pokémon Let\'s Go Pikachu/Eevee (Gen VII)',
            'Sword/Shield' => 'Pokémon Sword/Shield (Gen VIII)',
            'Brilliant Diamond/Shining Pearl' => 'Pokémon Brilliant Diamond/Shining Pearl (Gen VIII)',
            'Legends: Arceus' => 'Pokémon Legends: Arceus (Gen VIII)',
            'Scarlet/Violet' => 'Pokémon Scarlet/Violet (Gen IX)',
            'Pokémon GO' => 'Pokémon GO',
            'Other' => 'Outro/Personalizado'
        ];
    }
}
