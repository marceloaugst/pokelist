<?php

namespace App\Http\Controllers;

use App\Services\PokeApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class PokedexController extends Controller
{
    private const MAX_POKEMON = 1025;
    private const MAX_SEARCH_RESULTS = 30;

    public function __construct(private PokeApiService $pokeApi)
    {
    }

    /**
     * Tela principal da Pokédex (Inertia/React).
     * A lista é carregada pelo cliente via /pokedex/list.
     */
    public function index()
    {
        return Inertia::render('Pokedex/Index', [
            'total' => self::MAX_POKEMON,
        ]);
    }

    /**
     * Lote de Pokémon para a lista (JSON, com cache em arquivo).
     */
    public function list(Request $request)
    {
        $offset = max(0, (int) $request->get('offset', 0));
        $limit = min(60, max(1, (int) $request->get('limit', 30)));

        $pokemons = Cache::store('file')->remember(
            "pokedex_list_{$offset}_{$limit}",
            now()->addWeek(),
            fn() => $this->pokeApi->getPokemonSummaries($limit, $offset)
        );

        return response()->json([
            'pokemons' => $pokemons,
            'offset' => $offset,
            'limit' => $limit,
            'total' => self::MAX_POKEMON,
        ]);
    }

    /**
     * Busca por nome (prefixo) ou número da Pokédex.
     */
    public function search(Request $request)
    {
        $query = strtolower(trim((string) $request->get('q', '')));

        if ($query === '') {
            return response()->json(['pokemons' => [], 'query' => '']);
        }

        $index = Cache::store('file')->remember(
            'pokedex_name_index',
            now()->addWeek(),
            fn() => $this->pokeApi->getNameIndex()
        );

        $ids = collect($index)
            ->filter(fn($pokemon) => str_starts_with($pokemon['name'], $query))
            ->pluck('id');

        // Também aceita o número da Pokédex digitado diretamente
        if (ctype_digit($query)) {
            $id = (int) $query;
            if ($id >= 1 && $id <= self::MAX_POKEMON) {
                $ids = $ids->prepend($id);
            }
        }

        $ids = $ids->unique()->take(self::MAX_SEARCH_RESULTS)->values()->all();

        $pokemons = Cache::store('file')->remember(
            'pokedex_search_' . md5(implode(',', $ids)),
            now()->addWeek(),
            fn() => $this->pokeApi->getSummariesByIds($ids)
        );

        return response()->json([
            'pokemons' => $pokemons,
            'query' => $query,
        ]);
    }

    /**
     * Detalhes completos de um Pokémon (JSON, com cache em arquivo).
     */
    public function show(int $pokemonId)
    {
        if ($pokemonId < 1 || $pokemonId > self::MAX_POKEMON) {
            return response()->json(['error' => 'Pokémon não encontrado'], 404);
        }

        $pokemon = Cache::store('file')->remember(
            "pokedex_pokemon_{$pokemonId}",
            now()->addDay(),
            fn() => $this->pokeApi->getPokemon($pokemonId)
        );

        if (!$pokemon) {
            return response()->json(['error' => 'Pokémon não encontrado'], 404);
        }

        return response()->json($pokemon);
    }

    /**
     * Movimentos do Pokémon agrupados por método de aprendizado (JSON, com cache em arquivo).
     * Endpoint separado porque exige muitas chamadas à PokéAPI.
     */
    public function moves(int $pokemonId)
    {
        if ($pokemonId < 1 || $pokemonId > self::MAX_POKEMON) {
            return response()->json(['error' => 'Pokémon não encontrado'], 404);
        }

        $moves = Cache::store('file')->remember(
            "pokedex_moves_{$pokemonId}",
            now()->addWeek(),
            fn() => $this->pokeApi->getMoves($pokemonId)
        );

        return response()->json($moves);
    }
}
