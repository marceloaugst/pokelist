<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

// Rota inicial pública - Pokédex
Route::get('/', [PokemonController::class, 'pokedex'])->name('home');

// Rotas públicas da Pokédx
Route::get('/pokedex', [PokemonController::class, 'pokedex'])->name('pokedex.index');
Route::get('/pokedex/load-more', [PokemonController::class, 'loadMorePokedex'])->name('pokedex.load-more');
Route::get('/pokedex/search', [PokemonController::class, 'searchPokedex'])->name('pokedex.search');
Route::get('/pokedex/{pokemonId}', [PokemonController::class, 'showPokedexPokemon'])->name('pokedex.pokemon.show');

Route::get('/dashboard', function () {
    return redirect()->route('pokemons.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de Pokémon
    Route::get('/pokemons', [PokemonController::class, 'index'])->name('pokemons.index');
    Route::get('/pokemons/create', [PokemonController::class, 'create'])->name('pokemons.create');
    Route::get('/pokemons/search-suggestions', [PokemonController::class, 'searchSuggestions'])->name('pokemons.search.suggestions');
    Route::get('/pokemons/get-pokemon', [PokemonController::class, 'getPokemonForCreate'])->name('pokemons.get-pokemon');
    Route::post('/pokemons/search', [PokemonController::class, 'search'])->name('pokemons.search');
    Route::post('/pokemons', [PokemonController::class, 'store'])->name('pokemons.store');
    Route::delete('/pokemons/{pokemon}', [PokemonController::class, 'destroy'])->name('pokemons.destroy');

    // Rotas do Team
    Route::get('/team', [PokemonController::class, 'team'])->name('pokemons.team');
    Route::post('/pokemons/{pokemon}/add-to-team', [PokemonController::class, 'addToTeam'])->name('pokemons.add-to-team');
    Route::delete('/pokemons/{pokemon}/remove-from-team', [PokemonController::class, 'removeFromTeam'])->name('pokemons.remove-from-team');

    // Rota para visualizar pokémon capturado (logado)
    Route::get('/pokemons/{pokemon}', [PokemonController::class, 'show'])->name('pokemons.show');

    // Rotas para variedades e movimentos
    Route::get('/pokemon/varieties', [PokemonController::class, 'getVarieties'])->name('pokemon.varieties');
    Route::get('/pokemon/moves', [PokemonController::class, 'getLearnedMoves'])->name('pokemon.moves');

    // Rota para buscar Pokémon por ID e redirecionar para criar
    Route::get('/pokemon/search-by-id', [PokemonController::class, 'searchById'])->name('pokemon.search-by-id');
});

require __DIR__ . '/auth.php';
