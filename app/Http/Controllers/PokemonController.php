<?php

namespace App\Http\Controllers;

use App\Models\UserPokemon;
use App\Models\UserTeam;
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

    public function create(Request $request)
    {
        $pokemonData = null;
        $games = $this->pokeApi->getGames();

        // Se um pokemon_id foi passado via URL, buscar os dados
        if ($request->has('pokemon_id')) {
            $pokemonId = $request->get('pokemon_id');
            $pokemonData = $this->pokeApi->getPokemon($pokemonId);
        }

        return view('pokemons.create', compact('pokemonData', 'games'));
    }

    public function getPokemonForCreate(Request $request)
    {
        $pokemonId = $request->get('id');

        if (!$pokemonId) {
            return response()->json(['error' => 'ID do Pokémon é obrigatório'], 400);
        }

        $pokemonData = $this->pokeApi->getPokemon($pokemonId);

        if (!$pokemonData) {
            return response()->json(['error' => 'Pokémon não encontrado'], 404);
        }

        return response()->json($pokemonData);
    }

    public function search(Request $request)
    {
        $request->validate([
            'pokemon_search' => 'required|string|min:1',
        ]);

        $searchTerm = trim($request->pokemon_search);
        $games = $this->pokeApi->getGames();

        $pokemonData = $this->pokeApi->getPokemon($searchTerm);

        if (!$pokemonData) {
            return back()->with('error', 'Pokémon "' . $searchTerm . '" não encontrado! Tente usar o nome em inglês (ex: charmander) ou o ID numérico.');
        }

        return view('pokemons.create', compact('pokemonData', 'games'));
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

    public function pokedex()
    {
        // Carregar os primeiros 20 Pokémon para carregamento mais rápido
        $pokemons = $this->loadPokemonsFromApi(20, 0);
        return view('pokedex.index', compact('pokemons'));
    }

    public function loadMorePokedex(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20); // Reduzir para 20 por vez

        $pokemons = $this->loadPokemonsFromApi($limit, $offset);

        return response()->json($pokemons);
    }

    private function loadPokemonsFromApi($limit, $offset)
    {
        $pokemons = [];

        // Usar um método mais eficiente para carregar Pokémon em lote
        try {
            for ($i = $offset + 1; $i <= $offset + $limit && $i <= 1010; $i++) {
                $pokemonData = $this->pokeApi->getPokemon($i);

                if ($pokemonData) {
                    $pokemons[] = [
                        'id' => $pokemonData['id'],
                        'name' => $pokemonData['name'],
                        'sprite' => $pokemonData['sprite'],
                        'types' => $pokemonData['types'],
                        'type_colors' => $pokemonData['type_colors'],
                        'weaknesses' => $pokemonData['weaknesses']
                    ];
                } else {
                    // Se um Pokémon não for encontrado, continue com o próximo
                    continue;
                }

                // Pequeno delay para evitar sobrecarga da API
                if ($i % 5 == 0) {
                    usleep(50000); // 50ms de pausa a cada 5 Pokémon
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao carregar Pokémon da API', ['error' => $e->getMessage(), 'offset' => $offset]);
        }

        return $pokemons;
    }

    public function team()
    {
        $user = Auth::user();
        $teamPokemons = $user->teamPokemons()->get();

        // Criar array com 6 posições
        $team = [];
        for ($i = 1; $i <= 6; $i++) {
            $teamPokemon = $teamPokemons->firstWhere('position', $i);
            $team[$i] = $teamPokemon ? $teamPokemon->userPokemon : null;
        }

        // Definir trainer baseado no gênero
        $trainerData = [
            'name' => $user->gender === 'female' ? 'May' : 'Brendan',
            'sprite' => $user->gender === 'female'
                ? 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/other/official-artwork/10025.png' // May
                : 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/other/official-artwork/10026.png', // Brendan
            'gender' => $user->gender
        ];

        return view('pokemons.team', compact('team', 'trainerData'));
    }

    public function addToTeam(Request $request, UserPokemon $pokemon)
    {
        $this->authorize('view', $pokemon);

        // Verificar se o Pokémon já está no time
        if ($pokemon->isInTeam()) {
            return back()->with('error', 'Este Pokémon já está no seu time!');
        }

        // Verificar se há espaço no time (máximo 6)
        $currentTeamCount = Auth::user()->userTeam()->count();
        if ($currentTeamCount >= 6) {
            return back()->with('error', 'Seu time já está completo! (máximo 6 Pokémon)');
        }

        // Encontrar próxima posição disponível
        $occupiedPositions = Auth::user()->userTeam()->pluck('position')->toArray();
        $nextPosition = 1;
        for ($i = 1; $i <= 6; $i++) {
            if (!in_array($i, $occupiedPositions)) {
                $nextPosition = $i;
                break;
            }
        }

        Auth::user()->userTeam()->create([
            'user_pokemon_id' => $pokemon->id,
            'position' => $nextPosition
        ]);

        return back()->with('success', $pokemon->pokemon_name . ' foi adicionado ao seu time!');
    }

    public function removeFromTeam(UserPokemon $pokemon)
    {
        $this->authorize('view', $pokemon);

        $teamEntry = Auth::user()->userTeam()->where('user_pokemon_id', $pokemon->id)->first();

        if (!$teamEntry) {
            return back()->with('error', 'Este Pokémon não está no seu time!');
        }

        $teamEntry->delete();

        return back()->with('success', $pokemon->pokemon_name . ' foi removido do seu time!');
    }
}
