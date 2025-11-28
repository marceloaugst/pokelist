<?php

namespace App\Http\Controllers;

use App\Models\UserPokemon;
use App\Services\PokeApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class PokemonController extends Controller
{
    use AuthorizesRequests;
    private PokeApiService $pokeApi;

    public function __construct(PokeApiService $pokeApi)
    {
        $this->pokeApi = $pokeApi;
    }

    public function index()
    {
        $pokemons = Auth::user()->userPokemons()->latest()->paginate(12);
        return view('pokemons.index', compact('pokemons'));
    }

    public function create()
    {
        return view('pokemons.create');
    }

    public function search(Request $request)
    {
        $request->validate([
            'pokemon_search' => 'required|string|min:1',
        ]);

        $searchTerm = trim($request->pokemon_search);

        $pokemonData = $this->pokeApi->getPokemon($searchTerm);

        if (!$pokemonData) {
            return back()->with('error', 'Pokémon "' . $searchTerm . '" não encontrado! Tente usar o nome em inglês (ex: charmander) ou o ID numérico.');
        }

        return view('pokemons.create', compact('pokemonData'));
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = $this->pokeApi->searchPokemonByName($query);

        return response()->json($suggestions->values()->all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'pokemon_id' => 'required|integer',
            'pokemon_name' => 'required|string',
            'game_name' => 'required|string|max:255',
            'sprite_url' => 'nullable|string',
            'base_hp' => 'required|integer|min:0',
            'base_attack' => 'required|integer|min:0',
            'base_defense' => 'required|integer|min:0',
            'base_sp_attack' => 'required|integer|min:0',
            'base_sp_defense' => 'required|integer|min:0',
            'base_speed' => 'required|integer|min:0',
            'game_hp' => 'required|integer|min:0',
            'game_attack' => 'required|integer|min:0',
            'game_defense' => 'required|integer|min:0',
            'game_sp_attack' => 'required|integer|min:0',
            'game_sp_defense' => 'required|integer|min:0',
            'game_speed' => 'required|integer|min:0',
            'level' => 'required|integer|min:1|max:100',
            'notes' => 'nullable|string',
            // Novos campos
            'species' => 'nullable|string',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'abilities' => 'nullable|string',
            'types' => 'nullable|string',
            'ev_yield' => 'nullable|string',
            'catch_rate' => 'nullable|integer',
            'base_friendship' => 'nullable|integer',
            'base_experience' => 'nullable|integer',
            'growth_rate' => 'nullable|string',
            'egg_groups' => 'nullable|string',
            'gender_male_rate' => 'nullable|numeric',
            'gender_female_rate' => 'nullable|numeric',
            'egg_cycles' => 'nullable|integer',
            'hatch_steps_min' => 'nullable|integer',
            'hatch_steps_max' => 'nullable|integer',
            'type_defenses' => 'nullable|string',
            'evolution_chain' => 'nullable|string',
            'mega_evolutions' => 'nullable|string',
            'pokedex_numbers' => 'nullable|string',
        ]);

        $data = $request->except(['abilities', 'types', 'egg_groups', 'type_defenses', 'evolution_chain', 'mega_evolutions', 'pokedex_numbers']);

        // Converter strings JSON de volta para arrays
        if ($request->filled('abilities')) {
            $data['abilities'] = json_decode($request->abilities, true);
        }
        if ($request->filled('types')) {
            $data['types'] = json_decode($request->types, true);
        }
        if ($request->filled('egg_groups')) {
            $data['egg_groups'] = json_decode($request->egg_groups, true);
        }
        if ($request->filled('type_defenses')) {
            $data['type_defenses'] = json_decode($request->type_defenses, true);
        }
        if ($request->filled('evolution_chain')) {
            $data['evolution_chain'] = json_decode($request->evolution_chain, true);
        }
        if ($request->filled('mega_evolutions')) {
            $data['mega_evolutions'] = json_decode($request->mega_evolutions, true);
        }
        if ($request->filled('pokedex_numbers')) {
            $data['pokedex_numbers'] = json_decode($request->pokedex_numbers, true);
        }

        Auth::user()->userPokemons()->create($data);

        return redirect()->route('pokemons.index')->with('success', 'Pokémon adicionado com sucesso!');
    }

    public function show(UserPokemon $pokemon)
    {
        $this->authorize('view', $pokemon);
        return view('pokemons.show', compact('pokemon'));
    }

    public function destroy(UserPokemon $pokemon)
    {
        $this->authorize('delete', $pokemon);
        $pokemon->delete();

        return redirect()->route('pokemons.index')->with('success', 'Pokémon removido com sucesso!');
    }
}
