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

            $response = Http::withOptions(['verify' => false])->timeout(10)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $types = collect($data['types'])->pluck('type.name')->toArray();

                // Buscar sprite do official-artwork
                $sprite = $data['sprites']['other']['official-artwork']['front_default']
                    ?? $data['sprites']['front_default']
                    ?? null;

                // Buscar dados da espécie para informações adicionais
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
            // Em caso de erro, retorna null
            return null;
        }
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

    private function getMegaEvolutions(string $pokemonName): array
    {
        // Lista de Pokémon com Mega Evoluções
        $megaPokemon = [
            'venusaur' => ['venusaur-mega'],
            'charizard' => ['charizard-mega-x', 'charizard-mega-y'],
            'blastoise' => ['blastoise-mega'],
            'alakazam' => ['alakazam-mega'],
            'gengar' => ['gengar-mega'],
            'kangaskhan' => ['kangaskhan-mega'],
            'pinsir' => ['pinsir-mega'],
            'gyarados' => ['gyarados-mega'],
            'aerodactyl' => ['aerodactyl-mega'],
            'mewtwo' => ['mewtwo-mega-x', 'mewtwo-mega-y'],
            'ampharos' => ['ampharos-mega'],
            'scizor' => ['scizor-mega'],
            'heracross' => ['heracross-mega'],
            'houndoom' => ['houndoom-mega'],
            'tyranitar' => ['tyranitar-mega'],
            'blaziken' => ['blaziken-mega'],
            'gardevoir' => ['gardevoir-mega'],
            'mawile' => ['mawile-mega'],
            'aggron' => ['aggron-mega'],
            'medicham' => ['medicham-mega'],
            'manectric' => ['manectric-mega'],
            'banette' => ['banette-mega'],
            'absol' => ['absol-mega'],
            'garchomp' => ['garchomp-mega'],
            'lucario' => ['lucario-mega'],
            'abomasnow' => ['abomasnow-mega'],
            'beedrill' => ['beedrill-mega'],
            'pidgeot' => ['pidgeot-mega'],
            'slowbro' => ['slowbro-mega'],
            'steelix' => ['steelix-mega'],
            'sceptile' => ['sceptile-mega'],
            'swampert' => ['swampert-mega'],
            'sableye' => ['sableye-mega'],
            'sharpedo' => ['sharpedo-mega'],
            'camerupt' => ['camerupt-mega'],
            'altaria' => ['altaria-mega'],
            'glalie' => ['glalie-mega'],
            'salamence' => ['salamence-mega'],
            'metagross' => ['metagross-mega'],
            'latias' => ['latias-mega'],
            'latios' => ['latios-mega'],
            'rayquaza' => ['rayquaza-mega'],
            'lopunny' => ['lopunny-mega'],
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
            $displayName = str_replace('Mega X', 'Mega X', $displayName);
            $displayName = str_replace('Mega Y', 'Mega Y', $displayName);

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
        // Lista básica de pokémons populares para busca rápida
        $basicPokemonList = $this->getBasicPokemonList();

        $query = strtolower(trim($query));

        if (empty($query)) {
            return collect();
        }

        return collect($basicPokemonList)
            ->filter(function ($pokemon) use ($query) {
                return str_contains(strtolower($pokemon['name']), $query) ||
                    str_contains((string)$pokemon['id'], $query);
            })
            ->take(10);
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
}
